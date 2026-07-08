<?php
$cart = isset($cart) ? $cart : array();
$items = isset($cart['items']) ? $cart['items'] : array();
$subtotal = isset($cart['subtotal']) ? (float) $cart['subtotal'] : 0.0;
$is_empty = !empty($cart['is_empty']);
?>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">Shopping Cart</h1>
    <p class="text-muted mb-0">Review your items before checkout.</p>
</div>

<?php if ($is_empty): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-shopping-cart fa-2x text-muted mb-3"></i>
            <p class="text-muted mb-3">Your cart is empty.</p>
            <a href="<?= site_url(''); ?>" class="btn btn-primary btn-sm">Continue shopping</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table align-middle mb-0 cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Price</th>
                        <th class="text-center" style="width: 140px;">Qty</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end" style="width: 110px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php $lineSubtotal = (float) $item['price'] * (int) $item['quantity']; ?>
                        <tr>
                            <td><?= html_escape($item['name']); ?></td>
                            <td class="text-end">$<?= html_escape(number_format((float) $item['price'], 2)); ?></td>
                            <td class="text-center">
                                <?php $this->load->view('components/cart_qty_stepper', array('item' => $item)); ?>
                            </td>
                            <td class="text-end">$<?= html_escape(number_format($lineSubtotal, 2)); ?></td>
                            <td class="text-end">
                                <a
                                    href="<?= site_url('user/cart/remove/' . (int) $item['product_id']); ?>"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Remove this item from your cart?');"
                                >
                                    Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">$<?= html_escape(number_format($subtotal, 2)); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-md-none cart-mobile-list">
        <?php foreach ($items as $item): ?>
            <?php $lineSubtotal = (float) $item['price'] * (int) $item['quantity']; ?>
            <article class="card border-0 shadow-sm cart-mobile-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div class="min-w-0">
                            <h2 class="h6 mb-1 text-truncate"><?= html_escape($item['name']); ?></h2>
                            <p class="text-muted small mb-0">
                                $<?= html_escape(number_format((float) $item['price'], 2)); ?> each
                            </p>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="text-muted small d-block">Subtotal</span>
                            <strong class="text-primary">$<?= html_escape(number_format($lineSubtotal, 2)); ?></strong>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <span class="text-muted small">Quantity</span>
                        <?php $this->load->view('components/cart_qty_stepper', array(
                            'item' => $item,
                            'stepper_class' => 'cart-qty-stepper-mobile',
                        )); ?>
                    </div>

                    <div class="mt-3">
                        <a
                            href="<?= site_url('user/cart/remove/' . (int) $item['product_id']); ?>"
                            class="btn btn-outline-danger btn-sm w-100"
                            onclick="return confirm('Remove this item from your cart?');"
                        >
                            Remove
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="card border-0 shadow-sm cart-mobile-total">
            <div class="card-body d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Total</span>
                <span class="fs-5 fw-bold text-primary mb-0">$<?= html_escape(number_format($subtotal, 2)); ?></span>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mt-4">
        <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">Continue shopping</a>
        <a href="<?= site_url('user/checkout'); ?>" class="btn btn-primary">Proceed to checkout</a>
    </div>
<?php endif; ?>
