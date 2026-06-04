@extends('layout')

@section('title', 'About Us')

@section('content')
<main class="bg-slate-950 text-white">
	<section class="relative overflow-hidden">
		<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.22),_transparent_38%),radial-gradient(circle_at_10%_80%,_rgba(249,115,22,0.18),_transparent_35%)]"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
			<p class="text-cyan-300 uppercase tracking-[0.24em] text-xs font-bold mb-5">About 2Step</p>
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end">
				<div>
					<h1 class="text-4xl sm:text-5xl md:text-6xl font-black leading-[1.05]">
						We build better steps
						<span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-orange-300">for everyday movement.</span>
					</h1>
					<p class="mt-6 text-slate-300 max-w-2xl text-base sm:text-lg leading-relaxed">
						2Step started with one belief: premium footwear should feel as good at kilometer 10 as it does in your first step. We combine performance design, modern style, and fair pricing for real people in motion.
					</p>
					<div class="mt-8 flex flex-wrap gap-3">
						<a href="{{ route('product') }}" class="px-6 py-3 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition">Explore Products</a>
					</div>
				</div>
				<div class="grid grid-cols-2 gap-4">
					<div class="rounded-2xl border border-white/10 bg-white/5 p-5">
						<p class="text-3xl font-black">12k+</p>
						<p class="text-slate-300 text-sm mt-1">Pairs sold</p>
					</div>
					<div class="rounded-2xl border border-white/10 bg-white/5 p-5">
						<p class="text-3xl font-black">98%</p>
						<p class="text-slate-300 text-sm mt-1">Customer satisfaction</p>
					</div>
					<div class="rounded-2xl border border-white/10 bg-white/5 p-5">
						<p class="text-3xl font-black">24h</p>
						<p class="text-slate-300 text-sm mt-1">Average dispatch</p>
					</div>
					<div class="rounded-2xl border border-white/10 bg-white/5 p-5">
						<p class="text-3xl font-black">40+</p>
						<p class="text-slate-300 text-sm mt-1">Cities shipped to</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="border-t border-white/10 bg-slate-900/60">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
				<article class="rounded-2xl bg-slate-900 border border-slate-700 p-6">
					<h2 class="text-xl font-extrabold mb-2">Our Mission</h2>
					<p class="text-slate-300 leading-relaxed">Create shoes that make movement feel natural, confident, and expressive for every lifestyle.</p>
				</article>
				<article class="rounded-2xl bg-slate-900 border border-slate-700 p-6">
					<h2 class="text-xl font-extrabold mb-2">Our Promise</h2>
					<p class="text-slate-300 leading-relaxed">No compromise on comfort, craftsmanship, or design. Every model is tested for daily wear and durability.</p>
				</article>
				<article class="rounded-2xl bg-slate-900 border border-slate-700 p-6">
					<h2 class="text-xl font-extrabold mb-2">Our Culture</h2>
					<p class="text-slate-300 leading-relaxed">We listen fast, build fast, and improve fast. Customer feedback directly shapes every new drop.</p>
				</article>
			</div>
		</div>
	</section>

	<section class="bg-white text-slate-900">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<div class="max-w-3xl">
				<p class="text-xs font-bold uppercase tracking-[0.24em] text-cyan-600">What We Value</p>
				<h2 class="mt-3 text-3xl sm:text-4xl font-black">Built on clear principles, not trends.</h2>
			</div>

			<div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
				<div class="rounded-2xl border border-slate-200 p-5 bg-gradient-to-b from-white to-slate-50">
					<p class="text-lg font-bold">Comfort First</p>
					<p class="text-slate-600 mt-2 text-sm leading-relaxed">Each silhouette is designed to support long days on your feet.</p>
				</div>
				<div class="rounded-2xl border border-slate-200 p-5 bg-gradient-to-b from-white to-slate-50">
					<p class="text-lg font-bold">Honest Quality</p>
					<p class="text-slate-600 mt-2 text-sm leading-relaxed">Materials are selected for durability and tested in real daily conditions.</p>
				</div>
				<div class="rounded-2xl border border-slate-200 p-5 bg-gradient-to-b from-white to-slate-50">
					<p class="text-lg font-bold">Accessible Price</p>
					<p class="text-slate-600 mt-2 text-sm leading-relaxed">Premium feel without premium-only pricing.</p>
				</div>
				<div class="rounded-2xl border border-slate-200 p-5 bg-gradient-to-b from-white to-slate-50">
					<p class="text-lg font-bold">Progressive Design</p>
					<p class="text-slate-600 mt-2 text-sm leading-relaxed">A modern look that stays wearable season after season.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-100 text-slate-900">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<div class="max-w-3xl">
				<p class="text-xs font-bold uppercase tracking-[0.24em] text-orange-500">Our Team</p>
				<h2 class="mt-3 text-3xl sm:text-4xl font-black">People behind every pair.</h2>
			</div>
			<div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
				<article class="rounded-2xl bg-white border border-slate-200 p-6">
					<p class="font-black text-xl">Mia Rattan</p>
					<p class="text-sm text-cyan-700 font-semibold">Product Design Lead</p>
					<p class="mt-3 text-slate-600 text-sm">Focuses on fit, comfort, and silhouette development across all categories.</p>
				</article>
				<article class="rounded-2xl bg-white border border-slate-200 p-6">
					<p class="font-black text-xl">Kai Santoso</p>
					<p class="text-sm text-cyan-700 font-semibold">Head of Operations</p>
					<p class="mt-3 text-slate-600 text-sm">Optimizes production and logistics to keep quality high and delivery fast.</p>
				</article>
				<article class="rounded-2xl bg-white border border-slate-200 p-6">
					<p class="font-black text-xl">Alya Pramudita</p>
					<p class="text-sm text-cyan-700 font-semibold">Customer Experience</p>
					<p class="mt-3 text-slate-600 text-sm">Turns feedback into action and ensures every customer journey feels smooth.</p>
				</article>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 text-white border-t border-white/10">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
			<h2 class="text-3xl sm:text-4xl font-black">Ready to find your next pair?</h2>
			<p class="text-slate-300 mt-4 max-w-2xl mx-auto">Browse the collection and discover shoes engineered for motion and styled for everyday life.</p>
			<a href="{{ route('product') }}" class="inline-flex mt-8 px-7 py-3 rounded-full bg-cyan-400 text-slate-900 font-black hover:bg-cyan-300 transition">
				Shop Now
			</a>
		</div>
	</section>
</main>
@endsection