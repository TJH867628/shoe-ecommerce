@extends('admin.layout')

@section('title', 'Shoes')
@section('eyebrow', 'Admin Catalog')
@section('page-title', 'Shoes')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Shoes</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Create, edit, update, and delete shoes from a single admin workspace.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-arrow-left"></i>Back to Dashboard</a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Shoes</p>
        <p class="text-3xl font-black mt-3">{{ $shoes->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Brands</p>
        <p class="text-3xl font-black mt-3">{{ $brands->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Options</p>
        <p class="text-3xl font-black mt-3">{{ $shoes->sum(fn ($shoe) => $shoe->options->count()) }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Variations</p>
        <p class="text-3xl font-black mt-3">{{ $shoes->sum(fn ($shoe) => $shoe->variations->count()) }}</p>
    </div>
</div>

<div class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6 md:p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Create Shoe</p>
                <h2 class="text-2xl font-black mt-2">New product</h2>
            </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">CRUD</span>
        </div>
        <form action="{{ route('admin.shoes.store') }}" method="POST" class="space-y-4">@csrf<div><label class="block text-sm font-bold text-slate-700 mb-2">Brand</label><select name="brand_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" required>
                    <option value="">Select brand</option>@foreach ($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>@endforeach
                </select></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-2">Shoe Name</label><input type="text" name="shoe_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Air Max Runner" required></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-2">Description</label><textarea name="shoe_description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Product summary..." required></textarea></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-2">Price</label><input type="number" step="0.01" name="shoe_price" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="499.00" required></div><button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-plus"></i>Create Shoe</button>
        </form>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Product Directory</p>
                <h2 class="text-2xl font-black mt-2">Edit or delete shoes</h2>
            </div><span class="px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold uppercase tracking-[0.2em]">Live CRUD</span>
        </div>
        <div class="overflow-x-auto p-6 md:p-8">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Brand</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($shoes as $shoe)
                    <tr>
                        <td class="px-4 py-3 align-top">
                            <div class="font-bold">{{ $shoe->shoe_name }}</div>
                            <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($shoe->shoe_description, 80) }}</div>
                        </td>
                        <td class="px-4 py-3 align-top text-slate-600">{{ $shoe->brand?->brand_name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3 align-top font-bold">RM {{ number_format((float) $shoe->shoe_price, 2) }}</td>
                        <td class="px-4 py-3 align-top text-slate-600">{{ $shoe->variations->sum('stock_quantity') }}</td>
                        <td class="px-4 py-3 align-top">
                            <a href="{{ route('admin.shoes.show', $shoe->id) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 mr-2">Edit</a>
                            <form action="{{ route('admin.shoes.destroy', $shoe->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this shoe?')">@csrf @method('DELETE')<button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Delete</button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6">
            {{ $shoes->links() }}
        </div>
    </section>
</div>
@endsection