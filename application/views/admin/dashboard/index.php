<?php
$summary = isset($summary) ? $summary : array();

$recent_orders = isset($summary['recent_orders']) ? $summary['recent_orders'] : array();
$recent_audit_logs = isset($summary['recent_audit_logs']) ? $summary['recent_audit_logs'] : array();

$status_labels = array(
    'pending' => array('label' => 'Pending', 'class' => 'text-bg-warning'),
    'paid' => array('label' => 'Paid', 'class' => 'text-bg-success'),
    'failed' => array('label' => 'Failed', 'class' => 'text-bg-danger'),
    'cancelled' => array('label' => 'Cancelled', 'class' => 'text-bg-secondary'),
    'refunded' => array('label' => 'Refunded', 'class' => 'text-bg-info'),
);
?>

<div class="row g-3 dashboard-stat-row mb-3">
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/orders'); ?>" class="small-box text-bg-primary text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['total_orders']) ? $summary['total_orders'] : 0); ?></h3>
                <p>Total Orders<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/orders'); ?>" class="small-box text-bg-success text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['paid_orders']) ? $summary['paid_orders'] : 0); ?></h3>
                <p>Paid Orders<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/orders'); ?>" class="small-box text-bg-info text-decoration-none">
            <div class="inner">
                <h3><?= html_escape(number_format((float) (isset($summary['revenue']) ? $summary['revenue'] : 0), 2)); ?></h3>
                <p>Paid Revenue (SGD)<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/orders'); ?>" class="small-box text-bg-warning text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['pending_orders']) ? $summary['pending_orders'] : 0); ?></h3>
                <p>Pending Orders<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-clock"></i>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 dashboard-stat-row mb-4">
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/users'); ?>" class="small-box text-bg-secondary text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['total_users']) ? $summary['total_users'] : 0); ?></h3>
                <p>
                    Users
                    <small><?= (int) (isset($summary['active_users']) ? $summary['active_users'] : 0); ?> active</small>
                </p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-users"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/products'); ?>" class="small-box text-bg-secondary text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['total_products']) ? $summary['total_products'] : 0); ?></h3>
                <p>
                    Products
                    <small><?= (int) (isset($summary['active_products']) ? $summary['active_products'] : 0); ?> active</small>
                </p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-box"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/invoices'); ?>" class="small-box text-bg-secondary text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['total_invoices']) ? $summary['total_invoices'] : 0); ?></h3>
                <p>Invoices<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= site_url('admin/receipts'); ?>" class="small-box text-bg-secondary text-decoration-none">
            <div class="inner">
                <h3><?= (int) (isset($summary['total_receipts']) ? $summary['total_receipts'] : 0); ?></h3>
                <p>Receipts<small>&nbsp;</small></p>
            </div>
            <div class="small-box-icon">
                <i class="fas fa-receipt"></i>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 dashboard-activity">
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <h3 class="card-title mb-0">Recent Orders</h3>
                <a href="<?= site_url('admin/orders'); ?>" class="btn btn-outline-secondary btn-sm">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="d-none d-md-block table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_orders)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_orders as $order): ?>
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
                                            <div class="small text-muted d-none d-lg-block"><?= html_escape($order->customer_email); ?></div>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none">
                    <?php if (empty($recent_orders)): ?>
                        <p class="text-center text-muted py-4 mb-0">No orders yet.</p>
                    <?php else: ?>
                        <?php foreach ($recent_orders as $order): ?>
                            <?php
                            $status = isset($status_labels[$order->status])
                                ? $status_labels[$order->status]
                                : array('label' => ucfirst($order->status), 'class' => 'text-bg-secondary');
                            ?>
                            <a href="<?= site_url('admin/orders/show/' . (int) $order->id); ?>" class="dashboard-mobile-item text-decoration-none text-body">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate"><?= html_escape($order->order_number); ?></div>
                                        <div class="small text-muted text-truncate"><?= html_escape($order->customer_name); ?></div>
                                    </div>
                                    <span class="badge <?= html_escape($status['class']); ?> flex-shrink-0">
                                        <?= html_escape($status['label']); ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="small text-muted"><?= html_escape(date('d M Y, H:i', strtotime($order->created_at))); ?></span>
                                    <strong>
                                        <?= html_escape($order->currency); ?>
                                        <?= html_escape(number_format((float) $order->total_amount, 2)); ?>
                                    </strong>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                <h3 class="card-title mb-0">Recent Audit Logs</h3>
                <a href="<?= site_url('admin/audit-logs'); ?>" class="btn btn-outline-secondary btn-sm">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="d-none d-md-block table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_audit_logs)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No audit logs yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_audit_logs as $log): ?>
                                    <tr>
                                        <td><?= html_escape(date('d M Y, H:i', strtotime($log->created_at))); ?></td>
                                        <td>
                                            <?php if (!empty($log->user_id)): ?>
                                                <?= html_escape($log->user_name); ?>
                                            <?php else: ?>
                                                <span class="text-muted">System</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?= html_escape($log->action); ?></code></td>
                                        <td>
                                            <span class="badge text-bg-secondary"><?= html_escape($log->entity_type); ?></span>
                                            #<?= (int) $log->entity_id; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none">
                    <?php if (empty($recent_audit_logs)): ?>
                        <p class="text-center text-muted py-4 mb-0">No audit logs yet.</p>
                    <?php else: ?>
                        <?php foreach ($recent_audit_logs as $log): ?>
                            <div class="dashboard-mobile-item">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="min-w-0">
                                        <code class="small"><?= html_escape($log->action); ?></code>
                                        <div class="small text-muted mt-1">
                                            <?php if (!empty($log->user_id)): ?>
                                                <?= html_escape($log->user_name); ?>
                                            <?php else: ?>
                                                System
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge text-bg-secondary flex-shrink-0">
                                        <?= html_escape($log->entity_type); ?> #<?= (int) $log->entity_id; ?>
                                    </span>
                                </div>
                                <div class="small text-muted mt-2">
                                    <?= html_escape(date('d M Y, H:i', strtotime($log->created_at))); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
