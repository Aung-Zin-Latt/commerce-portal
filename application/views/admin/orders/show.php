<?php
$order = isset($order) ? $order : null;
$items = isset($items) ? $items : array();
$customer = isset($customer) ? $customer : null;
$invoice = isset($invoice) ? $invoice : null;
$receipt = isset($receipt) ? $receipt : null;

if (!$order) {
    return;
}

$status_labels = array(
    'pending' => array('label' => 'Pending', 'class' => 'text-bg-warning'),
    'paid' => array('label' => 'Paid', 'class' => 'text-bg-success'),
    'failed' => array('label' => 'Failed', 'class' => 'text-bg-danger'),
    'cancelled' => array('label' => 'Cancelled', 'class' => 'text-bg-secondary'),
    'refunded' => array('label' => 'Refunded', 'class' => 'text-bg-info'),
);

$status = isset($status_labels[$order->status])
    ? $status_labels[$order->status]
    : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');
?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Order <?= html_escape($order->order_number); ?></h3>
                <span class="badge <?= html_escape($status['class']); ?>">
                    <?= html_escape($status['label']); ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Customer</p>
                        <?php if ($customer): ?>
                            <p class="mb-0 fw-semibold"><?= html_escape($customer->name); ?></p>
                            <p class="mb-0 small text-muted"><?= html_escape($customer->email); ?></p>
                        <?php else: ?>
                            <p class="mb-0">User #<?= (int) $order->user_id; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Placed on</p>
                        <p class="mb-0"><?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Line items</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-center" style="width: 110px;">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No line items found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= html_escape($item->product_name); ?></td>
                                        <td class="text-end">
                                            <?= html_escape($order->currency); ?>
                                            <?= html_escape(number_format((float) $item->unit_price, 2)); ?>
                                        </td>
                                        <td class="text-center"><?= (int) $item->quantity; ?></td>
                                        <td class="text-end">
                                            <?= html_escape($order->currency); ?>
                                            <?= html_escape(number_format((float) $item->subtotal, 2)); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Summary</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>
                        <?= html_escape($order->currency); ?>
                        <?= html_escape(number_format((float) $order->subtotal, 2)); ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax</span>
                    <span>
                        <?= html_escape($order->currency); ?>
                        <?= html_escape(number_format((float) $order->tax_amount, 2)); ?>
                    </span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Total</span>
                    <span class="fs-5 fw-bold mb-0">
                        <?= html_escape($order->currency); ?>
                        <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <?php if (!empty($invoice)): ?>
                        <a href="<?= site_url('admin/invoices/show/' . (int) $invoice->id); ?>" class="btn btn-outline-primary">
                            View invoice
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($receipt)): ?>
                        <a href="<?= site_url('admin/receipts/show/' . (int) $receipt->id); ?>" class="btn btn-outline-primary">
                            View receipt
                        </a>
                    <?php endif; ?>

                    <a href="<?= site_url('admin/orders'); ?>" class="btn btn-outline-secondary">
                        Back to orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
