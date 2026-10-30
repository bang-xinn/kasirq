<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function create(): View
    {
        $openShift = auth()->user()->shifts()->where('status', 'open')->latest('start_time')->first();

        $totalSales = 0;

        if ($openShift) {
            $totalSales = (float) Transaction::where('shift_id', $openShift->id)->sum('total');
        }

        return view('shifts.create', compact('openShift', 'totalSales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'starting_cash' => ['required', 'numeric', 'min:0'],
        ]);

        $hasOpenShift = auth()->user()->shifts()->where('status', 'open')->exists();

        if ($hasOpenShift) {
            return redirect()->route('pos.index')
                ->with('error', 'Anda sudah memiliki shift yang sedang berjalan.');
        }

        auth()->user()->shifts()->create([
            'starting_cash' => $validated['starting_cash'],
            'start_time' => now(),
            'status' => 'open',
        ]);

        return redirect()->route('pos.index')
            ->with('success', 'Shift berhasil dibuka. Selamat bekerja!');
    }

    public function close(Request $request): RedirectResponse
    {
        $shift = auth()->user()->shifts()->where('status', 'open')->latest('start_time')->first();

        if (! $shift) {
            return redirect()->route('shifts.create')
                ->with('error', 'Tidak ada shift aktif untuk ditutup.');
        }

        $validated = $request->validate([
            'actual_ending_cash' => ['required', 'numeric', 'min:0'],
        ]);

        $totalSales = (float) Transaction::where('shift_id', $shift->id)->sum('total');

        $shift->update([
            'expected_ending_cash' => (float) $shift->starting_cash + $totalSales,
            'actual_ending_cash' => $validated['actual_ending_cash'],
            'end_time' => now(),
            'status' => 'closed',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Shift berhasil ditutup.');
    }
}
