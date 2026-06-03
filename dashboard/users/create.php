<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAdmin();

$pageTitle = 'Tambah User';
$assetPath = '../../assets';
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'User' => '/dashboard/users/index.php', 'Tambah' => null];
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

            setFlash('success', 'User berhasil ditambahkan.');
            header('Location: /dashboard/users/index.php');
            exit;
        }
    }
}

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<?php if ($error) : ?>
    <div class="alert alert-danger" role="alert"><?= h($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form User</h5>
    </div>
    <div class="card-body">
        <form method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= h($username) ?>" placeholder="Masukkan username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Admin" <?= $status === 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="User" <?= $status === 'User' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="6" placeholder="Masukkan password" required>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/dashboard/users/index.php" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
