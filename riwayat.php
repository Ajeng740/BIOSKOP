<?php
require_once __DIR__ . '/helpers/auth.php';
require_login();
$pageTitle = 'Riwayat Pemesanan';
$user = current_user();

$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, f.judul, jt.tanggal, jt.jam, s.nama_studio,
           GROUP_CONCAT(k.nomor_kursi ORDER BY k.nomor_kursi SEPARATOR ', ') AS daftar_kursi
    FROM pemesanan p
    JOIN jadwal_tayang jt ON jt.id = p.jadwal_id
    JOIN films f ON f.id = jt.film_id
    JOIN studios s ON s.id = jt.studio_id
    LEFT JOIN detail_pemesanan dp ON dp.pemesanan_id = p.id
    LEFT JOIN kursi k ON k.id = dp.kursi_id
    WHERE p.user_id = ?
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
mysqli_stmt_bind_param($stmt, 'i', $user['id']);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/includes/flash.php'; ?>
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Riwayat Pemesanan</h1>
            <p class="text-muted mb-0">Daftar tiket yang pernah dipesan oleh akun ini.</p>
        </div>
        <a href="<?= url('film.php') ?>" class="btn btn-warning align-self-md-start">Pesan Tiket Baru</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Booking</th>
                        <th>Film</th>
                        <th>Jadwal</th>
                        <th>Kursi</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($orders) > 0): ?>
                        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                            <tr>
                                <td class="fw-bold"><?= e($order['kode_booking']) ?></td>
                                <td><?= e($order['judul']) ?><br><span class="text-muted small"><?= e($order['nama_studio']) ?></span></td>
                                <td><?= tanggal_indonesia($order['tanggal']) ?><br><span class="text-muted small"><?= e(substr($order['jam'], 0, 5)) ?> WIB</span></td>
                                <td><?= e($order['daftar_kursi'] ?: '-') ?></td>
                                <td><?= rupiah($order['total_harga']) ?></td>
                                <td><span class="badge text-bg-<?= badge_status($order['status']) ?>"><?= e($order['status']) ?></span></td>
                                <td>
                                    <?php if ($order['status'] === 'Pending'): ?>
                                        <form action="<?= url('batalkan_pesanan.php') ?>" method="post" class="d-inline">
                                            <?= csrf_input() ?>
                                            <input type="hidden" name="id" value="<?= e($order['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Batalkan pemesanan ini?">Batalkan</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Tidak ada aksi</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pemesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
