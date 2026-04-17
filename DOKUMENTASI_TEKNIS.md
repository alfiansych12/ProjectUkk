# DOKUMENTASI TEKNIS: APLIKASI PEMINJAMAN ALAT (LAV-B-01)
*Sesuai Standar SPK-3/4 P1-25/26 Kemendikdasmen*

---

## 1. Analisis Kebutuhan & Fitur
Aplikasi ini dirancang untuk mengelola peminjaman barang/alat dengan tiga level akses:
- **Admin**: Manajemen Master Data (User, Alat, Kategori, Log Aktivitas).
- **Petugas**: Persetujuan peminjaman, proses pengembalian, dan laporan.
- **Peminjam**: Melihat katalog alat, mengajukan pinjaman, dan melihat riwayat.

---

## 2. Struktur Data (ERD)
Aplikasi menggunakan database relasional dengan skema berikut:

- **Users**: Menyimpan data pengguna dan peran (role).
- **Alats**: Menyimpan data alat, stok, dan harga sewa.
- **Kategoris**: Pengelompokan alat.
- **Peminjamans**: Transaksi peminjaman alat.
- **Pengembalians**: Detail pengembalian alat dan denda.
- **Aktivitas_logs**: Jejak audit sistem.

---

## 3. Diagram Alur (Flowchart)

### a. Proses Login
1. Start -> Input Email & Password.
2. Validasi Akun? (Jika Tidak, kembali ke input).
3. Cek Role (Admin/Petugas/Peminjam).
4. Masuk ke Dashboard masing-masing.

### b. Proses Peminjaman Alat
1. Peminjam memilih alat & masukkan tgl rencana kembali.
2. Sistem mengecek stok (via `AlatController`).
3. Jika stok ok, status diset sebagai 'Pending'.
4. Petugas menyetujui request (via `LoanController@approve`).
5. Status berubah jadi 'Borrowed', Stok berkurang (via Trigger `tr_peminjaman_after_update`).

### c. Proses Pengembalian & Denda
1. Petugas menginput ID peminjaman yang kembali.
2. Sistem menghitung selisih hari keterlambatan (via Function `hitung_denda`).
3. Jika terlambat, denda dihitung berdasarkan hari terlambat * tarif (Rp10.000).
4. Data disimpan ke tabel Pengembalian (via Stored Procedure `sp_kembalikan_alat`).
5. Status menjadi 'Returned', Stok bertambah otomatis (via Trigger).

---

## 4. Dokumentasi Modul (Input, Proses, Output)

| Modul | Input | Proses (Fungsi/Method) | Output |
| :--- | :--- | :--- | :--- |
| **Autentikasi** | Email, Password | `LoginRequest@authenticate()` | Session User & Akses Dashboard |
| **Manajemen Alat** | Nama, Kategori, Stok, Foto | `AlatController@store` | Data Alat tersimpan di Database |
| **Peminjaman** | Alat_ID, Tgl Pinjam, Tgl Kembali | `LoanController@store` | Record Peminjaman (Pending) |
| **Persetujuan** | Loan_ID | `LoanController@approve` | Status: Borrowed & Stok Berkurang |
| **Pengembalian** | Loan_ID, Tgl Kembali Real | `sp_kembalikan_alat` (Stored Proc) | Data Pengembalian & Denda |

---

## 5. Fitur Database Tingkat Lanjut (Poin 6)

### a. Trigger (`tr_peminjaman_after_update`)
Mengurangi/menambah stok alat secara otomatis setiap kali status transaksi berubah.

### b. Stored Function (`hitung_denda`)
Fungsi MySQL murni untuk menjamin perhitungan denda konsisten di tingkat database.

### c. Stored Procedure (`sp_kembalikan_alat`)
Mengelola integritas data dengan `START TRANSACTION`, `COMMIT`, dan `ROLLBACK` untuk memproses log pengembalian.

---

## 6. Skenario Uji Coba

| No | Kasus Uji | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- |
| 1 | Login role Peminjam | Masuk ke dashboard peminjam, menu admin tersembunyi. | ✅ Passed |
| 2 | Tambah Alat baru | Alat muncul di katalog dengan stok yang benar. | ✅ Passed |
| 3 | Pinjam Alat (> stok) | Muncul notifikasi "Stok tidak mencukupi". | ✅ Passed |
| 4 | Kembali Terlambat | Denda terisi otomatis pada laporan pengembalian. | ✅ Passed |
| 5 | Cek Privilege Admin | Admin bisa akses semua menu master data. | ✅ Passed |
