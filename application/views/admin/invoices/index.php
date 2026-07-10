<?php
$invoices = isset($invoices) ? $invoices : array();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Invoices</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Order #</th>
                        <th class="text-end">Total</th>
                        <th>Issued</th>
                        <th class="text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No invoices yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($invoices as $invoice): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <a href="<?= site_url('admin/invoices/show/' . (int) $invoice->id); ?>">
                                        <?= html_escape($invoice->invoice_number); ?>
                                    </a>
                                </td>
                                <td>
                                    <div><?= html_escape($invoice->customer_name); ?></div>
                                    <div class="small text-muted"><?= html_escape($invoice->customer_email); ?></div>
                                </td>
                                <td><?= html_escape($invoice->order_number); ?></td>
                                <td class="text-end">
                                    <?= html_escape($invoice->currency); ?>
                                    <?= html_escape(number_format((float) $invoice->total_amount, 2)); ?>
                                </td>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($invoice->issued_at))); ?></td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/invoices/show/' . (int) $invoice->id); ?>" class="btn btn-outline-primary btn-sm">
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
        'pagination_base_path' => 'admin/invoices',
        'pagination_aria' => 'Invoices pagination',
    ));
    ?>
</div>