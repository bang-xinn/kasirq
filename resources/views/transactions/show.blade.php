@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
        .main { margin-left: 0 !important; }
        .card { border: none !important; }
        body { background: white !important; color: black !important; }
    }
</style>
@endpush

@section('content')
<div style="max-width:700px">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">← Kembali</a>
        <button onclick="window.print()" class="btn btn-primary no-print">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Struk
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="font-semibold text-accent" style="font-size:18px">{{ $transaction->invoice_number }}</div>
                <div class="text-sm text-muted">{{ $transaction->created_at->format('l, d F Y — H:i') }}</div>
            </div>
            <span class="badge {{ $transaction->payment_method === 'cash' ? 'badge-success' : ($transaction->payment_method === 'qris' ? 'badge-purple' : 'badge-info') }}" style="font-size:13px; padding:6px 14px">
                {{ strtoupper($transaction->payment_method) }}
            </span>
        </div>

        <div class="grid-2 mb-4">
            <div>
                <div class="text-xs text-muted mb-1">Kasir</div>
                <div class="font-semibold">{{ $transaction->cashier?->name ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-muted mb-1">Catatan</div>
                <div class="font-semibold">{{ $transaction->notes ?: '-' }}</div>
            </div>
        </div>

        <div class="table-wrapper" style="margin-bottom:20px">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td class="font-semibold">{{ $item->product_name }}</td>
                        <td class="text-muted">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td><span class="badge badge-gray">× {{ $item->quantity }}</span></td>
                        <td class="text-right text-green font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top:16px; display:flex; flex-direction:column; align-items:flex-end; gap:6px">
            <div class="summary-row" style="width:280px; display:flex; justify-content:space-between; font-size:14px; color:var(--text-secondary)">
                <span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="summary-row" style="width:280px; display:flex; justify-content:space-between; font-size:14px; color:var(--warning)">
                <span>Diskon</span><span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div style="width:280px; display:flex; justify-content:space-between; font-size:18px; font-weight:800; color:var(--text-primary); padding-top:8px; border-top:1px solid var(--border)">
                <span>Total</span><span class="text-green">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div style="width:280px; display:flex; justify-content:space-between; font-size:13px; color:var(--text-muted)">
                <span>Jumlah Bayar</span><span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div style="width:280px; display:flex; justify-content:space-between; font-size:13px; color:var(--text-muted)">
                <span>Kembalian</span><span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
