# Rencana Implementasi: Fitur Kelola Penarikan Dana (Withdrawal) Admin

## 1. Tujuan
Membuat fitur di sisi Admin untuk melihat, memantau, dan mengubah status pengajuan penarikan dana (withdraw) dari para clipper. Fitur ini akan menggantikan atau menjadi halaman spesifik baru berdasarkan mockup `template.html` terbaru yang telah di-update untuk "Penarikan Dana (Withdraw)".

## 2. Struktur URL & Routing
Akan ditambahkan di dalam `routes/web.php` pada prefix `admin`:
- `GET /admin/withdrawals` (Menampilkan tabel daftar penarikan dana)
- `PATCH /admin/withdrawals/{withdrawal}/status` (Mengubah status penarikan dana menjadi `processing`, `completed`, atau `rejected` melalui request AJAX/Form).

## 3. Controller: `App\Http\Controllers\Admin\WithdrawalController`
Controller ini akan meng-handle request admin:
- `index(Request $request)`: Mengambil data `Withdrawal` beserta relasi `user`. Tersedia filter status (all, completed, processing, pending, rejected). Hasil dikirim ke view `index`.
- `updateStatus(Request $request, Withdrawal $withdrawal)`: Method untuk update status.
  - Jika status diubah menjadi `rejected`:
    - Dana (`amount`) harus dikembalikan ke `user->balance`.
    - Harus tercatat mutasi dompet baru (`wallet_transactions`) bertipe `credit` dengan keterangan "Pengembalian dana penarikan (Rejected)".
  - Jika status diubah menjadi `completed`: Update status saja (dan `processed_at`).
  - Menyimpan `notes` atau catatan penolakan dari admin.

## 4. Views: `resources/views/dashboard/withdrawals/index.blade.php`
- Layout berdasarkan adaptasi dari `template.html`.
- Tabel berisi: ID Penarikan/Nama User, Nominal, Fee, Net Amount, Status Badge, dan Tombol Aksi (View).
- Modal "Detail Penarikan" (`withdrawDetailModal`): 
  - Di-trigger dari tombol View di masing-masing baris.
  - Berisi detail lengkap rekening (Metode, No. Rek, Nama), kalkulasi fee, dan status.
  - Menambahkan form sederhana (dropdown/button group) di dalam modal untuk mengeksekusi perubahan status (Set to Processing, Set to Completed, Set to Rejected).
  - Terdapat field textarea opsional untuk `notes` jika ditolak.

## 5. UI/UX Interaktif (`withdrawals.page.js`)
- Mengadaptasi script vanilla JS untuk Select2 filter status (`all`, `selesai`, `diproses`, `ditolak`).
- Mengatur data binding JSON pada fungsi `openWithdrawDetailModal(data)`.
- Request perubahan status dikirimkan secara konvensional (form POST dengan `@method('PATCH')`) dari dalam modal, atau menggunakan fetch API/AJAX. Disarankan menggunakan konvensional Form submission (Reload) agar simpel dan reliabel.

## 6. Pengujian (Testing): `tests/Feature/Admin/WithdrawalAdminTest.php`
- Uji relasi (Admin bisa akses index, Non-admin tidak bisa).
- Uji ubah status ke `completed` (memastikan berhasil tersimpan).
- Uji ubah status ke `rejected` (memastikan saldo dikembalikan secara utuh ke `user->balance` dan `wallet_transactions` terekam).

## 7. Penyesuaian Sidebar (`sidebar.blade.php`)
- Pastikan ada link menu aktif yang mengarah ke `admin.withdrawals.index`.
