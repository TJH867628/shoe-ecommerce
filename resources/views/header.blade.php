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

            @auth
            <!-- User Menu Dropdown -->
            <div class="relative group">
                <button tabindex="0" aria-haspopup="true" class="hidden sm:inline-flex w-10 h-10 items-center justify-center text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                    <i class="fas fa-user"></i>
                </button>
                <div class="hidden group-hover:block group-focus-within:block absolute right-0 mt-1 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 pointer-events-auto transition-opacity duration-150 opacity-0 group-hover:opacity-100 group-focus-within:opacity-100">
                    <div class="p-4 border-b border-slate-100">
                        <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">My Orders</a>
                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Admin Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="hidden sm:inline-flex w-10 h-10 items-center justify-center {{ request()->is('login') || request()->is('register') ? 'text-white bg-slate-900' : 'text-slate-600 hover:bg-slate-100' }} rounded-full transition-colors" aria-label="Login">
                <i class="fas fa-user"></i>
            </a>
            @endauth

            @php
            $cartCount = \App\Http\Controllers\CartController::currentCartCount();
            @endphp
            <a href="{{ route('cart.index') }}" class="w-10 h-10 flex items-center justify-center {{ request()->is('user/cart') ? 'text-white bg-slate-900' : 'text-slate-900 bg-slate-100 hover:bg-slate-200' }} rounded-full transition-colors relative" aria-label="Cart">
                <i class="fas fa-shopping-cart"></i>
                @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">{{ $cartCount }}</span>
                @endif
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

        @auth
        <div class="py-2 border-t-2 border-slate-100">
            <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
        </div>
        <a href="{{ route('user.profile') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Profile</a>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Admin Dashboard</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-xl font-black text-red-600 py-2">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>
        @else
        <a href="{{ route('login') }}" class="text-left text-xl font-black text-slate-900 py-2 border-b border-slate-50">Login</a>
        <a href="{{ route('register') }}" class="text-left text-xl font-black text-slate-900 py-2">Register</a>
        @endauth
    </div>
</nav>