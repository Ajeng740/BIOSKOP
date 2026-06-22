<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Edit Studio';
$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM studios WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$studio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$studio) {
    set_flash('danger', 'Studio tidak ditemukan.');
    redirect('admin/studio/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaStudio = trim($_POST['nama_studio'] ?? '');
    $kapasitas = (int) ($_POST['kapasitas'] ?? 0);
    if ($namaStudio === '' || $kapasitas <= 0) {
        set_flash('danger', 'Nama studio dan kapasitas wajib diisi.');
        redirect('admin/studio/edit.php?id=' . $id);
    }
    $update = mysqli_prepare($koneksi, "UPDATE studios SET nama_studio = ?, kapasitas = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, 'sii', $namaStudio, $kapasitas, $id);
    mysqli_stmt_execute($update);
    set_flash('success', 'Studio berhasil diperbarui. Kapasitas hanya mengubah data studio, bukan jumlah kursi yang sudah dibuat.');
    redirect('admin/studio/index.php');
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>
<main class="container py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="card form-card"><div class="card-body p-4"><h1 class="h3 fw-bold mb-4">Edit Studio</h1>
        <form method="post">
            <div class="mb-3"><label for="nama_studio" class="form-label">Nama Studio</label><input type="text" class="form-control" id="nama_studio" name="nama_studio" value="<?= e($studio['nama_studio']) ?>" required></div>
            <div class="mb-3"><label for="kapasitas" class="form-label">Kapasitas Kursi</label><input type="number" class="form-control" id="kapasitas" name="kapasitas" min="1" max="48" value="<?= e($studio['kapasitas']) ?>" required></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-warning">Simpan Perubahan</button><a href="<?= url('admin/studio/index.php') ?>" class="btn btn-outline-secondary">Kembali</a></div>
        </form>
    </div></div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
