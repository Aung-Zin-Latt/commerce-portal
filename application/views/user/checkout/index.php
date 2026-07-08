<?php
$checkout = isset($checkout) ? $checkout : array();
$items = isset($checkout['items']) ? $checkout['items'] : array();
$subtotal = isset($checkout['subtotal']) ? (float) $checkout['subtotal'] : 0.0;
$tax = isset($checkout['tax']) ? (float) $checkout['tax'] : 0.0;
$total = isset($checkout['total']) ? (float) $checkout['total'] : 0.0;
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= site_url('user/cart'); ?>">Cart</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
    </ol>
</nav>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">Checkout</h1>
    <p class="text-muted mb-0">Review your order before placing it.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
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
                            <?php $lineSubtotal = (float) $item['price'] * (int) $item['quantity']; ?>
                            <tr>
                                <td><?= html_escape($item['name']); ?></td>
                                <td class="text-end">$<?= html_escape(number_format((float) $item['price'], 2)); ?></td>
                                <td class="text-center"><?= (int) $item['quantity']; ?></td>
                                <td class="text-end">$<?= html_escape(number_format($lineSubtotal, 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-md-none">
            <?php foreach ($items as $item): ?>
                <?php $lineSubtotal = (float) $item['price'] * (int) $item['quantity']; ?>
                <article class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="min-w-0">
                                <h2 class="h6 mb-1 text-truncate"><?= html_escape($item['name']); ?></h2>
                                <p class="text-muted small mb-0">
                                    $<?= html_escape(number_format((float) $item['price'], 2)); ?> × <?= (int) $item['quantity']; ?>
                                </p>
                            </div>
                            <strong class="text-primary flex-shrink-0">
                                $<?= html_escape(number_format($lineSubtotal, 2)); ?>
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
                    <span>$<?= html_escape(number_format($subtotal, 2)); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax</span>
                    <span>$<?= html_escape(number_format($tax, 2)); ?></span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Total</span>
                    <span class="fs-5 fw-bold text-primary mb-0">$<?= html_escape(number_format($total, 2)); ?></span>
                </div>
                <div class="alert alert-light border small mb-4 mb-lg-0">
                    Payment will be added in a later phase. Your order will be created with status <strong>Pending</strong>.
                </div>
                <div class="d-grid gap-2 my-2">
                    <?= form_open('user/checkout/place'); ?>
                        <button type="submit" class="btn btn-primary w-100">
                            Place order
                        </button>
                    <?= form_close(); ?>
                    <a href="<?= site_url('user/cart'); ?>" class="btn btn-outline-secondary">
                        Back to cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>