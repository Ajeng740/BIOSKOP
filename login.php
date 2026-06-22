<?php
require_once __DIR__ . '/helpers/auth.php';
$pageTitle = 'Login';

if (is_logged_in()) {
    if (is_admin()) {
        redirect('admin/dashboard.php');
    }
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];

        set_flash('success', 'Login berhasil.');
        if ($user['role'] === 'admin') {
            redirect('admin/dashboard.php');
        }
        redirect('index.php');
    }

    set_flash('danger', 'Email atau password salah.');
    redirect('login.php');
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php include __DIR__ . '/includes/navbar.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <?php include __DIR__ . '/includes/flash.php'; ?>
            <div class="card form-card">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold mb-1">Login</h1>
                    <p class="text-muted mb-4">Masuk sebagai user atau admin.</p>
                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Login</button>
                    </form>
                    <div class="mt-4 small text-muted">
                        <p class="mb-1"><strong>Admin demo:</strong> admin@bioskop.test / admin123</p>
                        <p class="mb-0"><strong>User demo:</strong> user@bioskop.test / user123</p>
                    </div>
                    <hr>
                    <p class="mb-0 text-center">Belum punya akun? <a href="<?= url('register.php') ?>">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
