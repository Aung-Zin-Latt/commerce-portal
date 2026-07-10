<?php
$receipts = isset($receipts) ? $receipts : array();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Receipts</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Customer</th>
                        <th>Order #</th>
                        <th class="text-end">Amount</th>
                        <th>Issued</th>
                        <th class="text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($receipts)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No receipts yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($receipts as $receipt): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <a href="<?= site_url('admin/receipts/show/' . (int) $receipt->id); ?>">
                                        <?= html_escape($receipt->receipt_number); ?>
                                    </a>
                                </td>
                                <td>
                                    <div><?= html_escape($receipt->customer_name); ?></div>
                                    <div class="small text-muted"><?= html_escape($receipt->customer_email); ?></div>
                                </td>
                                <td><?= html_escape($receipt->order_number); ?></td>
                                <td class="text-end">
                                    <?= html_escape($receipt->currency); ?>
                                    <?= html_escape(number_format((float) $receipt->amount, 2)); ?>
                                </td>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($receipt->issued_at))); ?></td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/receipts/show/' . (int) $receipt->id); ?>" class="btn btn-outline-primary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $this->load->view('components/list_pagination', array(
        'pagination' => isset($pagination) ? $pagination : array(),
        'pagination_base_path' => 'admin/receipts',
        'pagination_aria' => 'Receipts pagination',
    ));
    ?>
</div>
