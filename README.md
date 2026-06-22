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
- Struktur siap diunggah ke GitHub.
