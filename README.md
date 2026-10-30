# KasirQ 🍔🥤

KasirQ adalah aplikasi Point of Sales (POS) berbasis web yang sederhana, cepat, dan modern, dibangun menggunakan **Laravel**. Aplikasi ini didesain khusus untuk memudahkan pengelolaan transaksi, inventaris produk, shift kasir, dan profil pengguna, dengan antarmuka yang elegan dan responsif.

## Fitur Utama ✨

- **Kasir (POS)**: Sistem transaksi cepat dengan keranjang belanja, kalkulasi otomatis, opsi diskon (3%, 5%, 7%, 10%, 15%), dan dukungan pembayaran tunai maupun QRIS.
- **Manajemen Shift Kasir**: Kasir membuka shift dengan mencatat modal awal di laci, lalu menutupnya dengan audit kas fisik. Sistem menghitung kas yang diharapkan dari modal awal + total transaksi shift. Admin dapat mengakses POS tanpa wajib membuka shift.
- **Edit Profil & Avatar**: Semua pengguna (admin dan kasir) dapat memperbarui nama, email, dan password melalui halaman profil. Avatar sidebar dibuat otomatis dari nama pengguna menggunakan **UI Avatars API** — tanpa fitur unggah foto.
- **Manajemen Produk**: CRUD produk dengan auto-generate SKU berdasarkan kategori (misal: `MKN-001`, `MNM-002`), fitur unggah foto produk, dan konversi otomatis ke format **WebP** untuk performa yang optimal.
- **Manajemen Kategori**: Kelola kategori produk dengan mudah.
- **Manajemen Pengguna**: Admin dapat mengelola akun admin dan kasir, termasuk reset password.
- **Manajemen Transaksi**: Riwayat dan detail transaksi, cetak struk (otomatis menampilkan kembalian, QRIS jika dipilih), lengkap dengan filter tanggal dan metode pembayaran.
- **Pengaturan Toko**: Admin dapat mengubah nama toko (untuk struk) dan pesan di bagian bawah struk, serta mengganti gambar kode QRIS toko dari halaman pengaturan.
- **Modern & Antislop UI/UX**: Tampilan memakai **Tailwind CSS v4** dengan efek *glassmorphism* premium, tipografi modern (Instrument Sans), animasi interaktif, transisi halus, serta tata letak *mobile-first* yang responsif. Pagination dan grid POS sudah diperbaiki agar konsisten di Tailwind v4.
- **Light & Dark Mode**: Dilengkapi dengan sistem pengganti tema otomatis (*Class-based Dark Mode*) yang menyimpan preferensi secara persisten di *browser* Anda.

## Persyaratan Sistem 🛠️

- PHP 8.3 atau lebih baru (dengan ekstensi GD)
- Composer
- MySQL atau MariaDB (SQLite juga didukung untuk development/testing)
- Node.js & NPM (untuk compile aset frontend)

## Instalasi 🚀

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/bang-xinn/KasirQ.git
   cd KasirQ
   ```

2. **Install dependensi PHP dan Node:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin file konfigurasi bawaan dan sesuaikan dengan database Anda.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan atur koneksi database. Contoh untuk MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kasirq
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   > `.env.example` bawaan memakai SQLite. Jika memakai SQLite, buat file `database/database.sqlite` lalu jalankan migrasi.

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Migrate dan Seed Database:**
   Proses ini akan membuat tabel-tabel yang dibutuhkan dan memasukkan data dummy (termasuk akun admin dan kasir default).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Link Storage:**
   Agar gambar produk dapat diakses, jalankan:
   ```bash
   php artisan storage:link
   ```

7. **Siapkan folder QRIS (opsional):**
   Buat folder `public/images` jika belum ada. Upload QRIS dilakukan dari halaman Pengaturan.
   ```bash
   mkdir public/images
   ```

8. **Compile Aset Frontend (Opsional namun disarankan):**
   ```bash
   npm run build
   ```

9. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://127.0.0.1:8000`.

## Login Default 🔐

Gunakan kredensial berikut setelah menjalankan seeder:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@kasirq.com` | `password` |
| Kasir | `kasir1@kasirq.com` | `password` |
| Kasir | `kasir2@kasirq.com` | `password` |

> Catatan: README versi lama menyebut `admin@kasirq.local`; kredensial yang benar sesuai `UserSeeder` adalah `admin@kasirq.com`.

## Alur Shift Kasir 🕒

1. Kasir membuka **Buka Shift** dan mengisi saldo awal (modal di laci).
2. Kasir bertransaksi di POS; setiap transaksi otomatis terhubung ke shift aktif melalui `shift_id`.
3. Saat selesai, kasir menekan **Tutup Shift**, menghitung uang fisik, dan mengisi `actual_ending_cash`.
4. Sistem menyimpan kas yang diharapkan (`starting_cash` + total transaksi shift) dan kas aktual untuk audit.
5. Admin dapat langsung memakai POS tanpa membuka shift.

## Testing & Formatting 🧪

```bash
# Menjalankan test
php artisan test --compact

# Format kode PHP
php vendor/bin/pint --dirty --format agent
```

## Lisensi 📄

Aplikasi ini bersifat open-source dan dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
