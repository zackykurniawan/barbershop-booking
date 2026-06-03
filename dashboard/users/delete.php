<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    setFlash('danger', 'User tidak valid.');
    header('Location: /dashboard/users/index.php');
    exit;
}

if ($id === (int) $_SESSION['user_id']) {
    setFlash('danger', 'Akun yang sedang dipakai tidak bisa dihapus.');
    header('Location: /dashboard/users/index.php');
    exit;
}

$stmt = $conn->prepare('UPDATE users SET deleted_at=NOW() WHERE id=? AND deleted_at IS NULL');
$stmt->bind_param('i', $id);
$stmt->execute();

setFlash('success', 'User berhasil dihapus.');
header('Location: /dashboard/users/index.php');
exit;
