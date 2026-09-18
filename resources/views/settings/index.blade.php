@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h2 class="card-title">Pengaturan Struk & Pembayaran</h2>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Header Struk (Nama Toko)</label>
            <input type="text" name="receipt_header" class="form-control" value="{{ old('receipt_header', $settings['receipt_header'] ?? 'KasirQ') }}" required>
            @error('receipt_header')
                <span class="text-danger text-sm" style="color: var(--danger)">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Teks Footer Struk (Ucapan Terima Kasih)</label>
            <input type="text" name="receipt_footer" class="form-control" value="{{ old('receipt_footer', $settings['receipt_footer'] ?? 'Terima kasih! 🙏') }}" required>
            @error('receipt_footer')
                <span class="text-danger text-sm" style="color: var(--danger)">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Gambar Kode QRIS</label>
            <div class="mb-2">
                @if(isset($settings['qris_image']))
                    <img src="{{ asset($settings['qris_image']) }}" alt="QRIS saat ini" style="max-width: 150px; border-radius: 8px; border: 1px solid var(--border);">
                @else
                    <span class="text-muted text-sm">Belum ada gambar.</span>
                @endif
            </div>
            <input type="file" name="qris_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
            <small class="text-muted text-xs">Biarkan kosong jika tidak ingin mengubah gambar. Max: 2MB.</small>
            @error('qris_image')
                <br><span class="text-danger text-sm" style="color: var(--danger)">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan Pengaturan
        </button>
    </form>
</div>
@endsection
