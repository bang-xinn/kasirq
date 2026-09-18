@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="flex items-center gap-3">
            <h2 class="card-title">Semua Transaksi</h2>
            <span class="badge badge-info">{{ $transactions->total() }} transaksi</span>
        </div>
    </div>

    <form method="GET" style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap">
        <input type="text" name="search" class="form-control" style="max-width:220px" placeholder="🔍 Nomor invoice..." value="{{ request('search') }}">
        <input type="date" name="date" class="form-control" style="max-width:180px" value="{{ request('date') }}">
        <select name="payment_method" class="form-control" style="max-width:160px">
            <option value="">Semua Metode</option>
            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai</option>
            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','date','payment_method']))
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Reset</a>
        @endif
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Metode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td class="font-semibold text-accent">{{ $t->invoice_number }}</td>
                    <td class="text-muted text-sm">{{ $t->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $t->cashier?->name ?? '-' }}</td>
                    <td><span class="badge badge-gray">{{ $t->items->count() }} item</span></td>
                    <td class="text-green font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td class="text-muted">Rp {{ number_format($t->payment_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $t->payment_method === 'cash' ? 'badge-success' : ($t->payment_method === 'qris' ? 'badge-purple' : 'badge-info') }}">
                            {{ strtoupper($t->payment_method) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('transactions.show', $t) }}" class="btn btn-secondary btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:var(--text-muted); padding:32px">Tidak ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">{{ $transactions->links('pagination.custom') }}</div>
</div>
@endsection
