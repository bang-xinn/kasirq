@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <h2 class="card-title">Form Pengguna Baru</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-control" required>
                <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Kasir</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
    </form>
</div>
@endsection
