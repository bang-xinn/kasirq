@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .no-print, header, nav, aside { display: none !important; }
        .main-content { margin-left: 0 !important; margin-top: 0 !important; padding: 0 !important; }
        body { background: white !important; color: black !important; }
        .print-receipt { 
            width: 80mm !important; 
            margin: 0 auto !important; 
            padding: 5mm !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
        }
        .print-receipt * {
            color: black !important;
        }
        @page { margin: 0; }
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Action Bar -->
    <div class="flex items-center justify-between mb-6 no-print">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center justify-center bg-white dark:bg-[#1a1d27] text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold px-4 py-2 rounded-xl transition-colors border border-slate-200 dark:border-slate-800/60 text-sm shadow-sm">
            ← Kembali
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Struk
        </button>
    </div>

    <!-- Receipt Card -->
    <div class="print-receipt bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 sm:p-8 shadow-sm">
        
        <!-- Receipt Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800/60">
            <div>
                <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">{{ $transaction->invoice_number }}</div>
                <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $transaction->created_at->format('l, d F Y — H:i') }}</div>
            </div>
            
            @if($transaction->payment_method === 'cash')
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">TUNAI</span>
            @elseif($transaction->payment_method === 'qris')
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/20">QRIS</span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">TRANSFER</span>
            @endif
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Kasir</div>
                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $transaction->cashier?->name ?? '-' }}</div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <div class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Catatan</div>
                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $transaction->notes ?: '-' }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-4">Item Transaksi</h3>
            <div class="overflow-hidden rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800/60">Produk</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800/60 text-right">Harga</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800/60 text-center">Qty</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800/60 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($transaction->items as $item)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $item->product_name }}</td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-sm font-medium text-right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">× {{ $item->quantity }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="flex justify-end pt-6 border-t border-slate-100 dark:border-slate-800/60">
            <div class="w-full sm:w-64 space-y-3">
                <div class="flex justify-between items-center text-sm font-medium text-slate-500 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                
                @if($transaction->discount_amount > 0)
                <div class="flex justify-between items-center text-sm font-bold text-amber-500 dark:text-amber-400">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="flex justify-between items-center text-lg font-black text-slate-900 dark:text-slate-100 pt-3 border-t border-slate-100 dark:border-slate-800/60">
                    <span>Total</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm font-medium text-slate-500 dark:text-slate-400 pt-2">
                    <span>Jumlah Bayar</span>
                    <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm font-medium text-slate-500 dark:text-slate-400">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
