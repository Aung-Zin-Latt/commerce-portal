<?php
$current_page = isset($pagination['page']) ? (int) $pagination['page'] : 1;
$total_pages = isset($pagination['total_pages']) ? (int) $pagination['total_pages'] : 1;

$build_page_url = function ($page) use ($filters) {
    $params = array_filter($filters, function ($value) {
        return $value !== NULL && $value !== '';
    });
    $params['page'] = $page;

    return site_url('admin/products') . '?' . http_build_query($params);
};
?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">Products</h3>
        <a href="<?= site_url('admin/products/create'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create Product
        </a>
    </div>

    <div class="card-body">
        <form method="get" action="<?= site_url('admin/products'); ?>" class="row g-3 mb-4">
            <div class="col-md-5">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search name or SKU"
                    value="<?= html_escape(isset($filters['search']) ? $filters['search'] : ''); ?>"
                >
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="active"<?= (isset($filters['status']) && $filters['status'] === 'active') ? ' selected' : ''; ?>>Active</option>
                    <option value="inactive"<?= (isset($filters['status']) && $filters['status'] === 'inactive') ? ' selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No products found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= html_escape($product->sku ?: '-'); ?></td>
                                <td><?= html_escape($product->name); ?></td>
                                <td><?= html_escape(number_format((float) $product->price, 2)); ?></td>
                                <td>
                                    <?php if ($product->status === 'active'): ?>
                                        <span class="badge text-bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-warning">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url('admin/products/edit/' . $product->id); ?>" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <a
                                        href="<?= site_url('admin/products/delete/' . $product->id); ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this product?');"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Products pagination">
                <ul class="pagination mb-0">
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
    <?php endif; ?>
</div>
