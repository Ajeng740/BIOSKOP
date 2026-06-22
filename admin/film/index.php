<?php
require_once __DIR__ . '/../../helpers/auth.php';
require_admin();
$pageTitle = 'Kelola Film';
$films = mysqli_query($koneksi, "SELECT * FROM films ORDER BY created_at DESC");
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_navbar.php'; ?>

<main class="container-fluid px-lg-4 py-4">
    <?php include __DIR__ . '/../../includes/flash.php'; ?>
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Kelola Film</h1>
            <p class="text-muted mb-0">CRUD data film bioskop.</p>
        </div>
        <a href="<?= url('admin/film/tambah.php') ?>" class="btn btn-warning align-self-md-start">Tambah Film</a>
    </div>

    <div class="card admin-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark"><tr><th>No</th><th>Judul</th><th>Genre</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no = 1; while ($film = mysqli_fetch_assoc($films)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= e($film['judul']) ?></td>
                            <td><?= e($film['genre']) ?></td>
                            <td><?= e($film['durasi']) ?> menit</td>
                            <td><span class="badge text-bg-<?= $film['status'] === 'Tayang' ? 'success' : 'secondary' ?>"><?= e($film['status']) ?></span></td>
                            <td>
                                <form action="<?= url('admin/film/hapus.php') ?>" method="post" class="d-inline">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="id" value="<?= e($film['id']) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm="Hapus film ini? Data jadwal terkait juga dapat terhapus.">Hapus</button>
                                </form>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= url('admin/film/edit.php?id=' . $film['id']) ?>" class="btn btn-outline-primary">Edit</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($films) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data film.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
