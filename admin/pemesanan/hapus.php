<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "DELETE FROM pemesanan WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
set_flash('success', 'Pemesanan berhasil dihapus.');
redirect('admin/pemesanan/index.php');
