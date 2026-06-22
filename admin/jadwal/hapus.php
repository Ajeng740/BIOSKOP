<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/jadwal/index.php');
}

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('danger', 'Token keamanan tidak valid.');
    redirect('admin/jadwal/index.php');
}

$id = (int) ($_POST['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "DELETE FROM jadwal_tayang WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
set_flash('success', 'Jadwal berhasil dihapus.');
redirect('admin/jadwal/index.php');
