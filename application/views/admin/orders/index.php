<?php
$orders = isset($orders) ? $orders : array();

$status_labels = array(
    'pending' => array('label' => 'Pending', 'class' => 'text-bg-warning'),
    'paid' => array('label' => 'Paid', 'class' => 'text-bg-success'),
    'failed' => array('label' => 'Failed', 'class' => 'text-bg-danger'),
    'cancelled' => array('label' => 'Cancelled', 'class' => 'text-bg-secondary'),
    'refunded' => array('label' => 'Refunded', 'class' => 'text-bg-info'),
);

$payment_labels = array(
    'stripe' => array('label' => 'Stripe', 'class' => 'text-bg-primary'),
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
                        <th>Payment</th>
                        <th class="text-end">Total</th>
                        <th>Placed</th>
                        <th class="text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No orders yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $status = isset($status_labels[$order->status])
                                ? $status_labels[$order->status]
                                : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');

                            $providerKey = isset($order->payment_provider) ? strtolower((string) $order->payment_provider) : '';
                            $payment = ($providerKey !== '' && isset($payment_labels[$providerKey]))
                                ? $payment_labels[$providerKey]
                                : ($providerKey !== ''
                                    ? array('label' => ucfirst($providerKey), 'class' => 'text-bg-secondary')
                                    : null);
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
                                <td>
                                    <?php if ($payment): ?>
                                        <span class="badge <?= html_escape($payment['class']); ?>">
                                            <?= html_escape($payment['label']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
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
    <?php
    $this->load->view('components/list_pagination', array(
        'pagination' => isset($pagination) ? $pagination : array(),
        'pagination_base_path' => 'admin/orders',
        'pagination_aria' => 'Orders pagination',
    ));
    ?>
</div>
