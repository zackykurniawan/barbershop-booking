<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAdmin();

$pageTitle = 'User';
$assetPath = '../../assets';
$useDataTable = true;
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'User' => null];

$result = $conn->query("SELECT id, username, status, created_at FROM users WHERE deleted_at IS NULL ORDER BY username ASC");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>
<?php showFlash(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data User</h5>
        <a href="/dashboard/users/create.php" class="btn btn-primary btn-sm">
            <i class="ti ti-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table class="table table-striped table-bordered align-middle datatable w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-end no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($user = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= h($user['username']) ?></td>
                            <td>
                                <span class="badge <?= $user['status'] === 'Admin' ? 'bg-primary' : 'bg-secondary' ?>">
                                    <?= h($user['status']) ?>
                                </span>
                            </td>
                            <td><?= h(date('d M Y H:i', strtotime($user['created_at']))) ?></td>
                            <td class="text-end">
                                <a href="/dashboard/users/edit.php?id=<?= (int) $user['id'] ?>" class="btn btn-light-primary btn-sm" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']) : ?>
                                    <a href="/dashboard/users/delete.php?id=<?= (int) $user['id'] ?>" class="btn btn-light-danger btn-sm" title="Hapus" onclick="return confirm('Hapus user ini?')">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
