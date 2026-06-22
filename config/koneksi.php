<?php
// Konfigurasi database.
// Jika folder project tidak bernama bioskop-online, ubah BASE_URL sesuai nama folder di htdocs.
define('BASE_URL', '/BIOSKOP/');

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'bioskop_online';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($koneksi, 'utf8mb4');

define('APP_NAME', 'Bioskop Online');
