@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .chart-container { position: relative; height: 250px; }
    .recent-table td:first-child { font-weight: 600; color: var(--accent); }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card indigo">
        <div class="stat-icon indigo">💰</div>
        <div class="stat-label">Penjualan Hari Ini</div>
        <div class="stat-value">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</div>
        <div class="text-xs text-muted" style="margin-top:6px">{{ $totalTransactionsToday }} transaksi</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">📈</div>
        <div class="stat-label">Penjualan Bulan Ini</div>
        <div class="stat-value">Rp {{ number_format($totalSalesThisMonth, 0, ',', '.') }}</div>
        <div class="text-xs text-muted" style="margin-top:6px">{{ $totalTransactionsThisMonth }} transaksi</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📦</div>
        <div class="stat-label">Total Produk Aktif</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        @if($lowStockProducts > 0)
            <div class="text-xs" style="margin-top:6px; color: var(--warning)">⚠ {{ $lowStockProducts }} stok menipis</div>
        @else
            <div class="text-xs text-muted" style="margin-top:6px">Stok aman</div>
        @endif
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue">👥</div>
        <div class="stat-label">Total Kasir</div>
        <div class="stat-value">{{ $totalCashiers }}</div>
        <div class="text-xs text-muted" style="margin-top:6px">Aktif</div>
    </div>
</div>

<div class="grid-2" style="gap:24px">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Grafik Penjualan 7 Hari</h2>
        </div>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Transaksi Terbaru</h2>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody class="recent-table">
                    @forelse($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->invoice_number }}</td>
                        <td class="text-muted">{{ $t->cashier?->name ?? '-' }}</td>
                        <td class="text-green font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $t->payment_method === 'cash' ? 'badge-success' : ($t->payment_method === 'qris' ? 'badge-purple' : 'badge-info') }}">
                                {{ strtoupper($t->payment_method) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; color: var(--text-muted)">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const chartData = @json($chartData);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Penjualan',
            data: chartData.sales,
            backgroundColor: 'rgba(99,102,241,0.3)',
            borderColor: 'rgba(99,102,241,0.8)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                grid: { color: 'rgba(45,49,80,0.5)' },
                ticks: { color: '#8b92b8', callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K' }
            },
            x: { grid: { display: false }, ticks: { color: '#8b92b8' } }
        }
    }
});
</script>
@endpush
