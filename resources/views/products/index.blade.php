@extends('layouts.app')
@section('title', 'Produk')
@section('page-title', 'Produk')

@section('content')
<div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Daftar Produk</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">{{ $products->total() }} produk</span>
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6 bg-slate-50/50 dark:bg-slate-800/20 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/40">
        <div class="relative flex-1 min-w-[200px] sm:max-w-[280px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 shadow-sm" placeholder="Cari nama / SKU..." value="{{ request('search') }}">
        </div>
        
        <select name="category" class="min-w-[160px] py-2.5 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-sm cursor-pointer text-slate-700 dark:text-slate-300">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        
        <select name="sort" class="min-w-[160px] py-2.5 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-sm cursor-pointer text-slate-700 dark:text-slate-300">
            <option value="">Urutkan Terbaru</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A-Z (Nama Produk)</option>
            <option value="sku_asc" {{ request('sort') == 'sku_asc' ? 'selected' : '' }}>SKU (A-Z)</option>
            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
        </select>
        
        <button type="submit" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 font-bold px-4 py-2.5 rounded-xl transition-colors border border-indigo-200 dark:border-indigo-500/30 text-sm">Filter</button>
        
        @if(request()->hasAny(['search','category','sort']))
            <a href="{{ route('products.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2.5 rounded-xl transition-colors border border-slate-200 dark:border-slate-700 text-sm">Reset</a>
        @endif
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800/60">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800/60">
                <tr>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">#</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Produk</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">SKU</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Kategori</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Harga Jual</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Stok</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Status</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-transparent">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-5 py-4 text-sm font-medium text-slate-400 dark:text-slate-500">{{ $loop->iteration }}</td>
                    <td class="px-5 py-4 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $product->name }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 tracking-wider">
                            {{ $product->sku }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $product->category->name }}</td>
                    <td class="px-5 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $product->stock <= 10 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' }}">
                            {{ $product->stock }} {{ $product->unit }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $product->is_active ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold px-3 py-1.5 rounded-lg transition-colors border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-500/30 text-xs">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center bg-white dark:bg-slate-900 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 font-bold px-3 py-1.5 rounded-lg transition-colors border border-red-100 dark:border-red-900/30 text-xs">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span class="text-sm font-medium">Tidak ada produk ditemukan</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links('pagination.custom') }}
    </div>
</div>
@endsection
