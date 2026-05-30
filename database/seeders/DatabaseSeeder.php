<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Shoe;
use App\Models\ShoeVariations;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0123456789',
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

        $puma = Brand::updateOrCreate(
            ['brand_name' => 'Puma'],
            ['brand_description' => 'Lifestyle and performance shoes for everyday wear.']
        );

        $newBalance = Brand::updateOrCreate(
            ['brand_name' => 'New Balance'],
            ['brand_description' => 'Comfort-first shoes with classic styling.']
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

        $rsx = Shoe::updateOrCreate(
            ['shoe_name' => 'RS-X Games'],
            [
                'brand_id' => $puma->id,
                'shoe_description' => 'Chunky retro-inspired sneaker with bold cushioning.',
                'shoe_price' => 399.00,
            ]
        );

        $nb990 = Shoe::updateOrCreate(
            ['shoe_name' => '990v6'],
            [
                'brand_id' => $newBalance->id,
                'shoe_description' => 'Premium everyday trainer with exceptional comfort.',
                'shoe_price' => 699.00,
            ]
        );

        $airMaxBlue = ShoeVariations::updateOrCreate(
            [
                'shoe_id' => $airMax->id,
                'attributes' => ['size' => 42, 'color' => 'Blue'],
            ],
            [
                'stock_quantity' => 12,
                'sku_code' => 'AMR-42-BLUE',
            ]
        );

        ShoeVariations::updateOrCreate(
            [
                'shoe_id' => $airMax->id,
                'attributes' => ['size' => 43, 'color' => 'Black'],
            ],
            [
                'stock_quantity' => 8,
                'sku_code' => 'AMR-43-BLK',
            ]
        );

        ShoeVariations::updateOrCreate(
            [
                'shoe_id' => $ultraboost->id,
                'attributes' => ['size' => 41, 'color' => 'White'],
            ],
            [
                'stock_quantity' => 15,
                'sku_code' => 'UBS-41-WHT',
            ]
        );

        ShoeVariations::updateOrCreate(
            [
                'shoe_id' => $rsx->id,
                'attributes' => ['size' => 42, 'color' => 'Red'],
            ],
            [
                'stock_quantity' => 10,
                'sku_code' => 'RSX-42-RED',
            ]
        );

        ShoeVariations::updateOrCreate(
            [
                'shoe_id' => $nb990->id,
                'attributes' => ['size' => 43, 'color' => 'Grey'],
            ],
            [
                'stock_quantity' => 6,
                'sku_code' => 'NB990-43-GRY',
            ]
        );

        $shoeSeeds = [
            [
                'shoe' => $airMax,
                'images' => [
                    ['image_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80', 'is_cover' => true, 'sort_order' => 1],
                    ['image_path' => 'https://images.unsplash.com/photo-1528701800489-47645c2a34f2?auto=format&fit=crop&w=1200&q=80', 'is_cover' => false, 'sort_order' => 2],
                ],
            ],
            [
                'shoe' => $ultraboost,
                'images' => [
                    ['image_path' => 'https://images.unsplash.com/photo-1600185365926-3a2ce3cdbd62?auto=format&fit=crop&w=1200&q=80', 'is_cover' => true, 'sort_order' => 1],
                ],
            ],
            [
                'shoe' => $rsx,
                'images' => [
                    ['image_path' => 'https://images.unsplash.com/photo-1542293787938-c9e299b880f4?auto=format&fit=crop&w=1200&q=80', 'is_cover' => true, 'sort_order' => 1],
                ],
            ],
            [
                'shoe' => $nb990,
                'images' => [
                    ['image_path' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?auto=format&fit=crop&w=1200&q=80', 'is_cover' => true, 'sort_order' => 1],
                ],
            ],
        ];

        foreach ($shoeSeeds as $shoeSeed) {
            foreach ($shoeSeed['images'] as $image) {
                DB::table('shoe_images')->updateOrInsert(
                    [
                        'shoe_id' => $shoeSeed['shoe']->id,
                        'image_path' => $image['image_path'],
                    ],
                    [
                        'is_cover' => $image['is_cover'],
                        'sort_order' => $image['sort_order'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        $variationImages = ShoeVariations::query()->get()->all();
        foreach ($variationImages as $variation) {
            DB::table('shoe_variation_images')->updateOrInsert(
                [
                    'shoe_variation_id' => $variation->id,
                    'image_path' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80',
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('shoe_options')->updateOrInsert(
            ['shoe_id' => $airMax->id, 'option_values' => 'Size, Color'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        DB::table('shoe_options')->updateOrInsert(
            ['shoe_id' => $ultraboost->id, 'option_values' => 'Size, Color'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $voucher = DB::table('vouchers')->updateOrInsert(
            ['voucher_code' => 'WELCOME10'],
            [
                'discount_value' => 10.00,
                'expiry_date' => Carbon::now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $voucherId = DB::table('vouchers')->where('voucher_code', 'WELCOME10')->value('id');

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

        $orderId = DB::table('orders')->updateOrInsert(
            [
                'user_id' => $user->id,
                'status' => 'pending',
            ],
            [
                'voucher_id' => $voucherId,
                'total_amount' => 499.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $orderId = DB::table('orders')->where('user_id', $user->id)->latest('id')->value('id');

        DB::table('order_items')->updateOrInsert(
            [
                'order_id' => $orderId,
                'shoe_variation_id' => $airMaxBlue->id,
            ],
            [
                'quantity' => 1,
                'unit_price' => 499.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('payments')->updateOrInsert(
            [
                'order_id' => $orderId,
                'payment_method' => 'ToyyibPay',
            ],
            [
                'payment_amount' => 499.00,
                'payment_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
