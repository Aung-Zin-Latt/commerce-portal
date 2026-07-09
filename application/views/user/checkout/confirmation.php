<?php
$order = isset($order) ? $order : null;
$items = isset($items) ? $items : array();

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

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Order confirmation</li>
    </ol>
</nav>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">Order placed</h1>
    <p class="text-muted mb-0">Thank you. Your order has been created and is pending payment.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <p class="text-muted small mb-1">Order number</p>
                        <p class="h5 mb-0"><?= html_escape($order->order_number); ?></p>
                    </div>
                    <span class="badge <?= html_escape($status['class']); ?>">
                        <?= html_escape($status['label']); ?>
                    </span>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Placed on <?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?>
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm d-none d-md-block">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center" style="width: 110px;">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
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
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-md-none">
            <?php foreach ($items as $item): ?>
                <article class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="min-w-0">
                                <h2 class="h6 mb-1 text-truncate"><?= html_escape($item->product_name); ?></h2>
                                <p class="text-muted small mb-0">
                                    <?= html_escape($order->currency); ?>
                                    <?= html_escape(number_format((float) $item->unit_price, 2)); ?>
                                    × <?= (int) $item->quantity; ?>
                                </p>
                            </div>
                            <strong class="text-primary flex-shrink-0">
                                <?= html_escape($order->currency); ?>
                                <?= html_escape(number_format((float) $item->subtotal, 2)); ?>
                            </strong>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 mb-3">Order summary</h2>

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
                    <span class="fs-5 fw-bold text-primary mb-0">
                        <?= html_escape($order->currency); ?>
                        <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                    </span>
                </div>

                <?php if ($order->status === 'pending'): ?>
                    <div class="d-grid gap-2 mb-2">
                        <?= form_open('user/checkout/pay/' . (int) $order->id); ?>
                            <button type="submit" class="btn-stripe">
                                Pay <?= html_escape($order->currency); ?> <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                            </button>
                        <?= form_close(); ?>
                    </div>
                    <p class="btn-stripe-note mb-4 mb-lg-0">
                        Secure payment powered by <span class="btn-stripe-wordmark">stripe</span>
                    </p>
                <?php else: ?>
                    <div class="alert alert-light border small mb-4 mb-lg-0">
                        Payment complete. You can track this order from your orders page.
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <a href="<?= site_url('user/purchase'); ?>" class="btn btn-outline-primary">
                        View my orders
                    </a>
                    <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">
                        Continue shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>