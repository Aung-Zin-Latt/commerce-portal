<?php
$categories = array('All', 'Electronics', 'Fashion', 'Home', 'Beauty', 'Sports');
$promo_banners = array(
    array(
        'title' => 'New Arrivals',
        'subtitle' => 'Discover the latest products this week',
        'badge' => 'Hot',
        'color' => 'primary',
    ),
    array(
        'title' => 'Free Shipping',
        'subtitle' => 'On orders above $50',
        'badge' => 'Deal',
        'color' => 'success',
    ),
);

$filters = isset($filters) ? $filters : array();
$products = isset($products) ? $products : array();
$pagination = isset($pagination) ? $pagination : array();

$build_page_url = function ($page) use ($filters) {
    $params = array();

    if (!empty($filters['search'])) {
        $params['search'] = $filters['search'];
    }

    $params['page'] = $page;
    return site_url('') . '?' . http_build_query($params);
};

$current_page = isset($pagination['page']) ? (int) $pagination['page'] : 1;
$total_pages = isset($pagination['total_pages']) ? (int) $pagination['total_pages'] : 1;
?>

<section class="store-hero mb-4">
    <div class="row g-3">
        <?php foreach ($promo_banners as $banner): ?>
            <div class="col-md-6">
                <div class="store-hero-card store-hero-card-<?= html_escape($banner['color']); ?>">
                    <div>
                        <span class="badge text-bg-light mb-2"><?= html_escape($banner['badge']); ?></span>
                        <h2 class="h4 mb-1"><?= html_escape($banner['title']); ?></h2>
                        <p class="mb-0 opacity-75"><?= html_escape($banner['subtitle']); ?></p>
                    </div>
                    <button type="button" class="btn btn-light btn-sm" disabled>Shop now</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="store-categories mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 mb-0">Categories</h2>
    </div>
    <div class="store-category-list d-flex flex-wrap gap-2">
        <?php foreach ($categories as $index => $category): ?>
            <button
                type="button"
                class="btn btn-sm <?= $index === 0 ? 'btn-primary' : 'btn-outline-secondary'; ?> rounded-pill"
                disabled
            >
                <?= html_escape($category); ?>
            </button>
        <?php endforeach; ?>
    </div>
</section>

<section class="store-products">
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
        <h2 class="h5 mb-0">
            <?php if (!empty($filters['search'])): ?>
                Results for "<?= html_escape($filters['search']); ?>"
            <?php else: ?>
                Recommended for you
            <?php endif; ?>
        </h2>
        <?php if (!empty($pagination['total'])): ?>
            <span class="text-muted small">
                <?= (int) $pagination['total']; ?> product<?= (int) $pagination['total'] === 1 ? '' : 's'; ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if (empty($products)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                <p class="text-muted mb-0">
                    <?php if (!empty($filters['search'])): ?>
                        No products found for your search.
                    <?php else: ?>
                        No products available right now. Please check back later.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <article class="card store-product-card h-100">
                        <a href="<?= site_url('user/products/' . (int) $product->id); ?>" class="store-product-image-wrap d-block text-decoration-none">
                            <img
                                src="<?= product_image_url($product); ?>"
                                class="card-img-top store-product-image"
                                alt="<?= html_escape($product->name); ?>"
                            >
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h6 mb-2 text-truncate" title="<?= html_escape($product->name); ?>">
                                <?= html_escape($product->name); ?>
                            </h3>
                            <?php if (!empty($product->sku)): ?>
                                <p class="text-muted small mb-2"><?= html_escape($product->sku); ?></p>
                            <?php endif; ?>
                            <p class="store-product-price mb-3">
                                $<?= html_escape(number_format((float) $product->price, 2)); ?>
                            </p>
                            <div class="d-grid gap-2 mt-auto store-product-actions">
                                <a
                                    href="<?= site_url('user/products/' . (int) $product->id); ?>"
                                    class="btn btn-outline-secondary btn-sm"
                                >
                                    View details
                                </a>
                                <?= form_open('user/cart/add', array('class' => 'mb-0')); ?>
                                    <input type="hidden" name="product_id" value="<?= (int) $product->id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-cart-plus me-1"></i>Add to cart
                                    </button>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav class="mt-4" aria-label="Products pagination">
                <ul class="pagination justify-content-center mb-0">
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
        <?php endif; ?>
    <?php endif; ?>
</section>