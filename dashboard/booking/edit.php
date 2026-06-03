<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/helpers.php';
requireAuth();

$id = (int) ($_GET['id'] ?? 0);
$pageTitle = 'Edit Booking';
$assetPath = '../../assets';
$breadcrumb = ['Dashboard' => '/dashboard/index.php', 'Booking' => '/dashboard/booking/index.php', 'Edit' => null];
$statuses = ['Pending', 'Confirmed', 'Done', 'Cancelled'];
$today = date('Y-m-d');
$error = '';

$stmt = $conn->prepare('SELECT * FROM bookings WHERE id=? AND deleted_at IS NULL LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    setFlash('danger', 'Data booking tidak ditemukan.');
    header('Location: /dashboard/booking/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $customerName = trim($_POST['customer_name'] ?? '');
    $bookingDate = $_POST['booking_date'] ?? '';
    $bookingTime = $_POST['booking_time'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    $status = $_POST['status'] ?? 'Pending';

    if ($serviceId <= 0 || $customerName === '' || $bookingDate === '' || $bookingTime === '' || !in_array($status, $statuses, true)) {
        $error = 'Semua field wajib diisi dengan benar.';
    } elseif (isPastBookingSchedule($bookingDate, $bookingTime)) {
        $error = 'Tanggal dan jam booking tidak boleh lebih kecil dari waktu sekarang.';
    } else {
        $stmt = $conn->prepare('UPDATE bookings SET service_id=?, customer_name=?, booking_date=?, booking_time=?, notes=?, status=? WHERE id=? AND deleted_at IS NULL');
        $stmt->bind_param('isssssi', $serviceId, $customerName, $bookingDate, $bookingTime, $notes, $status, $id);
        $stmt->execute();

        setFlash('success', 'Booking berhasil diperbarui.');
        header('Location: /dashboard/booking/index.php');
        exit;
    }

    $booking['service_id'] = $serviceId;
    $booking['customer_name'] = $customerName;
    $booking['booking_date'] = $bookingDate;
    $booking['booking_time'] = $bookingTime;
    $booking['notes'] = $notes;
    $booking['status'] = $status;
}

$services = $conn->query("SELECT id, name FROM services WHERE deleted_at IS NULL AND is_active = 1 ORDER BY name ASC");

include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/sidebar.php';
include __DIR__ . '/../../layouts/topbar.php';
?>

<?php if ($error) : ?>
    <div class="alert alert-danger" role="alert"><?= h($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Edit Booking</h5>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="customer_name">Customer</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?= h($booking['customer_name']) ?>" placeholder="Masukkan nama customer" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="service_id">Layanan</label>
                    <select class="form-select" id="service_id" name="service_id" required>
                        <?php while ($service = $services->fetch_assoc()) : ?>
                            <option value="<?= (int) $service['id'] ?>" <?= (int) $booking['service_id'] === (int) $service['id'] ? 'selected' : '' ?>>
                                <?= h($service['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="booking_date">Tanggal</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" value="<?= h($booking['booking_date']) ?>" min="<?= h($today) ?>" placeholder="Pilih tanggal booking" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="booking_time">Jam</label>
                    <input type="time" class="form-control" id="booking_time" name="booking_time" value="<?= h(substr($booking['booking_time'], 0, 5)) ?>" placeholder="Pilih jam booking" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($statuses as $item) : ?>
                            <option value="<?= h($item) ?>" <?= $booking['status'] === $item ? 'selected' : '' ?>><?= h($item) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="notes">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Masukkan catatan booking jika ada"><?= h($booking['notes']) ?></textarea>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="/dashboard/booking/index.php" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
$extraScripts = '<script>
document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("booking_date");
    const timeInput = document.getElementById("booking_time");

    function pad(value) {
        return String(value).padStart(2, "0");
    }

    function currentTime() {
        const now = new Date();
        return pad(now.getHours()) + ":" + pad(now.getMinutes());
    }

    function today() {
        const now = new Date();
        return now.getFullYear() + "-" + pad(now.getMonth() + 1) + "-" + pad(now.getDate());
    }

    function syncTimeLimit() {
        if (dateInput.value === today()) {
            timeInput.min = currentTime();
        } else {
            timeInput.removeAttribute("min");
        }
    }

    dateInput.addEventListener("change", syncTimeLimit);
    syncTimeLimit();
});
</script>';
?>
<?php include __DIR__ . '/../../layouts/footer.php'; ?>
