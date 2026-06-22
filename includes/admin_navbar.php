<?php $userLogin = current_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-admin sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand fw-bold" href="<?= url('admin/dashboard.php') ?>">Admin Bioskop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= url('admin/dashboard.php') ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('admin/film/index.php') ?>">Film</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('admin/studio/index.php') ?>">Studio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('admin/jadwal/index.php') ?>">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('admin/pemesanan/index.php') ?>">Pemesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('index.php') ?>">Lihat Website</a></li>
            </ul>
            <span class="text-light small me-3 d-none d-lg-inline"><?= e($userLogin['nama'] ?? 'Admin') ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?= url('logout.php') ?>">Logout</a>
        </div>
    </div>
</nav>
