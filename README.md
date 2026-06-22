BIOSKOP — Aplikasi Pemesanan Tiket Film (PHP)
=============================================

Ringkasan
---------
BIOSKOP adalah aplikasi web sederhana untuk pemesanan tiket film yang dibangun dengan PHP dan MySQL. Aplikasi ini sudah mencakup halaman publik, proses registrasi/login, pemilihan kursi, pemesanan, dan panel admin untuk mengelola film, jadwal, studio, dan pemesanan.

Fitur Utama
-----------
- Registrasi dan autentikasi pengguna (`register.php`, `login.php`, `logout.php`)
- Melihat daftar film dan detail (`film.php`, `detail_film.php`)
- Pemilihan kursi dan proses checkout (`pilih_kursi.php`, `checkout.php`)
- Riwayat pesanan dan pembatalan (`riwayat.php`, `batalkan_pesanan.php`)
- Panel admin untuk mengelola film, jadwal, studio, dan pemesanan (folder `admin/`)

Prasyarat
---------
- PHP 7.4+ atau setara
- MySQL / MariaDB
- Web server (Apache/Nginx) — disarankan gunakan XAMPP untuk pengembangan lokal di Windows

Instalasi & Jalankan (lokal)
---------------------------
1. Salin repo ke folder web server lokal Anda (mis. `c:\xampp\htdocs\BIOSKOP`).
2. Buat database MySQL dan impor struktur/seed (file SQL tidak disertakan — lihat `config/koneksi.php` untuk pengaturan koneksi).
3. Sesuaikan koneksi database di `config/koneksi.php`.
4. Pastikan folder `assets/` dapat diakses oleh server dan `helpers/` tersedia.
5. Buka `http://localhost/BIOSKOP/` di browser.

Struktur Ringkas
----------------
- `config/` — konfigurasi koneksi database
- `helpers/` — fungsi helper dan autentikasi
- `includes/` — header, navbar, footer, dan partial lainnya
- `admin/` — panel administrasi (login terpisah)


# Sistem Pemesanan Tiket Bioskop Online

Final Project Pemrograman Web menggunakan PHP Native, MySQL, Bootstrap, HTML5, CSS, dan JavaScript/DOM.

## Fitur Utama

### User/Pelanggan
- Registrasi dan login.
- Melihat daftar film.
- Melihat detail film dan jadwal tayang.
- Memilih kursi secara interaktif.
- Total harga dihitung otomatis dengan JavaScript.
- Checkout pemesanan tiket.
- Melihat riwayat pemesanan.
- Membatalkan pesanan yang masih Pending.

### Admin
- Login admin.
- Dashboard ringkasan data.
- CRUD data film.
- CRUD data studio.
- CRUD jadwal tayang.
- Melihat data pemesanan.
- Mengubah status pemesanan: Pending, Dibayar, Dibatalkan.
- Menghapus pemesanan.

## Cara Menjalankan di XAMPP

1. Extract folder `bioskop-online` ke dalam folder `htdocs`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka phpMyAdmin.
4. Import file SQL:
   `database/bioskop_online.sql`
5. Buka browser:
   `http://localhost/bioskop-online/`

Jika nama folder project diubah, sesuaikan nilai `BASE_URL` pada:

```php
config/koneksi.php
```

## Akun Demo

### Admin
- Email: `admin@bioskop.test`
- Password: `admin123`

### User
- Email: `user@bioskop.test`
- Password: `user123`

## Struktur Folder

```text
bioskop-online/
├── admin/
│   ├── dashboard.php
│   ├── film/
│   ├── jadwal/
│   ├── pemesanan/
│   └── studio/
├── assets/
│   ├── css/style.css
│   └── js/script.js
├── config/koneksi.php
├── database/bioskop_online.sql
├── helpers/
├── includes/
├── index.php
├── film.php
├── detail_film.php
├── pilih_kursi.php
├── checkout.php
├── riwayat.php
├── login.php
├── register.php
└── logout.php
```

## Bagian JavaScript/DOM

File JavaScript berada di:

```text
assets/js/script.js
```

Implementasi JS/DOM:
- Filter film berdasarkan judul.
- Filter film berdasarkan genre.
- Konfirmasi hapus/batal data.
- Pilih kursi interaktif.
- Hitung jumlah tiket dan total harga otomatis.

## Catatan Laporan

Proyek ini sudah memenuhi kebutuhan utama tugas:
- HTML5.
- Responsive design menggunakan Bootstrap.
- JavaScript/DOM.
- PHP Native tanpa framework.
- MySQL.
- CRUD.
- Role admin dan user.
