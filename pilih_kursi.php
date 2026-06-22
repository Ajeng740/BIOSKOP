<?php
require_once __DIR__ . '/helpers/auth.php';
require_login();

$pageTitle = 'Pilih Kursi';
$jadwalId = (int) ($_GET['jadwal_id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "
    SELECT jt.*, f.judul, f.genre, f.durasi, s.nama_studio, s.id AS studio_id
    FROM jadwal_tayang jt
    JOIN films f ON f.id = jt.film_id
    JOIN studios s ON s.id = jt.studio_id
    WHERE jt.id = ?
");
mysqli_stmt_bind_param($stmt, 'i', $jadwalId);
mysqli_stmt_execute($stmt);
$jadwal = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$jadwal) {
    set_flash('danger', 'Jadwal tidak ditemukan.');
    redirect('film.php');
}

$kursiStmt = mysqli_prepare($koneksi, "SELECT * FROM kursi WHERE studio_id = ? ORDER BY nomor_kursi ASC");
mysqli_stmt_bind_param($kursiStmt, 'i', $jadwal['studio_id']);
mysqli_stmt_execute($kursiStmt);
$kursiResult = mysqli_stmt_get_result($kursiStmt);

$bookedStmt = mysqli_prepare($koneksi, "
    SELECT dp.kursi_id
    FROM detail_pemesanan dp
    JOIN pemesanan p ON p.id = dp.pemesanan_id
    WHERE p.jadwal_id = ? AND p.status != 'Dibatalkan'
");
mysqli_stmt_bind_param($bookedStmt, 'i', $jadwalId);
mysqli_stmt_execute($bookedStmt);
$bookedResult = mysqli_stmt_get_result($bookedStmt);
$bookedSeats = [];
while ($row = mysqli_fetch_assoc($bookedResult)) {
    $bookedSeats[] = (int) $row['kursi_id'];
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/includes/flash.php'; ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card form-card">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold mb-1">Pilih Kursi</h1>
                    <p class="text-muted mb-4">Pilih kursi yang tersedia untuk jadwal film.</p>

                    <div class="seat-map">
                        <div class="text-center text-muted small mb-2">LAYAR</div>
                        <div class="screen-line"></div>

                        <form action="<?= url('checkout.php') ?>" method="post">
                            <input type="hidden" name="jadwal_id" value="<?= e($jadwalId) ?>">
                            <div class="seat-grid mb-4">
                                <?php while ($kursi = mysqli_fetch_assoc($kursiResult)): ?>
                                    <?php $isBooked = in_array((int) $kursi['id'], $bookedSeats, true); ?>
                                    <div>
                                        <input
                                            class="seat-check"
                                            type="checkbox"
                                            name="kursi[]"
                                            value="<?= e($kursi['id']) ?>"
                                            id="kursi<?= e($kursi['id']) ?>"
                                            data-seat-name="<?= e($kursi['nomor_kursi']) ?>"
                                            <?= $isBooked ? 'disabled' : '' ?>
                                        >
                                        <label class="seat-label <?= $isBooked ? 'booked' : '' ?>" for="kursi<?= e($kursi['id']) ?>">
                                            <?= e($kursi['nomor_kursi']) ?>
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>

                            <div class="d-flex flex-wrap gap-3 align-items-center mb-4 small">
                                <span><span class="seat-legend bg-secondary-subtle border"></span> Tersedia</span>
                                <span><span class="seat-legend bg-warning"></span> Dipilih</span>
                                <span><span class="seat-legend bg-danger"></span> Terisi</span>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100" data-submit-booking disabled>Checkout Pemesanan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card admin-card sticky-lg-top" style="top: 90px;">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Ringkasan Tiket</h2>
                    <div data-ticket-price="<?= e($jadwal['harga']) ?>"></div>
                    <p class="mb-1 fw-bold"><?= e($jadwal['judul']) ?></p>
                    <p class="text-muted small mb-3"><?= e($jadwal['genre']) ?> &bull; <?= e($jadwal['durasi']) ?> menit</p>
                    <ul class="list-unstyled small mb-4">
                        <li class="mb-2"><strong>Studio:</strong> <?= e($jadwal['nama_studio']) ?></li>
                        <li class="mb-2"><strong>Tanggal:</strong> <?= tanggal_indonesia($jadwal['tanggal']) ?></li>
                        <li class="mb-2"><strong>Jam:</strong> <?= e(substr($jadwal['jam'], 0, 5)) ?> WIB</li>
                        <li class="mb-2"><strong>Harga:</strong> <?= rupiah($jadwal['harga']) ?></li>
                    </ul>
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah tiket</span>
                            <strong><span data-ticket-count>0</span></strong>
                        </div>
                        <div class="mb-2">
                            <span>Kursi dipilih:</span>
                            <strong class="d-block" data-selected-seats>-</strong>
                        </div>
                        <div class="d-flex justify-content-between fs-5">
                            <span>Total</span>
                            <strong data-ticket-total>Rp0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
