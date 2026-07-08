<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 5 + AdminLTE 4 Test</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
</head>
<body class="layout-fixed bg-body-secondary">
    <main class="container py-5">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h1 class="card-title mb-0">Bootstrap 5 + AdminLTE 4 Test</h1>
                <div class="card-tools">
                    <span class="badge text-bg-success">
                        <i class="fa-solid fa-check me-1"></i>Loaded
                    </span>
                </div>
            </div>

            <div class="card-body">
                <p class="lead mb-4">
                    If this page has styled cards, colored buttons, icons, and working tooltips,
                    Bootstrap 5, AdminLTE 4, and Font Awesome are loading correctly.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button type="button" class="btn btn-primary">
                        <i class="fa-solid fa-bolt me-1"></i> Bootstrap Button
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-title="Bootstrap tooltip is active">
                        <i class="fa-solid fa-check me-1"></i> Tooltip Test
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-layer-group me-1"></i> AdminLTE Card
                    </button>
                </div>

                <div class="alert alert-info mb-0" role="alert">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    This page intentionally does not use any layout file.
                </div>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminlte/js/adminlte.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/app.js'); ?>"></script>
    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>
