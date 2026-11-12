<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/hananchaliq/antrian-app/actions"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="Versi PHP"></a>
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Versi Laravel"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="Lisensi"></a>
</p>

## Tentang Sistem Antrian Online

Sistem Antrian Online adalah aplikasi web manajemen antrian yang dibangun menggunakan Laravel dan Tailwind CSS. Dirancang agar ringan, responsif, dan dapat langsung dijalankan pada layanan *shared hosting* standar tanpa memerlukan konfigurasi server WebSocket terpisah, aplikasi ini mempermudah operasional antrian harian melalui:

- Sinkronisasi status antrian secara *real-time* menggunakan mekanisme *HTTP JSON Polling* (interval 2 detik).
- Antarmuka pengunjung publik untuk mengambil nomor antrian baru dan memantau panggilan aktif tanpa perlu memuat ulang halaman.
- Panel kendali admin dengan sistem autentikasi untuk mengelola alur antrian harian (*next*, *previous*, dan pemantauan daftar tunggu).
- Skema migrasi database yang kompatibel dengan MySQL maupun PostgreSQL.
- Tampilan responsif berbasis Tailwind CSS untuk layar monitor desktop maupun perangkat seluler.

Sistem Antrian Online mudah diakses, cepat, dan menyediakan fitur esensial yang dibutuhkan untuk operasional layanan antrian yang terstruktur.

## Instalasi

Ikuti langkah-langkah berikut untuk menyiapkan aplikasi di lingkungan pengembangan lokal Anda:

1. Clone repositori dan masuk ke direktori proyek:

```bash
git clone https://github.com/hananchaliq/antrian-app.git
cd antrian-app
```

2. Pasang dependensi PHP dan aset frontend:

```bash
composer install
npm install && npm run build
```

3. Salin berkas konfigurasi environment dan buat *application key*:

```bash
cp .env.example .env
php artisan key:generate
```

4. Atur kredensial database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada berkas `.env`, jalankan migrasi database, lalu hidupkan server pengembangan lokal:

```bash
php artisan migrate
php artisan serve
```

## Arsitektur Real-Time

Untuk memastikan kompatibilitas penuh di berbagai lingkungan *shared hosting* standar, pembaruan data secara *real-time* memanfaatkan *HTTP JSON polling* ringan melalui Fetch API alih-alih koneksi *socket* persisten.

Halaman tampilan publik dan pengunjung melakukan *polling* ke *endpoint* status setiap 2 detik untuk menyinkronkan nomor antrian aktif serta daftar tunggu secara otomatis, sehingga beban server tetap minimal namun pembaruan tetap responsif.

## Kontribusi

Kontribusi, laporan *bug*, dan saran fitur sangat terbuka. Silakan *fork* repositori ini dan ajukan *pull request* dengan deskripsi perubahan yang jelas.

## Kerentanan Keamanan

Jika Anda menemukan celah keamanan pada aplikasi ini, silakan buka *issue* atau hubungi pengelola secara langsung melalui [GitHub](https://github.com/hananchaliq). Setiap laporan keamanan akan segera ditinjau.

## Lisensi

Sistem Antrian Online adalah perangkat lunak *open-source* yang dilisensikan di bawah [lisensi MIT](https://opensource.org/licenses/MIT), dikembangkan oleh [Hanan Nurdin Ramadhan Chaliq](https://github.com/hananchaliq).
