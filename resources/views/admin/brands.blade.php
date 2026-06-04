@extends('admin.layout')

@section('title', 'Brands')
@section('eyebrow', 'Admin Catalog')
@section('page-title', 'Brands')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h1 class="text-4xl md:text-5xl font-black tracking-tight mt-2">Brands</h1>
        <p class="text-slate-600 mt-3 max-w-2xl">Full CRUD view for managing brand records.</p>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </a>
</div>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Brands</p>
        <p class="text-3xl font-black mt-3">{{ $brands->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">With Shoes</p>
        <p class="text-3xl font-black mt-3">{{ $brands->where('shoes_count', '>', 0)->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Empty</p>
        <p class="text-3xl font-black mt-3">{{ $brands->where('shoes_count', 0)->count() }}</p>
    </div>
    <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-bold">Total Shoes</p>
        <p class="text-3xl font-black mt-3">{{ $brands->sum('shoes_count') }}</p>
    </div>
</div>

<div class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6 md:p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Create Brand</p>
                <h2 class="text-2xl font-black mt-2">New record</h2>
            </div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold uppercase tracking-[0.2em]">CRUD</span>
        </div>
        <form action="{{ route('admin.brands.store') }}" method="POST" class="space-y-4">@csrf<div><label class="block text-sm font-bold text-slate-700 mb-2">Brand Name</label><input type="text" name="brand_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Nike" required></div>
            <div><label class="block text-sm font-bold text-slate-700 mb-2">Description</label><textarea name="brand_description" rows="5" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:ring-2 focus:ring-slate-900" placeholder="Brand summary..."></textarea></div><button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-colors"><i class="fas fa-plus"></i>Create Brand</button>
        </form>
    </section>

    <section class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500 font-bold">Brand Directory</p>
                <h2 class="text-2xl font-black mt-2">Edit and remove records</h2>
            </div><span class="px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-bold uppercase tracking-[0.2em]">Live CRUD</span>
        </div>
        <div class="overflow-x-auto p-6 md:p-8">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Brand</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Shoes</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($brands as $brand)
                    <tr>
                        <td class="px-4 py-3 align-top">
                            <input type="text" form="brand-update-{{ $brand->id }}" name="brand_name" value="{{ $brand->brand_name }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-slate-900">
                            <div class="text-xs text-slate-500 mt-2">ID: {{ $brand->id }}</div>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <textarea form="brand-update-{{ $brand->id }}" name="brand_description" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 outline-none focus:ring-2 focus:ring-slate-900">{{ $brand->brand_description }}</textarea>
                        </td>
                        <td class="px-4 py-3 align-top font-black">{{ $brand->shoes_count }}</td>
                        <td class="px-4 py-3 align-top whitespace-nowrap">
                            <form id="brand-update-{{ $brand->id }}" action="{{ route('admin.brands.update', $brand->id) }}" method="POST" class="inline-block mr-2">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">Save</button>
                            </form>

                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this brand?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 pb-6">
            {{ $brands->links() }}
        </div>
    </section>
</div>
@endsection