# Laporan Fitur Proyek AZCLIP

Dokumen ini merangkum seluruh fitur yang telah diimplementasikan dalam aplikasi AZCLIP, yang dibagi ke dalam beberapa modul utama: **Halaman Pengguna (End-User / Clipper)**, **Halaman Admin**, dan **Fitur Sistem Backend**.

---

## 1. Fitur Pengguna (End-User / Clipper)
Fitur-fitur ini diperuntukkan bagi para *clipper* yang mencari kampanye dan mensubmit video TikTok mereka untuk mendapatkan komisi.

- **Autentikasi & Otorisasi:**
  - Login dan Registrasi pengguna baru.
  - Lupa kata sandi (Forgot Password) dan Reset kata sandi.

- **Eksplorasi Kampanye:**
  - **Halaman Beranda (`/`)**: Menampilkan overview layanan.
  - **Daftar Kampanye (`/campaign`)**: Menjelajah daftar kampanye video yang sedang aktif atau yang akan datang.
  - **Detail Kampanye (`/campaign/{slug}`)**: Melihat persyaratan kampanye, hadiah (komisi per view), batas waktu, dan sisa kuota submisi.

- **Submisi Klip:**
  - **Form Submisi (`/campaign/{slug}/submissions`)**: Mengirimkan tautan (link) video TikTok untuk didaftarkan pada kampanye terkait.
  - **Daftar Klip Saya (`/klip`)**: Menampilkan daftar semua klip yang telah disubmit pengguna beserta statusnya (Pending, Approved, Rejected).
  - **Detail Klip (`/klip/{id}`)**: Melihat detail perkembangan klip (jumlah views yang terdeteksi) serta taksiran komisi yang akan didapat.

- **Manajemen Saldo (Wallet) & Penarikan Dana:**
  - **Halaman Saldo (`/saldo`)**: Menampilkan total saldo saat ini dan riwayat transaksi/mutasi saldo (baik uang masuk dari komisi, maupun keluar untuk penarikan).
  - **Form Penarikan Dana (`/tarik-saldo`)**: Mengajukan *withdrawal* atau pencairan dana ke rekening / e-wallet yang telah diatur.
  - **Riwayat Penarikan (`/tarik-saldo/riwayat`)**: Melacak status permohonan penarikan dana (Pending, Sukses, Ditolak).

- **Profil & Pengaturan Akun (`/akun`):**
  - **Edit Profil (`/akun/edit`)**: Mengubah informasi dasar pengguna (nama, dll).
  - **Ubah Kata Sandi (`/akun/kata-sandi`)**: Memperbarui kata sandi secara berkala demi keamanan.
  - **Pengaturan Rekening (`/akun/rekening`)**: Menentukan bank / e-wallet dan nomor rekening/nomor HP untuk tujuan pencairan dana.

- **Informasi Tambahan:**
  - Halaman Bantuan (`/bantuan`).
  - Halaman Kebijakan Layanan (`/kebijakan-layanan`).

---

## 2. Fitur Administrator
Panel kontrol eksklusif untuk staf pengelola (Admin) untuk mengatur jalannya kampanye dan pencairan dana. (Akses dibatasi menggunakan middleware `is_admin`).

- **Dashboard Utama (`/admin/dashboard`):**
  - Ringkasan analitik dan statistik utama (seperti total saldo tertahan, total pengguna aktif, submisi klip hari ini, dan transaksi terbaru).

- **Manajemen Kampanye (Clip Campaigns):**
  - **CRUD Kampanye (`/admin/clip-campaigns`)**: Membuat, membaca, memperbarui, dan menghapus data kampanye. Mengatur periode, kuota target, dan banner/gambar.
  - **Daftar Submisi per Kampanye (`/admin/clip-campaigns/{id}/submissions`)**: Melihat seluruh submisi khusus pada satu kampanye dengan desain UI tabel yang modern (dilengkapi modal interaktif dan toggle visibilitas kolom).

- **Manajemen Submisi Klip (Clip Submissions):**
  - **Daftar Keseluruhan Submisi (`/admin/clip-submissions`)**: Meninjau seluruh submisi klip dari seluruh kampanye.
  - **Pengecekan Views Manual (`/admin/clip-submissions/{id}/check-views`)**: Fitur trigger manual untuk memeriksa views terbaru dari video TikTok tanpa perlu menunggu pengecekan otomatis *cron job*.
  - **Ubah Status Submisi (`/admin/clip-submissions/{id}/status`)**: Mengubah status submisi pengguna (misal: menyetujui submisi, atau menolaknya jika melanggar syarat).

- **Manajemen Riwayat Saldo (`/admin/riwayat-saldo`):**
  - Memonitor seluruh aktivitas arus masuk dan keluar saldo pada *wallet* para pengguna dengan fitur filter mutasi yang komprehensif. (Penamaan ID transaksi disederhanakan menggunakan numeric ID demi keterbacaan, sesuai *request*).

- **Manajemen Data Induk & Pengaturan:**
  - **Kelola Administrator (`/admin/administrators`)**: Menambah akun admin baru atau mencabut akses admin lama (Toggle Status Aktif/Nonaktif).
  - **Kelola Channel Penarikan (`/admin/withdraw-channels`)**: Mengatur opsi e-Wallet/Bank apa saja yang bisa digunakan *clipper* saat pencairan (OVO, GoPay, BCA, dll).
  - **Pengaturan Sistem (`/admin/settings`)**: Konfigurasi global aplikasi.

---

## 3. Fitur Backend, Integrasi & UI/UX Experience

- **Integrasi Notifikasi Telegram:**
  - Secara otomatis mengirimkan peringatan ke grup/channel admin Telegram ketika terjadi aktivitas krusial, seperti:
    - Adanya *Withdrawal Request* (pengajuan penarikan dana) baru.
    - Adanya *Clip Submission* baru yang perlu direview.

- **Proses Background (Queues & Scheduler):**
  - Pengecekan jumlah penayangan (*views*) video TikTok dapat dilakukan melalui API *scraper* / layanan eksternal di belakang layar (*background job*).
  - Penghitungan komisi secara dinamis berlandaskan pencapaian jumlah tayangan yang sukses divalidasi.

- **Keamanan & Standar Kode (Clean Code):**
  - Pemisahan *Business Logic* dari *Controllers/Views*.
  - *Early Returns* & *Guard Clauses* diutamakan untuk mengamankan flow aplikasi (mencegah *nested if*).
  - Logika perhitungan finansial (*database storage*) mutlak menggunakan bilangan bulat (*Integer*) agar nilai uang Rupiah tetap stabil dan mencegah *bug desimal*.

- **Peningkatan Antarmuka (UI/UX):**
  - Pemanfaatan *Select2* untuk *dropdown* yang kaya fitur.
  - Implementasi *Infinite Scrolling* (atau asinkronus pagination) untuk daftar data panjang tanpa *reload*.
  - Modal dinamis untuk detail data.
  - Menyimpan preferensi kustomisasi tabel (visibilitas kolom) di memori lokal peramban (*LocalStorage*).

---

*Laporan ini menggambarkan ekosistem fitur lengkap AZCLIP (sampai titik pengembangan saat ini).*
