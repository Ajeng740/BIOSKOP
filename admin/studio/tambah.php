<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Tambah Studio';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaStudio = trim($_POST['nama_studio'] ?? '');
    $kapasitas = (int) ($_POST['kapasitas'] ?? 0);

    if ($namaStudio === '' || $kapasitas <= 0) {
        set_flash('danger', 'Nama studio dan kapasitas wajib diisi.');
        redirect('admin/studio/tambah.php');
    }

    mysqli_begin_transaction($koneksi);
    try {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO studios (nama_studio, kapasitas) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'si', $namaStudio, $kapasitas);
        mysqli_stmt_execute($stmt);
        $studioId = mysqli_insert_id($koneksi);

        $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
        $insertSeat = mysqli_prepare($koneksi, "INSERT INTO kursi (studio_id, nomor_kursi) VALUES (?, ?)");
        $created = 0;
        foreach ($rows as $row) {
            for ($i = 1; $i <= 8 && $created < $kapasitas; $i++) {
                $nomor = $row . $i;
                mysqli_stmt_bind_param($insertSeat, 'is', $studioId, $nomor);
                mysqli_stmt_execute($insertSeat);
                $created++;
            }
        }

        mysqli_commit($koneksi);
        set_flash('success', 'Studio dan kursi berhasil ditambahkan.');
        redirect('admin/studio/index.php');
    } catch (Throwable $e) {
        mysqli_rollback($koneksi);
        set_flash('danger', 'Studio gagal ditambahkan.');
        redirect('admin/studio/tambah.php');
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>
<main class="container py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="card form-card"><div class="card-body p-4"><h1 class="h3 fw-bold mb-4">Tambah Studio</h1>
        <form method="post">
            <div class="mb-3"><label for="nama_studio" class="form-label">Nama Studio</label><input type="text" class="form-control" id="nama_studio" name="nama_studio" required></div>
            <div class="mb-3"><label for="kapasitas" class="form-label">Kapasitas Kursi</label><input type="number" class="form-control" id="kapasitas" name="kapasitas" min="1" max="48" value="32" required><div class="form-text">Sistem otomatis membuat nomor kursi dari A1, A2, dan seterusnya.</div></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-warning">Simpan</button><a href="<?= url('admin/studio/index.php') ?>" class="btn btn-outline-secondary">Kembali</a></div>
        </form>
    </div></div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
