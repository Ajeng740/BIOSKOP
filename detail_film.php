<?php
require_once __DIR__ . '/helpers/auth.php';
$pageTitle = 'Detail Film';

$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($koneksi, "SELECT * FROM films WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$film = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$film) {
    set_flash('danger', 'Film tidak ditemukan.');
    redirect('film.php');
}

$jadwalStmt = mysqli_prepare($koneksi, "
    SELECT jt.*, s.nama_studio
    FROM jadwal_tayang jt
    JOIN studios s ON s.id = jt.studio_id
    WHERE jt.film_id = ?
    ORDER BY jt.tanggal ASC, jt.jam ASC
");
mysqli_stmt_bind_param($jadwalStmt, 'i', $id);
mysqli_stmt_execute($jadwalStmt);
$jadwalResult = mysqli_stmt_get_result($jadwalStmt);
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/includes/flash.php'; ?>
    <div class="row g-4">
        <div class="col-lg-4">
            <?php if (!empty($film['poster'])): ?>
                <img src="<?= e($film['poster']) ?>" class="w-100 detail-poster object-fit-cover" alt="Poster <?= e($film['judul']) ?>">
            <?php else: ?>
                <div class="detail-poster d-flex align-items-center justify-content-center display-1 fw-bold">
                    <?= e(substr($film['judul'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-8">
            <span class="badge text-bg-danger mb-2"><?= e($film['genre']) ?></span>
            <h1 class="fw-bold mb-2"><?= e($film['judul']) ?></h1>
            <p class="text-muted"><?= e($film['durasi']) ?> menit &bull; Sutradara: <?= e($film['sutradara'] ?: '-') ?> &bull; <?= e($film['status']) ?></p>
            <p><?= nl2br(e($film['deskripsi'])) ?></p>

            <hr class="my-4">
            <h2 class="h4 fw-bold mb-3">Jadwal Tayang</h2>
            <?php if (mysqli_num_rows($jadwalResult) > 0): ?>
                <div class="row g-3">
                    <?php while ($jadwal = mysqli_fetch_assoc($jadwalResult)): ?>
                        <div class="col-md-6">
                            <div class="card admin-card h-100">
                                <div class="card-body">
                                    <h3 class="h6 fw-bold mb-2"><?= e($jadwal['nama_studio']) ?></h3>
                                    <p class="mb-1"><?= tanggal_indonesia($jadwal['tanggal']) ?>, <?= e(substr($jadwal['jam'], 0, 5)) ?> WIB</p>
                                    <p class="text-muted mb-3"><?= rupiah($jadwal['harga']) ?> / tiket</p>
                                    <a href="<?= url('pilih_kursi.php?jadwal_id=' . $jadwal['id']) ?>" class="btn btn-warning w-100">Pilih Kursi</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Jadwal untuk film ini belum tersedia.</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
