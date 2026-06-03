<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard/index.php');
    exit;
}

$error = '';
$username = '';
$status = 'User';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $status = $_POST['status'] ?? 'User';

    if ($username === '' || $password === '' || !in_array($status, ['Admin', 'User'], true)) {
        $error = 'Semua field wajib diisi dengan benar.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username=? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $existingUser = $stmt->get_result()->fetch_assoc();

        if ($existingUser) {
            $error = 'Username sudah digunakan.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare('INSERT INTO users (username, password, status) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $hashedPassword, $status);
            $stmt->execute();

            setFlash('success', 'Registrasi berhasil. Silakan login.');
            header('Location: /auth/login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Register | Barbershop Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="../assets/fonts/feather.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style-preset.css">
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="/auth/login.php" class="b-brand d-inline-flex align-items-center gap-2 text-decoration-none">
                        <img src="../assets/images/logo-icon.svg" alt="Barbershop" style="width: 44px; height: 44px;">
                        <span class="fs-2 fw-bold text-dark">Barbershop</span>
                    </a>
                </div>
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Register</b></h3>
                            <a href="/auth/login.php" class="link-primary">Sudah punya akun?</a>
                        </div>

                        <?php if ($error) : ?>
                            <div class="alert alert-danger" role="alert"><?= h($error) ?></div>
                        <?php endif; ?>

                        <form method="post" autocomplete="off">
                            <div class="form-group mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= h($username) ?>" placeholder="Masukkan username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Admin" <?= $status === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="User" <?= $status === 'User' ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Barbershop Booking &copy; <?= date('Y') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/pcoded.js"></script>
</body>

</html>