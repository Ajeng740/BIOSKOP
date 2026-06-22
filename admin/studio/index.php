<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Kelola Studio';
$studios = mysqli_query($koneksi, "SELECT * FROM studios ORDER BY nama_studio ASC");
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>

<main class="container-fluid px-lg-4 py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div><h1 class="fw-bold mb-1">Kelola Studio</h1><p class="text-muted mb-0">CRUD data studio bioskop.</p></div>
        <a href="<?= url('admin/studio/tambah.php') ?>" class="btn btn-warning align-self-md-start">Tambah Studio</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark"><tr><th>No</th><th>Nama Studio</th><th>Kapasitas</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no = 1; while ($studio = mysqli_fetch_assoc($studios)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= e($studio['nama_studio']) ?></td>
                            <td><?= e($studio['kapasitas']) ?> kursi</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= url('admin/studio/edit.php?id=' . $studio['id']) ?>" class="btn btn-outline-primary">Edit</a>
                                </div>
                                <form action="<?= url('admin/studio/hapus.php') ?>" method="post" class="d-inline">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="id" value="<?= e($studio['id']) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Hapus studio ini? Data kursi dan jadwal terkait juga dapat terhapus.">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($studios) === 0): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data studio.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
