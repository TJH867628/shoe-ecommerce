<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Shoe;
use App\Models\SKU;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ]
        );

        $nike = Brand::updateOrCreate(
            ['brand_name' => 'Nike'],
            ['brand_description' => 'Performance footwear and sportswear brand.']
        );

        $adidas = Brand::updateOrCreate(
            ['brand_name' => 'Adidas'],
            ['brand_description' => 'Athletic shoes with everyday comfort and style.']
        );

        $airMax = Shoe::updateOrCreate(
            ['shoe_name' => 'Air Max Runner'],
            [
                'brand_id' => $nike->id,
                'shoe_description' => 'Lightweight running shoe with responsive cushioning.',
                'shoe_price' => 499.00,
            ]
        );

        $ultraboost = Shoe::updateOrCreate(
            ['shoe_name' => 'Ultraboost Street'],
            [
                'brand_id' => $adidas->id,
                'shoe_description' => 'Comfort-focused sneaker for daily wear and training.',
                'shoe_price' => 579.00,
            ]
        );

        $airMaxBlue = SKU::create([
            'shoe_id' => $airMax->id,
            'attributes' => ['size' => 42, 'color' => 'Blue'],
            'stock_quantity' => 12,
        ]);

        SKU::create([
            'shoe_id' => $airMax->id,
            'attributes' => ['size' => 43, 'color' => 'Black'],
            'stock_quantity' => 8,
        ]);

        SKU::create([
            'shoe_id' => $ultraboost->id,
            'attributes' => ['size' => 41, 'color' => 'White'],
            'stock_quantity' => 15,
        ]);

        $cart = Cart::updateOrCreate(
            ['user_id' => $user->id],
            []
        );

        CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'shoe_variation_id' => $airMaxBlue->id,
            ],
            ['quantity' => 1]
        );
    }
}
