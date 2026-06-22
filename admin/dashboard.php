<?php
require_once __DIR__ . '/../helpers/auth.php';
require_admin();
$pageTitle = 'Dashboard Admin';

$totalFilm = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM films"))['total'];
$totalStudio = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM studios"))['total'];
$totalJadwal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_tayang"))['total'];
$totalPemesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pemesanan"))['total'];
$latestOrders = mysqli_query($koneksi, "
    SELECT p.*, u.nama, f.judul
    FROM pemesanan p
    JOIN users u ON u.id = p.user_id
    JOIN jadwal_tayang jt ON jt.id = p.jadwal_id
    JOIN films f ON f.id = jt.film_id
    ORDER BY p.created_at DESC
    LIMIT 5
");
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<?php include __DIR__ . '/../includes/admin_navbar.php'; ?>

<main class="container-fluid px-lg-4 py-4">
    <?php include __DIR__ . '/../includes/flash.php'; ?>
    <div class="mb-4">
        <h1 class="fw-bold mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Ringkasan data sistem pemesanan tiket bioskop online.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3"><div class="card admin-card"><div class="card-body"><p class="text-muted mb-1">Total Film</p><h2 class="fw-bold mb-0"><?= e($totalFilm) ?></h2></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card admin-card"><div class="card-body"><p class="text-muted mb-1">Total Studio</p><h2 class="fw-bold mb-0"><?= e($totalStudio) ?></h2></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card admin-card"><div class="card-body"><p class="text-muted mb-1">Total Jadwal</p><h2 class="fw-bold mb-0"><?= e($totalJadwal) ?></h2></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="card admin-card"><div class="card-body"><p class="text-muted mb-1">Total Pemesanan</p><h2 class="fw-bold mb-0"><?= e($totalPemesanan) ?></h2></div></div></div>
    </div>

    <div class="card admin-card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="h5 fw-bold mb-0">Pemesanan Terbaru</h2>
            <a href="<?= url('admin/pemesanan/index.php') ?>" class="btn btn-sm btn-dark">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Kode</th><th>User</th><th>Film</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (mysqli_num_rows($latestOrders) > 0): ?>
                        <?php while ($order = mysqli_fetch_assoc($latestOrders)): ?>
                            <tr>
                                <td class="fw-bold"><?= e($order['kode_booking']) ?></td>
                                <td><?= e($order['nama']) ?></td>
                                <td><?= e($order['judul']) ?></td>
                                <td><?= rupiah($order['total_harga']) ?></td>
                                <td><span class="badge text-bg-<?= badge_status($order['status']) ?>"><?= e($order['status']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pemesanan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
