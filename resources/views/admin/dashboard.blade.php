@extends('admin.layout')

@section('title', 'Dashboard')
@section('eyebrow', 'Dashboard')
@section('page-title', 'Store Control Center')

@section('content')
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    @foreach ($stats as $stat)
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">{{ $stat['label'] }}</p>
        <div class="mt-3 flex items-end justify-between gap-3">
            <p class="text-3xl font-black">{{ $stat['value'] }}</p>
            <span class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
                <i class="fas fa-chart-simple"></i>
            </span>
        </div>
        <p class="mt-3 text-sm text-slate-500">{{ $stat['note'] }}</p>
    </div>
    @endforeach
</section>

<section class="mt-6 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
    <div class="space-y-6 min-w-0">
        <article class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Inventory</p>
                    <h3 class="text-lg font-black mt-1">Recent shoes</h3>
                </div>
                <a href="{{ route('admin.shoes.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-180 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Product</th>
                            <th class="px-5 py-3">Brand</th>
                            <th class="px-5 py-3">Price</th>
                            <th class="px-5 py-3">Stock</th>
                            <th class="px-5 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentShoes as $shoe)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $shoe->shoe_name }}</div>
                                <div class="text-slate-500 text-xs mt-1 line-clamp-1">{{ $shoe->shoe_description }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</td>
                            <td class="px-5 py-4 font-bold">RM {{ number_format((float) $shoe->shoe_price, 2) }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $shoe->variations->sum('stock_quantity') }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.shoes.show', $shoe->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Transactions</p>
                    <h3 class="text-lg font-black mt-1">Recent orders</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-180 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentOrders as $order)
                        <tr>
                            <td class="px-5 py-4 font-bold text-slate-900">#{{ $order->id }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="px-5 py-4 font-bold">RM {{ number_format((float) $order->total_amount, 2) }}</td>
                            <td class="px-5 py-4"><span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-[0.2em] bg-slate-100 text-slate-700">{{ $order->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </div>

    <aside class="space-y-6 min-w-0">
        <article class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Users</p>
                    <h3 class="text-lg font-black mt-1">Recent users</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100 max-h-117.5 overflow-auto">
                @foreach ($recentUsers as $user)
                <div class="px-5 sm:px-6 py-4 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $user->email }}</div>
                    </div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-[0.2em] {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $user->role }}
                    </span>
                </div>
                @endforeach
            </div>
        </article>

        <article class="rounded-3xl bg-amber-400 text-slate-950 shadow-sm p-6">
            <p class="text-xs uppercase tracking-[0.25em] font-bold">Quick Actions</p>
            <div class="mt-4 space-y-3 text-sm font-bold">
                <a href="{{ route('admin.shoes.index') }}" class="block rounded-2xl bg-white/70 px-4 py-3 hover:bg-white transition-colors">Manage shoes</a>
                <a href="{{ route('admin.brands.index') }}" class="block rounded-2xl bg-white/70 px-4 py-3 hover:bg-white transition-colors">Manage brands</a>
                <a href="{{ route('admin.orders.index') }}" class="block rounded-2xl bg-white/70 px-4 py-3 hover:bg-white transition-colors">Manage orders</a>
                <a href="{{ route('admin.users.index') }}" class="block rounded-2xl bg-white/70 px-4 py-3 hover:bg-white transition-colors">Manage users</a>
            </div>
        </article>
    </aside>
</section>
@endsection