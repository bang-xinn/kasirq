@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <h2 class="card-title">Edit: {{ $category->name }}</h2>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Nama Kategori *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name')<p class="text-sm text-red" style="margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Kategori</button>
    </form>
</div>
@endsection
