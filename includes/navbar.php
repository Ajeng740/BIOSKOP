<?php $userLogin = current_user(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('index.php') ?>">Bioskop Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= url('index.php') ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('film.php') ?>">Film</a></li>
                <?php if ($userLogin && $userLogin['role'] === 'user'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('riwayat.php') ?>">Riwayat</a></li>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/dashboard.php') ?>">Admin</a></li>
                <?php endif; ?>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <?php if ($userLogin): ?>
                    <span class="text-light small d-none d-lg-inline">Halo, <?= e($userLogin['nama']) ?></span>
                    <a class="btn btn-outline-light btn-sm" href="<?= url('logout.php') ?>">Logout</a>
                <?php else: ?>
                    <a class="btn btn-outline-light btn-sm" href="<?= url('login.php') ?>">Login</a>
                    <a class="btn btn-warning btn-sm" href="<?= url('register.php') ?>">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
