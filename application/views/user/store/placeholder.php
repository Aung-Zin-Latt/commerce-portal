<div class="store-page-intro mb-4">
    <h1 class="h4 mb-1"><?= html_escape(isset($page_heading) ? $page_heading : 'Page'); ?></h1>
    <?php if (!empty($page_description)): ?>
        <p class="text-muted mb-0"><?= html_escape($page_description); ?></p>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body py-5 text-center">
        <i class="fas fa-tools fa-2x text-muted mb-3"></i>
        <p class="text-muted mb-0">
            <?= html_escape(isset($page_description) ? $page_description : 'This page is ready for implementation.'); ?>
        </p>
    </div>
</div>