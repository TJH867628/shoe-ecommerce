<!-- Navigation -->
<nav class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-100">
    <div class="container mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2 cursor-pointer group text-slate-900 text-decoration-none">
            <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white transform group-hover:-rotate-6 transition-transform">
                <span class="font-black text-xl italic">S</span>
            </div>
            <span class="text-2xl font-black tracking-tighter">2Step.</span>
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-sm font-bold {{ request()->is('/') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-900' }} transition-colors">Home</a>
            <a href="{{ url('/user/product') }}" class="text-sm font-bold {{ request()->is('user/product*') || request()->is('user/products/*') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-900' }} transition-colors">Product</a>
            <a href="{{ url('/user/wishlist') }}" class="text-sm font-bold {{ request()->is('user/wishlist') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-900' }} transition-colors">Wishlist</a>
            <a href="{{ url('/about') }}" class="text-sm font-bold {{ request()->is('about') ? 'text-slate-900' : 'text-slate-400 hover:text-slate-900' }} transition-colors">About</a>
        </div>

        <!-- Icons -->
        <div class="flex items-center gap-4">
            <button class="hidden sm:inline-flex w-10 h-10 items-center justify-center text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('login') }}" class="hidden sm:inline-flex w-10 h-10 items-center justify-center {{ request()->is('login') || request()->is('register') ? 'text-white bg-slate-900' : 'text-slate-600 hover:bg-slate-100' }} rounded-full transition-colors" aria-label="Login">
                <i class="fas fa-user"></i>
            </a>
            <a href="{{ route('cart.index') }}" class="w-10 h-10 flex items-center justify-center {{ request()->is('user/cart') ? 'text-white bg-slate-900' : 'text-slate-900 bg-slate-100 hover:bg-slate-200' }} rounded-full transition-colors relative" aria-label="Cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">2</span>
            </a>

            <!-- Mobile Menu Toggle -->
            <button onclick="toggleMobileMenu()" class="w-10 h-10 flex items-center justify-center text-slate-900 md:hidden ml-2">
                <i class="fas fa-bars text-xl" id="menuIcon"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobileMenu" class="hidden absolute top-20 left-0 w-full bg-white border-b border-slate-100 py-4 px-6 shadow-xl flex-col gap-4">
        <a href="{{ url('/') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Home</a>
        <a href="{{ url('/user/product') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Product</a>
        <a href="{{ url('/user/wishlist') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Wishlist</a>
        <a href="{{ route('cart.index') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Cart</a>
        <a href="{{ url('/about') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">About</a>
    </div>
</nav>