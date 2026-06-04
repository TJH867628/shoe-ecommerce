<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>2Step Admin - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #f4f7fb;
        }
    </style>
</head>

<body class="min-h-screen text-slate-900">
    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
        <aside class="hidden lg:flex lg:flex-col bg-slate-950 text-white border-r border-slate-800 sticky top-0 h-screen">
            <div class="p-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center font-black text-xl">S</div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Admin Panel</p>
                        <h1 class="text-xl font-black tracking-tight">2Step</h1>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2 text-sm font-bold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">
                    <i class="fas fa-chart-pie w-5 text-amber-300"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.shoes.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.shoes.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">
                    <i class="fas fa-shoe-prints w-5"></i>
                    Shoes
                </a>
                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.brands.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">
                    <i class="fas fa-copyright w-5"></i>
                    Brands
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">
                    <i class="fas fa-receipt w-5"></i>
                    Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} transition-colors">
                    <i class="fas fa-users w-5"></i>
                    Users
                </a>
            </nav>

            <div class="mt-auto p-6 border-t border-slate-800">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Signed in as</p>
                <p class="mt-2 font-black">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-sm text-slate-400">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </aside>

        <main class="min-w-0">
            <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-200">
                <div class="px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button class="lg:hidden w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">@yield('eyebrow', 'Dashboard')</p>
                            <h2 class="font-black text-xl">@yield('page-title', 'Store Control Center')</h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                                <i class="fas fa-right-from-bracket"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 font-bold">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 font-bold">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>

</html>