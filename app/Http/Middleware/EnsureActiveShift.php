<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveShift
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Admins can access the POS without opening a shift.
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Cashiers must have an open shift.
        if (! $user->shifts()->where('status', 'open')->exists()) {
            return redirect()->route('shifts.create')
                ->with('error', 'Anda harus membuka shift terlebih dahulu sebelum memulai transaksi.');
        }

        return $next($request);
    }
}
