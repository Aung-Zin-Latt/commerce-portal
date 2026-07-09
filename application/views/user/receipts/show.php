<?php
$receipt = isset($receipt) ? $receipt : null;

if (!$receipt) {
    return;
}
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= site_url('user/receipts'); ?>">Receipts</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= html_escape($receipt->receipt_number); ?>
        </li>
    </ol>
</nav>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">Receipt details</h1>
    <p class="text-muted mb-0">This receipt confirms payment for your order.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <p class="text-muted small mb-1">Receipt number</p>
                        <p class="h5 mb-0"><?= html_escape($receipt->receipt_number); ?></p>
                    </div>
                    <span class="badge text-bg-success">Paid</span>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Issued on <?= html_escape(date('d M Y, H:i', strtotime($receipt->issued_at))); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 mb-3">Payment summary</h2>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Amount paid</span>
                    <span class="fs-5 fw-bold text-primary mb-0">
                        <?= html_escape($receipt->currency); ?>
                        <?= html_escape(number_format((float) $receipt->amount, 2)); ?>
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= site_url('user/purchase/show/' . (int) $receipt->order_id); ?>" class="btn btn-outline-primary">
                        View related order
                    </a>
                    <a href="<?= site_url('user/receipts'); ?>" class="btn btn-outline-secondary">
                        Back to receipts
                    </a>
                    <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">
                        Continue shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>