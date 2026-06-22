<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();

$id = (int) ($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'Pending';

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('danger', 'Token keamanan tidak valid.');
    redirect('admin/pemesanan/index.php');
}

if (!in_array($status, ['Pending', 'Dibayar', 'Dibatalkan'], true)) {
    set_flash('danger', 'Status tidak valid.');
    redirect('admin/pemesanan/index.php');
}

$stmt = mysqli_prepare($koneksi, "UPDATE pemesanan SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'si', $status, $id);
mysqli_stmt_execute($stmt);

set_flash('success', 'Status pemesanan berhasil diperbarui.');
redirect('admin/pemesanan/index.php');
