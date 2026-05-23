@extends('layout')

@section('title', isset($shoe) ? $shoe->shoe_name : 'Product Details')

@section('content')
<div class="min-h-screen bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-slate-500 mb-6">
            <a href="/" class="hover:text-slate-700">Home</a> / <a href="/shoes" class="hover:text-slate-700">Shop</a> / <span class="text-slate-900">{{ $shoe->shoe_name ?? 'Product' }}</span>
        </nav>

        @php
            // Sample fallback products (same as listing)
            $sampleProducts = [
                ['id' => 1, 'name' => 'Air Max Pro', 'brand' => 'Nike', 'price' => 129.99, 'image' => 'https://images.unsplash.com/photo-1528701800489-47645c2a34f2?auto=format&fit=crop&w=1200&q=80', 'rating' => 5, 'reviews' => 128, 'discount' => 18, 'desc' => 'Lightweight running shoe with responsive cushioning.'],
                ['id' => 2, 'name' => 'Ultraboost 22', 'brand' => 'Adidas', 'price' => 149.99, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80', 'rating' => 5, 'reviews' => 95, 'discount' => 15, 'desc' => 'Comfort-focused sneaker for daily wear and training.'],
                ['id' => 3, 'name' => 'Jordan Legacy', 'brand' => 'Jordan', 'price' => 159.99, 'image' => 'https://images.unsplash.com/photo-1526178615590-8d5f6c9b9b1f?auto=format&fit=crop&w=1200&q=80', 'rating' => 5, 'reviews' => 203, 'discount' => 20, 'desc' => 'Iconic basketball silhouette with modern comfort.'],
                ['id' => 4, 'name' => 'RS-X Games', 'brand' => 'Puma', 'price' => 99.99, 'image' => 'https://images.unsplash.com/photo-1542293787938-c9e299b880f4?auto=format&fit=crop&w=1200&q=80', 'rating' => 4, 'reviews' => 67, 'discount' => 12, 'desc' => 'Bold design and cushioned sole for everyday wear.'],
                ['id' => 5, 'name' => '990v6', 'brand' => 'New Balance', 'price' => 139.99, 'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=1200&q=80', 'rating' => 5, 'reviews' => 142, 'discount' => 10, 'desc' => 'Classic support and premium materials.'],
            ];

            $id = $shoe->id ?? request()->route('shoeId') ?? request()->route('id') ?? request()->get('id');
            $product = null;
            if (!isset($shoe)) {
                if ($id) {
                    foreach ($sampleProducts as $p) {
                        if ($p['id'] == $id) { $product = (object) $p; break; }
                    }
                }
                if (!$product) {
                    $product = (object) $sampleProducts[0];
                }
            }
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <img src="{{ $shoe->images->first()->image_path ?? ($product->image ?? '') }}" alt="{{ $shoe->shoe_name ?? $product->name }}" class="w-full h-[520px] object-cover rounded-lg">
            </div>

            <div class="flex flex-col gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">{{ $shoe->shoe_name ?? $product->name }}</h1>
                    <p class="text-sm text-slate-600 mt-2">By <span class="font-semibold text-slate-800">{{ $shoe->brand->brand_name ?? $product->brand }}</span></p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-2xl font-bold text-slate-900">${{ isset($shoe) ? number_format($shoe->shoe_price,2) : number_format($product->price,2) }}</div>
                    @if(($shoe->shoe_price ?? $product->price) && ($shoe->shoe_price ?? $product->price) > 0)
                        <div class="text-sm text-slate-500 line-through">${{ number_format((($shoe->shoe_price ?? $product->price) * 1.2),2) }}</div>
                    @endif
                    <div class="ml-auto text-sm text-green-600 font-semibold">In Stock</div>
                </div>

                <div class="text-slate-700 leading-relaxed">
                    <p>{{ $shoe->shoe_description ?? $product->desc ?? 'No description available.' }}</p>
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <label class="font-semibold">Size</label>
                    <select class="px-3 py-2 border rounded-lg">
                        @for($s = 6; $s <= 13; $s++)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg">Add to Cart</button>
                    <button class="border border-slate-300 px-5 py-3 rounded-lg text-slate-700">Add to Wishlist</button>
                </div>

                <div class="pt-6 border-t mt-6">
                    <h3 class="font-semibold text-slate-900 mb-3">Specifications</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm text-slate-600">
                        <div>Material: <span class="font-semibold text-slate-800">Premium Leather</span></div>
                        <div>Sole: <span class="font-semibold text-slate-800">Rubber</span></div>
                        <div>Weight: <span class="font-semibold text-slate-800">280g</span></div>
                        <div>SKU: <span class="font-mono text-sm text-slate-800">{{ $shoe->sku_code ?? 'SKU-12345' }}</span></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
