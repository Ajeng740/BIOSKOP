<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Kelola Pemesanan';
$statusFilter = $_GET['status'] ?? '';

$sql = "
    SELECT p.*, u.nama, u.email, f.judul, jt.tanggal, jt.jam, s.nama_studio,
           GROUP_CONCAT(k.nomor_kursi ORDER BY k.nomor_kursi SEPARATOR ', ') AS daftar_kursi
    FROM pemesanan p
    JOIN users u ON u.id = p.user_id
    JOIN jadwal_tayang jt ON jt.id = p.jadwal_id
    JOIN films f ON f.id = jt.film_id
    JOIN studios s ON s.id = jt.studio_id
    LEFT JOIN detail_pemesanan dp ON dp.pemesanan_id = p.id
    LEFT JOIN kursi k ON k.id = dp.kursi_id
";

if (in_array($statusFilter, ['Pending', 'Dibayar', 'Dibatalkan'], true)) {
    $sql .= " WHERE p.status = ?";
}
$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

if (in_array($statusFilter, ['Pending', 'Dibayar', 'Dibatalkan'], true)) {
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $statusFilter);
    mysqli_stmt_execute($stmt);
    $orders = mysqli_stmt_get_result($stmt);
} else {
    $orders = mysqli_query($koneksi, $sql);
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>

<main class="container-fluid px-lg-4 py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div><h1 class="fw-bold mb-1">Kelola Pemesanan</h1><p class="text-muted mb-0">Admin dapat melihat, mengubah status, dan menghapus pemesanan.</p></div>
        <form method="get" class="d-flex gap-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Dibayar" <?= $statusFilter === 'Dibayar' ? 'selected' : '' ?>>Dibayar</option>
                <option value="Dibatalkan" <?= $statusFilter === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
            <button class="btn btn-dark" type="submit">Filter</button>
        </form>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark"><tr><th>Kode</th><th>User</th><th>Film & Jadwal</th><th>Kursi</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if (mysqli_num_rows($orders) > 0): ?>
                        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                            <tr>
                                <td class="fw-bold"><?= e($order['kode_booking']) ?></td>
                                <td><?= e($order['nama']) ?><br><span class="text-muted small"><?= e($order['email']) ?></span></td>
                                <td><?= e($order['judul']) ?><br><span class="text-muted small"><?= e($order['nama_studio']) ?> &bull; <?= tanggal_indonesia($order['tanggal']) ?> <?= e(substr($order['jam'], 0, 5)) ?> WIB</span></td>
                                <td><?= e($order['daftar_kursi'] ?: '-') ?></td>
                                <td><?= rupiah($order['total_harga']) ?></td>
                                <td><span class="badge text-bg-<?= badge_status($order['status']) ?>"><?= e($order['status']) ?></span></td>
                                <td>
                                    <form action="<?= url('admin/pemesanan/update_status.php') ?>" method="post" class="d-flex gap-2 mb-2">
                                        <input type="hidden" name="id" value="<?= e($order['id']) ?>">
                                        <select name="status" class="form-select form-select-sm" aria-label="Ubah status">
                                            <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Dibayar" <?= $order['status'] === 'Dibayar' ? 'selected' : '' ?>>Dibayar</option>
                                            <option value="Dibatalkan" <?= $order['status'] === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                    <a href="<?= url('admin/pemesanan/hapus.php?id=' . $order['id']) ?>" class="btn btn-sm btn-outline-danger" data-confirm="Hapus pemesanan ini?">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pemesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
