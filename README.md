Gerai UMKM Mart - Sistem Manajemen Kasir & Inventaris
Gerai UMKM Mart adalah sistem manajemen kasir (Point of Sale) dan inventaris berbasis web yang dibangun menggunakan Framework Laravel 8. Sistem ini dirancang khusus untuk memenuhi kebutuhan UMKM, terutama dalam mengelola penjualan produk yang berasal dari berbagai mitra binaan.

Tujuan utama dari sistem ini adalah untuk memodernisasi dan mempermudah proses pencatatan transaksi, manajemen stok, serta menyediakan analisis data penjualan yang akurat untuk mendukung pengambilan keputusan bisnis.

Fitur Utama
Sistem ini dilengkapi dengan berbagai fitur untuk mendukung operasional bisnis UMKM secara menyeluruh:

Dashboard Analitik: Tampilan utama yang menyajikan ringkasan performa bisnis secara visual, termasuk pendapatan, laba kotor & bersih, produk terlaris, dan status inventaris kritis (stok menipis & barang akan expired).

Manajemen Produk:

CRUD (Create, Read, Update, Delete) data barang.

Pengelolaan stok, harga asli (modal), dan harga jual.

Dukungan skema harga dinamis seperti harga grosir dan tebus murah.

Pencatatan tanggal masuk dan tanggal kedaluwarsa.

Manajemen Mitra: Pengelolaan data mitra (supplier) yang memasok produk ke gerai.

Sistem Kasir (Point of Sale): Antarmuka kasir yang intuitif untuk mencatat transaksi penjualan dengan cepat dan akurat.

Manajemen Transaksi: Melihat riwayat transaksi, mencetak ulang nota, dan mengelola status pembayaran.

Manajemen Inventaris:

Fitur Restock untuk mencatat penambahan stok barang.

Fitur Return untuk mengelola pengembalian barang.

Laporan Keuangan:

Pencatatan Biaya Operasional untuk perhitungan laba bersih.

Ekspor laporan penjualan dan data lainnya ke format PDF.

Manajemen Pengguna: Pengelolaan akun untuk admin dan kasir dengan level akses yang berbeda.

Teknologi yang Digunakan
Backend: Laravel 8

Frontend: Bootstrap 5, ApexCharts.js

Database: MySQL

Fitur Lainnya:

Eloquent ORM untuk interaksi database.

Sistem otentikasi bawaan Laravel.

Pencarian data, paginasi, dan logika kasir otomatis.

Pembuatan nota transaksi otomatis.

Instalasi & Menjalankan Proyek Secara Lokal
Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

Clone proyek dari repositori GitHub:

git clone https://github.com/badfellas/Gerai-umkm-mart-finalisasi

Masuk ke direktori proyek:

cd Gerai-umkm-mart-finalisasi

Install semua dependency yang dibutuhkan:

composer install

Salin file .env.example menjadi .env dan konfigurasikan koneksi database Anda:

cp .env.example .env

Generate kunci aplikasi Laravel:

php artisan key:generate

Buat symbolic link untuk storage:

php artisan storage:link

Jalankan migrasi database untuk membuat semua tabel yang dibutuhkan:

php artisan migrate

(Opsional) Jalankan seeder untuk mengisi data awal ke database:

php artisan db:seed

Jalankan server pengembangan:

php artisan serve

Aplikasi sekarang dapat diakses di http://127.0.0.1:8000.

Kontak
Untuk saran dan masukan terkait pengembangan sistem ini, silakan kirim email ke: deruanggoro26@gmail.com