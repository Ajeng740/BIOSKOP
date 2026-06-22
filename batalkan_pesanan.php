<?php
require_once __DIR__ . '/helpers/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('riwayat.php');
}

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('danger', 'Token keamanan tidak valid.');
    redirect('riwayat.php');
}

$user = current_user();
$id = (int) ($_POST['id'] ?? 0);
$status = 'Dibatalkan';

$stmt = mysqli_prepare($koneksi, "UPDATE pemesanan SET status = ? WHERE id = ? AND user_id = ? AND status = 'Pending'");
mysqli_stmt_bind_param($stmt, 'sii', $status, $id, $user['id']);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    set_flash('success', 'Pemesanan berhasil dibatalkan.');
} else {
    set_flash('warning', 'Pemesanan tidak dapat dibatalkan.');
}

redirect('riwayat.php');
