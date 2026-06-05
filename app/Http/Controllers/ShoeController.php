<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Shoe;
use App\Models\ShoeVariations;
use Illuminate\Http\Request;
use App\Services\Builders\Builders\AdminShoeBuilder;
use App\Services\Builders\Builders\AdminShoeSkuBuilder;
use App\Models\ShoeOption;
use App\Models\ShoeImage;
use App\Models\ShoeVariationImage;
use App\Services\Builders\Directors\ShoeSkuDirector;
use App\Services\Builders\Directors\ShoeDirector;
use Exception;
use Illuminate\Support\Facades\Auth;

class ShoeController extends Controller
{
    public function home()
    {
        $trendingShoes = Shoe::with(['brand', 'images', 'variations'])
            ->latest()
            ->take(4)
            ->get();

        return view('main', compact('trendingShoes'));
    }

    public function index()
    {
        $shoes = Shoe::with(['brand', 'images', 'variations'])
            ->latest()
            ->get();

        $sampleProducts = $shoes->map(function (Shoe $shoe) {
            return $this->buildProductCardData($shoe);
        });

        $brands = Brand::orderBy('brand_name')->pluck('brand_name');

        $sizes = ShoeVariations::query()
            ->get()
            ->map(fn(ShoeVariations $variation) => data_get($variation->attributes, 'size'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $priceMin = (float) $shoes->min('shoe_price');
        $priceMax = (float) $shoes->max('shoe_price');

        if ($shoes->isEmpty()) {
            $priceMin = 0;
            $priceMax = 0;
        }

        return view('user.product', compact('sampleProducts', 'brands', 'sizes', 'priceMin', 'priceMax'));
    }

    public function adminIndex()
    {
        $shoes = Shoe::with([
            'brand',
            'options',
            'variations',
            'images',
        ])->latest()->paginate(15);

        $brands = Brand::orderBy('brand_name')->get();

        return view('admin.manage-shoes', compact('shoes', 'brands'));
    }

    public function adminShow(int $shoeId)
    {
        $shoe = Shoe::with([
            'brand',
            'options',
            'images',
            'variations',
            'variations.images',
        ])->findOrFail($shoeId);

        $brands = Brand::orderBy('brand_name')->get();

        return view('admin.product', compact('shoe', 'brands'));
    }

    public function show(int $shoeId)
    {
        $shoe = Shoe::with([
            'brand',
            'variations.images',
            'options',
            'images',
        ])->findOrFail($shoeId);

        $options = [];

        foreach ($shoe->variations as $variation) {

            foreach (($variation->attributes ?? []) as $name => $value) {

                if (!isset($options[$name])) {
                    $options[$name] = [];
                }

                $options[$name][] = $value;
            }
        }

        foreach ($options as $name => $values) {
            $options[$name] = collect($values)
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        $variationMatrix = $shoe->variations
            ->map(function ($variation) {

                return [
                    'id' => $variation->id,
                    'sku_code' => $variation->sku_code,
                    'stock_quantity' => (int) $variation->stock_quantity,
                    'attributes' => $variation->attributes ?? [],
                    'images' => $variation->images
                        ->map(function ($image) {

                            return str_starts_with(
                                $image->image_path,
                                'http'
                            )
                                ? $image->image_path
                                : asset('storage/' . $image->image_path);

                        })
                        ->values(),
                ];
            })
            ->values();

        return view(
            'user.product-details',
            compact(
                'shoe',
                'options',
                'variationMatrix'
            )
        );
    }

    public function getAllShoes()
    {
        return Shoe::with('variations', 'brand')->get();
    }

    public function getShoeById($id)
    {
        return Shoe::with('variations', 'brand')->findOrFail($id);
    }

    public function getShoesByBrand($brandId)
    {
        return Shoe::with('variations', 'brand')->where('brand_id', $brandId)->get();
    }

    public function searchShoes(Request $request)
    {
        $query = Shoe::with('variations', 'brand');
        if ($request->has('name')) {
            $query->where('shoe_name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('min_price')) {
            $query->where('shoe_price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('shoe_price', '<=', $request->input('max_price'));
        }

        return $query->get();
    }

    public function createShoe(Request $request)
    {
        $director = new ShoeDirector(
            new AdminShoeBuilder()
        );

        $shoeData = $director->buildShoe(
            $request->brand_id,
            $request->shoe_name,
            $request->shoe_description,
            $request->shoe_price
        );

        $shoe = Shoe::create($shoeData);

        return back()->with([
            'success' => 'Shoe created successfully.',
            'shoe_id' => $shoe->id
        ]);
    }

    public function uploadShoeImages(Request $request, int $shoeId)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'images.required' => 'Please select at least one product image.',
            'images.max' => 'Please upload no more than 10 product images at once.',
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.mimes' => 'Images must be JPG, JPEG, PNG, or WEBP files.',
            'images.*.max' => 'Each image must be 4 MB or smaller.',
        ]);

        $shoe = Shoe::findOrFail($shoeId);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('shoes', 'public');
                ShoeImage::create([
                    'shoe_id' => $shoe->id,
                    'image_path' => $path,
                    'is_cover' => $index === 0 && $shoe->images()->count() === 0,
                    'sort_order' => $shoe->images()->count()
                ]);
            }
        }

        return back()->with('success', 'Product images uploaded successfully.');
    }

    public function removeShoeImage(int $imageId)
    {
        $image = ShoeImage::findOrFail($imageId);
        $image->delete();

        return back()->with('success', 'Product image removed successfully.');
    }

    public function hasOption(int $shoeId, string $optionName)
    {
        return ShoeOption::where('shoe_id', $shoeId)->where('option_name', $optionName)->exists();
    }

    private function hasDuplicateVariation(int $shoeId, array $attributes, int $currentVariationId)
    {
        ksort($attributes);

        return ShoeVariations::where('shoe_id', $shoeId)
            ->where('id', '!=', $currentVariationId)
            ->get()
            ->contains(function ($variation) use ($attributes) {

                $existingAttributes = $variation->attributes;

                ksort($existingAttributes);

                return $existingAttributes == $attributes;
            });
    }

    public function uploadVariationImages(Request $request, int $variationId)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'images.required' => 'Please select at least one variation image.',
            'images.max' => 'Please upload no more than 10 variation images at once.',
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.mimes' => 'Images must be JPG, JPEG, PNG, or WEBP files.',
            'images.*.max' => 'Each image must be 4 MB or smaller.',
        ]);

        $variation = ShoeVariations::findOrFail($variationId);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('variations', 'public');
                ShoeVariationImage::create([
                    'shoe_variation_id' => $variation->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Variation images uploaded successfully.');
    }

    public function removeVariationImage(int $imageId)
    {
        $image = ShoeVariationImage::findOrFail($imageId);
        $image->delete();

        return back()->with('success', 'Variation image removed successfully.');
    }

    public function createShoeOptions(Request $request)
    {
        $options = [];
        foreach ($request->option_names as $optionName) {
            if ($this->hasOption($request->shoe_id, $optionName)) {
                return back()->with('error', 'This options already exist for this shoe.');
            }
            $options[] = [
                'shoe_id' => $request->shoe_id,
                'option_name' => $optionName,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        ShoeOption::insert($options);

        return back()->with('success', 'Options added successfully.');
    }

    private function buildProductCardData(Shoe $shoe): array
    {
        $coverImage = $shoe->images->firstWhere('is_cover', true)
            ?? $shoe->images->sortBy('sort_order')->first();
        $firstVariation = $shoe->variations->first();

        // Collect available sizes and colors for this shoe (used for client-side filtering)
        $sizes = $shoe->variations
            ->map(fn($v) => data_get($v->attributes, 'size'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $colors = $shoe->variations
            ->map(fn($v) => data_get($v->attributes, 'color'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'id' => $shoe->id,
            'name' => $shoe->shoe_name,
            'brand' => $shoe->brand?->brand_name ?? 'Unknown',
            'price' => $shoe->shoe_price,
            'image' => $coverImage?->image_path ?? null,
            'stock' => (int) $shoe->variations->sum('stock_quantity'),
            'variation_id' => $firstVariation?->id,
            'sizes' => $sizes,
            'colors' => $colors,
        ];
    }

    public function createSkus(Request $request)
    {

        try {
            $director = new ShoeSkuDirector(new AdminShoeSkuBuilder());

            foreach ($request->skus as $sku) {
                $variationData = $director->buildSku(
                    $request->shoe_id,
                    $sku['attributes'],
                    $sku['stock'] ?? 0
                );

                foreach ($variationData as $variation) {
                    ShoeVariations::create([
                        'shoe_id' => $variation['shoe_id'],
                        'attributes' => $variation['attributes'],
                        'stock_quantity' => $variation['stock'],
                        'sku_code' => $variation['sku_code']
                    ]);
                }
            }
            return back()->with(
                'success',
                'SKUs created successfully.'
            );
        } catch (Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function updateShoe(Request $request, int $shoeId)
    {
        $shoe = Shoe::findOrFail($shoeId);
        $shoe->update([
            'brand_id' => $request->brand_id ?? $shoe->brand_id,
            'shoe_name' => $request->shoe_name ?? $shoe->shoe_name,
            'shoe_description' => $request->shoe_description ?? $shoe->shoe_description,
            'shoe_price' => $request->shoe_price ?? $shoe->shoe_price
        ]);

        $shoe->load([
            'brand',
            'variations'
        ]);

        foreach ($shoe->variations as $variation) {
            $variation->sku_code =
                AdminShoeSkuBuilder::generateSkuCode(
                    $shoe,
                    $variation->attributes
                );

            $variation->save();
        }

        return back()->with('success', 'Shoe updated successfully.');
    }

    public function deleteShoe(int $shoeId)
    {
        $shoe = Shoe::findOrFail($shoeId);
        $shoe->delete();

        return back()->with('success', 'Shoe deleted successfully.');
    }

    public function updateOption(Request $request, int $optionId)
    {
        $option = ShoeOption::findOrFail($optionId);
        if ($this->hasOption($option->shoe_id, $request->option_name)) {
            return back()->with('error', 'This options already exist for this shoe.');
        }

        $option->update([
            'option_name' => $request->option_name
        ]);

        return back()->with('success', 'Option updated successfully.');
    }

    public function deleteShoeOption(int $optionId)
    {
        $option = ShoeOption::findOrFail($optionId);

        ShoeVariations::where('shoe_id', $option->shoe_id)->get()->each(function (ShoeVariations $variation) use ($option) {
            $attributes = $variation->attributes;

            if (isset($attributes[$option->option_name])) {

                unset(
                    $attributes[$option->option_name]
                );

                if (empty($attributes)) {

                    $variation->delete();
                    return;
                }

                $variation->update([
                    'attributes' => $attributes
                ]);
            }
        });

        $option->delete();

        return back()->with('success', 'Option deleted successfully.');
    }

    public function updateSku(Request $request, int $variationId)
    {
        $variation = ShoeVariations::findOrFail($variationId);
        $attributes = $request->input('attributes', []);

        if ($this->hasDuplicateVariation($variation->shoe_id, $attributes, $variationId)) {

            return back()->with(
                'error',
                'Another variation with the same attributes already exists.'
            );
        }

        $allowedOptions = ShoeOption::where('shoe_id', $variation->shoe_id)
            ->pluck('option_name')
            ->toArray();

        foreach (array_keys($attributes) as $attributeName) {

            if (!in_array($attributeName, $allowedOptions)) {

                return back()->with(
                    'error',
                    "Invalid option: {$attributeName}"
                );
            }
        }

        $shoe = Shoe::with('brand')->findOrFail($variation->shoe_id);

        $skuCode = AdminShoeSkuBuilder::generateSkuCode($shoe, $attributes);

        $variation->update([
            'attributes' => $attributes,
            'stock_quantity' => $request->stock,
            'sku_code' => $skuCode
        ]);

        return back()->with(
            'success',
            'Variation updated successfully.'
        );
    }

    public function deleteSku(int $variationId)
    {
        $variation = ShoeVariations::findOrFail($variationId);
        $variation->delete();

        return back()->with('success', 'Variation deleted successfully.');
    }
}
