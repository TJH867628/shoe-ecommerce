@extends('layout')

@section('title', $shoe->shoe_name ?? 'Product Details')

@section('content')
@php
$coverImage = $shoe->images->firstWhere('is_cover', true)
?? $shoe->images->sortBy('sort_order')->first();
$activeVariation = $shoe->variations->first();
@endphp

<div class="min-h-screen bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-slate-500 mb-6">
            <a href="/" class="hover:text-slate-700">Home</a> / <a href="{{ route('product') }}" class="hover:text-slate-700">Shop</a> / <span class="text-slate-900">{{ $shoe->shoe_name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <img src="{{ $coverImage?->image_path ?? 'https://via.placeholder.com/1200x1200?text=No+Image' }}" alt="{{ $shoe->shoe_name }}" class="w-full h-130 object-cover rounded-lg">
                @if($shoe->images->count() > 1)
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach($shoe->images->take(4) as $image)
                    <img src="{{ $image->image_path }}" alt="{{ $shoe->shoe_name }}" class="h-20 w-full object-cover rounded-lg border border-slate-100">
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex flex-col gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-cyan-700 font-bold">{{ $shoe->brand?->brand_name ?? 'Brand' }}</p>
                    <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $shoe->shoe_name }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-2xl font-bold text-slate-900">RM{{ number_format($shoe->shoe_price, 2) }}</div>
                    <div class="text-sm text-green-600 font-semibold">In Stock</div>
                </div>

                <div class="text-slate-700 leading-relaxed">
                    <p>{{ $shoe->shoe_description ?? 'No description available.' }}</p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-5" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                    <input type="hidden" name="variation_id" id="variation-id-input" value="{{ $activeVariation->id ?? '' }}">
                    <input type="hidden" name="quantity" id="quantity-input" value="1">

                    <div class="space-y-4">
                        <div>
                            <label class="font-semibold text-slate-900 mb-2 block">Size</label>
                            <div id="size-buttons" class="flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                <button type="button" data-size="{{ $size }}" class="size-btn px-3 py-2 border rounded-md text-sm bg-white hover:border-slate-300">{{ $size }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="font-semibold text-slate-900 mb-2 block">Color</label>
                            <div id="color-buttons" class="flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                <button type="button" data-color="{{ $color }}" class="color-btn px-3 py-2 border rounded-md text-sm bg-white hover:border-slate-300">{{ $color }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex items-center gap-2">
                                <button type="button" id="qty-decrease" class="px-3 py-1.5 bg-slate-100 rounded-md">-</button>
                                <span id="qty-display" class="px-4 font-bold">1</span>
                                <button type="button" id="qty-increase" class="px-3 py-1.5 bg-slate-100 rounded-md">+</button>
                            </div>

                            <button type="submit" id="add-to-cart-btn" class="ml-auto bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-lg">Add to Cart</button>
                            <button type="button" id="buy-now-btn" class="ml-2 border border-slate-300 px-5 py-3 rounded-lg text-slate-700">Buy Now</button>
                        </div>

                        <div class="text-sm text-slate-600">
                            <span>Selected SKU: <strong id="selected-sku">{{ $activeVariation->sku_code ?? '-' }}</strong></span>
                            <span class="ml-4">Stock: <strong id="selected-stock">{{ $activeVariation->stock_quantity ?? 0 }}</strong></span>
                        </div>
                    </div>
                </form>

                <div class="pt-6 border-t mt-2">
                    <h3 class="font-semibold text-slate-900 mb-3">Variation Matrix</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500 border-b">
                                    <th class="py-2 pr-4">SKU</th>
                                    <th class="py-2 pr-4">Size</th>
                                    <th class="py-2 pr-4">Color</th>
                                    <th class="py-2 pr-4">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($variationMatrix as $variation)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-2 pr-4 font-mono text-xs">{{ $variation['sku_code'] ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $variation['size'] ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $variation['color'] ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $variation['stock_quantity'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-slate-500">No variations found for this shoe.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-6 border-t">
                    <h3 class="font-semibold text-slate-900 mb-3">Specifications</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm text-slate-600">
                        <div>Brand: <span class="font-semibold text-slate-800">{{ $shoe->brand?->brand_name ?? '-' }}</span></div>
                        <div>Base Price: <span class="font-semibold text-slate-800">RM{{ number_format($shoe->shoe_price, 2) }}</span></div>
                        <div>Cover Image: <span class="font-semibold text-slate-800">{{ $coverImage?->image_path ? 'Available' : 'None' }}</span></div>
                        <div>Default SKU: <span class="font-mono text-sm text-slate-800">{{ $activeVariation->sku_code ?? 'No SKU yet' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script>
    // Build a lightweight variations array for client-side matching
    window.__shoeVariations = {
        !!json_encode($shoe - > variations - > map(function($v) {
            return [
                'id' => $v - > id,
                'attributes' => $v - > attributes ?? [],
                'stock_quantity' => $v - > stock_quantity ?? 0,
                'sku_code' => $v - > sku_code ?? null
            ];
        })) !!
    };

    // Build attribute mappings for Shopee-like filtering
    function buildAttributeMappings() {
        const sizesToColors = {}; // size -> [colors]
        const colorsToSizes = {}; // color -> [sizes]

        window.__shoeVariations.forEach(v => {
            const attrs = v.attributes || {};
            const size = String(attrs.size);
            const color = String(attrs.color);

            if (size && color) {
                if (!sizesToColors[size]) sizesToColors[size] = [];
                if (!colorsToSizes[color]) colorsToSizes[color] = [];

                if (!sizesToColors[size].includes(color)) sizesToColors[size].push(color);
                if (!colorsToSizes[color].includes(size)) colorsToSizes[color].push(size);
            }
        });

        return {
            sizesToColors,
            colorsToSizes
        };
    }

    const attributeMappings = buildAttributeMappings();

    function updateColorAvailability() {
        const selectedSizeBtn = document.querySelector('.size-btn.active');
        const selectedSize = selectedSizeBtn?.getAttribute('data-size');

        document.querySelectorAll('.color-btn').forEach(btn => {
            const color = btn.getAttribute('data-color');
            const isAvailable = !selectedSize || (attributeMappings.sizesToColors[selectedSize] && attributeMappings.sizesToColors[selectedSize].includes(color));

            if (isAvailable) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'line-through');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'line-through');
                // if this color is selected, deselect it
                if (btn.classList.contains('active')) {
                    btn.classList.remove('active', 'border-slate-900', 'border-2');
                }
            }
        });
    }

    function updateSizeAvailability() {
        const selectedColorBtn = document.querySelector('.color-btn.active');
        const selectedColor = selectedColorBtn?.getAttribute('data-color');

        document.querySelectorAll('.size-btn').forEach(btn => {
            const size = btn.getAttribute('data-size');
            const isAvailable = !selectedColor || (attributeMappings.colorsToSizes[selectedColor] && attributeMappings.colorsToSizes[selectedColor].includes(size));

            if (isAvailable) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'line-through');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'line-through');
                // if this size is selected, deselect it
                if (btn.classList.contains('active')) {
                    btn.classList.remove('active', 'border-slate-900', 'border-2');
                }
            }
        });
    }

    function findMatchingVariation() {
        const selectedSizeBtn = document.querySelector('.size-btn.active');
        const selectedColorBtn = document.querySelector('.color-btn.active');
        const size = selectedSizeBtn?.getAttribute('data-size') ?? null;
        const color = selectedColorBtn?.getAttribute('data-color') ?? null;

        // exact match
        for (const v of window.__shoeVariations) {
            const attrs = v.attributes || {};
            if (size && color) {
                if (String(attrs.size) === String(size) && String(attrs.color) === String(color)) return v;
            }
        }

        // fallback size
        if (size) {
            for (const v of window.__shoeVariations) {
                const attrs = v.attributes || {};
                if (String(attrs.size) === String(size)) return v;
            }
        }

        // fallback color
        if (color) {
            for (const v of window.__shoeVariations) {
                const attrs = v.attributes || {};
                if (String(attrs.color) === String(color)) return v;
            }
        }

        return null;
    }

    function updateVariationDisplay() {
        const match = findMatchingVariation();
        const input = document.getElementById('variation-id-input');
        const skuEl = document.getElementById('selected-sku');
        const stockEl = document.getElementById('selected-stock');
        const priceEl = document.getElementById('bottom-price');
        const addBtn = document.getElementById('add-to-cart-btn');

        if (match) {
            if (input) input.value = match.id;
            if (skuEl) skuEl.textContent = match.sku_code ?? '-';
            if (stockEl) stockEl.textContent = (match.stock_quantity ?? 0);
            if (priceEl) priceEl.textContent = 'RM' + (parseFloat({
                {
                    $shoe - > shoe_price
                }
            })?.toFixed(2) ?? '0.00');
            if (addBtn) addBtn.disabled = false;
        } else {
            // no match
            if (input) input.value = '';
            if (skuEl) skuEl.textContent = '-';
            if (stockEl) stockEl.textContent = '0';
            if (addBtn) addBtn.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // wire size/color buttons with Shopee-like availability logic
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active', 'border-slate-900', 'border-2'));
                this.classList.add('active', 'border-slate-900', 'border-2');
                updateColorAvailability();
                updateVariationDisplay();
            });
        });

        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.disabled) return;
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active', 'border-slate-900', 'border-2'));
                this.classList.add('active', 'border-slate-900', 'border-2');
                updateSizeAvailability();
                updateVariationDisplay();
            });
        });

        // quantity
        const qtyDisplay = document.getElementById('qty-display');
        const qtyInput = document.getElementById('quantity-input');
        document.getElementById('qty-increase').addEventListener('click', function() {
            let q = parseInt(qtyDisplay.textContent) || 1;
            q++;
            qtyDisplay.textContent = q;
            if (qtyInput) qtyInput.value = q;
        });
        document.getElementById('qty-decrease').addEventListener('click', function() {
            let q = parseInt(qtyDisplay.textContent) || 1;
            q = Math.max(1, q - 1);
            qtyDisplay.textContent = q;
            if (qtyInput) qtyInput.value = q;
        });

        // wire bottom bar buttons to form actions
        document.getElementById('bottom-add').addEventListener('click', function() {
            document.getElementById('add-to-cart-btn').click();
        });
        document.getElementById('bottom-buy').addEventListener('click', function() {
            document.getElementById('buy-now-btn').click();
        });

        // pre-select active variation attributes
        const activeId = {
            {
                $activeVariation ? - > id ?? 'null'
            }
        };
        if (activeId) {
            const av = window.__shoeVariations.find(v => v.id == activeId);
            if (av && av.attributes) {
                if (av.attributes.size) {
                    const btn = document.querySelector('.size-btn[data-size="' + av.attributes.size + '"]');
                    if (btn) btn.classList.add('active', 'border-slate-900', 'border-2');
                }
                if (av.attributes.color) {
                    const btn = document.querySelector('.color-btn[data-color="' + av.attributes.color + '"]');
                    if (btn) btn.classList.add('active', 'border-slate-900', 'border-2');
                }
            }
        }

        // Initial state - update availability based on any pre-selected options
        updateColorAvailability();
        updateSizeAvailability();
        updateVariationDisplay();
    });
</script>
@endsection

@endsection