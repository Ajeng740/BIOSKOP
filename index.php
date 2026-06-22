<?php
require_once __DIR__ . '/helpers/auth.php';
$pageTitle = 'Beranda';

$films = mysqli_query($koneksi, "SELECT * FROM films WHERE status = 'Tayang' ORDER BY created_at DESC LIMIT 6");
$genres = mysqli_query($koneksi, "SELECT DISTINCT genre FROM films ORDER BY genre ASC");
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<section class="hero-section py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge text-bg-warning mb-3">Pemesanan Tiket Bioskop Online</span>
                <h1 class="display-5 fw-bold mb-3">Pesan tiket film favorit dengan lebih cepat dan mudah.</h1>
                <p class="lead text-white-50 mb-4">Lihat daftar film, pilih jadwal tayang, pilih kursi, lalu simpan kode booking secara online.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= url('film.php') ?>" class="btn btn-warning btn-lg">Lihat Film</a>
                    <?php if (!is_logged_in()): ?>
                        <a href="<?= url('register.php') ?>" class="btn btn-outline-light btn-lg">Daftar Akun</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card rounded-4 p-4">
                    <h2 class="h4 fw-bold mb-3">Alur Pemesanan</h2>
                    <ol class="mb-0 text-white-50">
                        <li>Pilih film yang sedang tayang.</li>
                        <li>Pilih jadwal dan studio.</li>
                        <li>Pilih kursi yang masih tersedia.</li>
                        <li>Simpan kode booking pemesanan.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="container py-4">
    <?php include __DIR__ . '/includes/flash.php'; ?>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Film Sedang Tayang</h2>
            <p class="text-muted mb-0">Gunakan pencarian dan filter genre dengan JavaScript/DOM.</p>
        </div>
        <div class="row g-2 w-100 w-lg-auto" style="max-width: 520px;">
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
        <?php if (mysqli_num_rows($films) > 0): ?>
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
                            <h3 class="h5 fw-bold"><?= e($film['judul']) ?></h3>
                            <p class="text-muted small mb-3"><?= e($film['durasi']) ?> menit &bull; <?= e($film['status']) ?></p>
                            <a href="<?= url('detail_film.php?id=' . $film['id']) ?>" class="btn btn-dark w-100">Detail Film</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada film yang sedang tayang.</div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
