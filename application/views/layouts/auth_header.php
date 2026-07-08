<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= isset($title) ? $title . ' | Commerce Portal' : 'Commerce Portal'; ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">

    <?php if (!empty($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= base_url($style); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="login-page bg-body-secondary">