<?php
require_once __DIR__ . '/helpers/auth.php';
require_login();

$pageTitle = 'Profil Saya';
$user = current_user();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('danger', 'Token keamanan tidak valid. Silakan muat ulang halaman.');
        redirect('profile.php');
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nama === '' || $email === '') {
        set_flash('danger', 'Nama dan email wajib diisi.');
        redirect('profile.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Format email tidak valid.');
        redirect('profile.php');
    }

    $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'ssi', $email, $user['username'], $user['id']);
    mysqli_stmt_execute($stmt);
    $existing = mysqli_stmt_get_result($stmt);

    if ($existing && mysqli_num_rows($existing) > 0) {
        set_flash('danger', 'Email sudah digunakan oleh akun lain.');
        redirect('profile.php');
    }

    $updateStmt = mysqli_prepare($koneksi, "UPDATE users SET nama = ?, email = ? WHERE id = ?");
    mysqli_stmt_bind_param($updateStmt, 'ssi', $nama, $email, $user['id']);
    mysqli_stmt_execute($updateStmt);

    if (mysqli_affected_rows($koneksi) >= 0) {
        $_SESSION['user']['nama'] = $nama;
        $_SESSION['user']['email'] = $email;
        set_flash('success', 'Profil berhasil diperbarui.');
    } else {
        set_flash('danger', 'Tidak ada perubahan yang disimpan.');
    }

    redirect('profile.php');
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <?php include __DIR__ . '/includes/flash.php'; ?>
            <div class="card form-card">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold mb-1">Profil Saya</h1>
                    <p class="text-muted mb-4">Kelola informasi akun kamu di sini.</p>
                    <form method="post">
                        <?= csrf_input() ?>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= e($user['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= e($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= e($user['username']) ?>" disabled>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>