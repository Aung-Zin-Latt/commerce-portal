<?php
$receipts = isset($receipts) ? $receipts : array();
?>

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">My Receipts</h1>
    <p class="text-muted mb-0">Payment confirmations for your completed purchases.</p>
</div>
<?php if (empty($receipts)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
            <p class="text-muted mb-3">You have no receipts yet.</p>
            <a href="<?= site_url(''); ?>" class="btn btn-primary btn-sm">Start shopping</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Issued</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($receipts as $receipt): ?>
                        <tr
                            class="order-row-clickable"
                            data-href="<?= site_url('user/receipts/show/' . (int) $receipt->id); ?>"
                            role="link"
                            tabindex="0"
                        >
                            <td class="fw-semibold"><?= html_escape($receipt->receipt_number); ?></td>
                            <td><?= html_escape(date('d M Y, H:i', strtotime($receipt->issued_at))); ?></td>
                            <td class="text-end">
                                <?= html_escape($receipt->currency); ?>
                                <?= html_escape(number_format((float) $receipt->amount, 2)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-md-none order-mobile-list">
        <?php foreach ($receipts as $receipt): ?>
            <article class="card border-0 shadow-sm order-mobile-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <div>
                            <h2 class="h6 mb-1">
                                <a href="<?= site_url('user/receipts/show/' . (int) $receipt->id); ?>" class="text-decoration-none">
                                    <?= html_escape($receipt->receipt_number); ?>
                                </a>
                            </h2>
                            <p class="text-muted small mb-0">
                                <?= html_escape(date('d M Y, H:i', strtotime($receipt->issued_at))); ?>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Amount</span>
                        <strong class="text-primary mb-0">
                            <?= html_escape($receipt->currency); ?>
                            <?= html_escape(number_format((float) $receipt->amount, 2)); ?>
                        </strong>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>