@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-header">
        <h2 class="card-title">Edit: {{ $product->name }}</h2>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">SKU *</label>
                <div style="display:flex; gap:10px;">
                    <input type="text" name="sku" id="skuInput" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                    <button type="button" class="btn btn-secondary" onclick="generateSku()" style="padding: 0 12px; font-size:12px; flex-shrink:0" title="Generate ulang SKU">🔄</button>
                </div>
                @error('sku')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="category_id" id="categoryId" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Satuan *</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Harga Jual (Rp) *</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" required>
                @error('price')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Harga Modal (Rp)</label>
                <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Stok *</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
            </div>
            <div class="form-group" style="display:flex; align-items:center; padding-top:28px">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} style="accent-color:var(--accent); width:18px; height:18px">
                    <span class="text-sm">Produk Aktif</span>
                </label>
            </div>
            <div class="form-group" style="grid-column: 1 / -1">
                <label class="form-label">Foto Produk (Opsional)</label>
                @if($product->image)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ Storage::url($product->image) }}" alt="Foto {{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <span class="text-sm text-muted">Biarkan kosong jika tidak ingin mengubah foto.</span>
                @error('image')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Produk</button>
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
            const originalBtnText = skuInput.nextElementSibling.innerHTML;
            skuInput.nextElementSibling.innerHTML = '⏳';

            fetch(`{{ route('products.generate-sku') }}?category_id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.sku) {
                        skuInput.value = data.sku;
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    skuInput.nextElementSibling.innerHTML = originalBtnText;
                });
        }
    }
</script>
@endpush
