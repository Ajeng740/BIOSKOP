<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Edit Film';
$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM films WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$film = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$film) {
    set_flash('danger', 'Film tidak ditemukan.');
    redirect('admin/film/index.php');
}

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
        redirect('admin/film/edit.php?id=' . $id);
    }

    $update = mysqli_prepare($koneksi, "UPDATE films SET judul=?, genre=?, durasi=?, sutradara=?, deskripsi=?, poster=?, status=? WHERE id=?");
    mysqli_stmt_bind_param($update, 'ssissssi', $judul, $genre, $durasi, $sutradara, $deskripsi, $poster, $status, $id);
    mysqli_stmt_execute($update);

    set_flash('success', 'Film berhasil diperbarui.');
    redirect('admin/film/index.php');
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="card form-card">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Edit Film</h1>
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-8"><label class="form-label" for="judul">Judul Film</label><input type="text" class="form-control" id="judul" name="judul" value="<?= e($film['judul']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label" for="genre">Genre</label><input type="text" class="form-control" id="genre" name="genre" value="<?= e($film['genre']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label" for="durasi">Durasi (menit)</label><input type="number" class="form-control" id="durasi" name="durasi" min="1" value="<?= e($film['durasi']) ?>" required></div>
                    <div class="col-md-4"><label class="form-label" for="sutradara">Sutradara</label><input type="text" class="form-control" id="sutradara" name="sutradara" value="<?= e($film['sutradara']) ?>"></div>
                    <div class="col-md-4"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status"><option value="Tayang" <?= $film['status'] === 'Tayang' ? 'selected' : '' ?>>Tayang</option><option value="Segera Tayang" <?= $film['status'] === 'Segera Tayang' ? 'selected' : '' ?>>Segera Tayang</option></select></div>
                    <div class="col-12"><label class="form-label" for="poster">URL Poster</label><input type="url" class="form-control" id="poster" name="poster" value="<?= e($film['poster']) ?>"></div>
                    <div class="col-12"><label class="form-label" for="deskripsi">Deskripsi</label><textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"><?= e($film['deskripsi']) ?></textarea></div>
                </div>
                <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-warning">Simpan Perubahan</button><a href="<?= url('admin/film/index.php') ?>" class="btn btn-outline-secondary">Kembali</a></div>
            </form>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
