<?php
$order = isset($order) ? $order : null;
$invoice = isset($invoice) ? $invoice : null;
$receipt = isset($receipt) ? $receipt : null;
$sync_success = !empty($sync_success);
$sync_message = isset($sync_message) ? trim((string) $sync_message) : '';

if (!$order) {
    return;
}

$isPaid = ($order->status === 'paid');
$isPendingConfirmation = !$isPaid && $sync_success;
$isPaymentFailed = !$isPaid && !$sync_success;
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= $isPaid ? 'Payment successful' : ($isPaymentFailed ? 'Payment not confirmed' : 'Confirming payment'); ?>
        </li>
    </ol>
</nav>

<div class="text-center mb-4">
    <div class="mb-3">
        <i class="fas fa-3x <?= $isPaid ? 'fa-circle-check text-success' : ($isPaymentFailed ? 'fa-circle-xmark text-danger' : 'fa-circle-exclamation text-warning'); ?>"></i>
    </div>
    <h1 class="h4 mb-1">
        <?php if ($isPaid): ?>
            Payment successful
        <?php elseif ($isPaymentFailed): ?>
            Payment not confirmed
        <?php else: ?>
            Payment received — confirming…
        <?php endif; ?>
    </h1>
    <p class="text-muted mb-0">
        <?php if ($isPaid): ?>
            Thank you. Your payment has been received.
        <?php elseif ($isPaymentFailed): ?>
            <?= html_escape($sync_message !== '' ? $sync_message : 'We could not confirm your payment.'); ?>
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
                    <span class="badge <?= $isPaid ? 'text-bg-success' : ($isPaymentFailed ? 'text-bg-danger' : 'text-bg-warning'); ?>">
                        <?= $isPaid ? 'Paid' : html_escape(ucfirst($order->status)); ?>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted"><?= $isPaid ? 'Amount paid' : 'Order total'; ?></span>
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
                <?= $isPaymentFailed ? 'Return to order and pay' : 'View order details'; ?>
            </a>
            <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">
                Continue shopping
            </a>
        </div>
    </div>
</div>