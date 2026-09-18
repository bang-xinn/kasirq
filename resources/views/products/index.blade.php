@extends('layouts.app')
@section('title', 'Produk')
@section('page-title', 'Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <h2 class="card-title">Daftar Produk</h2>
            <span class="badge badge-info">{{ $products->total() }} produk</span>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </a>
    </div>

    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap">
        <input type="text" name="search" class="form-control" style="max-width:280px" placeholder="🔍 Cari nama / SKU..." value="{{ request('search') }}">
        <select name="category" class="form-control" style="max-width:200px">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="sort" class="form-control" style="max-width:200px">
            <option value="">Urutkan Terbaru</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z (Nama Produk)</option>
            <option value="sku_asc" {{ request('sort') == 'sku_asc' ? 'selected' : '' }}>SKU (A-Z)</option>
            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','category','sort']))
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td class="font-semibold">{{ $product->name }}</td>
                    <td><span class="badge badge-gray">{{ $product->sku }}</span></td>
                    <td class="text-muted">{{ $product->category->name }}</td>
                    <td class="text-green font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $product->stock <= 10 ? 'badge-warning' : 'badge-success' }}">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:var(--text-muted); padding:32px">Tidak ada produk ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $products->links('pagination.custom') }}</div>
</div>
@endsection
