<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Tambah Jadwal';
$films = mysqli_query($koneksi, "SELECT id, judul FROM films ORDER BY judul ASC");
$studios = mysqli_query($koneksi, "SELECT id, nama_studio FROM studios ORDER BY nama_studio ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filmId = (int) ($_POST['film_id'] ?? 0);
    $studioId = (int) ($_POST['studio_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $jam = $_POST['jam'] ?? '';
    $harga = (float) ($_POST['harga'] ?? 0);

    if ($filmId <= 0 || $studioId <= 0 || $tanggal === '' || $jam === '' || $harga <= 0) {
        set_flash('danger', 'Semua field jadwal wajib diisi.');
        redirect('admin/jadwal/tambah.php');
    }

    $stmt = mysqli_prepare($koneksi, "INSERT INTO jadwal_tayang (film_id, studio_id, tanggal, jam, harga) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iissd', $filmId, $studioId, $tanggal, $jam, $harga);
    mysqli_stmt_execute($stmt);
    set_flash('success', 'Jadwal berhasil ditambahkan.');
    redirect('admin/jadwal/index.php');
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>
<main class="container py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="card form-card"><div class="card-body p-4"><h1 class="h3 fw-bold mb-4">Tambah Jadwal</h1>
        <form method="post">
            <div class="row g-3">
                <div class="col-md-6"><label for="film_id" class="form-label">Film</label><select class="form-select" id="film_id" name="film_id" required><option value="">Pilih Film</option><?php while ($film = mysqli_fetch_assoc($films)): ?><option value="<?= e($film['id']) ?>"><?= e($film['judul']) ?></option><?php endwhile; ?></select></div>
                <div class="col-md-6"><label for="studio_id" class="form-label">Studio</label><select class="form-select" id="studio_id" name="studio_id" required><option value="">Pilih Studio</option><?php while ($studio = mysqli_fetch_assoc($studios)): ?><option value="<?= e($studio['id']) ?>"><?= e($studio['nama_studio']) ?></option><?php endwhile; ?></select></div>
                <div class="col-md-4"><label for="tanggal" class="form-label">Tanggal</label><input type="date" class="form-control" id="tanggal" name="tanggal" required></div>
                <div class="col-md-4"><label for="jam" class="form-label">Jam</label><input type="time" class="form-control" id="jam" name="jam" required></div>
                <div class="col-md-4"><label for="harga" class="form-label">Harga</label><input type="number" class="form-control" id="harga" name="harga" min="1" value="35000" required></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-warning">Simpan</button><a href="<?= url('admin/jadwal/index.php') ?>" class="btn btn-outline-secondary">Kembali</a></div>
        </form>
    </div></div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
