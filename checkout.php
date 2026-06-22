<?php
require_once __DIR__ . '/helpers/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('film.php');
}

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('danger', 'Token keamanan tidak valid. Silakan ulangi pemesanan.');
    redirect('film.php');
}

$user = current_user();
$errors = [];

if (!validate_booking_data($_POST, $errors)) {
    $jadwalId = (int) ($_POST['jadwal_id'] ?? 0);
    set_flash('danger', implode(' ', $errors));
    redirect('pilih_kursi.php?jadwal_id=' . $jadwalId);
}

$jadwalId = (int) sanitize_input($_POST['jadwal_id']);
$kursiIds = normalize_seat_ids($_POST['kursi'] ?? []);

$jadwalStmt = mysqli_prepare($koneksi, "SELECT * FROM jadwal_tayang WHERE id = ?");
mysqli_stmt_bind_param($jadwalStmt, 'i', $jadwalId);
mysqli_stmt_execute($jadwalStmt);
$jadwal = mysqli_fetch_assoc(mysqli_stmt_get_result($jadwalStmt));

if (!$jadwal) {
    set_flash('danger', 'Jadwal tidak ditemukan.');
    redirect('film.php');
}

$bookedStmt = mysqli_prepare($koneksi, "
    SELECT COUNT(*) AS total
    FROM detail_pemesanan dp
    JOIN pemesanan p ON p.id = dp.pemesanan_id
    WHERE p.jadwal_id = ? AND p.status != 'Dibatalkan' AND dp.kursi_id = ?
");

foreach ($kursiIds as $kursiId) {
    mysqli_stmt_bind_param($bookedStmt, 'ii', $jadwalId, $kursiId);
    mysqli_stmt_execute($bookedStmt);
    $booked = mysqli_fetch_assoc(mysqli_stmt_get_result($bookedStmt));

    if ((int) $booked['total'] > 0) {
        set_flash('danger', 'Sebagian kursi sudah dipesan. Silakan pilih kursi lain.');
        redirect('pilih_kursi.php?jadwal_id=' . $jadwalId);
    }
}

$total = (float) $jadwal['harga'] * count($kursiIds);
$kodeBooking = generate_booking_code();
$status = 'Pending';

mysqli_begin_transaction($koneksi);
try {
    $orderStmt = mysqli_prepare($koneksi, "INSERT INTO pemesanan (user_id, jadwal_id, kode_booking, total_harga, status) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($orderStmt, 'iisds', $user['id'], $jadwalId, $kodeBooking, $total, $status);
    mysqli_stmt_execute($orderStmt);
    $pemesananId = mysqli_insert_id($koneksi);

    $detailStmt = mysqli_prepare($koneksi, "INSERT INTO detail_pemesanan (pemesanan_id, kursi_id) VALUES (?, ?)");
    foreach ($kursiIds as $kursiId) {
        mysqli_stmt_bind_param($detailStmt, 'ii', $pemesananId, $kursiId);
        mysqli_stmt_execute($detailStmt);
    }

    mysqli_commit($koneksi);
    set_flash('success', 'Pemesanan berhasil. Kode booking: ' . $kodeBooking);
    redirect('riwayat.php');
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    set_flash('danger', 'Pemesanan gagal diproses.');
    redirect('pilih_kursi.php?jadwal_id=' . $jadwalId);
}
