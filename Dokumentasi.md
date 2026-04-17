# Dokumentasi Aplikasi Peminjaman Alat

## 1. Deskripsi Program
Aplikasi Peminjaman Alat adalah sistem informasi berbasis web yang dibangun menggunakan Laravel 12 dan Tailwind CSS. Aplikasi ini dirancang untuk mengelola proses peminjaman alat di sebuah organisasi dengan 3 level pengguna: Admin, Petugas, dan Peminjam.

### Fitur Utama:
- **Admin**: Manajemen pengguna, alat, kategori, dan melihat log aktivitas.
- **Petugas**: Validasi permohonan peminjaman dan pengelolaan pengembalian alat serta laporan.
- **Peminjam**: Melihat daftar alat yang tersedia dan mengajukan permohonan peminjaman.

## 2. Dokumentasi Fungsi/Prosedur Database
Aplikasi ini memaksimalkan logika di sisi database untuk konsistensi data:
- **Trigger `tr_peminjaman_after_update`**: Otomatis mengurangi/menambah stok alat saat status berubah menjadi 'borrowed' atau 'returned'.
- **Function `hitung_denda`**: Menghitung denda secara otomatis berdasarkan keterlambatan (Rp10.000/hari).
- **Stored Procedure `sp_kembalikan_alat`**: Memproses pengembalian dalam satu transaksi (Atomic) menggunakan logic COMMIT/ROLLBACK.

## 3. Skenario Pengujian (Test Case)
| No | Kasus Uji | Langkah | Hasil Diharapkan | Status |
|----|-----------|---------|------------------|--------|
| 1 | Login User | Masukkan username/password benar | Berhasil masuk ke dashboard | OK |
| 2 | Tambah Alat | Admin mengisi form alat baru | Alat tersimpan di database | OK |
| 3 | Pinjam Alat | Peminjam klik 'Pinjam' di daftar alat | Status peminjaman menjadi 'pending' | OK |
| 4 | Kembalikan Alat | Petugas klik 'Kembalikan' pada alat yang dipinjam | Status 'returned', stok bertambah, denda terhitung | OK |
| 5 | Cek Privilege | Peminjam mencoba akses `/admin/users` | Muncul error 403 (Unauthorized) | OK |

## 4. Laporan Evaluasi
- **Fitur Berjalan**: Auth, CRUD Alat/Kategori, Logic Trigger/SP, Dashboard Stats.
- **Bug**: Tidak ada bug kritis ditemukan saat pengujian awal.
- **Rencana Pengembangan**: Integrasi payment gateway untuk denda, fitur notifikasi WhatsApp.
