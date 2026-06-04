@extends('admin.layout')

@section('title', 'Shoe Detail')
@section('eyebrow', 'Admin Product Detail')
@section('page-title', $shoe->shoe_name)

@section('content')
@php
    $coverImage = $shoe->images->firstWhere('is_cover', true) ?? $shoe->images->sortBy('sort_order')->first();
    $coverUrl = $coverImage?->image_path
        ? (str_starts_with($coverImage->image_path, 'http') ? $coverImage->image_path : asset('storage/' . $coverImage->image_path))
        : null;
    $allImages = $shoe->images
        ->map(function ($image) {
            return [
                'type' => 'shoe',
                'url' => str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path),
                'label' => $image->is_cover ? 'Cover' : 'Image',
                'meta' => 'Order ' . $image->sort_order,
            ];
        })
        ->concat(
            $shoe->variations->flatMap(function ($variation) {
                return $variation->images->map(function ($image) use ($variation) {
                    return [
                        'type' => 'sku',
                        'url' => str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path),
                        'label' => 'SKU ' . $variation->sku_code,
                        'meta' => 'Variation image',
                    ];
                });
            })
        );
    $imageCount = $shoe->images->count();
    $variationCount = $shoe->variations->count();
    $optionCount = $shoe->options->count();
    $totalStock = $shoe->variations->sum('stock_quantity');
@endphp

<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Admin Product Detail</p>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">{{ $shoe->shoe_name }}</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Manage media, options, SKU creation, and product details from one organized workspace.</p>
    </div>
    <a href="{{ route('admin.shoes.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
        <i class="fas fa-arrow-left"></i>
        Back to Shoes
    </a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Images</p>
        <p class="text-3xl font-black mt-3">{{ $imageCount }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Options</p>
        <p class="text-3xl font-black mt-3">{{ $optionCount }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Variations</p>
        <p class="text-3xl font-black mt-3">{{ $variationCount }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Stock</p>
        <p class="text-3xl font-black mt-3">{{ $totalStock }}</p>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5 md:p-6">
        <div class="grid gap-6 lg:grid-cols-[220px_1fr] lg:items-center">
            <button type="button" id="cover-image-trigger" class="group relative aspect-square w-full max-w-[220px] justify-self-center overflow-hidden rounded-3xl border border-slate-200 bg-slate-100 shadow-lg ring-1 ring-slate-900/5 cursor-zoom-in text-left">
            @if($coverUrl)    
            <img src="{{ $coverUrl }}" alt="{{ $shoe->shoe_name }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
            @else
            <div class="h-full w-full flex items-center justify-center text-slate-500">
                No Cover Image
            </div>
            @endif
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent p-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex px-2.5 py-1 rounded-full bg-white/10 backdrop-blur text-white text-[10px] font-bold uppercase tracking-[0.2em]">Preview</span>
                        <span class="inline-flex px-2.5 py-1 rounded-full bg-white/10 backdrop-blur text-white text-[10px] font-bold uppercase tracking-[0.2em]">Click</span>
                    </div>
                </div>
            </button>

            <div class="min-w-0">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Product Overview</p>
                <h2 class="text-xl md:text-2xl font-black mt-2">{{ $shoe->shoe_name }}</h2>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold uppercase tracking-[0.2em]">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</span>
                    <span class="inline-flex px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-bold uppercase tracking-[0.2em]">RM {{ number_format((float) $shoe->shoe_price, 2) }}</span>
                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">{{ $variationCount }} variations</span>
                </div>
                <p class="mt-4 text-sm md:text-base text-slate-600 leading-6 max-w-2xl">{{ $shoe->shoe_description }}</p>

                <div class="mt-5 grid gap-3 sm:grid-cols-2 max-w-2xl">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Image Status</p>
                        <p class="text-sm text-slate-600 mt-1.5">{{ $coverImage ? 'Cover image is set' : 'No image uploaded yet' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Brand</p>
                        <p class="text-sm text-slate-600 mt-1.5">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Edit Product</p>
                <h2 class="text-2xl font-black mt-2">Update brand, name, price, and description</h2>
                <p class="text-sm text-slate-600 mt-2">These fields map directly to the existing product update route.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">Form</span>
        </div>

        <form action="{{ route('admin.shoes.update', $shoe->id) }}" method="POST" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Brand</label>
                <select name="brand_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected($shoe->brand_id === $brand->id)>{{ $brand->brand_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Shoe Name</label>
                <input type="text" name="shoe_name" value="{{ $shoe->shoe_name }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Price</label>
                <input type="number" step="0.01" name="shoe_price" value="{{ $shoe->shoe_price }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="shoe_description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" required>{{ $shoe->shoe_description }}</textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                    <i class="fas fa-save"></i>
                    Save Product Changes
                </button>
            </div>
        </form>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Shoe Images</p>
                <h2 class="text-2xl font-black mt-2">Upload and manage</h2>
                <p class="text-sm text-slate-600 mt-2">Use the first image as the cover, then keep the rest as gallery assets.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">Media</span>
        </div>

        <form action="/shoes/{{ $shoe->id }}/images" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Upload Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                <p class="mt-2 text-xs font-semibold text-slate-500">Up to 10 images per upload. JPG, PNG, or WEBP. Max 4 MB each.</p>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                <i class="fas fa-cloud-arrow-up"></i>
                Upload Shoe Images
            </button>
        </form>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            @forelse ($shoe->images as $image)
                @php
                    $imageUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path);
                @endphp
                <article class="rounded-3xl border border-slate-200 overflow-hidden bg-slate-50">
                    <img src="{{ $imageUrl }}" alt="{{ $shoe->shoe_name }} image" class="w-full h-32 object-cover object-center">
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-[0.2em] {{ $image->is_cover ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $image->is_cover ? 'Cover' : 'Image' }}</span>
                            <span class="text-xs text-slate-500">Order {{ $image->sort_order }}</span>
                        </div>
                        <form action="/shoes/images/{{ $image->id }}" method="POST" onsubmit="return confirm('Delete this shoe image?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash"></i>
                                Delete Image
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-slate-500 md:col-span-2">
                    No images uploaded yet.
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">All Images</p>
                <h2 class="text-2xl font-black mt-2">Shoe and SKU images together</h2>
                <p class="text-sm text-slate-600 mt-2">This gallery includes the main shoe images and every variation image.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">Combined</span>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($allImages as $image)
                <button type="button" class="group text-left rounded-3xl border border-slate-200 bg-slate-50 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    @if($image['url'] && $image['url'] !== 'https://via.placeholder.com/800x800?text=No+Image' && $image['url'] !== null)
                    <img src="{{ $image['url'] }}" alt="Product image" class="w-full aspect-square object-cover object-center transition-transform duration-300 group-hover:scale-105">
                    @else
                    <div class="w-full aspect-square flex items-center justify-center bg-slate-100 text-slate-500">
                        No Image
                    </div>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] {{ $image['type'] === 'sku' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ $image['label'] }}</span>
                            <span class="text-xs text-slate-500">{{ $image['meta'] }}</span>
                        </div>
                    </div>
                </button>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-slate-500 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                    No images available for this shoe.
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Options</p>
                <h2 class="text-2xl font-black mt-2">Add, edit, or remove options</h2>
                <p class="text-sm text-slate-600 mt-2">Create the option first, then refine or delete it from the table below.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">Table</span>
        </div>

        <form action="{{ route('admin.shoes.options.store') }}" method="POST" class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            @csrf
            <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Option Name</label>
                <input type="text" name="option_names[]" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Size, Color">
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                <i class="fas fa-plus"></i>
                Add Option
            </button>
        </form>

        <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-slate-50">
            <table class="w-full min-w-160 text-sm">
                <thead class="bg-slate-100 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Option</th>
                        <th class="px-4 py-3">Edit</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($shoe->options as $option)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-900 align-top">{{ $option->option_name }}</td>
                            <td class="px-4 py-4 align-top">
                                <form action="{{ route('admin.shoes.options.update', $option->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="option_name" value="{{ $option->option_name }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-400 text-slate-950 font-bold hover:bg-amber-300 transition-colors">
                                        <i class="fas fa-pen-to-square"></i>
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <form action="{{ route('admin.shoes.options.destroy', $option->id) }}" method="POST" onsubmit="return confirm('Delete this option?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-slate-500">No options available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 p-6 md:p-8 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Add SKU</p>
                <h2 class="text-2xl font-black mt-2">Create a variation</h2>
                <p class="text-sm text-slate-600 mt-2">Fill in one value for each option, then create the SKU in the same place you review it.</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">SKU</span>
        </div>

        @if ($shoe->options->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-slate-500">
                Add at least one option before creating a SKU.
            </div>
        @else
            <form action="{{ route('shoes.skus.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">

                <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-slate-50">
                    <table class="w-full min-w-180 text-sm">
                        <thead class="bg-slate-100 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                @foreach ($shoe->options as $option)
                                    <th class="px-4 py-3">{{ $option->option_name }}</th>
                                @endforeach
                                <th class="px-4 py-3">Stock</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($shoe->options as $option)
                                    <td class="px-4 py-4 align-top">
                                        <input type="text" name="skus[0][attributes][{{ $option->option_name }}]" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="{{ $option->option_name }}">
                                    </td>
                                @endforeach
                                <td class="px-4 py-4 align-top">
                                    <input type="number" name="skus[0][stock]" min="0" value="0" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                                        <i class="fas fa-plus"></i>
                                        Add SKU
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        @endif

        <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-slate-50">
            <table class="w-full min-w-220 text-sm">
                <thead class="bg-slate-100 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">Attributes</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Images</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($shoe->variations as $variation)
                        <tr class="align-top">
                            <td class="px-4 py-4 font-black text-slate-900">{{ $variation->sku_code }}</td>
                            <td class="px-4 py-4 text-slate-600">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(($variation->attributes ?? []) as $key => $value)
                                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700">
                                            <strong>{{ $key }}:</strong> {{ $value }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <form action="{{ route('admin.shoes.variations.update', $variation->id) }}" method="POST" class="space-y-3 max-w-40">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="stock" value="{{ $variation->stock_quantity }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
                                        <i class="fas fa-pen-to-square"></i>
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <form action="/shoe-variations/{{ $variation->id }}/images" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <input type="file" name="images[]" multiple accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900">
                                    <p class="text-xs font-semibold text-slate-500">Up to 10 images, max 4 MB each.</p>
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-400 text-slate-950 font-bold hover:bg-amber-300 transition-colors">
                                        <i class="fas fa-cloud-arrow-up"></i>
                                        Upload
                                    </button>
                                </form>

                                @if ($variation->images->count())
                                    <div class="mt-3 grid gap-2 md:grid-cols-2">
                                        @foreach ($variation->images as $image)
                                            @php
                                                $variationImageUrl = str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path);
                                            @endphp
                                            <article class="rounded-xl overflow-hidden border border-slate-200 bg-white">
                                                <img src="{{ $variationImageUrl }}" alt="Variation image" class="w-full h-20 object-cover object-center">
                                                <div class="p-2">
                                                    <form action="/shoe/variations/image/{{ $image->id }}" method="POST" onsubmit="return confirm('Delete this variation image?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors">
                                                            <i class="fas fa-trash"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-3 text-xs text-slate-500">No images.</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top">
                                <form action="/shoe-variations/{{ $variation->id }}" method="POST" onsubmit="return confirm('Delete this variation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-slate-500">No variations available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<dialog id="cover-image-dialog" class="rounded-3xl p-0 w-[min(92vw,860px)] bg-transparent backdrop:bg-slate-950/70">
    <div class="relative rounded-3xl bg-white overflow-hidden shadow-2xl">
        <button type="button" id="cover-image-close" class="absolute top-4 right-4 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white hover:bg-slate-800">
            <i class="fas fa-xmark"></i>
        </button>
        <div class="bg-slate-100 p-3 md:p-4">
            <img src="{{ $coverUrl }}" alt="{{ $shoe->shoe_name }} large preview" class="w-full max-h-[80vh] object-contain">
        </div>
    </div>
</dialog>

<script>
    (function () {
        const trigger = document.getElementById('cover-image-trigger');
        const dialog = document.getElementById('cover-image-dialog');
        const closeButton = document.getElementById('cover-image-close');

        if (!trigger || !dialog) {
            return;
        }

        trigger.addEventListener('click', function () {
            if (typeof dialog.showModal === 'function') {
                dialog.showModal();
            }
        });

        closeButton?.addEventListener('click', function () {
            dialog.close();
        });

        dialog.addEventListener('click', function (event) {
            const rect = dialog.getBoundingClientRect();
            const isBackdropClick = event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom;
            if (isBackdropClick) {
                dialog.close();
            }
        });
    })();
</script>
@endsection
