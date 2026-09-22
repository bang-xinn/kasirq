@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="max-w-2xl bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800/60">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Form Kategori Baru</h2>
        <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-700 text-sm">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('name') }}" placeholder="Contoh: Makanan" required>
            @error('name')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" placeholder="Deskripsi singkat kategori">{{ old('description') }}</textarea>
            @error('description')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
