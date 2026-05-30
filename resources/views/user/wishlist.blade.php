@extends('layout')

@section('title', 'My Wishlist')

@section('content')
<div class="bg-slate-50 py-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-[70vh]">
    
    <!-- Page Header -->
    <header class="mb-12 border-b border-stone-200 pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-stone-900 mb-2">My Wishlist</h1>
            <p class="text-stone-500">Keep track of the sneakers you love.</p>
        </div>
        <div class="bg-stone-100 text-stone-600 py-2 px-5 rounded-full text-sm font-bold shadow-inner">
            <span id="wishlist-count">{{ $wishlistItems->count() }}</span> Items Saved
        </div>
    </header>

    <!-- Wishlist Grid -->
    <div id="wishlist-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        
        @forelse($wishlistItems as $item)
            <div class="wishlist-item bg-white rounded-3xl p-5 border border-stone-100 shadow-sm relative group hover:-translate-y-2 transition-all duration-300" data-shoe-id="{{ $item->product->id }}">
                
                <!-- Remove from Wishlist Button -->
                <button type="button" class="remove-wishlist-btn absolute top-8 right-8 z-10 w-9 h-9 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-stone-400 hover:text-red-500 hover:bg-red-50 hover:scale-110 shadow-sm transition-all" title="Remove from wishlist">
                    <i class="fas fa-trash-alt text-sm"></i>
                </button>

                <!-- Product Image -->
                <a href="{{ route('products.show', ['shoeId' => $item->product->id]) }}" class="block bg-stone-100 rounded-2xl h-64 mb-4 overflow-hidden relative">
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                </a>

                <!-- Product Details -->
                <div class="flex justify-between items-start mb-4">
                    <div class="pe-3">
                        <p class="text-stone-400 text-xs font-bold uppercase tracking-wider">{{ $item->product->category }}</p>
                        <a href="{{ route('products.show', ['shoeId' => $item->product->id]) }}" class="text-lg font-bold text-stone-900 hover:text-amber-600 transition-colors line-clamp-1">
                            {{ $item->product->name }}
                        </a>
                    </div>
                    <span class="font-bold text-stone-800 text-lg">RM{{ number_format($item->product->price, 2) }}</span>
                </div>

                <!-- Add to Cart Action -->
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="shoe_id" value="{{ $item->product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-stone-900 text-white py-3 rounded-xl font-medium hover:bg-amber-600 hover:shadow-lg hover:shadow-amber-600/20 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart text-sm"></i> Move to Cart
                    </button>
                </form>
            </div>

        @empty
            <!-- Empty State Layout -->
            <div class="col-span-full py-24 flex flex-col items-center justify-center text-center bg-stone-50/50 rounded-[3rem] border border-dashed border-stone-200">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-stone-300 shadow-sm mb-6 border border-stone-100">
                    <i class="far fa-heart fa-3x"></i>
                </div>
                <h2 class="text-2xl font-bold text-stone-900 mb-3">Your wishlist is empty</h2>
                <p class="text-stone-500 mb-8 max-w-md mx-auto">You haven't saved any items yet. Start exploring our premium collection and tap the heart icon to save your favorites.</p>
                <a href="{{ route('product') }}" class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-amber-600 hover:shadow-xl hover:shadow-amber-600/20 transition-all">
                    Discover Footwear
                </a>
            </div>
        @endforelse

    </div>
</div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle remove from wishlist
        document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const item = this.closest('.wishlist-item');
                const shoeId = item.getAttribute('data-shoe-id');

                try {
                    const response = await fetch(`/wishlist/remove/${shoeId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove item with animation
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';
                        
                        setTimeout(() => {
                            item.remove();
                            
                            // Update count
                            const countSpan = document.getElementById('wishlist-count');
                            const newCount = data.wishlist_count;
                            countSpan.textContent = newCount;

                            // Check if wishlist is now empty
                            const grid = document.getElementById('wishlist-grid');
                            if (grid.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } catch (error) {
                    console.error('Error removing from wishlist:', error);
                }
            });
        });
    });
</script>
@endsection

@endsection