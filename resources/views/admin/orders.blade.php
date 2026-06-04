@extends('admin.layout')

@section('title', 'Orders')
@section('eyebrow', 'Admin Operations')
@section('page-title', 'Orders')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Orders</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Update order status or remove records from the admin panel.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5 mb-8">
    @foreach ($statusSummary as $status => $count)
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">{{ $status }}</p>
        <p class="text-3xl font-black mt-3">{{ $count }}</p>
    </div>
    @endforeach
</div>

<div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Order Timeline</p>
                <h2 class="text-2xl font-black mt-2">Latest orders</h2>
            </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">Live CRUD</span>
        </div>
        <div class="overflow-x-auto p-6 md:p-8">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 align-top font-black">#{{ $order->id }}</td>
                        <td class="px-4 py-3 align-top">
                            <div class="font-bold">{{ $order->user?->name ?? 'Guest' }}</div>
                            <div class="text-xs text-slate-500">{{ $order->user?->email ?? 'No email' }}</div>
                        </td>
                        <td class="px-4 py-3 align-top text-center font-black">{{ $order->items->count() }}</td>
                        <td class="px-4 py-3 align-top font-bold">RM {{ number_format((float) $order->total_amount, 2) }}</td>
                        <td class="px-4 py-3 align-top">
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex items-center gap-2">@csrf @method('PUT')<select name="status" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none">@foreach (['pending', 'paid', 'shipping', 'delivered', 'cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>@endforeach</select><button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">Save</button></form>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Delete</button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6">
            {{ $orders->links() }}
        </div>
    </section>

    <aside class="grid gap-6">
        <div class="rounded-3xl bg-slate-950 text-white p-6 shadow-xl">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Status Notes</p>
            <h3 class="text-2xl font-black mt-2">Operational overview</h3>
            <div class="mt-5 grid gap-3">
                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="font-bold">Pending</p>
                    <p class="text-sm text-slate-300 mt-1">Orders awaiting payment confirmation or processing.</p>
                </div>
                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="font-bold">Paid / Shipping / Delivered</p>
                    <p class="text-sm text-slate-300 mt-1">Orders that are moving through fulfillment.</p>
                </div>
            </div>
        </div>
        <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-lg">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Recent Items</p>
            <div class="mt-5 grid gap-3">@foreach ($orders->take(3) as $order)<div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-black">Order #{{ $order->id }}</p><span class="text-xs text-slate-500">{{ $order->status }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-2">{{ $order->items->first()?->variation?->shoe?->shoe_name ?? 'No item preview' }}</p>
                </div>@endforeach</div>
        </div>
    </aside>
</div>
@endsection