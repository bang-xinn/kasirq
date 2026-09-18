@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Daftar Pengguna</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold">{{ $user->name }}</span>
                            @if($user->id === auth()->id())
                                <span class="badge badge-info" style="font-size:10px">Kamu</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-purple' : 'badge-info' }}">
                            {{ $user->role === 'admin' ? '👑 Admin' : '🧑‍💼 Kasir' }}
                        </span>
                    </td>
                    <td class="text-muted text-sm">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:var(--text-muted); padding:32px">Tidak ada pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $users->links('pagination.custom') }}</div>
</div>
@endsection
