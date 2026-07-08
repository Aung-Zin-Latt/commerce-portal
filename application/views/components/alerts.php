<?php if (!empty($alerts)): ?>
    <?php foreach ($alerts as $alert): ?>
        <?php
        $type = isset($alert['type']) ? $alert['type'] : 'info';
        $message = isset($alert['message']) ? $alert['message'] : '';
        ?>
        <div class="alert alert-<?= html_escape($type); ?> alert-dismissible fade show" role="alert">
            <?= html_escape($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>