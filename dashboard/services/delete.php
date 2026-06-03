<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAuth();

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare('UPDATE services SET deleted_at=NOW() WHERE id=? AND deleted_at IS NULL');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    setFlash('success', 'Layanan berhasil dihapus.');
}

header('Location: /dashboard/services/index.php');
exit;
