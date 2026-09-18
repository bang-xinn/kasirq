<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['cashier', 'items']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $totalFiltered = $query->sum('total');

        return view('transactions.index', compact('transactions', 'totalFiltered'));
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['cashier', 'items.product']);

        return view('transactions.show', compact('transaction'));
    }
}
