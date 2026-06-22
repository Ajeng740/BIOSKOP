<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Tambah Film';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $durasi = (int) ($_POST['durasi'] ?? 0);
    $sutradara = trim($_POST['sutradara'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $poster = trim($_POST['poster'] ?? '');
    $status = $_POST['status'] ?? 'Tayang';

    if ($judul === '' || $genre === '' || $durasi <= 0) {
        set_flash('danger', 'Judul, genre, dan durasi wajib diisi.');
        redirect('admin/film/tambah.php');
    }

    $stmt = mysqli_prepare($koneksi, "INSERT INTO films (judul, genre, durasi, sutradara, deskripsi, poster, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssissss', $judul, $genre, $durasi, $sutradara, $deskripsi, $poster, $status);
    mysqli_stmt_execute($stmt);

    set_flash('success', 'Film berhasil ditambahkan.');
    redirect('admin/film/index.php');
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="card form-card">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Tambah Film</h1>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-8"><label class="form-label" for="judul">Judul Film</label><input type="text" class="form-control" id="judul" name="judul" required></div>
                    <div class="col-md-4"><label class="form-label" for="genre">Genre</label><input type="text" class="form-control" id="genre" name="genre" required></div>
                    <div class="col-md-4"><label class="form-label" for="durasi">Durasi (menit)</label><input type="number" class="form-control" id="durasi" name="durasi" min="1" required></div>
                    <div class="col-md-4"><label class="form-label" for="sutradara">Sutradara</label><input type="text" class="form-control" id="sutradara" name="sutradara"></div>
                    <div class="col-md-4"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status"><option value="Tayang">Tayang</option><option value="Segera Tayang">Segera Tayang</option></select></div>
                    <div class="col-12"><label class="form-label" for="poster">URL Poster</label><input type="url" class="form-control" id="poster" name="poster" placeholder="Opsional"></div>
                    <div class="col-12"><label class="form-label" for="deskripsi">Deskripsi</label><textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"></textarea></div>
                </div>
                <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-warning">Simpan</button><a href="<?= url('admin/film/index.php') ?>" class="btn btn-outline-secondary">Kembali</a></div>
            </form>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
