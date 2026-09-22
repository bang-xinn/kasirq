@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="max-w-4xl bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800/60">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Edit: {{ $product->name }}</h2>
        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-700 text-sm">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('name', $product->name) }}" required>
                @error('name')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">SKU <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="sku" id="skuInput" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('sku', $product->sku) }}" required>
                    <button type="button" onclick="generateSku()" class="shrink-0 inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-700" title="Generate ulang SKU">
                        🔄
                    </button>
                </div>
                @error('sku')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" id="categoryId" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Satuan <span class="text-red-500">*</span></label>
                <input type="text" name="unit" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('unit', $product->unit) }}" required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('price', $product->price) }}" min="0" required>
                @error('price')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Harga Modal (Rp)</label>
                <input type="number" name="cost_price" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('cost_price', $product->cost_price) }}" min="0">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Stok <span class="text-red-500">*</span></label>
                <input type="number" name="stock" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('stock', $product->stock) }}" min="0" required>
            </div>

            <div class="space-y-2 flex items-center h-full pt-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:ring-offset-slate-800 transition-all cursor-pointer">
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Produk Aktif</span>
                </label>
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Foto Produk (Opsional)</label>
                
                @if($product->image)
                    <div class="mb-4 inline-block relative group">
                        <img src="{{ Storage::url($product->image) }}" alt="Foto {{ $product->name }}" class="w-32 h-32 object-cover rounded-2xl border-4 border-white dark:border-slate-800 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                            <span class="text-white text-xs font-bold px-2 py-1 bg-black/50 rounded-lg">Current Image</span>
                        </div>
                    </div>
                @endif
                
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400"><span class="font-bold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG or JPEG (MAX. 2MB)</p>
                        </div>
                        <input type="file" name="image" class="hidden" accept="image/*" />
                    </label>
                </div>
                <span class="text-xs font-medium text-slate-400 mt-2 block">Biarkan kosong jika tidak ingin mengubah foto.</span>
                @error('image')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
                Update Produk
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function generateSku() {
        const categoryId = document.getElementById('categoryId').value;
        const skuInput = document.getElementById('skuInput');
        
        if (!categoryId) {
            alert('Silakan pilih kategori terlebih dahulu!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin mengganti SKU yang sudah ada?')) {
            const btn = skuInput.nextElementSibling;
            const originalBtnText = btn.innerHTML;
            btn.innerHTML = '⏳';

            fetch(`{{ route('products.generate-sku') }}?category_id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.sku) {
                        skuInput.value = data.sku;
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    btn.innerHTML = originalBtnText;
                });
        }
    }

    // Add visual feedback when file is selected
    const fileInput = document.querySelector('input[type="file"]');
    fileInput.addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            const label = this.parentElement.querySelector('p:first-of-type');
            label.innerHTML = `<span class="font-bold text-indigo-500">Selected: ${this.files[0].name}</span>`;
        }
    });
</script>
@endpush
