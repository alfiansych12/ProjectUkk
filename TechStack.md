# Stack Teknologi & Arsitektur Sistem

## 1. Framework Utama
Web ini dibangun menggunakan **Laravel 12.x**, framework PHP modern yang paling populer di dunia. Alasan penggunaan Laravel:
- **Keamanan**: Memiliki proteksi bawaan terhadap SQL Injection, CSRF, dan XSS.
- **Efisien**: Dilengkapi dengan **Eloquent ORM** untuk pengelolaan database yang sangat mudah.
- **Breeze**: Digunakan untuk sistem autentikasi (Login/Register) yang cepat dan aman.

## 2. Frontend & Styling
- **Tailwind CSS 4**: Digunakan untuk membuat desain yang modern, premium, dan responsif tanpa menulis CSS manual yang panjang.
- **Alpine.js**: Framework JavaScript ringan yang menangani interaksi dinamis seperti **Modal** dan **Dropdown** tanpa memperlambat loading halaman.
- **Vite**: Alat build super cepat yang mengompilasi CSS dan JS agar aplikasi berjalan ringan di browser.

## 3. Database
- **MySQL**: Sebagai media penyimpanan data.
- **Advanced Logic**: Menggunakan **Stored Procedures**, **Triggers**, dan **Transaction Processing** langsung di tingkat database untuk efisiensi dan integritas data yang maksimal.

---

## FAQ: Kenapa ada Logo Laravel dan Icon Default?

### Mengapa Logo Laravel Muncul?
Logo Laravel yang Anda lihat (terutama di halaman login atau dashboard) merupakan **komponen default dari Laravel Breeze**. Breeze menyediakan kerangka dasar aplikasi profesional sehingga pengembang tidak perlu membuat sistem login dari nol.

### Bagaimana Cara Mengubahnya?
Jika ingin mengganti logo atau icon tersebut dengan logo instansi/projek Anda, Anda dapat mengedit file berikut:
1.  **Halaman Utama (Welcome)**: Edit di `resources/views/welcome.blade.php`.
2.  **Logo Aplikasi (Navbar & Auth)**: Edit di `resources/views/components/application-logo.blade.php`.
3.  **Favicon**: Ganti file `public/favicon.ico`.

### Kenapa Iconnya Sangat Bagus?
Web ini menggunakan **SVG Icons** dari Heroicons (dibuat oleh tim Tailwind) yang terintegrasi secara native dalam kode Blade, memberikan tampilan tajam di semua resolusi layar (Retina/4K).
