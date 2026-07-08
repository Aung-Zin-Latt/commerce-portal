<?php
$productId = (int) $item['product_id'];
$quantity = (int) $item['quantity'];
$stepperClass = isset($stepper_class) ? $stepper_class : '';
?>

<div class="cart-qty-stepper <?= html_escape($stepperClass); ?>">
    <?= form_open('user/cart/decrease/' . $productId, array('class' => 'cart-qty-stepper-form')); ?>
        <button type="submit" class="btn cart-qty-btn" aria-label="Decrease quantity">
            <i class="fas fa-minus"></i>
        </button>
    <?= form_close(); ?>

    <span class="cart-qty-value" aria-label="Quantity"><?= $quantity; ?></span>

    <?= form_open('user/cart/increase/' . $productId, array('class' => 'cart-qty-stepper-form')); ?>
        <button type="submit" class="btn cart-qty-btn" aria-label="Increase quantity">
            <i class="fas fa-plus"></i>
        </button>
    <?= form_close(); ?>
</div>
