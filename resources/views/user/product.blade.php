@extends('layout')

@section('title', 'Shop All Shoes')

@section('content')
<div class="min-h-screen bg-slate-50">

    <!-- Header Section -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Shop All Shoes</h1>
                    <p class="text-slate-600 mt-1">Discover our complete collection of premium footwear</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <i class="fas fa-filter"></i>
                    <span id="shoeCount">Showing all products</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- SIDEBAR: Filters -->
            <aside class="w-full lg:w-64 shrink-0">

                <!-- Mobile Filter Toggle -->
                <button id="toggleFilters" class="w-full lg:hidden mb-4 bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-700 transition">
                    <i class="fas fa-filter"></i>
                    Toggle Filters
                </button>

                <!-- Filters Panel -->
                <div id="filtersPanel" class="hidden lg:block bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">

                    <!-- Search Filter -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-3">Search</label>
                        <input type="text" id="searchInput" placeholder="Search shoes..."
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <!-- Price Range -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Price Range</label>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                    <span>Min</span>
                                    <span id="priceMinDisplay">{{ number_format($priceMin ?? 0, 0) }}</span>
                                </div>
                                <input type="range" id="priceMin" min="{{ $priceMin ?? 0 }}" max="{{ $priceMax ?? 0 }}" value="{{ $priceMin ?? 0 }}"
                                    class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer">
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                    <span>Max</span>
                                    <span id="priceMaxDisplay">Unlimited</span>
                                </div>
                                <input type="range" id="priceMax" min="{{ $priceMin ?? 0 }}" max="{{ $priceMax ?? 0 }}" value="{{ $priceMax ?? 0 }}"
                                    class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-slate-900">$<span id="priceMinValue">{{ number_format($priceMin ?? 0, 0) }}</span></span>
                                <span class="text-slate-400">-</span>
                                <span class="text-sm font-semibold text-slate-900">$<span id="priceMaxValue">Unlimited</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Brand</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach($brands as $brand)
                            <label class="flex items-center gap-2 cursor-pointer hover:text-blue-600 transition">
                                <input type="checkbox" class="filter-checkbox w-4 h-4 rounded border-slate-300" value="{{ $brand }}">
                                <span class="text-sm text-slate-700">{{ $brand }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Size</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($sizes as $size)
                            <button type="button" class="size-filter-btn p-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:border-blue-500 hover:text-blue-600 transition">
                                {{ $size }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Filter -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Color</label>
                        <div class="space-y-2">
                            @php
                            $allColors = collect($sampleProducts)
                            ->flatMap(fn($p) => $p['colors'] ?? [])
                            ->filter()
                            ->unique()
                            ->values()
                            ->all();

                            $colorClasses = ['White' => 'bg-white', 'Black' => 'bg-black', 'Red' => 'bg-red-500', 'Blue' => 'bg-blue-500', 'Gray' => 'bg-gray-400', 'Brown' => 'bg-amber-800'];
                            @endphp
                            <div class="flex flex-wrap gap-2">
                                @forelse($allColors as $colorName)
                                <button type="button" data-color="{{ $colorName }}" class="color-filter-btn flex items-center gap-3 p-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition">
                                    <div class="w-6 h-6 rounded {{ $colorClasses[$colorName] ?? 'bg-slate-400' }} border border-slate-300"></div>
                                    <span class="text-sm text-slate-700">{{ $colorName }}</span>
                                </button>
                                @empty
                                <div class="text-sm text-slate-500">No colors available</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <div class="border-t pt-6">
                        <button onclick="clearAllFilters()" class="w-full py-2 px-4 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Clear All Filters
                        </button>
                    </div>
                </div>
            </aside>

            <!-- MAIN: Products Grid -->
            <main class="flex-1 min-w-0">

                <!-- Sort & View Options -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-slate-700">Sort by:</label>
                        <select id="sortSelect" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 transition">
                            <option value="newest">Latest</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="p-2 rounded-lg border border-slate-300 hover:bg-slate-100 transition grid-view-btn active" onclick="setGridView(3)">
                            <i class="fas fa-th text-slate-700"></i>
                        </button>
                        <button class="p-2 rounded-lg border border-slate-300 hover:bg-slate-100 transition" onclick="setGridView(2)">
                            <i class="fas fa-th-large text-slate-700"></i>
                        </button>
                        <button class="p-2 rounded-lg border border-slate-300 hover:bg-slate-100 transition" onclick="setGridView(1)">
                            <i class="fas fa-bars text-slate-700"></i>
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($sampleProducts as $product)
                    <a href="{{ route('products.show', ['shoeId' => $product['id']]) }}" class="product-card block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-blue-300 transition group" data-product-id="{{ $product['id'] }}">

                        <!-- Image Container -->
                        <div class="relative overflow-hidden bg-slate-100 aspect-square">
                            @if($product['image'])
                            <img src="{{ str_starts_with($product['image'], 'http') ? $product['image'] : asset('storage/' . $product['image']) }}"
                                alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-500">
                                No Image
                            </div>
                            @endif

                            <!-- Discount Badge -->
                            <div class="absolute top-3 right-3 bg-slate-900 text-white px-3 py-1 rounded-full text-sm font-bold">
                                In stock: {{ $product['stock'] }}
                            </div>

                            <!-- Quick View Button -->
                            <div class="absolute bottom-0 left-0 right-0 bg-blue-600 text-white font-semibold py-3 translate-y-full group-hover:translate-y-0 transition duration-300 text-center">
                                Quick View
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <p class="text-xs font-bold text-blue-600 mb-1">{{ $product['brand'] }}</p>
                            <h3 class="text-sm font-bold text-slate-900 mb-2 line-clamp-2 hover:text-blue-600 cursor-pointer">
                                {{ $product['name'] }}
                            </h3>

                            <!-- Price -->
                            <div class="flex items-baseline gap-2 mb-4">
                                <span class="text-lg font-bold text-slate-900">RM{{ number_format($product['price'], 2) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div id="pagination" class="flex items-center justify-center gap-2 mt-12"></div>

            </main>

        </div>
    </div>

</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        line-clamp: 2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .grid-view-btn.active {
        background-color: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 1024px) {
        #filtersPanel {
            position: fixed;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100vh;
            background: white;
            z-index: 50;
            overflow-y: auto;
            transition: left 0.3s ease;
            padding: 24px;
        }

        #filtersPanel.active {
            left: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.__products = @json($sampleProducts);

        // Toggle Filters on Mobile
        document.getElementById('toggleFilters')?.addEventListener('click', function () {
            const panel = document.getElementById('filtersPanel');
            panel?.classList.toggle('active');
        });

        // Price Slider
        const priceMinInput = document.getElementById('priceMin');
        const priceMaxInput = document.getElementById('priceMax');
        const priceMinDisplay = document.getElementById('priceMinDisplay');
        const priceMaxDisplay = document.getElementById('priceMaxDisplay');
        const priceMinValue = document.getElementById('priceMinValue');
        const priceMaxValue = document.getElementById('priceMaxValue');
        const catalogMaxPrice = {{ (float) ($priceMax ?? 0) }};

        function syncPriceRange() {
            if (!priceMinInput || !priceMaxInput) return;

            let minValue = parseFloat(priceMinInput.value) || 0;
            let maxValue = parseFloat(priceMaxInput.value) || 0;

            if (minValue > maxValue) {
                [minValue, maxValue] = [maxValue, minValue];
                priceMinInput.value = minValue;
                priceMaxInput.value = maxValue;
            }

            if (priceMinDisplay) priceMinDisplay.textContent = Math.round(minValue);
            if (priceMaxDisplay) {
                priceMaxDisplay.textContent = maxValue >= catalogMaxPrice ? 'Unlimited' : Math.round(maxValue);
            }
            if (priceMinValue) priceMinValue.textContent = Math.round(minValue);
            if (priceMaxValue) {
                priceMaxValue.textContent = maxValue >= catalogMaxPrice ? 'Unlimited' : Math.round(maxValue);
            }
        }

        priceMinInput?.addEventListener('input', function () {
            syncPriceRange();
            filterProducts(1);
        });

        priceMaxInput?.addEventListener('input', function () {
            syncPriceRange();
            filterProducts(1);
        });

        // Brand checkbox change
        document.querySelectorAll('.filter-checkbox').forEach(cb => cb.addEventListener('change', () => filterProducts(1)));

        // Size filter buttons (toggle active)
        document.querySelectorAll('.size-filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (this.disabled) return;
                this.classList.toggle('active');
                filterProducts(1);
            });
        });

        // Color option toggles
        document.querySelectorAll('.color-filter-btn').forEach(lbl => {
            lbl.addEventListener('click', function () {
                if (this.disabled) return;
                this.classList.toggle('active');
                filterProducts(1);
            });
        });

        // Grid View Toggle
        function setGridView(cols) {
            const grid = document.getElementById('productsGrid');
            grid.className = `grid gap-6`;

            if (cols === 1) {
                grid.classList.add('grid-cols-1');
            } else if (cols === 2) {
                grid.classList.add('sm:grid-cols-1', 'lg:grid-cols-2');
            } else {
                grid.classList.add('sm:grid-cols-2', 'lg:grid-cols-3');
            }

            // Update active button
            document.querySelectorAll('.grid-view-btn').forEach((btn, idx) => {
                btn.classList.remove('active');
                if (idx === (3 - cols)) btn.classList.add('active');
            });
        }

        // Search Filter
        document.getElementById('searchInput')?.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            filterProducts();
        });

        // Sort Products
        document.getElementById('sortSelect')?.addEventListener('change', function (e) {
            filterProducts(1);
        });

        // Clear All Filters
        function clearAllFilters() {
            const searchInput = document.getElementById('searchInput');
            const priceMin = document.getElementById('priceMin');
            const priceMax = document.getElementById('priceMax');
            const defaultPriceMin = {{ (float) ($priceMin ?? 0) }};
            const defaultPriceMax = {{ (float) ($priceMax ?? 0) }};

            if (searchInput) searchInput.value = '';
            if (priceMin) priceMin.value = defaultPriceMin;
            if (priceMax) priceMax.value = defaultPriceMax;
            syncPriceRange();
            document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.size-filter-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.color-filter-btn').forEach(c => c.classList.remove('active'));
            filterProducts(1);
        }

        // Filter Products with pagination
        function filterProducts(page = 1) {
            const perPage = 9; // products per page
            const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase();
            const minPrice = parseFloat(document.getElementById('priceMin')?.value || '{{ (float) ($priceMin ?? 0) }}') || 0;
            const maxPrice = parseFloat(document.getElementById('priceMax')?.value || String(catalogMaxPrice)) || catalogMaxPrice;
            const sortBy = document.getElementById('sortSelect')?.value || 'newest';

            // brands
            const selectedBrands = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(i => i.value);

            // sizes
            const selectedSizes = Array.from(document.querySelectorAll('.size-filter-btn.active')).map(b => b.textContent.trim());

            // colors
            const selectedColors = Array.from(document.querySelectorAll('.color-filter-btn.active')).map(c => c.getAttribute('data-color'));

            // Filter using window.__products
            const filtered = window.__products.filter(p => {
                if (searchTerm && !p.name.toLowerCase().includes(searchTerm)) return false;
                const price = parseFloat(p.price);
                if (price < minPrice || price > maxPrice) return false;
                if (selectedBrands.length && !selectedBrands.includes(p.brand)) return false;
                if (selectedSizes.length && (!p.sizes || !p.sizes.some(s => selectedSizes.includes(String(s))))) return false;
                if (selectedColors.length && (!p.colors || !p.colors.some(c => selectedColors.includes(String(c))))) return false;
                return true;
            });

            filtered.sort((a, b) => {
                if (sortBy === 'price-low') return parseFloat(a.price) - parseFloat(b.price);
                if (sortBy === 'price-high') return parseFloat(b.price) - parseFloat(a.price);
                if (sortBy === 'newest') return parseInt(b.id) - parseInt(a.id);
                return String(a.name).localeCompare(String(b.name));
            });

            // Compute availability for sizes/colors in real-time
            const filteredExceptSizes = window.__products.filter(p => {
                if (searchTerm && !p.name.toLowerCase().includes(searchTerm)) return false;
                const price = parseFloat(p.price);
                if (price < minPrice || price > maxPrice) return false;
                if (selectedBrands.length && !selectedBrands.includes(p.brand)) return false;
                // do NOT apply size filter here
                if (selectedColors.length && (!p.colors || !p.colors.some(c => selectedColors.includes(String(c))))) return false;
                return true;
            });

            const filteredExceptColors = window.__products.filter(p => {
                if (searchTerm && !p.name.toLowerCase().includes(searchTerm)) return false;
                const price = parseFloat(p.price);
                if (price < minPrice || price > maxPrice) return false;
                if (selectedBrands.length && !selectedBrands.includes(p.brand)) return false;
                // do NOT apply color filter here
                if (selectedSizes.length && (!p.sizes || !p.sizes.some(s => selectedSizes.includes(String(s))))) return false;
                return true;
            });

            const availableSizes = new Set(filteredExceptSizes.flatMap(p => p.sizes || []).map(String));
            const availableColors = new Set(filteredExceptColors.flatMap(p => p.colors || []).map(String));

            // Update size buttons availability
            document.querySelectorAll('.size-filter-btn').forEach(btn => {
                const size = btn.textContent.trim();
                const isAvailable = availableSizes.has(size);
                btn.disabled = !isAvailable;
                if (isAvailable) {
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    if (btn.classList.contains('active')) btn.classList.remove('active');
                }
            });

            // Update color buttons availability
            document.querySelectorAll('.color-filter-btn').forEach(btn => {
                const color = btn.getAttribute('data-color');
                const isAvailable = availableColors.has(String(color));
                btn.disabled = !isAvailable;
                if (isAvailable) {
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    if (btn.classList.contains('active')) btn.classList.remove('active');
                }
            });

            const total = filtered.length;
            const totalPages = Math.max(1, Math.ceil(total / perPage));
            const currentPage = Math.min(Math.max(1, page), totalPages);
            const start = (currentPage - 1) * perPage;
            const pageItems = filtered.slice(start, start + perPage);
            const pageItemIds = new Set(pageItems.map(p => String(p.id)));
            const productsGrid = document.getElementById('productsGrid');

            if (productsGrid) {
                pageItems.forEach(product => {
                    const card = document.querySelector(`.product-card[data-product-id="${product.id}"]`);
                    if (card) {
                        productsGrid.appendChild(card);
                    }
                });
            }

            // show/hide product cards based on filtered page items
            document.querySelectorAll('.product-card').forEach(card => {
                const pid = String(card.getAttribute('data-product-id'));
                const shouldShow = pageItemIds.has(pid);
                card.style.display = shouldShow ? '' : 'none';
            });

            const shoeCount = document.getElementById('shoeCount');
            if (shoeCount) {
                shoeCount.textContent = `Showing ${total} products`;
            }

            renderPagination(totalPages, currentPage);
        }

        function renderPagination(totalPages, currentPage) {
            const container = document.getElementById('pagination');
            container.innerHTML = '';

            const makeBtn = (label, page, disabled = false, cls = '') => {
                const btn = document.createElement('button');
                btn.className = `px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-semibold ${cls}`;
                btn.textContent = label;
                if (disabled) btn.disabled = true;
                btn.addEventListener('click', () => filterProducts(page));
                return btn;
            };

            // Prev
            container.appendChild(makeBtn('‹', Math.max(1, currentPage - 1), currentPage === 1));

            // pages (show up to 7 pages window)
            const maxButtons = 7;
            let start = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            let end = Math.min(totalPages, start + maxButtons - 1);
            if (end - start + 1 < maxButtons) start = Math.max(1, end - maxButtons + 1);

            for (let p = start; p <= end; p++) {
                const btn = makeBtn(String(p), p, false, p === currentPage ? 'bg-blue-600 text-white border-blue-600' : '');
                container.appendChild(btn);
            }

            // Next
            container.appendChild(makeBtn('›', Math.min(totalPages, currentPage + 1), currentPage === totalPages));
        }

        window.setGridView = setGridView;
        window.clearAllFilters = clearAllFilters;
        window.filterProducts = filterProducts;

        // initial filter render
        if (priceMinInput) priceMinInput.value = 0;
        if (priceMaxInput) priceMaxInput.value = catalogMaxPrice;
        syncPriceRange();
        filterProducts(1);
    });
</script>
@endsection
