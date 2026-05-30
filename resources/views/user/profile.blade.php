@extends('layout')

@section('title', 'My Profile')

@section('content')
<main class="min-h-screen bg-white py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6">
            <h2 class="text-2xl font-black">Profile</h2>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-600">Name</p>
                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-slate-600">Email</p>
                    <p class="font-semibold text-slate-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-slate-600">Phone</p>
                    <p class="font-semibold text-slate-900">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-600">Member since</p>
                    <p class="font-semibold text-slate-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="text-2xl font-black">My Orders</h2>
            @if($orders->isEmpty())
                <p class="text-slate-500 mt-4">You have no orders yet.</p>
            @else
                <div class="mt-4 space-y-6">
                    @foreach($orders as $order)
                        <div class="border border-slate-100 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-slate-500">Order #{{ $order->id }}</p>
                                    <p class="font-bold text-slate-900">Placed {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-600">Status</p>
                                    <p class="font-bold text-slate-900">{{ ucfirst($order->status) }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    @foreach($order->items as $item)
                                        @php
                                            $variation = $item->variation;
                                            $shoe = $variation?->shoe;
                                            $brand = $shoe?->brand;
                                            $cover = $shoe?->images->firstWhere('is_cover', true) ?? $shoe?->images->first();
                                        @endphp
                                        <div class="flex items-center gap-4 py-3 border-b last:border-b-0">
                                            <img src="{{ $cover?->image_path ?? 'https://via.placeholder.com/100' }}" class="w-16 h-16 rounded-lg object-cover" alt="{{ $shoe?->shoe_name }}" />
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-900">{{ $shoe?->shoe_name ?? 'Item' }}</p>
                                                <p class="text-xs text-slate-500">{{ $brand?->brand_name ?? '' }} • SKU: {{ $variation?->sku_code ?? '-' }}</p>
                                                <p class="text-xs text-slate-600">Qty: {{ $item->quantity }} • RM{{ number_format($item->unit_price, 2) }}</p>
                                            </div>
                                            <div class="text-sm font-bold">RM{{ number_format($item->unit_price * $item->quantity, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="p-4 bg-slate-50 rounded-lg">
                                    <p class="text-sm text-slate-600">Total</p>
                                    <p class="text-lg font-black">RM{{ number_format($order->total_amount, 2) }}</p>
                                    <p class="text-xs text-slate-500 mt-2">Shipping status: <span class="font-semibold">{{ $order->status }}</span></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
