<?php
$logs = isset($logs) ? $logs : array();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Audit Logs</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No audit logs yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= html_escape(date('d M Y, H:i', strtotime($log->created_at))); ?></td>
                                <td>
                                    <?php if (!empty($log->user_id)): ?>
                                        <div><?= html_escape($log->user_name); ?></div>
                                        <div class="small text-muted"><?= html_escape($log->user_email); ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">System</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code><?= html_escape($log->action); ?></code>
                                </td>
                                <td>
                                    <span class="badge text-bg-secondary"><?= html_escape($log->entity_type); ?></span>
                                    #<?= (int) $log->entity_id; ?>
                                </td>
                                <td class="small"><?= html_escape($log->ip_address); ?></td>
                                <td class="small text-muted" style="max-width: 280px;">
                                    <span class="d-inline-block text-truncate" style="max-width: 280px;" title="<?= html_escape($log->user_agent); ?>">
                                        <?= html_escape($log->user_agent); ?>
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
        'pagination_base_path' => 'admin/audit-logs',
        'pagination_aria' => 'Audit logs pagination',
    ));
    ?>
</div>
