<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Shoe;
use App\Models\ShoeVariations;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now();

        $customer = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0123456789',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0999999999',
            ]
        );

        $brands = [
            'Nike' => 'Performance footwear and sportswear for training, running, and everyday wear.',
            'Adidas' => 'Athletic and lifestyle shoes built for comfort, movement, and daily style.',
            'Puma' => 'Street-ready sneakers with sporty silhouettes and bold designs.',
            'New Balance' => 'Comfort-first shoes with classic profiles and dependable support.',
            'Asics' => 'Running-focused footwear with stable cushioning and lightweight builds.',
        ];

        $brandModels = [];
        foreach ($brands as $name => $description) {
            $brandModels[$name] = Brand::updateOrCreate(
                ['brand_name' => $name],
                ['brand_description' => $description]
            );
        }

        $shoeImages = [
            'Black' => 'images/seed-shoes/realistic-black.svg',
            'Blue' => 'images/seed-shoes/realistic-blue.svg',
            'White' => 'images/seed-shoes/realistic-white.svg',
            'Grey' => 'images/seed-shoes/realistic-grey.svg',
            'Red' => 'images/seed-shoes/realistic-red.svg',
        ];

        $shoeCatalog = [
            [
                'name' => 'Air Max Runner',
                'brand' => 'Nike',
                'price' => 499.00,
                'description' => 'Lightweight runner with responsive cushioning for daily training and long walks.',
                'images' => [
                    ['url' => $shoeImages['Black'], 'cover' => true],
                ],
                'variations' => [
                    ['attributes' => ['size' => 40, 'color' => 'Black'], 'stock' => 14, 'sku' => 'AMR-40-BLK'],
                    ['attributes' => ['size' => 41, 'color' => 'Black'], 'stock' => 11, 'sku' => 'AMR-41-BLK'],
                    ['attributes' => ['size' => 42, 'color' => 'Blue'], 'stock' => 8, 'sku' => 'AMR-42-BLU'],
                    ['attributes' => ['size' => 43, 'color' => 'White'], 'stock' => 5, 'sku' => 'AMR-43-WHT'],
                ],
            ],
            [
                'name' => 'Ultraboost Street',
                'brand' => 'Adidas',
                'price' => 579.00,
                'description' => 'Comfort-focused sneaker for everyday wear, commute days, and light workouts.',
                'images' => [
                    ['url' => $shoeImages['White'], 'cover' => true],
                ],
                'variations' => [
                    ['attributes' => ['size' => 40, 'color' => 'White'], 'stock' => 10, 'sku' => 'UBS-40-WHT'],
                    ['attributes' => ['size' => 41, 'color' => 'White'], 'stock' => 12, 'sku' => 'UBS-41-WHT'],
                    ['attributes' => ['size' => 42, 'color' => 'Grey'], 'stock' => 9, 'sku' => 'UBS-42-GRY'],
                    ['attributes' => ['size' => 43, 'color' => 'Black'], 'stock' => 6, 'sku' => 'UBS-43-BLK'],
                ],
            ],
            [
                'name' => 'RS-X Games',
                'brand' => 'Puma',
                'price' => 399.00,
                'description' => 'Chunky retro-inspired sneaker with bold cushioning and a streetwear edge.',
                'images' => [
                    ['url' => $shoeImages['Red'], 'cover' => true],
                ],
                'variations' => [
                    ['attributes' => ['size' => 39, 'color' => 'Red'], 'stock' => 7, 'sku' => 'RSX-39-RED'],
                    ['attributes' => ['size' => 40, 'color' => 'Red'], 'stock' => 8, 'sku' => 'RSX-40-RED'],
                    ['attributes' => ['size' => 41, 'color' => 'Black'], 'stock' => 9, 'sku' => 'RSX-41-BLK'],
                    ['attributes' => ['size' => 42, 'color' => 'Grey'], 'stock' => 6, 'sku' => 'RSX-42-GRY'],
                ],
            ],
            [
                'name' => '990v6',
                'brand' => 'New Balance',
                'price' => 699.00,
                'description' => 'Premium everyday trainer with elevated comfort and a classic running aesthetic.',
                'images' => [
                    ['url' => $shoeImages['Grey'], 'cover' => true],
                ],
                'variations' => [
                    ['attributes' => ['size' => 40, 'color' => 'Grey'], 'stock' => 4, 'sku' => 'NB990-40-GRY'],
                    ['attributes' => ['size' => 41, 'color' => 'Grey'], 'stock' => 6, 'sku' => 'NB990-41-GRY'],
                    ['attributes' => ['size' => 42, 'color' => 'Blue'], 'stock' => 5, 'sku' => 'NB990-42-BLU'],
                    ['attributes' => ['size' => 43, 'color' => 'Black'], 'stock' => 3, 'sku' => 'NB990-43-BLK'],
                ],
            ],
            [
                'name' => 'Gel Pulse',
                'brand' => 'Asics',
                'price' => 459.00,
                'description' => 'Neutral running shoe with reliable shock absorption and lightweight support.',
                'images' => [
                    ['url' => $shoeImages['Blue'], 'cover' => true],
                ],
                'variations' => [
                    ['attributes' => ['size' => 40, 'color' => 'Blue'], 'stock' => 11, 'sku' => 'GEL-40-BLU'],
                    ['attributes' => ['size' => 41, 'color' => 'Black'], 'stock' => 10, 'sku' => 'GEL-41-BLK'],
                    ['attributes' => ['size' => 42, 'color' => 'White'], 'stock' => 7, 'sku' => 'GEL-42-WHT'],
                    ['attributes' => ['size' => 43, 'color' => 'Red'], 'stock' => 5, 'sku' => 'GEL-43-RED'],
                ],
            ],
        ];

        $primaryVariations = [];

        foreach ($shoeCatalog as $shoeSeed) {
            $shoe = Shoe::updateOrCreate(
                [
                    'shoe_name' => $shoeSeed['name'],
                    'brand_id' => $brandModels[$shoeSeed['brand']]->id,
                ],
                [
                    'shoe_description' => $shoeSeed['description'],
                    'shoe_price' => $shoeSeed['price'],
                ]
            );
            DB::table('shoe_images')->where('shoe_id', $shoe->id)->delete();
            foreach ($shoeSeed['images'] as $index => $image) {
                DB::table('shoe_images')->insert([
                    'shoe_id' => $shoe->id,
                    'image_path' => $image['url'],
                    'is_cover' => $image['cover'],
                    'sort_order' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('shoe_options')->updateOrInsert(
                ['shoe_id' => $shoe->id],
                ['option_name' => 'Size, Color', 'updated_at' => $now, 'created_at' => $now]
            );

            foreach ($shoeSeed['variations'] as $variationSeed) {
                $variation = ShoeVariations::updateOrCreate(
                    [
                        'sku_code' => $variationSeed['sku'],
                    ],
                    [
                        'shoe_id' => $shoe->id,
                        'attributes' => $variationSeed['attributes'],
                        'stock_quantity' => $variationSeed['stock'],
                    ]
                );

                $primaryVariations[] = $variation;

                DB::table('shoe_variation_images')->where('shoe_variation_id', $variation->id)->delete();
                $variationColor = $variationSeed['attributes']['color'] ?? null;
                DB::table('shoe_variation_images')->insert([
                    'shoe_variation_id' => $variation->id,
                    'image_path' => $shoeImages[$variationColor] ?? $shoeSeed['images'][0]['url'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $cart = Cart::updateOrCreate(
            ['user_id' => $customer->id],
            []
        );

        CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'shoe_variation_id' => $primaryVariations[0]->id,
            ],
            ['quantity' => 1]
        );

        $order = DB::table('orders')->insertGetId([
            'user_id' => $customer->id,
            'total_amount' => 499.00,
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('order_items')->updateOrInsert(
            [
                'order_id' => $order,
                'shoe_variation_id' => $primaryVariations[0]->id,
            ],
            [
                'quantity' => 1,
                'unit_price' => 499.00,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('payments')->updateOrInsert(
            ['order_id' => $order],
            [
                'payment_amount' => 499.00,
                'payment_status' => 'pending',
                'payment_method' => 'ToyyibPay',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
