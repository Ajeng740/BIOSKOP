<?php
require_once __DIR__ . '/helpers/auth.php';
$pageTitle = 'Daftar';

if (is_logged_in()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    if ($nama === '' || $email === '' || $username === '' || $password === '') {
        set_flash('danger', 'Semua field wajib diisi.');
        redirect('register.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Format email tidak valid.');
        redirect('register.php');
    }

    if (strlen($password) < 6) {
        set_flash('danger', 'Password minimal 6 karakter.');
        redirect('register.php');
    }

    if ($password !== $konfirmasi) {
        set_flash('danger', 'Konfirmasi password tidak sesuai.');
        redirect('register.php');
    }

    $cekStmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
    mysqli_stmt_bind_param($cekStmt, 'ss', $email, $username);
    mysqli_stmt_execute($cekStmt);
    $cek = mysqli_stmt_get_result($cekStmt);

    if (mysqli_num_rows($cek) > 0) {
        set_flash('danger', 'Email atau username sudah digunakan.');
        redirect('register.php');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';
    $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $username, $hash, $role);
    mysqli_stmt_execute($stmt);

    set_flash('success', 'Registrasi berhasil. Silakan login.');
    redirect('login.php');
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
                    <h1 class="h3 fw-bold mb-1">Daftar Akun</h1>
                    <p class="text-muted mb-4">Buat akun untuk memesan tiket bioskop.</p>
                    <form method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 mt-4">Daftar</button>
                    </form>
                    <hr>
                    <p class="mb-0 text-center">Sudah punya akun? <a href="<?= url('login.php') ?>">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
