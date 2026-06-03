<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAuth();

$id = (int) ($_GET['id'] ?? 0);
$pageTitle = 'Edit Layanan';
$assetPath = '../../assets';
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'Layanan' => '/dashboard/services/index.php', 'Edit' => null];

$stmt = $conn->prepare('SELECT * FROM services WHERE id=? AND deleted_at IS NULL LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$service = $stmt->get_result()->fetch_assoc();

if (!$service) {
    setFlash('danger', 'Data layanan tidak ditemukan.');
    header('Location: /dashboard/services/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $durationMinute = (int) ($_POST['duration_minute'] ?? 30);
    $isActive = (int) ($_POST['is_active'] ?? 0);

    $stmt = $conn->prepare('UPDATE services SET name=?, description=?, price=?, duration_minute=?, is_active=? WHERE id=? AND deleted_at IS NULL');
    $stmt->bind_param('ssiiii', $name, $description, $price, $durationMinute, $isActive, $id);
    $stmt->execute();

    setFlash('success', 'Layanan berhasil diperbarui.');
    header('Location: /dashboard/services/index.php');
    exit;
}

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Edit Layanan</h5>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="name">Nama Layanan</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= h($service['name']) ?>" placeholder="Masukkan nama layanan" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="price">Harga</label>
                    <input type="number" class="form-control" id="price" name="price" min="0" value="<?= (int) $service['price'] ?>" placeholder="Contoh: 35000" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="duration_minute">Durasi (menit)</label>
                    <input type="number" class="form-control" id="duration_minute" name="duration_minute" min="1" value="<?= (int) $service['duration_minute'] ?>" placeholder="Contoh: 30" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="is_active">Status</label>
                    <select class="form-select" id="is_active" name="is_active" required>
                        <option value="1" <?= (int) $service['is_active'] === 1 ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= (int) $service['is_active'] === 0 ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Masukkan deskripsi layanan"><?= h($service['description']) ?></textarea>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="/dashboard/services/index.php" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
