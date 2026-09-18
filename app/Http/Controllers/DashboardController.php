<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSalesToday = Transaction::whereDate('created_at', today())
            ->sum('total');

        $totalTransactionsToday = Transaction::whereDate('created_at', today())
            ->count();

        $totalSalesThisMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $totalTransactionsThisMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock', '<=', 10)->where('is_active', true)->count();
        $totalCashiers = User::where('role', 'cashier')->count();

        $recentTransactions = Transaction::with(['cashier', 'items'])
            ->latest()
            ->take(5)
            ->get();

        $chartData = $this->buildChartData();

        return view('dashboard.index', compact(
            'totalSalesToday',
            'totalTransactionsToday',
            'totalSalesThisMonth',
            'totalTransactionsThisMonth',
            'totalProducts',
            'lowStockProducts',
            'totalCashiers',
            'recentTransactions',
            'chartData',
        ));
    }

    /**
     * Build last-7-days chart data.
     *
     * @return array<string, array<int, mixed>>
     */
    private function buildChartData(): array
    {
        $labels = [];
        $salesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $salesData[] = Transaction::whereDate('created_at', $date)->sum('total');
        }

        return ['labels' => $labels, 'sales' => $salesData];
    }
}
