@extends('layouts.app')
@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="min-h-[calc(100vh-11rem)] flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl p-8 text-gray-100">

            <div class="text-center mb-7">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-2xl font-bold text-gray-100">Edit Profil</h2>
                <p class="mt-1 text-sm text-gray-400">Perbarui informasi akun Anda.</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-200 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus
                           class="w-full px-4 py-3 rounded-xl bg-gray-900 text-gray-100 placeholder-gray-500 border focus:outline-none focus:ring-2 transition-colors
                                  {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500/40 focus:border-red-500' : 'border-gray-700 focus:ring-indigo-500/40 focus:border-indigo-500' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-200 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-gray-900 text-gray-100 placeholder-gray-500 border focus:outline-none focus:ring-2 transition-colors
                                  {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500/40 focus:border-red-500' : 'border-gray-700 focus:ring-indigo-500/40 focus:border-indigo-500' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-200 mb-2">Password Baru</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl bg-gray-900 text-gray-100 placeholder-gray-500 border focus:outline-none focus:ring-2 transition-colors
                                  {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500/40 focus:border-red-500' : 'border-gray-700 focus:ring-indigo-500/40 focus:border-indigo-500' }}">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-200 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl bg-gray-900 text-gray-100 placeholder-gray-500 border focus:outline-none focus:ring-2 transition-colors
                                  {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500/40 focus:border-red-500' : 'border-gray-700 focus:ring-indigo-500/40 focus:border-indigo-500' }}">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-600/30 transition-colors">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
