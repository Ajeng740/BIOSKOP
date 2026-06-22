<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/studio/index.php');
}

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('danger', 'Token keamanan tidak valid.');
    redirect('admin/studio/index.php');
}

$id = (int) ($_POST['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "DELETE FROM studios WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
set_flash('success', 'Studio berhasil dihapus.');
redirect('admin/studio/index.php');
