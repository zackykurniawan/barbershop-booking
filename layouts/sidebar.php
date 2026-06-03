<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="/dashboard/index.php" class="b-brand d-flex align-items-center gap-2 text-decoration-none">
                <img src="/assets/images/logo-icon.svg" alt="Barbershop" style="width: 34px; height: 34px;">
                <span class="fs-4 fw-bold text-dark">Barbershop</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Menu</label>
                </li>
                <li class="pc-item">
                    <a href="/dashboard/index.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Manajemen</label>
                </li>
                <li class="pc-item">
                    <a href="/dashboard/booking/index.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                        <span class="pc-mtext">Booking</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/dashboard/services/index.php" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-scissors"></i></span>
                        <span class="pc-mtext">Layanan</span>
                    </a>
                </li>
                <?php if (($_SESSION['status'] ?? '') === 'Admin') : ?>
                    <li class="pc-item pc-caption">
                        <label>Admin</label>
                    </li>
                    <li class="pc-item">
                        <a href="/dashboard/users/index.php" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">User</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>