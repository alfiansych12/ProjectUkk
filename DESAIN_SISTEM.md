# DOKUMENTASI PERANCANGAN SISTEM (POIN 1, 2, 3)
*Aplikasi Peminjaman Alat - Standar SPK Kemendikdasmen*

---

## 1. STRUKTUR DATA DAN KONTROL PROGRAM (POIN 1)

### a. Struktur Data (Schema Database)
Berikut adalah rincian tipe data dan struktur tabel utama:

| Tabel | Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- | :--- |
| **users** | id, name, email, password, role | BIGINT, VARCHAR, VARCHAR, VARCHAR, ENUM | Master data pengguna (Admin, Petugas, Peminjam) |
| **alats** | id, nama_alat, stok, harga_sewa, images | BIGINT, VARCHAR, INT, DECIMAL(10,2), JSON | Master data alat dan tarif sewa |
| **peminjamans** | id, user_id, alat_id, jumlah, status | BIGINT, BIGINT(FK), BIGINT(FK), INT, ENUM | Transaksi utama peminjaman |
| **peminjamans** | tgl_pinjam, tgl_kembali_rencana | DATE, DATE | Deadline peminjaman |
| **peminjamans** | denda, tgl_kembali_real | DECIMAL(10,2), DATE | Data pengembalian & denda otomatis |

### b. Kontrol Program
Program dikendalikan menggunakan arsitektur **MVC (Model-View-Controller)**:
1.  **Input**: Diproses melalui View (Blade) dan divalidasi oleh Request di Controller.
2.  **Logic**: Logika bisnis berada di Controller dan Database Logic (Trigger & SP).
3.  **Output**: Data dikirim kembali ke View dalam bentuk tabel atau notifikasi status.

---

## 2. DESKRIPSI DAN DIAGRAM ALUR (POIN 2)

### a. Proses Login
**Deskripsi**: User memasukkan kredensial. Sistem memverifikasi email dan password menggunakan Laravel Auth. Jika benar, user diarahkan ke dashboard sesuai Role (Privilege).

**Pseudocode Login**:
```text
BEGIN LOGIN
    INPUT email, password
    QUERY users WHERE email = INPUT_email
    IF password MATCHES hashing(INPUT_password) THEN
        CREATE session
        REDIRECT to dashboard_by_role
    ELSE
        RETURN error "Login Gagal"
    ENDIF
END
```

### b. Proses Peminjaman Alat
**Deskripsi**: Peminjam memilih alat, jumlah, dan tanggal kembali. Sistem mengecek stok. Jika stok cukup, data disimpan dengan status 'Pending'. Stok berkurang otomatis setelah disetujui Petugas.

**Pseudocode Peminjaman**:
```text
BEGIN PEMINJAMAN
    INPUT alat_id, tgl_pinjam, tgl_kembali_rencana, jumlah
    CHECK stock IN alats WHERE id = alat_id
    IF stock >= jumlah THEN
        INSERT INTO peminjamans (status='pending')
        OUTPUT "Menunggu Persetujuan"
    ELSE
        OUTPUT "Stok Tidak Cukup"
    ENDIF
END
```

### c. Proses Pengembalian Alat & Perhitungan Denda
**Deskripsi**: Petugas menginput tanggal kembali riil. Fungsi `hitung_denda` di database menghitung selisih hari jika tanggal riil melewati tanggal rencana.

**Pseudocode Pengembalian**:
```text
BEGIN PENGEMBALIAN
    INPUT peminjaman_id, tgl_kembali_real
    GET tgl_kembali_rencana, harga_sewa FROM database
    
    selisih_hari = tgl_kembali_real - tgl_kembali_rencana
    IF selisih_hari > 0 THEN
        denda = selisih_hari * 10000
    ELSE
        denda = 0
    ENDIF
    
    UPDATE peminjamans SET status='returned', denda=denda
    UPDATE alats SET stok = stok + jumlah
END
```

---

## 3. DOKUMENTASI MODUL - IPO (POIN 3)

### Modul 1: Manajemen User (Admin)
- **Input**: Data User Baru (Nama, Email, Password, Role).
- **Proses**: Method `UserController@store` mengenkripsi password dan menyimpan data.
- **Output**: Notifikasi sukses dan tampilan user di tabel.

### Modul 2: Peminjaman Alat (Peminjam & Petugas)
- **Input**: ID Alat, Durasi, Jumlah.
- **Proses**: Method `LoanController@store` (Peminjam) -> `LoanController@approve` (Petugas).
- **Output**: Status pinjam berubah dan stok alat tersinkronisasi.

### Modul 3: Pengembalian & Laporan (Petugas)
- **Input**: ID Peminjaman, Tanggal Selesai.
- **Proses**: Prosedur database `sp_kembalikan_alat` mengeksekusi perhitungan denda dan update transaksi dalam satu `COMMIT`.
- **Output**: Angka denda muncul di laporan dan alat tersedia kembali di katalog.

---

### Daftar Fungsi, Prosedur, dan Method Utama:

1.  **Method (Laravel Controller)**:
    - `AlatController@index`, `@store`, `@update`: Mengelola master alat.
    - `LoanController@store`: Mengajukan pinjaman.
    - `LoanController@returnTool`: Memanggil Stored Procedure pengembalian.
2.  **Fungsi & Prosedur (MySQL)**:
    - `hitung_denda(peminjaman_id, tgl_real)`: Fungsi penghitung denda.
    - `sp_kembalikan_alat(...)`: Prosedur transaksional pengembalian.
    - `tr_peminjaman_after_update`: Trigger sinkronisasi stok otomatis.
