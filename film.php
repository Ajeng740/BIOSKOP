<?php
require_once __DIR__ . '/helpers/auth.php';
$pageTitle = 'Daftar Film';

$films = mysqli_query($koneksi, "SELECT * FROM films ORDER BY created_at DESC");
$genres = mysqli_query($koneksi, "SELECT DISTINCT genre FROM films ORDER BY genre ASC");
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-4">
    <?php include __DIR__ . '/includes/flash.php'; ?>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Daftar Film</h1>
            <p class="text-muted mb-0">Pilih film untuk melihat detail dan jadwal tayang.</p>
        </div>
        <div class="row g-2 w-100" style="max-width: 540px;">
            <div class="col-md-7">
                <input type="search" class="form-control" placeholder="Cari judul film..." data-search-film>
            </div>
            <div class="col-md-5">
                <select class="form-select" data-filter-genre>
                    <option value="">Semua Genre</option>
                    <?php while ($genre = mysqli_fetch_assoc($genres)): ?>
                        <option value="<?= e($genre['genre']) ?>"><?= e($genre['genre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php while ($film = mysqli_fetch_assoc($films)): ?>
            <div class="col-md-6 col-lg-4" data-film-card data-title="<?= e($film['judul']) ?>" data-genre="<?= e($film['genre']) ?>">
                <div class="card movie-card">
                    <?php if (!empty($film['poster'])): ?>
                        <img src="<?= e($film['poster']) ?>" class="poster-img" alt="Poster <?= e($film['judul']) ?>">
                    <?php else: ?>
                        <div class="poster-frame"><?= e(substr($film['judul'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge text-bg-danger mb-2"><?= e($film['genre']) ?></span>
                        <h2 class="h5 fw-bold"><?= e($film['judul']) ?></h2>
                        <p class="text-muted small mb-3"><?= e($film['durasi']) ?> menit &bull; <?= e($film['status']) ?></p>
                        <a href="<?= url('detail_film.php?id=' . $film['id']) ?>" class="btn btn-dark w-100">Detail Film</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
