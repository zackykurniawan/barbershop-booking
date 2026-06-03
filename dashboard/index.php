<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';
requireAuth();

$pageTitle = 'Dashboard';
$assetPath = '../assets';
$useDataTable = true;
$useApexChart = true;
$breadcrumb = ['Home' => null];

function countRows($conn, $sql)
{
    return (int) $conn->query($sql)->fetch_row()[0];
}

$totalBooking = countRows($conn, "SELECT COUNT(*) FROM bookings WHERE deleted_at IS NULL");
$bookingToday = countRows($conn, "SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE() AND deleted_at IS NULL");
$totalServices = countRows($conn, "SELECT COUNT(*) FROM services WHERE deleted_at IS NULL");
$pendingBooking = countRows($conn, "SELECT COUNT(*) FROM bookings WHERE status = 'Pending' AND deleted_at IS NULL");
$completedRevenue = (int) $conn->query(
    "SELECT COALESCE(SUM(s.price), 0)
     FROM bookings b
     JOIN services s ON b.service_id = s.id
     WHERE b.deleted_at IS NULL AND b.status = 'Done'"
)->fetch_row()[0];

$trendLabels = [];
$trendValues = [];
$trendResult = $conn->query(
    "SELECT booking_date, COUNT(*) AS total
     FROM bookings
     WHERE deleted_at IS NULL
     GROUP BY booking_date
     ORDER BY booking_date ASC
     LIMIT 7"
);

while ($row = $trendResult->fetch_assoc()) {
    $trendLabels[] = date('d M', strtotime($row['booking_date']));
    $trendValues[] = (int) $row['total'];
}

$statusLabels = ['Pending', 'Confirmed', 'Done', 'Cancelled'];
$statusValues = array_fill_keys($statusLabels, 0);
$statusResult = $conn->query(
    "SELECT status, COUNT(*) AS total
     FROM bookings
     WHERE deleted_at IS NULL
     GROUP BY status"
);

while ($row = $statusResult->fetch_assoc()) {
    $statusValues[$row['status']] = (int) $row['total'];
}

$transactionHistory = $conn->query(
    "SELECT b.*, s.name AS service_name, s.price
     FROM bookings b
     JOIN services s ON b.service_id = s.id
     WHERE b.deleted_at IS NULL
     ORDER BY b.created_at DESC, b.booking_date DESC, b.booking_time DESC"
);

$extraScripts = '<script>
document.addEventListener("DOMContentLoaded", function () {
    var bookingTrend = new ApexCharts(document.querySelector("#booking-trend-chart"), {
        chart: {
            type: "area",
            height: 310,
            toolbar: { show: false }
        },
        series: [{
            name: "Booking",
            data: ' . json_encode($trendValues) . '
        }],
        xaxis: {
            categories: ' . json_encode($trendLabels) . '
        },
        colors: ["#1677ff"],
        stroke: {
            curve: "smooth",
            width: 3
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: "#f1f1f1" }
    });

    bookingTrend.render();

    var statusChart = new ApexCharts(document.querySelector("#booking-status-chart"), {
        chart: {
            type: "donut",
            height: 310
        },
        series: ' . json_encode(array_values($statusValues)) . ',
        labels: ' . json_encode(array_keys($statusValues)) . ',
        colors: ["#faad14", "#1677ff", "#52c41a", "#ff4d4f"],
        legend: {
            position: "bottom"
        },
        dataLabels: {
            enabled: true
        }
    });

    statusChart.render();
});
</script>';

include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
include __DIR__ . '/../layouts/topbar.php';
?>
<?php showFlash(); ?>

<div class="row">
    <div class="col-12">
        <div class="card bg-primary overflow-hidden">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <h3 class="text-white mb-2">Selamat datang, <?= h($_SESSION['username'] ?? 'User') ?></h3>
                        <p class="text-white text-opacity-75 mb-0">
                            Pantau performa booking dan layanan barbershop dari dashboard ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Booking</h6>
                <h4 class="mb-3"><?= $totalBooking ?></h4>
                <span class="badge bg-light-primary border border-primary"><i class="ti ti-calendar-event"></i> Semua data aktif</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Booking Hari Ini</h6>
                <h4 class="mb-3"><?= $bookingToday ?></h4>
                <span class="badge bg-light-success border border-success"><i class="ti ti-calendar-check"></i> <?= date('d M Y') ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Layanan</h6>
                <h4 class="mb-3"><?= $totalServices ?></h4>
                <span class="badge bg-light-info border border-info"><i class="ti ti-scissors"></i> Katalog layanan</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Pendapatan Selesai</h6>
                <h4 class="mb-3"><?= rupiah($completedRevenue) ?></h4>
                <span class="badge bg-light-warning border border-warning"><i class="ti ti-cash"></i> Transaksi done</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Booking</h5>
            </div>
            <div class="card-body">
                <div id="booking-trend-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Booking</h5>
            </div>
            <div class="card-body">
                <div id="booking-status-chart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Booking</h5>
                <a href="/dashboard/booking/index.php" class="btn btn-primary btn-sm">
                    <i class="ti ti-calendar-event"></i> Lihat Booking
                </a>
            </div>
            <div class="card-body">
                <div class="dt-responsive table-responsive">
                    <table class="table table-striped table-bordered align-middle datatable w-100">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $transactionHistory->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= h($booking['customer_name']) ?></td>
                                    <td><?= h($booking['service_name']) ?></td>
                                    <td><?= h(date('d M Y', strtotime($booking['booking_date']))) ?></td>
                                    <td><?= h(substr($booking['booking_time'], 0, 5)) ?></td>
                                    <td><?= rupiah($booking['price']) ?></td>
                                    <td><span class="badge <?= statusBadge($booking['status']) ?>"><?= h($booking['status']) ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
