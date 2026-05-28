<?php

namespace App\Http\Controllers;

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

class ShoeController extends Controller
{
    public function index()
    {
        return view('user.product');
    }

    public function wishlist()
    {
        //fake data for wishlist page
        $wishlistItems = collect([
            (object) [
                'id' => 1,
                'product' => (object) [
                    'id' => 1,
                    'name' => 'Air Max Pro',
                    'category' => 'Running',
                    'price' => 129.99,
                    'image_url' => 'https://images.unsplash.com/photo-1528701800489-47645c2a34f2?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            (object) [
                'id' => 2,
                'product' => (object) [
                    'id' => 2,
                    'name' => 'Ultraboost 22',
                    'category' => 'Training',
                    'price' => 149.99,
                    'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
                ],
            ],
            (object) [
                'id' => 3,
                'product' => (object) [
                    'id' => 3,
                    'name' => 'Jordan Legacy',
                    'category' => 'Basketball',
                    'price' => 159.99,
                    'image_url' => 'https://images.unsplash.com/photo-1526178615590-8d5f6c9b9b1f?auto=format&fit=crop&w=900&q=80',
                ],
            ],
        ]);

        return view('user.wishlist', compact('wishlistItems'));
    }

    public function show(int $shoeId)
    {
        $shoe = Shoe::with([
            'brand',
            'variations',
            'options',
            'images',
        ])->findOrFail($shoeId);

        $sizes = $shoe->variations
            ->map(function ($variation) {
                return data_get($variation->attributes, 'size');
            })
            ->filter()
            ->unique()
            ->values();

        $colors = $shoe->variations
            ->map(function ($variation) {
                return data_get($variation->attributes, 'color');
            })
            ->filter()
            ->unique()
            ->values();

        $variationMatrix = $shoe->variations
            ->map(function ($variation) {
                return [
                    'sku_code' => $variation->sku_code,
                    'size' => data_get($variation->attributes, 'size'),
                    'color' => data_get($variation->attributes, 'color'),
                    'stock_quantity' => (int) $variation->stock_quantity,
                ];
            })
            ->values();

        return view('user.product-details', compact('shoe', 'sizes', 'colors', 'variationMatrix'));
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

    public function showAdminTestPage(int $shoeId)
    {
        $shoe = Shoe::with([
            'brand',
            'options',
            'images',
            'variations',
            'variations.images'
        ])->findOrFail($shoeId);

        $brands = \App\Models\Brand::all();

        return view(
            'test-product',
            compact('shoe', 'brands')
        );
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
