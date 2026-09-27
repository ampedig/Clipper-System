# AZCLIP - Web Clipper System

AZCLIP adalah aplikasi Web Clipper yang dibangun menggunakan framework **Laravel**. Aplikasi ini dirancang untuk mengelola kampanye video (khususnya TikTok), memantau submisi klip dari pengguna (clipper), serta mengelola sistem dompet dan komisi berdasarkan jumlah penayangan video.

## Fitur Utama

- **Admin Dashboard**: Panel kontrol terpusat untuk memantau performa, pengguna, dan transaksi.
- **Manajemen Kampanye (Campaigns)**: Admin dapat membuat dan mengelola kampanye video dengan kuota dan periode aktif.
- **Submisi Klip (Clip Submissions)**: Clipper dapat mengirimkan tautan video TikTok untuk kampanye tertentu.
- **Sistem Komisi & Penayangan**: Aplikasi memeriksa jumlah tayangan (views) secara berkala dan menghitung komisi berdasarkan capaian tersebut.
- **Sistem Dompet (Wallet System)**: 
  - Riwayat saldo masuk dan keluar.
  - Penarikan dana (Withdrawal) oleh clipper.
- **Notifikasi Telegram**: Terintegrasi dengan bot Telegram untuk memberikan notifikasi otomatis terkait penarikan dana dan submisi baru.
- **UI/UX Modern**: Dibangun menggunakan template HTML kustom, dengan interaksi modal dinamis, Select2, infinite scrolling, dan persistensi pengaturan tabel menggunakan localStorage.

## Persyaratan Sistem

- PHP 8.2 atau lebih baru
- Composer
- Node.js & NPM
- Database MySQL / MariaDB (atau SQLite untuk testing)

## Instalasi

1. **Kloning repositori ini:**
   ```bash
   git clone <repository-url>
   cd clipper
   ```

2. **Instal dependensi PHP:**
   ```bash
   composer install
   ```

3. **Instal dependensi Node.js:**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment:**
   Salin file konfigurasi contoh dan sesuaikan pengaturan database dan notifikasi Telegram Anda.
   ```bash
   cp .env.example .env
   ```
   Atur variabel kredensial (termasuk variabel untuk notifikasi Telegram):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=clipper
   DB_USERNAME=root
   DB_PASSWORD=

   TELEGRAM_BOT_TOKEN="your-bot-token"
   TELEGRAM_CHAT_ID="your-admin-chat-id"
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database dan Seeding:**
   ```bash
   php artisan migrate --seed
   ```

7. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://localhost:8000`.

## Pengujian (Testing)

Aplikasi ini menggunakan PHPUnit untuk pengujian fungsionalitas dan logika bisnis secara ketat.
Jalankan perintah berikut untuk menjalankan seluruh rangkaian pengujian fitur:
```bash
php artisan test
```

## Teknologi yang Digunakan

- [Laravel](https://laravel.com/)
- [PHPUnit](https://phpunit.de/) (Testing)
- [Bootstrap](https://getbootstrap.com/)
- [jQuery](https://jquery.com/)
- [Select2](https://select2.org/)

## Aturan Pengembangan (Development Rules)

- Menggunakan prinsip **Clean Code** dengan pemisahan business logic dan presentation layer (Early Returns, Guard Clauses).
- Semua operasi database terkait keuangan/nominal Rupiah hanya menggunakan **angka bulat (integer)**.
- **Conventional Commits** selalu diterapkan melalui format pesan komit (`#CCM`).

## Lisensi

Proyek ini terbatas untuk keperluan internal atau sesuai perjanjian terkait proyek aplikasi ini.
