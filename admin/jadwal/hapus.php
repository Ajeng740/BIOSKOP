<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "DELETE FROM jadwal_tayang WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
set_flash('success', 'Jadwal berhasil dihapus.');
redirect('admin/jadwal/index.php');
