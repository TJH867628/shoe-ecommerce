@extends('layout')

@section('title', 'Shop All Shoes')

@section('content')
<div class="min-h-screen bg-slate-50">
    
    <!-- Header Section -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-40">
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
            <aside class="w-full lg:w-64 flex-shrink-0">
                
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
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="range" id="priceMin" min="0" max="500" value="0" 
                                       class="flex-1 h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-slate-900">$<span id="priceDisplay">0</span></span>
                                <span class="text-slate-400">-</span>
                                <span class="text-sm font-semibold text-slate-900">$500+</span>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Brand</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @php
                                $brands = ['Nike', 'Adidas', 'Puma', 'New Balance', 'Jordan', 'Converse', 'Vans', 'Timberland'];
                            @endphp
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
                            @php
                                $sizes = ['6', '7', '8', '9', '10', '11', '12', '13'];
                            @endphp
                            @foreach($sizes as $size)
                                <button class="size-filter-btn p-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 hover:border-blue-500 hover:text-blue-600 transition">
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
                                $colors = ['White' => '#ffffff', 'Black' => '#000000', 'Red' => '#ef4444', 'Blue' => '#3b82f6', 'Gray' => '#9ca3af', 'Brown' => '#92400e'];
                            @endphp
                            @foreach($colors as $colorName => $colorCode)
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition">
                                    <div class="w-6 h-6 rounded border border-slate-300" style="background-color: {{ $colorCode }}"></div>
                                    <span class="text-sm text-slate-700">{{ $colorName }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-slate-900 mb-3">Rating</label>
                        <div class="space-y-2">
                            @for($i = 5; $i >= 3; $i--)
                                <label class="flex items-center gap-2 cursor-pointer hover:text-blue-600 transition">
                                    <input type="checkbox" class="w-4 h-4 rounded border-slate-300">
                                    <div class="flex items-center gap-1">
                                        @for($j = 0; $j < $i; $j++)
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @endfor
                                        @for($j = $i; $j < 5; $j++)
                                            <i class="far fa-star text-yellow-400 text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-600">& up</span>
                                </label>
                            @endfor
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
                            <option value="newest">Newest</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="popularity">Most Popular</option>
                            <option value="rating">Highest Rated</option>
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
                    
                    <!-- Sample Product Card (Repeat for each product) -->
                    @php
                        $sampleProducts = [
                            ['id' => 1, 'name' => 'Air Max Pro', 'brand' => 'Nike', 'price' => 129.99, 'image' => 'https://via.placeholder.com/300?text=Air+Max+Pro', 'rating' => 5, 'reviews' => 128, 'discount' => 18],
                            ['id' => 2, 'name' => 'Ultraboost 22', 'brand' => 'Adidas', 'price' => 149.99, 'image' => 'https://via.placeholder.com/300?text=Ultraboost+22', 'rating' => 5, 'reviews' => 95, 'discount' => 15],
                            ['id' => 3, 'name' => 'Jordan Legacy', 'brand' => 'Jordan', 'price' => 159.99, 'image' => 'https://via.placeholder.com/300?text=Jordan+Legacy', 'rating' => 5, 'reviews' => 203, 'discount' => 20],
                            ['id' => 4, 'name' => 'RS-X Games', 'brand' => 'Puma', 'price' => 99.99, 'image' => 'https://via.placeholder.com/300?text=RS-X+Games', 'rating' => 4, 'reviews' => 67, 'discount' => 12],
                            ['id' => 5, 'name' => '990v6', 'brand' => 'New Balance', 'price' => 139.99, 'image' => 'https://via.placeholder.com/300?text=990v6', 'rating' => 5, 'reviews' => 142, 'discount' => 10],
                            ['id' => 6, 'name' => 'Chuck 70', 'brand' => 'Converse', 'price' => 69.99, 'image' => 'https://via.placeholder.com/300?text=Chuck+70', 'rating' => 4, 'reviews' => 89, 'discount' => 8],
                            ['id' => 7, 'name' => 'Old Skool Pro', 'brand' => 'Vans', 'price' => 79.99, 'image' => 'https://via.placeholder.com/300?text=Old+Skool+Pro', 'rating' => 5, 'reviews' => 156, 'discount' => 14],
                            ['id' => 8, 'name' => 'Timberland Classic', 'brand' => 'Timberland', 'price' => 189.99, 'image' => 'https://via.placeholder.com/300?text=Timberland+Classic', 'rating' => 4, 'reviews' => 110, 'discount' => 5],
                            ['id' => 9, 'name' => 'Dunk Low SB', 'brand' => 'Nike', 'price' => 119.99, 'image' => 'https://via.placeholder.com/300?text=Dunk+Low+SB', 'rating' => 5, 'reviews' => 234, 'discount' => 16],
                        ];
                    @endphp

                    @foreach($sampleProducts as $product)
                        <div class="product-card bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-blue-300 transition group">
                            
                            <!-- Image Container -->
                            <div class="relative overflow-hidden bg-slate-100 aspect-square">
                                <img src="{{ $product['image'] }}" 
                                     alt="{{ $product['name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                
                                <!-- Discount Badge -->
                                @if($product['discount'] > 0)
                                    <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        -{{ $product['discount'] }}%
                                    </div>
                                @endif

                                <!-- Wishlist Button -->
                                <button class="absolute top-3 left-3 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-red-50 transition opacity-0 group-hover:opacity-100">
                                    <i class="far fa-heart text-red-500 text-lg"></i>
                                </button>

                                <!-- Quick View Button -->
                                <button class="absolute bottom-0 left-0 right-0 bg-blue-600 text-white font-semibold py-3 translate-y-full group-hover:translate-y-0 transition duration-300">
                                    Quick View
                                </button>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <p class="text-xs font-bold text-blue-600 mb-1">{{ $product['brand'] }}</p>
                                <h3 class="text-sm font-bold text-slate-900 mb-2 line-clamp-2 hover:text-blue-600 cursor-pointer">
                                    {{ $product['name'] }}
                                </h3>

                                <!-- Rating -->
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 0; $i < $product['rating']; $i++)
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @endfor
                                        @for($i = $product['rating']; $i < 5; $i++)
                                            <i class="far fa-star text-yellow-400 text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-600">({{ $product['reviews'] }})</span>
                                </div>

                                <!-- Price -->
                                <div class="flex items-baseline gap-2 mb-4">
                                    <span class="text-lg font-bold text-slate-900">${{ number_format($product['price'], 2) }}</span>
                                    @if($product['discount'] > 0)
                                        <span class="text-sm text-slate-500 line-through">
                                            ${{ number_format($product['price'] / (1 - $product['discount']/100), 2) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button -->
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart text-sm"></i>
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-center gap-2 mt-12">
                    <button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-semibold">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    @for($i = 1; $i <= 5; $i++)
                        @if($i == 1)
                            <button class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                                {{ $i }}
                            </button>
                        @else
                            <button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-semibold">
                                {{ $i }}
                            </button>
                        @endif
                    @endfor
                    
                    <button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-semibold">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

            </main>

        </div>
    </div>

</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .grid-view-btn.active {
        @apply bg-blue-600 text-white border-blue-600;
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
            overflow-y-auto;
            transition: left 0.3s ease;
            padding: 24px;
        }

        #filtersPanel.active {
            left: 0;
        }
    }
</style>

<script>
    // Toggle Filters on Mobile
    document.getElementById('toggleFilters').addEventListener('click', function() {
        const panel = document.getElementById('filtersPanel');
        panel.classList.toggle('active');
    });

    // Price Slider
    document.getElementById('priceMin').addEventListener('input', function() {
        document.getElementById('priceDisplay').textContent = Math.round(this.value);
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
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterProducts();
    });

    // Sort Products
    document.getElementById('sortSelect').addEventListener('change', function(e) {
        console.log('Sorting by:', e.target.value);
        // Add sorting logic here
    });

    // Clear All Filters
    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('priceMin').value = 0;
        document.getElementById('priceDisplay').textContent = '0';
        document.querySelectorAll('.filter-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.size-filter-btn').forEach(btn => btn.classList.remove('active'));
        filterProducts();
    }

    // Filter Products
    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('shoeCount').textContent = `Showing ${visibleCount} products`;
    }
</script>
@endsection
