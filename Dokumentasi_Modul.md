# Dokumentasi Teknis Modul Aplikasi

## Modul 1: Autentikasi & Otorisasi
- **Input**: Username/Email, Password.
- **Proses**: Verifikasi hash password (BCrypt), pengecekan role melalui middleware.
- **Output**: Session login, pengalihan ke dashboard sesuai privilege.
- **Method/Fungsi**: `AuthenticatedSessionController@store`, `RoleMiddleware@handle`.

## Modul 2: Peminjaman Alat
- **Input**: `alat_id`, `tgl_pinjam`, `tgl_kembali_rencana`, `jumlah`.
- **Proses**: Pengecekan stok via Eloquent, insert data ke tabel `peminjamans` status 'pending'.
- **Output**: Notifikasi sukses, list permohonan di halaman 'Pinjaman Saya'.
- **Method/Fungsi**: `LoanController@store`.

## Modul 3: Persetujuan Peminjaman
- **Input**: `peminjaman_id`.
- **Proses**: Update status menjadi 'borrowed'. Database Trigger `tr_peminjaman_after_update` otomatis memotong stok.
- **Output**: Update data, stok alat berkurang secara otomatis.
- **Method/Fungsi**: `LoanController@approve`.

## Modul 4: Pengembalian & Denda
- **Input**: `peminjaman_id`, `tgl_kembali_real`.
- **Proses**: Eksekusi Stored Procedure `sp_kembalikan_alat`. Di dalamnya memanggil Function `hitung_denda`.
- **Output**: Rekaman di tabel `pengembalians`, status peminjaman jadi 'returned', stok alat bertambah.
- **Method/Fungsi**: `sp_kembalikan_alat` (Stored Procedure), `hitung_denda` (SQL Function).
