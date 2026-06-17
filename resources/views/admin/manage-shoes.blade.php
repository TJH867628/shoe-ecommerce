@extends('admin.layout')

@section('title', 'Shoes')
@section('eyebrow', 'Admin Catalog')
@section('page-title', 'Shoes')

@section('content')
@php
    $totalShoes = $shoes->count();
    $totalBrands = $brands->count();
    $totalOptions = $shoes->sum(fn ($shoe) => $shoe->options->count());
    $totalVariations = $shoes->sum(fn ($shoe) => $shoe->variations->count());
    $totalStock = $shoes->sum(fn ($shoe) => $shoe->variations->sum('stock_quantity'));
    $featuredShoe = $shoes->first();
@endphp

<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Admin Catalog</p>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Shoes</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Manage the full shoes flow from one workspace: create a product, review the catalog, and jump into the detail page for images, options, and variations.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5 mb-8">
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Shoes</p>
        <p class="text-3xl font-black mt-3">{{ $totalShoes }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Brands</p>
        <p class="text-3xl font-black mt-3">{{ $totalBrands }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Options</p>
        <p class="text-3xl font-black mt-3">{{ $totalOptions }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Variations</p>
        <p class="text-3xl font-black mt-3">{{ $totalVariations }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Stock</p>
        <p class="text-3xl font-black mt-3">{{ $totalStock }}</p>
    </div>
</div>

@if ($featuredShoe)
    <section class="mb-8 rounded-3xl bg-slate-950 text-white overflow-hidden shadow-sm">
        <div class="grid gap-0 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="p-6 md:p-8 xl:p-10">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Catalog Snapshot</p>
                <h2 class="text-3xl md:text-4xl font-black mt-3">{{ $featuredShoe->shoe_name }}</h2>
                <p class="mt-4 text-slate-300 leading-7 max-w-2xl">{{ Str::limit($featuredShoe->shoe_description, 180) }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="inline-flex px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-[0.2em]">{{ $featuredShoe->brand?->brand_name ?? 'Unassigned' }}</span>
                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-bold uppercase tracking-[0.2em]">RM {{ number_format((float) $featuredShoe->shoe_price, 2) }}</span>
                    <span class="inline-flex px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-[0.2em]">{{ $featuredShoe->variations->sum('stock_quantity') }} stock</span>
                </div>
                <a href="{{ route('admin.shoes.show', $featuredShoe->id) }}" class="inline-flex items-center gap-2 mt-8 px-4 py-3 rounded-2xl bg-white text-slate-950 font-bold hover:bg-slate-100 transition-colors">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    Open details flow
                </a>
            </div>
            <div class="bg-gradient-to-br from-amber-400 via-amber-300 to-orange-300 p-6 md:p-8 xl:p-10 text-slate-950 flex items-end">
                <div class="w-full rounded-3xl bg-white/70 backdrop-blur border border-white/60 p-5 shadow-lg">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-600 font-bold">Quick Read</p>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Options</p>
                            <p class="text-2xl font-black mt-2">{{ $featuredShoe->options->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/80 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Variations</p>
                            <p class="text-2xl font-black mt-2">{{ $featuredShoe->variations->count() }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/80 p-4 col-span-2">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Description</p>
                            <p class="mt-2 text-sm text-slate-700 leading-6 line-clamp-4">{{ $featuredShoe->shoe_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6 md:p-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Create Shoe</p>
                <h2 class="text-2xl font-black mt-2">New product</h2>
                <p class="text-sm text-slate-600 mt-2 max-w-md">Create the base product record first, then open the detail page to add media, options, and stock variations.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">Flow</span>
        </div>

        <form action="{{ route('admin.shoes.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Brand</label>
                <select name="brand_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" required>
                    <option value="">Select brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Shoe Name</label>
                <input type="text" name="shoe_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Air Max Runner" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="shoe_description" rows="5" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Product summary..." required></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Price</label>
                <input type="number" step="0.01" name="shoe_price" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="499.00" required>
            </div>

            <button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                <i class="fas fa-plus"></i>
                Create Shoe
            </button>
        </form>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Product Directory</p>
                <h2 class="text-2xl font-black mt-2">Edit or delete shoes</h2>
                <p class="text-sm text-slate-600 mt-2">Use the action buttons to jump into the full shoe details page or remove a product.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold uppercase tracking-[0.2em]">Live CRUD</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-180 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Brand</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($shoes as $shoe)
                        <tr class="align-top">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $shoe->shoe_name }}</div>
                                <div class="text-slate-500 text-xs mt-1 line-clamp-1">{{ Str::limit($shoe->shoe_description, 90) }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 font-bold">RM {{ number_format((float) $shoe->shoe_price, 2) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $shoe->variations->sum('stock_quantity') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.shoes.show', $shoe->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                                        <i class="fas fa-pen-to-square"></i>
                                        Details
                                    </a>
                                    <form action="{{ route('admin.shoes.clone', $shoe->id) }}" method="POST" onsubmit="return confirm('Clone this shoe?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 transition-colors">
                                            <i class="fas fa-copy"></i>
                                            Clone
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.shoes.destroy', $shoe->id) }}" method="POST" onsubmit="return confirm('Delete this shoe?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No shoes yet. Create the first product using the form on the left.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 pb-6 pt-4">
            {{ $shoes->links() }}
        </div>
    </section>
</div>
@endsection
