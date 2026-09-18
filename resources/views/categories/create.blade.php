@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <h2 class="card-title">Form Kategori Baru</h2>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Kategori *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Makanan" required>
            @error('name')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat kategori">{{ old('description') }}</textarea>
        </div>
        <div class="flex gap-2 justify-between">
            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
        </div>
    </form>
</div>
@endsection
