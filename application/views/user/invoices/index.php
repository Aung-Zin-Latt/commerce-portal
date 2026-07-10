<?php
$invoices = isset($invoices) ? $invoices : array();
?>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">My Invoices</h1>
    <p class="text-muted mb-0">View invoices for your completed purchases.</p>
</div>
<?php if (empty($invoices)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-file-invoice fa-2x text-muted mb-3"></i>
            <p class="text-muted mb-3">You have no invoices yet.</p>
            <a href="<?= site_url(''); ?>" class="btn btn-primary btn-sm">Start shopping</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Issued</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr
                            class="order-row-clickable"
                            data-href="<?= site_url('user/invoices/show/' . (int) $invoice->id); ?>"
                            role="link"
                            tabindex="0"
                        >
                            <td class="fw-semibold"><?= html_escape($invoice->invoice_number); ?></td>
                            <td><?= html_escape(date('d M Y, H:i', strtotime($invoice->issued_at))); ?></td>
                            <td class="text-end">
                                <?= html_escape($invoice->currency); ?>
                                <?= html_escape(number_format((float) $invoice->total_amount, 2)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-md-none order-mobile-list">
        <?php foreach ($invoices as $invoice): ?>
            <article class="card border-0 shadow-sm order-mobile-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <div>
                            <h2 class="h6 mb-1">
                                <a href="<?= site_url('user/invoices/show/' . (int) $invoice->id); ?>" class="text-decoration-none">
                                    <?= html_escape($invoice->invoice_number); ?>
                                </a>
                            </h2>
                            <p class="text-muted small mb-0">
                                <?= html_escape(date('d M Y, H:i', strtotime($invoice->issued_at))); ?>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total</span>
                        <strong class="text-primary mb-0">
                            <?= html_escape($invoice->currency); ?>
                            <?= html_escape(number_format((float) $invoice->total_amount, 2)); ?>
                        </strong>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$this->load->view('components/list_pagination', array(
    'pagination' => isset($pagination) ? $pagination : array(),
    'pagination_base_path' => 'user/invoices',
    'pagination_aria' => 'Invoices pagination',
    'pagination_wrapper_class' => 'mt-4 d-flex justify-content-center',
    'pagination_nav_class' => 'mb-0 justify-content-center',
));
?>