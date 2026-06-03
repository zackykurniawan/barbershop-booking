<?php
$username = $_SESSION['username'] ?? 'Guest';
$status = $_SESSION['status'] ?? 'User';
?>
<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0 d-flex align-items-center gap-2 px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light-primary text-primary flex-shrink-0" style="width: 32px; height: 32px; line-height: 1;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <span class="d-none d-sm-inline fw-medium text-dark"><?= htmlspecialchars($username) ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= htmlspecialchars($username) ?></h6>
                                    <span><?= htmlspecialchars($status) ?></span>
                                </div>
                            </div>
                        </div>
                        <a href="/auth/logout.php" class="dropdown-item">
                            <i class="ti ti-logout"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<div class="pc-container">
    <div class="pc-content">
        <?php if (isset($breadcrumb) && is_array($breadcrumb)) : ?>
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb mb-2">
                                <?php foreach ($breadcrumb as $label => $url) : ?>
                                    <?php if ($url) : ?>
                                        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($label) ?></a></li>
                                    <?php else : ?>
                                        <li class="breadcrumb-item" aria-current="page"><?= htmlspecialchars($label) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title mb-2">
                                <h2 class="mb-0"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
