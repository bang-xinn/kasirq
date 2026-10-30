@extends('layouts.app')
@section('title', 'Buka Shift')
@section('page-title', '🕒 Shift Kasir')

@section('content')
<div class="min-h-[calc(100vh-11rem)] flex items-center justify-center">
    <div class="w-full max-w-md">

        @if($openShift ?? null)
            <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-2xl shadow-sm p-8 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amber-50 dark:bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl">
                    🕒
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Shift Masih Berjalan</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Anda sudah membuka shift pada
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $openShift->start_time->isoFormat('D MMMM Y — HH:mm') }}</span>.
                    Tutup shift terlebih dahulu sebelum membuka shift baru.
                </p>
                <a href="{{ route('pos.index') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-500/30 transition-all">
                    Kembali ke POS
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-2xl shadow-sm p-8">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-2xl">
                        🕒
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Buka Shift</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Masukkan saldo awal sebelum memulai transaksi.</p>
                </div>

                <form action="{{ route('shifts.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="starting_cash" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            Saldo Awal (Modal di Laci)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm">Rp</span>
                            <input type="number" name="starting_cash" id="starting_cash" step="0.01" min="0"
                                   value="{{ old('starting_cash') }}" required autofocus placeholder="0"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors">
                        </div>
                        @error('starting_cash')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-500/30 transition-all">
                        Buka Shift
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
