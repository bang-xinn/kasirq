# KasirQ 🍔🥤

KasirQ adalah aplikasi Point of Sales (POS) berbasis web yang sederhana, cepat, dan modern, dibangun menggunakan **Laravel**. Aplikasi ini didesain khusus untuk memudahkan pengelolaan transaksi, inventaris produk, dan kategori, dengan antarmuka yang elegan dan responsif.

## Fitur Utama ✨
- **Kasir (POS)**: Sistem transaksi cepat dengan keranjang belanja, kalkulasi otomatis, opsi diskon (3%, 5%, 7%, 10%, 15%), dan dukungan pembayaran tunai maupun QRIS.
- **Manajemen Produk**: CRUD produk dengan auto-generate SKU berdasarkan kategori (misal: `MKN-001`, `MNM-002`, `SNK-003`).
- **Manajemen Kategori**: Kelola kategori produk dengan mudah.
- **Manajemen Transaksi**: Riwayat dan detail transaksi, cetak struk (otomatis menampilkan kembalian, QRIS jika dipilih).
- **Pengaturan Toko**: Admin dapat mengubah nama toko (untuk struk) dan pesan di bagian bawah struk, serta mengganti gambar kode QRIS toko dari halaman pengaturan.
- **Modern UI**: Desain mode gelap (dark mode) dengan efek glassmorphism yang cantik, animasi hover, dan responsif.

## Persyaratan Sistem 🛠️
- PHP 8.2 atau lebih baru
- Composer
- MySQL atau MariaDB
- Node.js & NPM (untuk compile aset frontend)

## Instalasi 🚀

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/username/kasirq.git
   cd kasirq
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
   Buka file `.env` dan atur koneksi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kasirq
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Migrate dan Seed Database:**
   Proses ini akan membuat tabel-tabel yang dibutuhkan dan memasukkan data dummy (termasuk admin default).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Link Storage:**
   Agar gambar (seperti QRIS) dapat diakses, jalankan:
   ```bash
   php artisan storage:link
   ```

7. **Compile Aset Frontend (Opsional namun disarankan):**
   ```bash
   npm run build
   ```

8. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di `http://127.0.0.1:8000`.

## Login Default 🔐
Gunakan kredensial berikut untuk masuk sebagai Administrator:
- **Email**: admin@kasirq.local
- **Password**: password

## Lisensi 📄
Aplikasi ini bersifat open-source dan dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
