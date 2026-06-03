<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAuth();

$pageTitle = 'Booking';
$assetPath = '../../assets';
$useDataTable = true;
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'Booking' => null];

$result = $conn->query(
    "SELECT b.*, s.name AS service_name
     FROM bookings b
     JOIN services s ON b.service_id = s.id
     WHERE b.deleted_at IS NULL
     ORDER BY b.booking_date DESC, b.booking_time DESC"
);

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>
<?php showFlash(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Booking</h5>
        <a href="/dashboard/booking/create.php" class="btn btn-primary btn-sm">
            <i class="ti ti-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table class="table table-striped table-bordered align-middle datatable w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th class="text-end no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php while ($booking = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= h($booking['customer_name']) ?></td>
                            <td><?= h($booking['service_name']) ?></td>
                            <td><?= h(date('d M Y', strtotime($booking['booking_date']))) ?></td>
                            <td><?= h(substr($booking['booking_time'], 0, 5)) ?></td>
                            <td><span class="badge <?= statusBadge($booking['status']) ?>"><?= h($booking['status']) ?></span></td>
                            <td class="text-end">
                                <a href="/dashboard/booking/edit.php?id=<?= (int) $booking['id'] ?>" class="btn btn-light-primary btn-sm" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="/dashboard/booking/delete.php?id=<?= (int) $booking['id'] ?>" class="btn btn-light-danger btn-sm" title="Hapus" onclick="return confirm('Hapus booking ini?')">
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
