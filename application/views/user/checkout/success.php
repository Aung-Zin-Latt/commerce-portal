<?php
$order = isset($order) ? $order : null;
$invoice = isset($invoice) ? $invoice : null;
$receipt = isset($receipt) ? $receipt : null;
$sync_success = !empty($sync_success);

if (!$order) {
    return;
}

$isPaid = ($order->status === 'paid');
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Payment successful</li>
    </ol>
</nav>

<div class="text-center mb-4">
    <div class="mb-3">
        <i class="fas <?= $isPaid ? 'fa-circle-check text-success' : 'fa-circle-exclamation text-warning'; ?> fa-3x"></i>
        <!-- fa-check-circle -->
    </div>
    <h1 class="h4 mb-1">
        <?= $isPaid ? 'Payment successful' : 'Payment received — confirming…'; ?>
    </h1>
    <p class="text-muted mb-0">
        <?php if ($isPaid): ?>
            Thank you. Your payment has been received.
        <?php else: ?>
            We’re confirming your payment. This usually takes a moment.
        <?php endif; ?>
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <p class="text-muted small mb-1">Order number</p>
                        <p class="h5 mb-0"><?= html_escape($order->order_number); ?></p>
                    </div>
                    <span class="badge <?= $isPaid ? 'text-bg-success' : 'text-bg-warning'; ?>">
                        <?= $isPaid ? 'Paid' : html_escape(ucfirst($order->status)); ?>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Amount paid</span>
                    <strong class="fs-5 text-primary mb-0">
                        <?= html_escape($order->currency); ?>
                        <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                    </strong>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <?php if (!empty($invoice)): ?>
                <a href="<?= site_url('user/invoices/show/' . (int) $invoice->id); ?>" class="btn btn-outline-primary">
                    View invoice
                </a>
            <?php endif; ?>

            <?php if (!empty($receipt)): ?>
                <a href="<?= site_url('user/receipts/show/' . (int) $receipt->id); ?>" class="btn btn-outline-primary">
                    View receipt
                </a>
            <?php endif; ?>

            <a href="<?= site_url('user/purchase/show/' . (int) $order->id); ?>" class="btn btn-primary">
                View order details
            </a>
            <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">
                Continue shopping
            </a>
        </div>
    </div>
</div>