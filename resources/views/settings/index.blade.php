@extends('layouts.app')
@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')

@section('content')
<div class="max-w-3xl bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-800/60 rounded-3xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800/60">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Pengaturan Struk & Pembayaran</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sesuaikan informasi toko dan QRIS untuk pembayaran.</p>
        </div>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Header Struk (Nama Toko) <span class="text-red-500">*</span></label>
            <input type="text" name="receipt_header" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('receipt_header', $settings['receipt_header'] ?? 'KasirQ') }}" required>
            @error('receipt_header')
                <p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Teks Footer Struk (Ucapan Terima Kasih) <span class="text-red-500">*</span></label>
            <input type="text" name="receipt_footer" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-slate-900 dark:text-slate-100 placeholder-slate-400 shadow-sm transition-all" value="{{ old('receipt_footer', $settings['receipt_footer'] ?? 'Terima kasih! 🙏') }}" required>
            @error('receipt_footer')
                <p class="text-sm font-medium text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800/60">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Gambar Kode QRIS</label>
            
            <div class="flex flex-col sm:flex-row gap-6">
                <div class="shrink-0">
                    @if(isset($settings['qris_image']))
                        <div class="relative group rounded-2xl overflow-hidden border-4 border-white dark:border-slate-800 shadow-lg w-40 h-40 bg-slate-50 dark:bg-slate-900 flex items-center justify-center">
                            <img src="{{ asset($settings['qris_image']) }}" alt="QRIS saat ini" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold px-2 py-1 bg-black/50 rounded-lg">Current QRIS</span>
                            </div>
                        </div>
                    @else
                        <div class="w-40 h-40 rounded-2xl border-2 border-slate-200 dark:border-slate-700 border-dashed flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50 text-slate-400">
                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-xs font-medium">Belum ada gambar</span>
                        </div>
                    @endif
                </div>

                <div class="flex-1 space-y-2">
                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400"><span class="font-bold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">PNG, JPG or JPEG (MAX. 2MB)</p>
                        </div>
                        <input type="file" name="qris_image" class="hidden" accept="image/png, image/jpeg, image/jpg" />
                    </label>
                    <p class="text-xs font-medium text-slate-400">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    @error('qris_image')
                        <p class="text-sm font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Add visual feedback when file is selected
    const fileInput = document.querySelector('input[type="file"]');
    fileInput.addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            const label = this.parentElement.querySelector('p:first-of-type');
            label.innerHTML = `<span class="font-bold text-indigo-500">Selected: ${this.files[0].name}</span>`;
        }
    });
</script>
@endpush
