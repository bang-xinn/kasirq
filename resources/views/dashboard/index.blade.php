@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-500/10 group">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all"></div>
        <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm border border-indigo-100 dark:border-indigo-500/20">💰</div>
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Penjualan Hari Ini</div>
        <div class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</div>
        <div class="text-xs font-medium text-slate-400 mt-2 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $totalTransactionsToday }} transaksi</div>
    </div>
    
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10 group">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-emerald-500/10 dark:bg-emerald-500/20 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm border border-emerald-100 dark:border-emerald-500/20">📈</div>
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Penjualan Bulan Ini</div>
        <div class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Rp {{ number_format($totalSalesThisMonth, 0, ',', '.') }}</div>
        <div class="text-xs font-medium text-slate-400 mt-2 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $totalTransactionsThisMonth }} transaksi</div>
    </div>
    
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10 group">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-500/10 dark:bg-amber-500/20 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
        <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm border border-amber-100 dark:border-amber-500/20">📦</div>
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Produk Aktif</div>
        <div class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">{{ $totalProducts }}</div>
        @if($lowStockProducts > 0)
            <div class="text-xs font-medium text-amber-600 mt-2 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ $lowStockProducts }} stok menipis</div>
        @else
            <div class="text-xs font-medium text-slate-400 mt-2 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Stok aman</div>
        @endif
    </div>
    
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 relative overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 group">
        <div class="absolute -top-6 -right-6 w-24 h-24 bg-blue-500/10 dark:bg-blue-500/20 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm border border-blue-100 dark:border-blue-500/20">👥</div>
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">Total Kasir</div>
        <div class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">{{ $totalCashiers }}</div>
        <div class="text-xs font-medium text-slate-400 mt-2 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart -->
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Grafik Penjualan 7 Hari</h2>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Transaksi Terbaru</h2>
            <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-500/10 px-4 py-2 rounded-xl transition-colors">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800/60 whitespace-nowrap">Invoice</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800/60 whitespace-nowrap">Kasir</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800/60 text-right whitespace-nowrap">Total</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800/60 text-center whitespace-nowrap">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($recentTransactions as $t)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-4 py-4 text-sm font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">{{ $t->invoice_number }}</td>
                        <td class="px-4 py-4 text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">{{ $t->cashier?->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400 text-right whitespace-nowrap">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            @if($t->payment_method === 'cash')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">TUNAI</span>
                            @elseif($t->payment_method === 'qris')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/20">QRIS</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">TRANSFER</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada transaksi</td>
                    </tr>
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
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chartData = @json($chartData);
    
    // Check if dark mode is active for chart colors
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(51, 65, 85, 0.4)' : 'rgba(226, 232, 240, 0.8)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Penjualan',
                data: chartData.sales,
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(99, 102, 241, 0.4)',
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                    titleColor: isDark ? '#f8fafc' : '#0f172a',
                    bodyColor: isDark ? '#cbd5e1' : '#475569',
                    borderColor: isDark ? 'rgba(51, 65, 85, 1)' : 'rgba(226, 232, 240, 1)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: gridColor, drawBorder: false },
                    border: { display: false },
                    ticks: { color: textColor, callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K', font: { family: 'Inter', size: 11, weight: '500' } },
                    beginAtZero: true
                },
                x: { 
                    grid: { display: false, drawBorder: false }, 
                    border: { display: false },
                    ticks: { color: textColor, font: { family: 'Inter', size: 11, weight: '500' } } 
                }
            },
            animation: {
                y: { duration: 1000, easing: 'easeOutQuart' }
            }
        }
    });

    // Handle theme toggle to update chart colors dynamically
    const themeBtn = document.getElementById('themeToggleBtn');
    if(themeBtn) {
        themeBtn.addEventListener('click', () => {
            setTimeout(() => {
                const currentIsDark = document.documentElement.classList.contains('dark');
                const newGridColor = currentIsDark ? 'rgba(51, 65, 85, 0.4)' : 'rgba(226, 232, 240, 0.8)';
                const newTextColor = currentIsDark ? '#94a3b8' : '#64748b';
                
                chart.options.scales.y.grid.color = newGridColor;
                chart.options.scales.x.ticks.color = newTextColor;
                chart.options.scales.y.ticks.color = newTextColor;
                chart.options.plugins.tooltip.backgroundColor = currentIsDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.9)';
                chart.options.plugins.tooltip.titleColor = currentIsDark ? '#f8fafc' : '#0f172a';
                chart.options.plugins.tooltip.bodyColor = currentIsDark ? '#cbd5e1' : '#475569';
                chart.options.plugins.tooltip.borderColor = currentIsDark ? 'rgba(51, 65, 85, 1)' : 'rgba(226, 232, 240, 1)';
                
                chart.update();
            }, 10); // Small delay to let the class toggle apply
        });
    }
});
</script>
@endpush
