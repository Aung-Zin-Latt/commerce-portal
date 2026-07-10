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

<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1">My Orders</h1>
    <p class="text-muted mb-0">Track your order history and payment status.</p>
</div>
<?php if (empty($orders)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
            <p class="text-muted mb-3">You have no orders yet.</p>
            <a href="<?= site_url(''); ?>" class="btn btn-primary btn-sm">Start shopping</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <?php
                        $status = isset($status_labels[$order->status])
                            ? $status_labels[$order->status]
                            : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');
                        ?>
                        <tr
                            class="order-row-clickable"
                            data-href="<?= site_url('user/purchase/show/' . (int) $order->id); ?>"
                            role="link"
                            tabindex="0"
                        >
                            <td class="fw-semibold"><?= html_escape($order->order_number); ?></td>
                            <td><?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?></td>
                            <td>
                                <span class="badge <?= html_escape($status['class']); ?>">
                                    <?= html_escape($status['label']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?= html_escape($order->currency); ?>
                                <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-md-none order-mobile-list">
        <?php foreach ($orders as $order): ?>
            <?php
            $status = isset($status_labels[$order->status])
                ? $status_labels[$order->status]
                : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');
            ?>
            <article class="card border-0 shadow-sm order-mobile-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <div>
                            <h2 class="h6 mb-1">
                                <a href="<?= site_url('user/purchase/show/' . (int) $order->id); ?>" class="text-decoration-none">
                                    <?= html_escape($order->order_number); ?>
                                </a>
                            </h2>
                            <p class="text-muted small mb-0">
                                <?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?>
                            </p>
                        </div>
                        <span class="badge <?= html_escape($status['class']); ?>">
                            <?= html_escape($status['label']); ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total</span>
                        <strong class="text-primary mb-0">
                            <?= html_escape($order->currency); ?>
                            <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
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
    'pagination_base_path' => 'user/purchase',
    'pagination_aria' => 'Orders pagination',
    'pagination_wrapper_class' => 'mt-4 d-flex justify-content-center',
    'pagination_nav_class' => 'mb-0 justify-content-center',
));
?>