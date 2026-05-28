<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Prototype\ShoeRegistry;
use Illuminate\Support\Facades\DB;
use App\Services\Builders\Builders\AdminShoeSkuBuilder;

class ShoePrototypeController extends Controller
{
    public function clone(int $shoeId)
    {
        $shoe = Shoe::findOrFail($shoeId);

        return DB::transaction(function () use ($shoe)
        {
            $shoe->load([
                'variations.images',
                'options',
                'images'
            ]);

            $registry = new ShoeRegistry();

            $registry->addItem($shoe->id, $shoe);

            $newShoe = $registry->getById($shoe->id);

            $newShoe->brand_id = $shoe->brand_id;
            
            $newShoe->shoe_name = $shoe->shoe_name . ' Copy';

            $newShoe->save();

            foreach ($shoe->options as $option)
            {
                $newShoe->options()->create([
                    'option_name' => $option->option_name
                ]);
            }

            foreach ($shoe->images as $image)
            {
                $newShoe->images()->create([
                    'image_path' => $image->image_path,
                    'is_cover' => $image->is_cover,
                    'sort_order' => $image->sort_order
                ]);
            }

            foreach ($shoe->variations as $variation)
            {
                $newVariation = $variation->replicate();

                $newVariation->shoe_id = $newShoe->id;

                $newVariation->sku_code = AdminShoeSkuBuilder::generateSkuCode($newShoe,$variation->attributes);

                $newVariation->save();

                foreach ($variation->images as $image)
                {
                    $newVariation->images()->create([
                        'image_path' => $image->image_path
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Shoe cloned successfully.');
        });
    }   
}