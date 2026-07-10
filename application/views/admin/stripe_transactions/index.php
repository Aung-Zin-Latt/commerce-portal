<?php
$transactions = isset($transactions) ? $transactions : array();

$status_labels = array(
    'paid' => array('label' => 'Paid', 'class' => 'text-bg-success'),
    'pending' => array('label' => 'Pending', 'class' => 'text-bg-warning'),
    'unpaid' => array('label' => 'Unpaid', 'class' => 'text-bg-warning'),
    'failed' => array('label' => 'Failed', 'class' => 'text-bg-danger'),
    'cancelled' => array('label' => 'Cancelled', 'class' => 'text-bg-secondary'),
    'refunded' => array('label' => 'Refunded', 'class' => 'text-bg-info'),
);

$event_labels = array(
    'checkout.session.completed' => 'Checkout completed',
    'checkout.session.expired' => 'Checkout expired',
    'payment_intent.succeeded' => 'Payment succeeded',
    'payment_intent.payment_failed' => 'Payment failed',
);
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Stripe Transactions</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Order</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Event</th>
                        <th>Session</th>
                        <th>Event ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No Stripe transactions yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $row): ?>
                            <?php
                            $statusKey = strtolower((string) $row->payment_status);
                            $status = isset($status_labels[$statusKey])
                                ? $status_labels[$statusKey]
                                : array('label' => ucfirst((string) $row->payment_status), 'class' => 'text-bg-secondary');

                            $eventType = (string) $row->event_type;
                            $eventLabel = isset($event_labels[$eventType])
                                ? $event_labels[$eventType]
                                : $eventType;

                            $isSync = strpos((string) $row->stripe_event_id, 'sync_') === 0;
                            $orderLabel = !empty($row->order_number)
                                ? $row->order_number
                                : ('#' . (int) $row->order_id);
                            ?>
                            <tr>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($row->created_at))); ?></td>
                                <td class="fw-semibold">
                                    <?php if (!empty($row->order_id)): ?>
                                        <a href="<?= site_url('admin/orders/show/' . (int) $row->order_id); ?>">
                                            <?= html_escape($orderLabel); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?= html_escape($row->currency); ?>
                                    <?= html_escape(number_format((float) $row->amount, 2)); ?>
                                </td>
                                <td>
                                    <span class="badge <?= html_escape($status['class']); ?>">
                                        <?= html_escape($status['label']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($isSync): ?>
                                        <span class="badge text-bg-info">Sync</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-primary">Webhook</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span title="<?= html_escape($eventType); ?>">
                                        <?= html_escape($eventLabel); ?>
                                    </span>
                                </td>
                                <td class="small" style="max-width: 180px;">
                                    <span class="d-inline-block text-truncate font-monospace" style="max-width: 180px;" title="<?= html_escape($row->stripe_session_id); ?>">
                                        <?= html_escape($row->stripe_session_id); ?>
                                    </span>
                                </td>
                                <td class="small" style="max-width: 180px;">
                                    <span class="d-inline-block text-truncate font-monospace" style="max-width: 180px;" title="<?= html_escape($row->stripe_event_id); ?>">
                                        <?= html_escape($row->stripe_event_id); ?>
                                    </span>
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
        'pagination_base_path' => 'admin/stripe-transactions',
        'pagination_aria' => 'Stripe transactions pagination',
    ));
    ?>
</div>
