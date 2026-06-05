@extends('layout')

@section('title', 'Cart')

@section('content')
<main class="min-h-screen bg-linear-to-b from-slate-50 to-white py-10">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="mb-8">
			<p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-700">Your Cart</p>
			<h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2">Review your items before checkout</h1>
			<p class="text-slate-500 mt-2">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }} in your cart</p>
		</div>

		@if(count($cartItems) > 0)
		<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
			<section class="xl:col-span-2">
				<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
					@foreach($cartItems as $cartItem)
						@php
							$shoe = $cartItem->variation->shoe;
							$brand = $shoe?->brand;
							$image = $shoe?->images->firstWhere('is_cover', true) ?? $shoe?->images->first();
							$attributes = $cartItem->variation->attributes ?? [];
						@endphp
						<article class="p-5 sm:p-6 flex flex-col sm:flex-row gap-5 sm:items-center {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
							<img src="{{ $image?->image_path ?? 'https://via.placeholder.com/150' }}" alt="{{ $shoe?->shoe_name ?? 'Cart item' }}" class="w-full sm:w-28 h-40 sm:h-28 object-cover rounded-xl" />

							<div class="flex-1 min-w-0">
								<div class="flex items-start justify-between gap-4">
									<div>
										<p class="text-xs uppercase tracking-wider text-cyan-700 font-bold">{{ $brand?->brand_name ?? 'Brand' }}</p>
										<h2 class="text-lg font-black text-slate-900 mt-1">{{ $shoe?->shoe_name ?? 'Cart Item' }}</h2>
										<p class="text-sm text-slate-500 mt-1">
											{{-- Display variation attributes --}}
											@if(!empty($attributes))
												@foreach($attributes as $key => $value)
													{{ ucfirst($key) }}: {{ $value }}{{ !$loop->last ? ' | ' : '' }}
												@endforeach
											@endif
										</p>
									</div>
									<p class="text-lg font-black text-slate-900">
										RM{{ number_format($shoe?->shoe_price ?? 0, 2) }}
									</p>
								</div>

								<div class="mt-4 flex items-center justify-between">
									<form method="POST" action="{{ route('cart.update-quantity', $cartItem) }}" class="inline-flex items-center border border-slate-200 rounded-full overflow-hidden">
										@csrf
										@method('PATCH')
										<button type="submit" name="quantity" value="{{ max(1, $cartItem->quantity - 1) }}" class="px-3 py-1.5 text-slate-500 hover:bg-slate-100">-</button>
										<span class="px-4 text-sm font-bold text-slate-900">{{ $cartItem->quantity }}</span>
										<button type="submit" name="quantity" value="{{ $cartItem->quantity + 1 }}" class="px-3 py-1.5 text-slate-500 hover:bg-slate-100">+</button>
									</form>

									<form method="POST" action="{{ route('cart.remove', $cartItem) }}" class="inline">
										@csrf
										@method('DELETE')
										<button type="submit" class="text-sm font-bold text-rose-600 hover:text-rose-700">Remove</button>
									</form>
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
					<form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Clear entire cart?')">
						@csrf
						@method('DELETE')
						<button type="submit" class="px-4 py-2 text-sm font-bold rounded-full border border-slate-200 text-slate-600 hover:bg-slate-100">Clear Cart</button>
					</form>
				</div>

				<div class="mt-8 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6">
					<h3 class="text-lg font-black text-slate-900">You might also like</h3>
					<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
						@if(isset($recommended) && $recommended->count())
							@foreach($recommended as $rec)
								@php
									$cover = $rec->images->firstWhere('is_cover', true) ?? $rec->images->first();
								@endphp
								<a href="{{ route('products.show', $rec->id) }}" class="group rounded-xl border border-slate-200 p-3 flex items-center gap-3 hover:border-cyan-400 transition">
									<img src="{{ $cover?->image_path ?? 'https://via.placeholder.com/150' }}" alt="{{ $rec->shoe_name }}" class="w-16 h-16 rounded-lg object-cover" />
									<div>
										<p class="text-sm font-black text-slate-900 group-hover:text-cyan-700">{{ $rec->shoe_name }}</p>
										<p class="text-xs text-slate-500">{{ $rec->brand?->brand_name ?? '' }}</p>
										<p class="text-sm font-bold text-slate-800 mt-1">RM{{ number_format($rec->shoe_price, 2) }}</p>
									</div>
								</a>
							@endforeach
						@else
							<p class="text-sm text-slate-500">No recommendations available right now.</p>
						@endif
					</div>
				</div>
			</section>

			<aside class="xl:col-span-1">
				<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6 sticky top-24">
					<h3 class="text-xl font-black text-slate-900">Order Summary</h3>

					<div class="mt-5 space-y-3 text-sm">
						<div class="flex items-center justify-between text-slate-600">
							<span>Subtotal</span>
							<span class="font-bold text-slate-900">RM{{ number_format($subtotal, 2) }}</span>
						</div>
						
						@if($discountPercentage > 0)
						<div class="flex items-center justify-between text-green-600">
							<span>Discount ({{ $discountPercentage }}%)</span>
							<span class="font-bold">-RM{{ number_format($discountAmount, 2) }}</span>
						</div>
						@endif

						<div class="flex items-center justify-between text-slate-600">
							<span>Shipping ({{ ucfirst($shippingMethod) }})</span>
							<span class="font-bold text-slate-900">@if($shipping == 0) FREE @else RM{{ number_format($shipping, 2) }} @endif</span>
						</div>
					</div>

					<div class="my-5 border-t border-slate-200"></div>

					<div class="flex items-center justify-between">
						<span class="text-slate-700 font-bold">Total</span>
						<span class="text-2xl font-black text-slate-900">RM{{ number_format($total, 2) }}</span>
					</div>

					<a href="{{ route('user.payment', [
						'amount' => number_format($total, 2, '.', ''),
						'subtotal' => number_format($subtotal, 2, '.', ''),
						'discount_amount' => number_format($discountAmount, 2, '.', ''),
						'shipping' => number_format($shipping, 2, '.', ''),
						'shipping_method' => $shippingMethod,
						'payment_type' => 'FPX',
						'customer_name' => auth()->user()?->name,
						'customer_email' => auth()->user()?->email,
						'customer_phone' => auth()->user()?->phone,
					]) }}" class="mt-6 w-full inline-flex items-center justify-center py-3 rounded-xl bg-slate-900 text-white font-black hover:bg-slate-800 transition">Proceed to Checkout</a>


					{{-- ToyyibPay Support Status --}}
					<div class="mt-5 p-3 rounded-lg bg-blue-50 border border-blue-200">
						<p class="text-xs font-bold text-blue-700">✨ ToyyibPay Supported</p>
						<p class="text-xs text-blue-600 mt-1">Secure online payments are available through ToyyibPay</p>
					</div>
				</div>
			</aside>
		</div>

		@else
		<div class="bg-white border border-slate-200 rounded-2xl p-12 text-center">
			<i class="fas fa-shopping-cart text-6xl text-slate-300 mb-4"></i>
			<h2 class="text-2xl font-bold text-slate-900 mt-4">Your cart is empty</h2>
			<p class="text-slate-500 mt-2">Add some shoes to get started!</p>
			<a href="{{ route('product') }}" class="inline-block mt-6 px-6 py-3 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800">
				Continue Shopping
			</a>
		</div>
		@endif
	</div>
</main>
@endsection
