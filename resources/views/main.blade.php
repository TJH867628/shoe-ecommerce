<!-- Extends the master layout we created -->
@extends('layout')

<!-- Sets the dynamic title tag -->
@section('title', 'Home')

<!-- The content that will be injected into @yield('content') -->
@section('content')
<main class="pb-20">
    <!-- Hero Section -->
    <section class="relative h-[85vh] min-h-[600px] flex items-center bg-slate-900 overflow-hidden rounded-b-[3rem] mx-2 mt-2">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1556906781-9a412961c28c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Hero Background" class="w-full h-full object-cover object-center opacity-40" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-2xl text-white">
                <span class="inline-block py-1 px-3 rounded-full bg-red-600/20 text-red-400 text-xs font-black uppercase tracking-widest mb-6 border border-red-500/30">
                    New Arrival Season
                </span>
                <h1 class="text-5xl md:text-7xl font-black leading-[1.1] tracking-tight mb-6">
                    Step Into <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-400">Greatness.</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-lg leading-relaxed">
                    Discover the latest premium footwear designed for performance, comfort, and undeniable street style.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ url('/shop') }}" class="bg-white text-slate-900 px-8 py-4 rounded-full font-black flex items-center gap-2 hover:bg-slate-100 transition-colors shadow-xl">
                        Shop Collection <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('/shop') }}" class="bg-transparent border-2 border-white/30 text-white px-8 py-4 rounded-full font-bold hover:bg-white/10 transition-colors">
                        View Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="container mx-auto px-6 py-24">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-2">Trending Now</h2>
                <p class="text-slate-500 font-medium">The most sought-after silhouettes this week.</p>
            </div>
            <a href="{{ url('/shop') }}" class="hidden md:flex items-center gap-2 text-slate-900 font-bold hover:text-red-600 transition-colors">
                View All <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Product Card 1 -->
            <a href="{{ url('/product') }}" class="group flex flex-col h-full text-left">
                <div class="relative aspect-square overflow-hidden bg-slate-100 rounded-3xl mb-4">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Aero Glide Pro" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out" />
                    <button class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md text-slate-400 hover:text-red-500 hover:scale-110 transition-all z-10" onclick="event.preventDefault()">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="flex-grow flex flex-col">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nike</span>
                    <h3 class="text-lg font-black text-slate-900 mb-1">Aero Glide Pro</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <i class="fas fa-star text-amber-400 text-sm"></i>
                        <span class="text-sm font-bold text-slate-700">4.8</span>
                    </div>
                    <div class="mt-auto pt-2">
                        <span class="text-xl font-black text-slate-900">$159.99</span>
                    </div>
                </div>
            </a>

            <!-- Add more product cards here -->
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="bg-slate-50 py-20 rounded-[3rem] mx-4 mb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-900 shadow-sm border border-slate-100 mb-6">
                        <i class="fas fa-truck text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-3">Free Express Delivery</h4>
                    <p class="text-slate-500">Free shipping on all orders over $100. Arrives in 2-3 business days.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-900 shadow-sm border border-slate-100 mb-6">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-3">Authenticity Guaranteed</h4>
                    <p class="text-slate-500">Every sneaker is verified by our team of experts before it reaches you.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-900 shadow-sm border border-slate-100 mb-6">
                        <i class="fas fa-undo text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-3">30-Day Returns</h4>
                    <p class="text-slate-500">Not the perfect fit? Return them within 30 days for a full refund.</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection