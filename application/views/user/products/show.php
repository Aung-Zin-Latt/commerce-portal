<?php
$product = isset($product) ? $product : null;

if (!$product) {
    return;
}
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= html_escape($product->name); ?>
        </li>
    </ol>
</nav>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="store-product-image-wrap rounded">
                    <img
                        src="<?= product_image_url($product); ?>"
                        class="store-product-detail-image w-100 rounded"
                        alt="<?= html_escape($product->name); ?>"
                    >
                </div>
            </div>
            <div class="col-md-7 d-flex flex-column">
                <h1 class="h4 mb-2"><?= html_escape($product->name); ?></h1>
                <?php if (!empty($product->sku)): ?>
                    <p class="text-muted small mb-2">SKU: <?= html_escape($product->sku); ?></p>
                <?php endif; ?>
                <p class="store-product-price fs-4 mb-3">
                    $<?= html_escape(number_format((float) $product->price, 2)); ?>
                </p>
                <?php if (!empty($product->description)): ?>
                    <div class="mb-4">
                        <h2 class="h6 text-muted mb-2">Description</h2>
                        <p class="mb-0"><?= nl2br(html_escape($product->description)); ?></p>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4">No description available.</p>
                <?php endif; ?>
                <?= form_open('user/cart/add', array('class' => 'mt-auto')); ?>
                    <input type="hidden" name="product_id" value="<?= (int) $product->id; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cart-plus me-1"></i>Add to cart
                    </button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>