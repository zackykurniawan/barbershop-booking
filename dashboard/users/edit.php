<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$pageTitle = 'Edit User';
$assetPath = '../../assets';
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'User' => '/dashboard/users/index.php', 'Edit' => null];
$error = '';

$stmt = $conn->prepare('SELECT id, username, status FROM users WHERE id=? AND deleted_at IS NULL LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    setFlash('danger', 'Data user tidak ditemukan.');
    header('Location: /dashboard/users/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $status = $_POST['status'] ?? 'User';

    if ($username === '' || !in_array($status, ['Admin', 'User'], true)) {
        $error = 'Username dan status wajib diisi dengan benar.';
    } elseif ($password !== '' && strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username=? AND id<>? LIMIT 1');
        $stmt->bind_param('si', $username, $id);
        $stmt->execute();
        $existingUser = $stmt->get_result()->fetch_assoc();

        if ($existingUser) {
            $error = 'Username sudah digunakan.';
        } else {
            if ($password !== '') {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare('UPDATE users SET username=?, password=?, status=? WHERE id=? AND deleted_at IS NULL');
                $stmt->bind_param('sssi', $username, $hashedPassword, $status, $id);
            } else {
                $stmt = $conn->prepare('UPDATE users SET username=?, status=? WHERE id=? AND deleted_at IS NULL');
                $stmt->bind_param('ssi', $username, $status, $id);
            }

            $stmt->execute();

            if ((int) $_SESSION['user_id'] === $id) {
                $_SESSION['username'] = $username;
                $_SESSION['status'] = $status;
            }

            setFlash('success', 'User berhasil diperbarui.');
            header('Location: /dashboard/users/index.php');
            exit;
        }
    }

    $user['username'] = $username;
    $user['status'] = $status;
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
        <h5 class="mb-0">Form Edit User</h5>
    </div>
    <div class="card-body">
        <form method="post" autocomplete="off">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= h($user['username']) ?>" placeholder="Masukkan username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Admin" <?= $user['status'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="User" <?= $user['status'] === 'User' ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="password">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="/dashboard/users/index.php" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
