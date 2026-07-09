<?php
$receipt = isset($receipt) ? $receipt : null;
$customer = isset($customer) ? $customer : null;
$order = isset($order) ? $order : null;

if (!$receipt) {
    return;
}
?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Receipt <?= html_escape($receipt->receipt_number); ?></h3>
                <span class="badge text-bg-success">Paid</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Customer</p>
                        <?php if ($customer): ?>
                            <p class="mb-0 fw-semibold"><?= html_escape($customer->name); ?></p>
                            <p class="mb-0 small text-muted"><?= html_escape($customer->email); ?></p>
                        <?php else: ?>
                            <p class="mb-0">User #<?= (int) $receipt->user_id; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Related order</p>
                        <p class="mb-0 fw-semibold">
                            <?= html_escape($order ? $order->order_number : ('Order #' . (int) $receipt->order_id)); ?>
                        </p>
                        <p class="mb-0 small text-muted">Payment ID #<?= (int) $receipt->payment_id; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Issued on</p>
                        <p class="mb-0"><?= html_escape(date('d M Y, H:i', strtotime($receipt->issued_at))); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Payment summary</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Amount paid</span>
                    <span class="fs-5 fw-bold mb-0">
                        <?= html_escape($receipt->currency); ?>
                        <?= html_escape(number_format((float) $receipt->amount, 2)); ?>
                    </span>
                </div>

                <a href="<?= site_url('admin/receipts'); ?>" class="btn btn-outline-secondary w-100">
                    Back to receipts
                </a>
            </div>
        </div>
    </div>
</div>
