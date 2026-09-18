@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <h2 class="card-title">Edit: {{ $user->name }}</h2>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Role *</label>
            <select name="role" class="form-control" required>
                <option value="cashier" {{ old('role', $user->role) === 'cashier' ? 'selected' : '' }}>Kasir</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-control">
                @error('password')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Pengguna</button>
    </form>
</div>
@endsection
