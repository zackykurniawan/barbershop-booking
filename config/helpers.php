<?php
date_default_timezone_set('Asia/Jakarta');

function startSessionIfNeeded()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requireAuth()
{
    startSessionIfNeeded();

    if (!isset($_SESSION['user_id'])) {
        header('Location: /auth/login.php');
        exit;
    }
}

function requireAdmin()
{
    requireAuth();

    if (($_SESSION['status'] ?? '') !== 'Admin') {
        setFlash('danger', 'Halaman ini hanya bisa diakses oleh Admin.');
        header('Location: /dashboard/index.php');
        exit;
    }
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function setFlash($type, $message)
{
    startSessionIfNeeded();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $message];
}

function showFlash()
{
    startSessionIfNeeded();

    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    echo '<div class="alert alert-' . h($flash['type']) . ' alert-dismissible fade show" role="alert">';
    echo h($flash['msg']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

function statusBadge($status)
{
    $map = [
        'Pending' => 'bg-warning',
        'Confirmed' => 'bg-primary',
        'Done' => 'bg-success',
        'Cancelled' => 'bg-danger',
    ];

    return $map[$status] ?? 'bg-secondary';
}

function rupiah($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

function isPastBookingSchedule($date, $time)
{
    $bookingDateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . substr($time, 0, 5));

    if (!$bookingDateTime) {
        return true;
    }

    return $bookingDateTime < new DateTime();
}
