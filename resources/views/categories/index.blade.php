@extends('layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Kategori</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Deskripsi</th>
                    <th>Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td class="font-semibold">{{ $category->name }}</td>
                    <td><span class="badge badge-gray">{{ $category->slug }}</span></td>
                    <td class="text-muted text-sm">{{ $category->description ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $category->products_count }} produk</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:var(--text-muted); padding:32px">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $categories->links('pagination.custom') }}</div>
</div>
@endsection
