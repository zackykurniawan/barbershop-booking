<?php
$pageTitle = $pageTitle ?? 'Barbershop Booking';
$assetPath = $assetPath ?? '../assets';
$useDataTable = $useDataTable ?? false;
?>
<!doctype html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($pageTitle) ?> | Barbershop Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistem Booking Barbershop">
    <meta name="author" content="Barbershop Booking">

    <link rel="icon" href="<?= $assetPath ?>/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= $assetPath ?>/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= $assetPath ?>/fonts/feather.css">
    <link rel="stylesheet" href="<?= $assetPath ?>/fonts/fontawesome.css">
    <?php if ($useDataTable) : ?>
        <link rel="stylesheet" href="<?= $assetPath ?>/css/plugins/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="<?= $assetPath ?>/css/plugins/responsive.bootstrap5.min.css">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= $assetPath ?>/css/style.css">
    <link rel="stylesheet" href="<?= $assetPath ?>/css/style-preset.css">
    <style>
        .pc-container .page-header + .row {
            padding-top: 8px;
        }
    </style>
    <?php if ($useDataTable) : ?>
        <style>
            .dataTables_wrapper,
            .dt-responsive {
                overflow-x: visible;
            }

            .datatable {
                width: 100% !important;
            }
        </style>
    <?php endif; ?>
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
