@extends('layout')

@section('title', 'Cart')

@section('content')
<main class="min-h-screen bg-gradient-to-b from-slate-50 to-white py-10">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="mb-8">
			<p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-700">Your Cart</p>
			<h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2">Review your items before checkout</h1>
			<p class="text-slate-500 mt-2">Design-only preview with sample products and pricing.</p>
		</div>

		@php
			$cartItems = [
				[
					'name' => 'Air Max Pro',
					'brand' => 'Nike',
					'size' => '42',
					'color' => 'Black',
					'price' => 129.99,
					'qty' => 1,
					'image' => 'https://images.unsplash.com/photo-1528701800489-47645c2a34f2?auto=format&fit=crop&w=800&q=80',
				],
				[
					'name' => 'Ultraboost 22',
					'brand' => 'Adidas',
					'size' => '41',
					'color' => 'White',
					'price' => 149.99,
					'qty' => 2,
					'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
				],
			];

			$subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['qty']);
			$shipping = 8.00;
			$tax = $subtotal * 0.08;
			$total = $subtotal + $shipping + $tax;
		@endphp

		<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
			<section class="xl:col-span-2">
				<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
					@foreach($cartItems as $item)
						<article class="p-5 sm:p-6 flex flex-col sm:flex-row gap-5 sm:items-center {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
							<img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full sm:w-28 h-40 sm:h-28 object-cover rounded-xl" />

							<div class="flex-1 min-w-0">
								<div class="flex items-start justify-between gap-4">
									<div>
										<p class="text-xs uppercase tracking-wider text-cyan-700 font-bold">{{ $item['brand'] }}</p>
										<h2 class="text-lg font-black text-slate-900 mt-1">{{ $item['name'] }}</h2>
										<p class="text-sm text-slate-500 mt-1">Size {{ $item['size'] }} | {{ $item['color'] }}</p>
									</div>
									<p class="text-lg font-black text-slate-900">${{ number_format($item['price'], 2) }}</p>
								</div>

								<div class="mt-4 flex items-center justify-between">
									<div class="inline-flex items-center border border-slate-200 rounded-full overflow-hidden">
										<button type="button" class="px-3 py-1.5 text-slate-500 hover:bg-slate-100">-</button>
										<span class="px-4 text-sm font-bold text-slate-900">{{ $item['qty'] }}</span>
										<button type="button" class="px-3 py-1.5 text-slate-500 hover:bg-slate-100">+</button>
									</div>

									<button type="button" class="text-sm font-bold text-rose-600 hover:text-rose-700">Remove</button>
								</div>
							</div>
						</article>
					@endforeach
				</div>

				<div class="mt-5 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
					<a href="{{ route('product') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-slate-900">
						<i class="fas fa-arrow-left"></i>
						Continue Shopping
					</a>
					<button type="button" class="px-4 py-2 text-sm font-bold rounded-full border border-slate-200 text-slate-600 hover:bg-slate-100">Clear Cart</button>
				</div>

				<div class="mt-8 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6">
					<h3 class="text-lg font-black text-slate-900">You might also like</h3>
					<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
						<a href="#" class="group rounded-xl border border-slate-200 p-3 flex items-center gap-3 hover:border-cyan-400 transition">
							<img src="https://images.unsplash.com/photo-1542293787938-c9e299b880f4?auto=format&fit=crop&w=400&q=80" alt="RS-X Games" class="w-16 h-16 rounded-lg object-cover" />
							<div>
								<p class="text-sm font-black text-slate-900 group-hover:text-cyan-700">RS-X Games</p>
								<p class="text-xs text-slate-500">Puma</p>
								<p class="text-sm font-bold text-slate-800 mt-1">$99.99</p>
							</div>
						</a>
						<a href="#" class="group rounded-xl border border-slate-200 p-3 flex items-center gap-3 hover:border-cyan-400 transition">
							<img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=400&q=80" alt="990v6" class="w-16 h-16 rounded-lg object-cover" />
							<div>
								<p class="text-sm font-black text-slate-900 group-hover:text-cyan-700">990v6</p>
								<p class="text-xs text-slate-500">New Balance</p>
								<p class="text-sm font-bold text-slate-800 mt-1">$139.99</p>
							</div>
						</a>
					</div>
				</div>
			</section>

			<aside class="xl:col-span-1">
				<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6 sticky top-24">
					<h3 class="text-xl font-black text-slate-900">Order Summary</h3>

					<div class="mt-5 space-y-3 text-sm">
						<div class="flex items-center justify-between text-slate-600">
							<span>Subtotal</span>
							<span class="font-bold text-slate-900">${{ number_format($subtotal, 2) }}</span>
						</div>
						<div class="flex items-center justify-between text-slate-600">
							<span>Shipping</span>
							<span class="font-bold text-slate-900">${{ number_format($shipping, 2) }}</span>
						</div>
						<div class="flex items-center justify-between text-slate-600">
							<span>Estimated Tax</span>
							<span class="font-bold text-slate-900">${{ number_format($tax, 2) }}</span>
						</div>
					</div>

					<div class="my-5 border-t border-slate-200"></div>

					<div class="flex items-center justify-between">
						<span class="text-slate-700 font-bold">Total</span>
						<span class="text-2xl font-black text-slate-900">${{ number_format($total, 2) }}</span>
					</div>

					<button type="button" class="mt-6 w-full py-3 rounded-xl bg-slate-900 text-white font-black hover:bg-slate-800 transition">Proceed to Checkout</button>

					<div class="mt-5 p-4 rounded-xl bg-cyan-50 border border-cyan-100">
						<p class="text-xs font-bold text-cyan-700 uppercase tracking-wider">Promo Code</p>
						<div class="mt-2 flex gap-2">
							<input type="text" placeholder="Enter code" class="w-full px-3 py-2 rounded-lg border border-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-300" />
							<button type="button" class="px-4 py-2 rounded-lg bg-cyan-700 text-white text-sm font-bold">Apply</button>
						</div>
					</div>
				</div>
			</aside>
		</div>
	</div>
</main>
@endsection
