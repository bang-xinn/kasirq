@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Daftar Pengguna</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">{{ $users->total() }} pengguna</span>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800/60">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800/60">
                <tr>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">#</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Nama</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Email</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Role</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Bergabung</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-transparent">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-5 py-4 text-sm font-medium text-slate-400 dark:text-slate-500">{{ $loop->iteration }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-500/20 shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">Kamu</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $user->role === 'admin' ? 'bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-200 dark:border-violet-500/20' : 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20' }}">
                            {{ $user->role === 'admin' ? '👑 Admin' : '🧑‍💼 Kasir' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold px-3 py-1.5 rounded-lg transition-colors border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-500/30 text-xs">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center bg-white dark:bg-slate-900 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 font-bold px-3 py-1.5 rounded-lg transition-colors border border-red-100 dark:border-red-900/30 text-xs">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span class="text-sm font-medium">Tidak ada pengguna</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links('pagination.custom') }}
    </div>
</div>
@endsection
