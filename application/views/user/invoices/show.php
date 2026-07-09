<?php
$invoice = isset($invoice) ? $invoice : null;
$items = isset($items) ? $items : array();

if (!$invoice) {
    return;
}
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?= site_url(''); ?>">Shop</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= site_url('user/invoices'); ?>">Invoices</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= html_escape($invoice->invoice_number); ?>
        </li>
    </ol>
</nav>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">Invoice details</h1>
    <p class="text-muted mb-0">View your issued invoice and line items below.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <p class="text-muted small mb-1">Invoice number</p>
                        <p class="h5 mb-0"><?= html_escape($invoice->invoice_number); ?></p>
                    </div>
                    <span class="badge text-bg-success">Issued</span>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Issued on <?= html_escape(date('d M Y, H:i', strtotime($invoice->issued_at))); ?>
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
                                    <?= html_escape($invoice->currency); ?>
                                    <?= html_escape(number_format((float) $item->unit_price, 2)); ?>
                                </td>
                                <td class="text-center"><?= (int) $item->quantity; ?></td>
                                <td class="text-end">
                                    <?= html_escape($invoice->currency); ?>
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
                                    <?= html_escape($invoice->currency); ?>
                                    <?= html_escape(number_format((float) $item->unit_price, 2)); ?>
                                    × <?= (int) $item->quantity; ?>
                                </p>
                            </div>
                            <strong class="text-primary flex-shrink-0">
                                <?= html_escape($invoice->currency); ?>
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
                <h2 class="h6 mb-3">Invoice summary</h2>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>
                        <?= html_escape($invoice->currency); ?>
                        <?= html_escape(number_format((float) $invoice->subtotal, 2)); ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax</span>
                    <span>
                        <?= html_escape($invoice->currency); ?>
                        <?= html_escape(number_format((float) $invoice->tax_amount, 2)); ?>
                    </span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Total</span>
                    <span class="fs-5 fw-bold text-primary mb-0">
                        <?= html_escape($invoice->currency); ?>
                        <?= html_escape(number_format((float) $invoice->total_amount, 2)); ?>
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= site_url('user/purchase/show/' . (int) $invoice->order_id); ?>" class="btn btn-outline-primary">
                        View related order
                    </a>
                    <a href="<?= site_url('user/invoices'); ?>" class="btn btn-outline-secondary">
                        Back to invoices
                    </a>
                    <a href="<?= site_url(''); ?>" class="btn btn-outline-secondary">
                        Continue shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
