<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAuth();

$pageTitle = 'Layanan';
$assetPath = '../../assets';
$useDataTable = true;
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'Layanan' => null];

$result = $conn->query("SELECT * FROM services WHERE deleted_at IS NULL ORDER BY name ASC");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>
<?php showFlash(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Layanan</h5>
        <a href="/dashboard/services/create.php" class="btn btn-primary btn-sm">
            <i class="ti ti-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table class="table table-striped table-bordered align-middle datatable w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th class="text-end no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($service = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-semibold"><?= h($service['name']) ?></div>
                                <small class="text-muted"><?= h($service['description']) ?></small>
                            </td>
                            <td><?= rupiah($service['price']) ?></td>
                            <td><?= (int) $service['duration_minute'] ?> menit</td>
                            <td>
                                <?php if ((int) $service['is_active'] === 1) : ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="/dashboard/services/edit.php?id=<?= (int) $service['id'] ?>" class="btn btn-light-primary btn-sm" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="/dashboard/services/delete.php?id=<?= (int) $service['id'] ?>" class="btn btn-light-danger btn-sm" title="Hapus" onclick="return confirm('Hapus layanan ini?')">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
