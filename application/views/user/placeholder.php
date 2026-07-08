<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= html_escape(isset($page_heading) ? $page_heading : 'User Page'); ?></h3>
    </div>

    <div class="card-body">
        <p class="mb-0">
            <?= html_escape(isset($page_description) ? $page_description : 'This user page is ready for implementation.'); ?>
        </p>
    </div>
</div>