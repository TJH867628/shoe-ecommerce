@extends('layout')

@section('title', $shoe->shoe_name ?? 'Product Details')

@section('content')
    @php
        $coverImage = $shoe->images->firstWhere('is_cover', true)
            ?? $shoe->images->sortBy('sort_order')->first();
        $coverUrl = $coverImage?->image_path
            ? (str_starts_with($coverImage->image_path, 'http') ? $coverImage->image_path : asset('storage/' . $coverImage->image_path))
            : null;
        $allImages = $shoe->images
            ->map(function ($image) {
                return [
                    'type' => 'shoe',
                    'url' => str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path),
                    'label' => $image->is_cover ? 'Cover' : 'Image',
                ];
            })
            ->concat(
                $shoe->variations->flatMap(function ($variation) {
                    return $variation->images->map(function ($image) use ($variation) {
                        return [
                            'type' => 'sku',
                            'url' => str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path),
                            'label' => 'SKU ' . $variation->sku_code,
                        ];
                    });
                })
            );
        $variationPayload = $shoe->variations
            ->map(function ($variation) {
                return [
                    'id' => $variation->id,
                    'attributes' => $variation->attributes ?? [],
                    'stock_quantity' => $variation->stock_quantity ?? 0,
                    'sku_code' => $variation->sku_code,
                    'images' => $variation->images
                        ->map(function ($image) {
                            return str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path);
                        })
                        ->values(),
                ];
            })
            ->values();
    @endphp
    <div class="min-h-screen bg-white py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-slate-500 mb-6">
                <a href="/" class="hover:text-slate-700">Home</a> / <a href="{{ route('product') }}"
                    class="hover:text-slate-700">Shop</a> / <span class="text-slate-900">{{ $shoe->shoe_name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                    <button type="button" id="main-image-button"
                        class="block w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        <span class="relative block aspect-square overflow-hidden rounded-lg bg-slate-100">
                            @if ($coverUrl)
                                <img id="main-product-image" src="{{ $coverUrl }}" alt="{{ $shoe->shoe_name }}"
                                    class="w-full h-full object-cover object-center">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500">
                                    No Image
                                </div>
                            @endif
                        </span>
                    </button>
                    @if(count($allImages) > 0)
                        <div class="mt-4 grid grid-cols-4 gap-3">
                            @forelse($allImages as $image)
                                <button type="button" data-preview-src="{{ $image['url'] }}"
                                    class="image-thumb rounded-lg border border-slate-100 overflow-hidden bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 {{ $loop->first ? 'active border-slate-900 border-2' : '' }}">
                                    <img src="{{ $image['url'] }}" alt="{{ $shoe->shoe_name }} {{ strtolower($image['label']) }}"
                                        class="aspect-square w-full object-cover object-center">
                                </button>
                            @empty
                                <div class="col-span-full aspect-square rounded-lg bg-slate-100"></div>
                            @endforelse
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-700 font-bold">
                            {{ $shoe->brand?->brand_name ?? 'Brand' }}
                        </p>
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
                        <input type="hidden" name="variation_id" id="variation-id-input" value="">
                        <input type="hidden" name="quantity" id="quantity-input" value="1">

                        <div class="space-y-4">
                            @foreach($options as $optionName => $values)

                                <div class="mb-4">

                                    <label class="font-semibold text-slate-900 mb-2 block">
                                        {{ $optionName }}
                                    </label>

                                    <div class="flex flex-wrap gap-2">

                                        @foreach($values as $value)

                                            <button type="button" class="option-btn px-3 py-2 border rounded-md text-sm bg-white"
                                                data-option="{{ $optionName }}" data-value="{{ $value }}">

                                                {{ $value }}

                                            </button>

                                        @endforeach

                                    </div>

                                </div>

                            @endforeach

                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center gap-2">
                                    <button type="button" id="qty-decrease"
                                        class="px-3 py-1.5 bg-slate-100 rounded-md">-</button>
                                    <span id="qty-display" class="px-4 font-bold">1</span>
                                    <button type="button" id="qty-increase"
                                        class="px-3 py-1.5 bg-slate-100 rounded-md">+</button>
                                </div>

                                <button type="submit" id="add-to-cart-btn"
                                    class="ml-auto bg-amber-500 hover:bg-amber-600 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold px-6 py-3 rounded-lg"
                                    disabled>Add to Cart</button>
                                <button type="button" id="buy-now-btn"
                                    class="ml-2 border border-slate-300 px-5 py-3 rounded-lg text-slate-700">Buy
                                    Now</button>
                            </div>

                            <div class="text-sm text-slate-600">
                                <span>Selected SKU: <strong id="selected-sku">-</strong></span>
                                <span class="ml-4">Stock: <strong id="selected-stock">0</strong></span>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div id="image-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 px-4 py-6">
        <div class="relative w-full max-w-5xl">
            <button type="button" id="close-image-preview"
                class="absolute right-0 top-0 z-10 -translate-y-12 rounded-full bg-white/95 px-4 py-2 text-sm font-bold text-slate-900 shadow-sm">Close</button>
            <img id="preview-modal-image" src="{{ $coverUrl }}" alt="{{ $shoe->shoe_name }} preview"
                class="max-h-[85vh] w-full rounded-lg object-contain bg-white">
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const variations = @json($variationPayload);

            const variationInput = document.getElementById('variation-id-input');
            const skuLabel = document.getElementById('selected-sku');
            const stockLabel = document.getElementById('selected-stock');
            const addToCartButton = document.getElementById('add-to-cart-btn');

            const quantityInput = document.getElementById('quantity-input');
            const quantityDisplay = document.getElementById('qty-display');

            const mainImage = document.getElementById('main-product-image');
            const mainImageButton = document.getElementById('main-image-button');

            const previewModal = document.getElementById('image-preview-modal');
            const previewModalImage = document.getElementById('preview-modal-image');
            const closePreviewButton = document.getElementById('close-image-preview');

            const imageButtons = Array.from(document.querySelectorAll('.image-thumb'));

            const optionButtons = Array.from(document.querySelectorAll('.option-btn'));

            const selectedOptions = {};

            function getCompatibleVariations() {
                return variations.filter(variation => {
                    const attrs = variation.attributes || {};

                    return Object.entries(selectedOptions).every(([key, value]) => {
                        return String(attrs[key]) === String(value);
                    });
                });
            }

            function getAvailableValuesForOption(optionName) {
                return new Set(
                    getCompatibleVariations()
                        .flatMap(variation => {
                            const attrs = variation.attributes || {};
                            const value = attrs[optionName];

                            return value === undefined || value === null
                                ? []
                                : [String(value)];
                        })
                );
            }

            function setMainImage(src) {

                if (!src || !mainImage) {
                    return;
                }

                mainImage.src = src;

                if (previewModalImage) {
                    previewModalImage.src = src;
                }

                imageButtons.forEach(button => {

                    const active =
                        button.dataset.previewSrc === src;

                    button.classList.toggle(
                        'active',
                        active
                    );

                    button.classList.toggle(
                        'border-slate-900',
                        active
                    );

                    button.classList.toggle(
                        'border-2',
                        active
                    );

                });
            }

            function resetSelection() {

                variationInput.value = '';

                skuLabel.textContent = '-';

                stockLabel.textContent = '0';

                addToCartButton.disabled = true;
            }

            function updateVariation() {
                const selectedCount =
                    Object.keys(selectedOptions).length;

                const partialMatch =
                    getCompatibleVariations()[0];

                if (!partialMatch) {

                    variationInput.value = '';

                    skuLabel.textContent = '-';

                    stockLabel.textContent = '0';

                    addToCartButton.disabled = true;

                    return;
                }

                const totalOptionCount =
                    Object.keys(
                        partialMatch.attributes || {}
                    ).length;

                if (
                    selectedCount <
                    totalOptionCount
                ) {

                    variationInput.value = '';

                    skuLabel.textContent = 'Select options';

                    stockLabel.textContent =
                        partialMatch.stock_quantity ?? 0;

                    addToCartButton.disabled = true;

                    return;
                }

                const exactMatch =
                    selectedCount === totalOptionCount
                        ? partialMatch
                        : null;

                if (!exactMatch || (exactMatch.stock_quantity ?? 0) <= 0) {

                    variationInput.value = '';

                    skuLabel.textContent = 'Unavailable';

                    stockLabel.textContent =
                        exactMatch?.stock_quantity ?? 0;

                    addToCartButton.disabled = true;

                    return;
                }

                variationInput.value =
                    exactMatch.id;

                skuLabel.textContent =
                    exactMatch.sku_code ?? '-';

                stockLabel.textContent =
                    exactMatch.stock_quantity ?? 0;

                addToCartButton.disabled = false;

                if (
                    exactMatch.images &&
                    exactMatch.images.length
                ) {

                    setMainImage(
                        exactMatch.images[0]
                    );

                }
            }

            function updateAvailability() {
                optionButtons.forEach(button => {
                    const option = button.dataset.option;
                    const value = button.dataset.value;
                    const isSelected = String(selectedOptions[option] ?? '') === String(value);
                    const availableValues = getAvailableValuesForOption(option);
                    const isAvailable = availableValues.has(String(value));
                    const shouldDisable = !isSelected && !isAvailable;

                    button.classList.toggle(
                        'active',
                        isSelected
                    );
                    button.classList.toggle(
                        'border-slate-900',
                        isSelected
                    );
                    button.classList.toggle(
                        'border-2',
                        isSelected
                    );
                    button.classList.toggle(
                        'opacity-40',
                        shouldDisable
                    );
                    button.classList.toggle(
                        'cursor-not-allowed',
                        shouldDisable
                    );

                    button.disabled = shouldDisable;
                });
            }

            optionButtons.forEach(button => {

                button.addEventListener(
                    'click',
                    function () {

                        if (this.disabled) {
                            return;
                        }

                        const option =
                            this.dataset.option;

                        const value =
                            this.dataset.value;

                        if (
                            String(selectedOptions[option] ?? '') ===
                            String(value)
                        ) {
                            delete selectedOptions[option];
                        } else {
                            selectedOptions[option] = value;
                        }

                        updateAvailability();
                        updateVariation();
                    }
                );

            });

            imageButtons.forEach(button => {

                button.addEventListener(
                    'click',
                    function () {

                        setMainImage(
                            this.dataset.previewSrc
                        );

                    }
                );

            });

            mainImageButton?.addEventListener(
                'click',
                function () {

                    if (
                        !previewModal ||
                        !mainImage
                    ) {
                        return;
                    }

                    previewModalImage.src =
                        mainImage.src;

                    previewModal.classList.remove(
                        'hidden'
                    );

                    previewModal.classList.add(
                        'flex'
                    );
                }
            );

            closePreviewButton?.addEventListener(
                'click',
                function () {

                    previewModal.classList.add(
                        'hidden'
                    );

                    previewModal.classList.remove(
                        'flex'
                    );

                }
            );

            previewModal?.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        previewModal
                    ) {

                        previewModal.classList.add(
                            'hidden'
                        );

                        previewModal.classList.remove(
                            'flex'
                        );

                    }

                }
            );

            document.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Escape'
                    ) {

                        previewModal?.classList.add(
                            'hidden'
                        );

                        previewModal?.classList.remove(
                            'flex'
                        );

                    }

                }
            );

            document
                .getElementById(
                    'qty-increase'
                )
                ?.addEventListener(
                    'click',
                    function () {

                        const current =
                            parseInt(
                                quantityDisplay.textContent,
                                10
                            ) || 1;

                        const next =
                            current + 1;

                        quantityDisplay.textContent =
                            next;

                        quantityInput.value =
                            next;

                    }
                );

            document
                .getElementById(
                    'qty-decrease'
                )
                ?.addEventListener(
                    'click',
                    function () {

                        const current =
                            parseInt(
                                quantityDisplay.textContent,
                                10
                            ) || 1;

                        const next =
                            Math.max(
                                1,
                                current - 1
                            );

                        quantityDisplay.textContent =
                            next;

                        quantityInput.value =
                            next;

                    }
                );

            updateAvailability();

        });
    </script>
@endsection
