<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shoe->shoe_name }} - Product Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --shopee-orange: #ee4d2d;
            --shopee-bg: #f5f5f5;
        }
        body { background-color: var(--shopee-bg); }
        .btn-shopee { background-color: var(--shopee-orange); color: white; }
        .btn-shopee:hover { background-color: #d73211; }
        .text-shopee { color: var(--shopee-orange); }
        .border-shopee { border-color: var(--shopee-orange); }
        .sticky-header th { position: sticky; top: 0; background: white; z-index: 10; border-bottom: 2px solid #eee; }
        .bg-shopee-light { background-color: #fff5f1; }
        .shopee-shadow { box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="text-gray-800 antialiased font-sans pb-20 bg-gray-50/50">

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Top Nav / Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-8 rounded-2xl shopee-shadow border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="bg-shopee/10 p-3 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-shopee" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight flex items-center gap-2">
                        <span class="text-shopee">Shopee</span> Seller Center
                    </h1>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-0.5">Product ID: #{{ $shoe->id }} &bull; {{ $shoe->shoe_name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Variation Count</p>
                    <p class="text-2xl font-black text-gray-700">{{ $shoe->variations->count() }} <span class="text-xs font-medium text-gray-400 lowercase">skus</span></p>
                </div>
                <div class="h-10 w-px bg-gray-100 hidden md:block"></div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Listing Status</p>
                    <div class="flex items-center justify-end gap-1.5 mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm font-bold text-green-600 uppercase">Active</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') ?? implode(' ', $errors->all()) }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Product & Options -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- 1. Product Basic Info -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h2 class="font-bold text-lg">Basic Information</h2>
                        <span class="text-xs text-shopee font-semibold uppercase">Required</span>
                    </div>
                    <form action="{{ route('shoes.update', $shoe->id) }}" method="POST" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input type="text" name="shoe_name" value="{{ $shoe->shoe_name }}" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:ring-shopee focus:border-shopee outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                            <select name="brand_id" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:ring-shopee focus:border-shopee outline-none transition">
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $shoe->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (RM)</label>
                            <input type="number" step="0.01" name="shoe_price" value="{{ $shoe->shoe_price }}" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:ring-shopee focus:border-shopee outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="shoe_description" rows="4" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:ring-shopee focus:border-shopee outline-none transition">{{ $shoe->shoe_description }}</textarea>
                        </div>
                        <div class="pt-2 flex gap-2">
                            <button type="submit" class="flex-1 btn-shopee font-bold py-2 rounded shadow-md transition transform active:scale-[0.98]">Update Product</button>
                            <button type="button" onclick="confirmDeleteShoe()" class="bg-gray-50 text-red-500 font-bold px-4 py-2 rounded hover:bg-red-500 hover:text-white transition border border-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                    <form id="delete-shoe-form" action="{{ route('shoes.destroy', $shoe->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')   
                    </form>
                </section>

                <!-- 1.5 Product Images -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-lg">Product Images</h2>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $shoe->images->count() }} Images</span>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6" id="product-images-grid">
                            @forelse($shoe->images as $image)
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                    @if($image->is_cover)
                                        <div class="absolute top-2 left-2 bg-shopee text-white text-[9px] font-black px-2 py-0.5 rounded shadow-lg uppercase tracking-widest z-10 ring-1 ring-white/20">Cover</div>
                                    @endif

                                    <form method="POST" action="/shoes/images/{{ $image->id }}" onsubmit="return confirm('Delete image?')" class="absolute top-2 right-2 z-20">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transform hover:scale-105 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3a1 1 0 100 2h14a1 1 0 100-2h-2V3a1 1 0 00-1-1H6zm2 6a1 1 0 10-2 0v6a1 1 0 102 0V8zm6 0a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>

                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2"></div>
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                    <p class="text-gray-400 text-sm font-medium italic">No images uploaded yet.</p>
                                </div>
                            @endforelse
                        </div>

                        <form action="/shoes/{{ $shoe->id }}/images" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label class="group relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-shopee/50 hover:bg-shopee/5 transition-all duration-300">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-10 h-10 bg-shopee/10 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300">
                                        <svg class="w-5 h-5 text-shopee" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] text-shopee font-black uppercase tracking-widest">Add Product Images</p>
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold">Multiple allowed</p>
                                </div>
                                <input type="file" name="images[]" multiple class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>

                        <div class="mt-3 flex justify-end">
                            <button id="save-order-btn" type="button" class="hidden btn-shopee font-semibold px-4 py-2 rounded-md shadow-sm" onclick="submitReorderForm()">Save Image Order</button>
                        </div>

                        <form id="reorder-images-form" action="/shoes/{{ $shoe->id }}/images/reorder" method="POST" class="hidden">
                            @csrf
                            <div id="reorder-inputs"></div>
                        </form>

                    </div>
                </section>

                <!-- 2. Product Options Card -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-bold text-lg text-gray-800">Sales Information</h2>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-bold">Define variations like Color and Size</p>
                    </div>
                    <div class="p-5 space-y-6">
                        <!-- Current Options List -->
                        <div class="space-y-3">
                            @forelse($shoe->options as $option)
                                <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm hover:border-shopee/30 transition">
                                    <form action="{{ route('shoes.options.update', $option->id) }}" method="POST" class="flex items-center gap-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="bg-shopee/10 text-shopee p-2 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="option_name" value="{{ $option->option_name }}" 
                                               class="flex-1 bg-transparent border-none focus:ring-0 font-bold text-gray-700 p-0">
                                        
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded bg-blue-50">Save</button>
                                        
                                        <button type="button" onclick="confirmDeleteOption('{{ $option->id }}')" 
                                                class="text-red-500 hover:text-red-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded bg-red-50">Delete</button>
                                    </form>
                                    <form id="delete-option-{{ $option->id }}" action="{{ route('shoes.options.destroy', $option->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-lg">
                                    <p class="text-gray-400 text-sm italic">No options defined yet.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Add New Option (Inline) -->
                        <div class="pt-6 border-t border-gray-100">
                            <form action="{{ route('shoes.options.store') }}" method="POST" class="space-y-3" onsubmit="return handleOptionSubmit(this)">
                                @csrf
                                <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Add New Variation Type</label>
                                <div class="flex gap-2">
                                    <input type="text" id="new_option_input" placeholder="e.g., Color" 
                                           class="flex-1 border border-gray-200 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-shopee outline-none transition">
                                    <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded text-sm font-bold shadow-md hover:bg-black transition transform active:scale-95">
                                        Add
                                    </button>
                                </div>
                                <div id="hidden_options_container"></div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: SKU Table -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 3. SKU Management Table -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col h-full border border-gray-100">
                    <div class="p-5 border-b border-gray-100 bg-white sticky top-0 z-20">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-bold text-lg">Variation List</h2>
                                <p class="text-xs text-gray-400 mt-1">Update stock and manage existing SKUs</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto relative">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead class="bg-gray-50/80 border-b border-gray-100 sticky top-0 z-10 sticky-header backdrop-blur-sm">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">SKU Code</th>
                                    @foreach($shoe->options as $option)
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $option->option_name }}</th>
                                    @endforeach
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Stock</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Images</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($shoe->variations as $variation)
                                    <tr id="variation-row-{{ $variation->id }}" class="hover:bg-shopee/5 transition-all group">
                                        <td class="px-6 py-4">
                                            <span class="text-[10px] font-mono font-black text-gray-500 bg-gray-100 px-2 py-1 rounded-sm border border-gray-200 uppercase">{{ $variation->sku_code }}</span>
                                        </td>
                                        
                                        <form action="{{ route('shoes.variations.update', $variation->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            @foreach($shoe->options as $option)
                                                <td class="px-6 py-4">
                                                    <input type="text" name="attributes[{{ $option->option_name }}]" 
                                                           value="{{ $variation->attributes[$option->option_name] ?? '' }}" 
                                                           class="w-full border-transparent group-hover:border-gray-200 border rounded px-2 py-1 text-sm focus:ring-0 focus:border-shopee bg-transparent transition">
                                                </td>
                                            @endforeach

                                            <td class="px-6 py-4">
                                                <input type="number" name="stock" value="{{ $variation->stock_quantity }}" 
                                                       class="w-20 border-transparent group-hover:border-gray-200 border rounded px-2 py-1 text-sm focus:ring-0 focus:border-shopee bg-transparent font-bold text-shopee">
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2 max-w-[200px]">
                                                    @foreach($variation->images as $vImage)
                                                        <div class="relative group/vimg w-10 h-10 rounded-lg overflow-hidden border border-gray-100 shadow-sm transition hover:scale-110 hover:z-20">
                                                            <img src="{{ asset('storage/' . $vImage->image_path) }}" class="w-full h-full object-cover">

                                                            <button type="submit" form="delete-variation-image-{{ $vImage->id }}" 
                                                                    class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transform hover:scale-105 transition" 
                                                                    title="Delete image">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3a1 1 0 100 2h14a1 1 0 100-2h-2V3a1 1 0 00-1-1H6zm2 6a1 1 0 10-2 0v6a1 1 0 102 0V8zm6 0a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                    
                                                    <button type="button" onclick="triggerVariationUpload('{{ $variation->id }}')" 
                                                            class="w-10 h-10 rounded-lg border-2 border-dashed border-shopee/30 bg-shopee/5 flex items-center justify-center cursor-pointer hover:bg-shopee/10 hover:border-shopee/50 transition-all group/upbtn">
                                                        <svg class="w-5 h-5 text-shopee opacity-60 group-hover/upbtn:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex justify-center items-center gap-1">
                                                    <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 p-1.5 rounded shadow-sm transition transform active:scale-90" title="Update SKU">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" onclick="confirmDeleteSku('{{ $variation->id }}')" 
                                                            class="text-white bg-red-400 hover:bg-red-500 p-1.5 rounded shadow-sm transition transform active:scale-90" title="Delete SKU">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </form>

                                        {{-- Hidden delete forms for variation images (outside the update form) --}}
                                        @foreach($variation->images as $vImage)
                                            <form id="delete-variation-image-{{ $vImage->id }}" action="/shoe/variations/image/{{ $vImage->id }}" method="POST" class="hidden" onsubmit="return confirm('Delete image?')">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endforeach

                                        <form id="delete-sku-{{ $variation->id }}" action="{{ route('shoes.variations.destroy', $variation->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 4 + $shoe->options->count() }}" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="bg-gray-50 p-4 rounded-full mb-3 text-gray-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <p class="text-gray-400 font-medium italic">No variations created yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- 4. Add SKU Variations (Multi-row) -->
                <section class="bg-white rounded-xl shadow-sm overflow-hidden border-2 border-shopee/20 ring-4 ring-shopee/5">
                    <div class="p-5 border-b border-shopee/10 bg-shopee/5 flex justify-between items-center">
                        <div>
                            <h2 class="font-bold text-lg text-shopee">Add SKU Variations</h2>
                            <p class="text-[10px] font-black text-shopee/40 uppercase tracking-widest">Generate multiple new SKUs at once</p>
                        </div>
                        <button type="button" onclick="addNewSkuRow()" 
                                class="flex items-center gap-1 bg-shopee text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg hover:shadow-shopee/20 hover:scale-[1.02] transition transform active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add New Row
                        </button>
                    </div>
                    
                    <form action="{{ route('shoes.skus.store') }}" method="POST" class="p-5">
                        @csrf
                        <input type="hidden" name="shoe_id" value="{{ $shoe->id }}">
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left" id="new-skus-table">
                                <thead class="border-b border-gray-100">
                                    <tr>
                                        @foreach($shoe->options as $option)
                                            <th class="px-3 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $option->option_name }}</th>
                                        @endforeach
                                        <th class="px-3 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest w-32">Stock</th>
                                        <th class="px-3 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest w-10"></th>
                                    </tr>
                                </thead>
                                <tbody id="sku-rows-container" class="divide-y divide-gray-50">
                                    <!-- Dynamic rows injected here -->
                                </tbody>
                            </table>
                        </div>

                        <div id="empty-add-state" class="py-10 text-center text-gray-400 text-sm font-medium italic hidden">
                            Click "Add New Row" to start adding variations.
                        </div>

                        <div class="mt-8 border-t border-gray-50 pt-6 flex justify-end">
                            <button type="submit" id="save-all-btn" class="btn-shopee font-bold px-12 py-4 rounded-xl shadow-xl hover:shadow-shopee/30 hover:translate-y-[-2px] transition transform active:translate-y-0 disabled:opacity-30 disabled:grayscale disabled:cursor-not-allowed">
                                Save All New SKUs
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        const optionNames = @json($shoe->options->pluck('option_name'));
        let rowIndex = 0;

        // --- Functions ---

        function handleOptionSubmit(form) {
            const val = document.getElementById('new_option_input').value.trim();
            if (!val) {
                alert('Please enter an option name (e.g. Color)');
                return false;
            }
            
            const container = document.getElementById('hidden_options_container');
            container.innerHTML = ''; 
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'option_names[]';
            hidden.value = val;
            container.appendChild(hidden);
            return true;
        }

        function addNewSkuRow() {
            const container = document.getElementById('sku-rows-container');
            const emptyState = document.getElementById('empty-add-state');
            const saveBtn = document.getElementById('save-all-btn');
            
            emptyState.classList.add('hidden');
            saveBtn.disabled = false;

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition anim-fade-in group';
            tr.id = `row-${rowIndex}`;

            let html = '';
            
            // Dynamic Attributes
            optionNames.forEach(option => {
                html += `
                    <td class="px-3 py-4">
                        <input type="text" name="skus[${rowIndex}][attributes][${option}]" required
                               placeholder="e.g., XL"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-shopee outline-none transition">
                    </td>
                `;
            });

            // Stock
            html += `
                <td class="px-3 py-4">
                    <input type="number" name="skus[${rowIndex}][stock]" value="10" min="0" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-shopee font-bold outline-none transition">
                </td>
            `;

            // Remove
            html += `
                <td class="px-3 py-4 text-right">
                    <button type="button" onclick="removeRow(${rowIndex})" class="text-gray-300 hover:text-red-500 transition transform active:scale-75">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </td>
            `;

            tr.innerHTML = html;
            container.appendChild(tr);
            rowIndex++;
        }

        function removeRow(idx) {
            const row = document.getElementById(`row-${idx}`);
            if (row) row.remove();
            
            const container = document.getElementById('sku-rows-container');
            if (container.children.length === 0) {
                document.getElementById('empty-add-state').classList.remove('hidden');
                document.getElementById('save-all-btn').disabled = true;
            }
        }

        // --- Confirmation Helpers ---

        function confirmDeleteShoe() {
            if (confirm('CRITICAL: This will delete the shoe and ALL its variations. Proceed?')) {
                document.getElementById('delete-shoe-form').submit();
            }
        }

        function confirmDeleteOption(id) {
            if (confirm('WARNING: Removing this option will modify ALL existing variations. If a variation becomes empty, it will be deleted. Proceed?')) {
                document.getElementById(`delete-option-${id}`).submit();
            }
        }

        function confirmDeleteSku(id) {
            if (confirm('Delete this specific variation?')) {
                document.getElementById(`delete-sku-${id}`).submit();
            }
        }

        function triggerVariationUpload(variationId) {
            const row = document.getElementById(`variation-row-${variationId}`);
            const existingImages = row ? row.querySelectorAll('img') : [];
            if (existingImages.length > 0) {
                if (!confirm('This variation already has an image. Replace it?')) return;
            }

            const form = document.getElementById('global-variation-upload-form');
            form.action = `/shoe-variations/${variationId}/images`;
            document.getElementById('variation-file-input').click();
        }

        // --- Product images drag & drop reorder ---
        let orderChanged = false;

        function initImageDnD() {
            const grid = document.getElementById('product-images-grid');
            if (!grid) return;
            let draggedEl = null;

            grid.querySelectorAll('[data-image-id]').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    draggedEl = this;
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', this.dataset.imageId);
                    this.classList.add('opacity-70');
                });

                item.addEventListener('dragend', function() { this.classList.remove('opacity-70'); });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    this.classList.add('ring-2', 'ring-shopee/40');
                });

                item.addEventListener('dragleave', function() { this.classList.remove('ring-2', 'ring-shopee/40'); });

                item.addEventListener('drop', function(e) {
                    e.stopPropagation();
                    const draggedId = e.dataTransfer.getData('text/plain');
                    if (!draggedId) return;
                    const dragged = grid.querySelector(`[data-image-id='${draggedId}']`);
                    if (dragged && dragged !== this) {
                        grid.insertBefore(dragged, this);
                        markOrderChanged();
                    }
                    this.classList.remove('ring-2', 'ring-shopee/40');
                });
            });
        }

        function markOrderChanged() {
            orderChanged = true;
            const btn = document.getElementById('save-order-btn');
            if (btn) btn.classList.remove('hidden');
            updateCoverBadge();
        }

        function updateCoverBadge() {
            const grid = document.getElementById('product-images-grid');
            if (!grid) return;
            const items = grid.querySelectorAll('[data-image-id]');
            items.forEach((el, idx) => {
                const badge = el.querySelector('.cover-badge');
                if (idx === 0) {
                    if (badge) badge.classList.remove('hidden');
                    else {
                        const d = document.createElement('div');
                        d.className = 'absolute top-2 left-2 bg-shopee text-white text-[9px] font-black px-2 py-0.5 rounded shadow-lg uppercase tracking-widest z-10 ring-1 ring-white/20 cover-badge';
                        d.innerText = 'Cover';
                        el.appendChild(d);
                    }
                } else {
                    if (badge) badge.classList.add('hidden');
                }
            });
        }

        function submitReorderForm() {
            const grid = document.getElementById('product-images-grid');
            const inputs = document.getElementById('reorder-inputs');
            inputs.innerHTML = '';
            const items = grid.querySelectorAll('[data-image-id]');
            items.forEach(el => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = el.dataset.imageId;
                inputs.appendChild(input);
            });
            document.getElementById('reorder-images-form').submit();
        }

        // Initialize
        window.onload = () => {
            if (@json($shoe->variations->count()) === 0) {
                addNewSkuRow();
            } else {
                document.getElementById('empty-add-state').classList.remove('hidden');
                document.getElementById('save-all-btn').disabled = true;
            }

            // Initialize image drag & drop and cover badges
            initImageDnD();
            updateCoverBadge();
        };
    </script>

    <!-- Global Hidden Forms -->
    <form id="global-variation-upload-form" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" id="variation-file-input" name="images[]" onchange="this.form.submit()">
    </form>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        .anim-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</body>
</html>