@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Semua Transaksi</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">{{ $transactions->total() }} transaksi</span>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6 bg-slate-50/50 dark:bg-slate-800/20 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/40">
        <div class="relative flex-1 min-w-[200px] sm:max-w-[240px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" name="search" class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm" placeholder="Nomor invoice..." value="{{ request('search') }}">
        </div>
        
        <input type="date" name="date" class="min-w-[150px] py-2.5 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-sm text-slate-700 dark:text-slate-300" value="{{ request('date') }}">
        
        <select name="payment_method" class="min-w-[150px] py-2.5 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-sm cursor-pointer text-slate-700 dark:text-slate-300">
            <option value="">Semua Metode</option>
            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
        
        <button type="submit" class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 font-bold px-4 py-2.5 rounded-xl transition-colors border border-indigo-200 dark:border-indigo-500/30 text-sm">Filter</button>
        
        @if(request()->hasAny(['search','date','payment_method']))
            <a href="{{ route('transactions.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold px-4 py-2.5 rounded-xl transition-colors border border-slate-200 dark:border-slate-700 text-sm">Reset</a>
        @endif
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800/60">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800/60">
                <tr>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Invoice</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tanggal</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Kasir</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-center">Items</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-right">Total</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-right">Bayar</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-center">Metode</th>
                    <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-transparent">
                @forelse($transactions as $t)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-5 py-4 text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $t->invoice_number }}</td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-500 dark:text-slate-400">{{ $t->created_at->format('d M Y H:i') }}</td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $t->cashier?->name ?? '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            {{ $t->items->count() }} item
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400 text-right">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-500 dark:text-slate-400 text-right">Rp {{ number_format($t->payment_amount, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($t->payment_method === 'cash')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">TUNAI</span>
                        @elseif($t->payment_method === 'qris')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/20">QRIS</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">TRANSFER</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('transactions.show', $t) }}" class="inline-flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold px-3 py-1.5 rounded-lg transition-colors border border-slate-200 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-500/30 text-xs">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            <span class="text-sm font-medium">Tidak ada transaksi ditemukan</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links('pagination.custom') }}
    </div>
</div>
@endsection
