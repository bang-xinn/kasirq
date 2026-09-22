@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800/60">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Edit: {{ $user->name }}</h2>
        <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-700 text-sm">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('name', $user->name) }}" required>
            @error('name')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('email', $user->email) }}" required>
            @error('email')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Role <span class="text-red-500">*</span></label>
            <select name="role" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" required>
                <option value="cashier" {{ old('role', $user->role) === 'cashier' ? 'selected' : '' }}>Kasir</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Password Baru <span class="font-normal text-slate-400 dark:text-slate-500">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all">
                @error('password')<p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all">
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
                Update Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
