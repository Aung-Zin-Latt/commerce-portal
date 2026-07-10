<?php
$pagination = isset($pagination) && is_array($pagination) ? $pagination : array();
$current_page = isset($pagination['page']) ? (int) $pagination['page'] : 1;
$total_pages = isset($pagination['total_pages']) ? (int) $pagination['total_pages'] : 1;

if ($total_pages <= 1) {
    return;
}

$base_path = isset($pagination_base_path) ? $pagination_base_path : '';
$query = isset($pagination_query) && is_array($pagination_query) ? $pagination_query : array();
$aria = isset($pagination_aria) ? $pagination_aria : 'Pagination';
$wrapper_class = isset($pagination_wrapper_class) ? $pagination_wrapper_class : 'card-footer';
$nav_class = isset($pagination_nav_class) ? $pagination_nav_class : 'mb-0';

$build_page_url = function ($page) use ($base_path, $query) {
    $params = $query;
    $params['page'] = (int) $page;

    return site_url($base_path) . '?' . http_build_query($params);
};
?>

<div class="<?= html_escape($wrapper_class); ?>">
    <nav aria-label="<?= html_escape($aria); ?>">
        <ul class="pagination <?= html_escape($nav_class); ?>">
            <li class="page-item<?= $current_page <= 1 ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $current_page > 1 ? $build_page_url($current_page - 1) : '#'; ?>">Previous</a>
            </li>

            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <li class="page-item<?= $page === $current_page ? ' active' : ''; ?>">
                    <a class="page-link" href="<?= $build_page_url($page); ?>"><?= $page; ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item<?= $current_page >= $total_pages ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $current_page < $total_pages ? $build_page_url($current_page + 1) : '#'; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>