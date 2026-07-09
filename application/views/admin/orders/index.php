<?php
$orders = isset($orders) ? $orders : array();

$status_labels = array(
    'pending' => array('label' => 'Pending', 'class' => 'text-bg-warning'),
    'paid' => array('label' => 'Paid', 'class' => 'text-bg-success'),
    'failed' => array('label' => 'Failed', 'class' => 'text-bg-danger'),
    'cancelled' => array('label' => 'Cancelled', 'class' => 'text-bg-secondary'),
    'refunded' => array('label' => 'Refunded', 'class' => 'text-bg-info'),
);
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Orders</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th>Placed</th>
                        <th class="text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No orders yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $status = isset($status_labels[$order->status])
                                ? $status_labels[$order->status]
                                : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');
                            ?>
                            <tr>
                                <td class="fw-semibold">
                                    <a href="<?= site_url('admin/orders/show/' . (int) $order->id); ?>">
                                        <?= html_escape($order->order_number); ?>
                                    </a>
                                </td>
                                <td>
                                    <div><?= html_escape($order->customer_name); ?></div>
                                    <div class="small text-muted"><?= html_escape($order->customer_email); ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= html_escape($status['class']); ?>">
                                        <?= html_escape($status['label']); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <?= html_escape($order->currency); ?>
                                    <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                                </td>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?></td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/orders/show/' . (int) $order->id); ?>" class="btn btn-outline-primary btn-sm">
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
</div>
