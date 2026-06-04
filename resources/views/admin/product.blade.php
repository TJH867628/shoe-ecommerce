@extends('admin.layout')

@section('title', 'Shoe Detail')
@section('eyebrow', 'Admin Product Detail')
@section('page-title', $shoe->shoe_name)

@section('content')
@php
$coverImage = $shoe->images->firstWhere('is_cover', true) ?? $shoe->images->sortBy('sort_order')->first();
$coverUrl = $coverImage?->image_path
? (str_starts_with($coverImage->image_path, 'http') ? $coverImage->image_path : asset('storage/' . $coverImage->image_path))
: 'https://via.placeholder.com/1200x900?text=No+Image';
@endphp

<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">{{ $shoe->shoe_name }}</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Manage options, variations, and media for this shoe.</p>
    </div>
    <a href="{{ route('admin.shoes.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-arrow-left"></i>Back to Shoes</a>
</div>

<div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="aspect-4/3 bg-slate-100 overflow-hidden"><img src="{{ $coverUrl }}" alt="{{ $shoe->shoe_name }}" class="w-full h-full object-cover"></div>
        <div class="p-6 md:p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Brand</p>
                    <p class="text-xl font-black mt-2">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</p>
                </div>
                <div class="px-4 py-2 rounded-2xl bg-amber-100 text-amber-700 font-black">RM {{ number_format((float) $shoe->shoe_price, 2) }}</div>
            </div>
            <p class="mt-6 text-slate-600 leading-7">{{ $shoe->shoe_description }}</p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Images</p>
                    <p class="text-2xl font-black mt-2">{{ $shoe->images->count() }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Variations</p>
                    <p class="text-2xl font-black mt-2">{{ $shoe->variations->count() }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-6">
        <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Shoe Images</p>
                    <h2 class="text-2xl font-black mt-2">Upload and manage</h2>
                </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">Media</span>
            </div>
            <form action="/shoes/{{ $shoe->id }}/images" method="POST" enctype="multipart/form-data" class="space-y-4">@csrf<div><label class="block text-sm font-bold text-slate-700 mb-2">Upload Images</label><input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900"></div><button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-cloud-arrow-up"></i>Upload Shoe Images</button></form>
            <div class="mt-6 grid gap-3 md:grid-cols-2">@foreach ($shoe->images as $image)@php $imageUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path); @endphp <article class="rounded-3xl border border-slate-200 overflow-hidden bg-slate-50"><img src="{{ $imageUrl }}" alt="{{ $shoe->shoe_name }} image" class="w-full h-44 object-cover">
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between gap-3"><span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-[0.2em] {{ $image->is_cover ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $image->is_cover ? 'Cover' : 'Image' }}</span><span class="text-xs text-slate-500">Order {{ $image->sort_order }}</span></div>
                        <form action="/shoes/images/{{ $image->id }}" method="POST" onsubmit="return confirm('Delete this shoe image?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors"><i class="fas fa-trash"></i>Delete Image</button></form>
                    </div>
                </article>@endforeach</div>
        </section>

        <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Add Option</p>
                    <h2 class="text-2xl font-black mt-2">New attribute group</h2>
                </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">CRUD</span>
            </div>
            <form action="{{ route('admin.shoes.options.store') }}" method="POST" class="space-y-4">@csrf<input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                <div><label class="block text-sm font-bold text-slate-700 mb-2">Option Names</label><input type="text" name="option_names[]" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Size, Color"></div><button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-plus"></i>Add Option</button>
            </form>
        </section>

        <section class="rounded-3xl bg-slate-950 text-white p-6 md:p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Options</p>
            <h2 class="text-2xl font-black mt-2">Edit or remove options</h2>
            <div class="mt-5 grid gap-3">@forelse ($shoe->options as $option)<article class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <form action="{{ route('admin.shoes.options.update', $option->id) }}" method="POST" class="space-y-3">@csrf @method('PUT')<div><label class="block text-xs uppercase tracking-[0.2em] text-slate-400 font-bold mb-2">Option Name</label><input type="text" name="option_name" value="{{ $option->option_name }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white outline-none focus:ring-2 focus:ring-amber-300"></div><button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-400 text-slate-950 font-bold hover:bg-amber-300 transition-colors"><i class="fas fa-pen-to-square"></i>Update Option</button></form>
                    <form action="{{ route('admin.shoes.options.destroy', $option->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete this option?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors"><i class="fas fa-trash"></i>Delete Option</button></form>
                </article>@empty<div class="rounded-2xl bg-white/10 border border-white/10 p-4 text-slate-300">No options available.</div>@endforelse</div>
        </section>

        <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Variations</p>
            <h2 class="text-2xl font-black mt-2">SKU overview</h2>
            <div class="mt-5 grid gap-3">@forelse ($shoe->variations as $variation)<article class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <form action="{{ route('admin.shoes.variations.update', $variation->id) }}" method="POST" class="space-y-4">@csrf @method('PUT')<div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-black text-slate-900">{{ $variation->sku_code }}</p>
                                <p class="text-sm text-slate-500 mt-1">Stock: {{ $variation->stock_quantity }}</p>
                            </div><span class="text-xs uppercase tracking-[0.2em] font-bold text-slate-500">{{ count($variation->attributes ?? []) }} attrs</span>
                        </div>
                        <div class="grid gap-3 md:grid-cols-2">@foreach(($variation->attributes ?? []) as $key => $value)<div><label class="block text-xs uppercase tracking-[0.2em] text-slate-500 font-bold mb-2">{{ $key }}</label><input type="text" name="attributes[{{ $key }}]" value="{{ $value }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900"></div>@endforeach<div><label class="block text-xs uppercase tracking-[0.2em] text-slate-500 font-bold mb-2">Stock Quantity</label><input type="number" name="stock" value="{{ $variation->stock_quantity }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900"></div>
                        </div><button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-pen-to-square"></i>Update Variation</button>
                    </form>
                    <form action="/shoe-variations/{{ $variation->id }}/images" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">@csrf<div><label class="block text-xs uppercase tracking-[0.2em] text-slate-500 font-bold mb-2">Upload Variation Images</label><input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900"></div><button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-400 text-slate-950 font-bold hover:bg-amber-300 transition-colors"><i class="fas fa-cloud-arrow-up"></i>Upload Variation Images</button></form>@if ($variation->images->count())<div class="mt-4 grid gap-3 md:grid-cols-2">@foreach ($variation->images as $image)@php $variationImageUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path); @endphp <article class="rounded-2xl overflow-hidden border border-slate-200 bg-white"><img src="{{ $variationImageUrl }}" alt="Variation image" class="w-full h-32 object-cover">
                            <div class="p-3">
                                <form action="/shoe/variations/image/{{ $image->id }}" method="POST" onsubmit="return confirm('Delete this variation image?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors"><i class="fas fa-trash"></i>Delete</button></form>
                            </div>
                        </article>@endforeach</div>@endif<form action="{{ route('admin.shoes.variations.destroy', $variation->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete this variation?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors"><i class="fas fa-trash"></i>Delete Variation</button></form>
                </article>@empty<div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 text-slate-500">No variations available.</div>@endforelse</div>
        </section>
    </div>

    <section class="mt-6 rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Brand Reference</p>
                <h2 class="text-2xl font-black mt-2">Available brands</h2>
            </div><span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">Read only</span>
        </div>
        <div class="p-6 md:p-8 grid gap-3 md:grid-cols-2 xl:grid-cols-4">@foreach ($brands as $brand)<div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                <p class="font-black">{{ $brand->brand_name }}</p>
                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $brand->brand_description }}</p>
            </div>@endforeach</div>
    </section>
    @endsection