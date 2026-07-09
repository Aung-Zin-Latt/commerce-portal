<?php
$transactions = isset($transactions) ? $transactions : array();
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
                        <th>Event ID</th>
                        <th>Event Type</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Session ID</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No Stripe transactions yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $row): ?>
                            <tr>
                                <td class="small"><?= html_escape($row->stripe_event_id); ?></td>
                                <td><?= html_escape($row->event_type); ?></td>
                                <td><?= (int) $row->order_id; ?></td>
                                <td>
                                    <?= html_escape($row->currency); ?>
                                    <?= html_escape(number_format((float) $row->amount, 2)); ?>
                                </td>
                                <td><?= html_escape($row->payment_status); ?></td>
                                <td class="small"><?= html_escape($row->stripe_session_id); ?></td>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($row->created_at))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>