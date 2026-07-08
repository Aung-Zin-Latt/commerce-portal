<?php if (!empty($breadcrumbs)): ?>
    <ol class="breadcrumb float-sm-end mb-0">
        <?php foreach ($breadcrumbs as $label => $url): ?>
            <?php if ($url === NULL): ?>
                <li class="breadcrumb-item active" aria-current="page"><?= html_escape($label); ?></li>
            <?php else: ?>
                <li class="breadcrumb-item">
                    <a href="<?= site_url($url); ?>"><?= html_escape($label); ?></a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>