<?php
$current_page = isset($pagination['page']) ? (int) $pagination['page'] : 1;
$total_pages = isset($pagination['total_pages']) ? (int) $pagination['total_pages'] : 1;

$build_page_url = function ($page) use ($filters) {
    $params = array();

    foreach ($filters as $key => $value) {
        if ($value !== NULL && $value !== '') {
            $params[$key] = $value;
        }
    }

    $params['page'] = $page;

    return site_url('admin/users') . '?' . http_build_query($params);
};
?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">Users</h3>
        <a href="<?= site_url('admin/users/create'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create User
        </a>
    </div>

    <div class="card-body">
        <form method="get" action="<?= site_url('admin/users'); ?>" class="row g-3 mb-4">
            <div class="col-md-3">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search name or email"
                    value="<?= html_escape(isset($filters['search']) ? $filters['search'] : ''); ?>"
                >
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    <option value="admin"<?= (isset($filters['role']) && $filters['role'] === 'admin') ? ' selected' : ''; ?>>Admin</option>
                    <option value="customer"<?= (isset($filters['role']) && $filters['role'] === 'customer') ? ' selected' : ''; ?>>Customer</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="active"<?= (isset($filters['status']) && $filters['status'] === 'active') ? ' selected' : ''; ?>>Active</option>
                    <option value="inactive"<?= (isset($filters['status']) && $filters['status'] === 'inactive') ? ' selected' : ''; ?>>Inactive</option>
                    <option value="archived"<?= (isset($filters['status']) && $filters['status'] === 'archived') ? ' selected' : ''; ?>>Archived</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="include_deleted"
                        id="include_deleted"
                        value="1"
                        <?= !empty($filters['include_deleted']) ? 'checked' : ''; ?>
                    >
                    <label class="form-check-label" for="include_deleted">Show archived users</label>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <?php
                        $empty_message = 'No users found.';

                        if (!empty($filters['status']) && $filters['status'] === 'inactive') {
                            $empty_message = 'No inactive users found. Edit a user and set Status to Inactive, or clear the filter to see all users.';
                        } elseif (!empty($filters['status']) && $filters['status'] === 'archived') {
                            $empty_message = 'No archived users found.';
                        } elseif (!empty($filters['include_deleted'])) {
                            $empty_message = 'No users match these filters, including archived accounts.';
                        } elseif (!empty($filters['search']) || !empty($filters['role']) || !empty($filters['status'])) {
                            $empty_message = 'No users match these filters.';
                        }
                        ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted"><?= html_escape($empty_message); ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php $is_deleted = !empty($user->deleted_at); ?>
                            <tr class="<?= $is_deleted ? 'table-secondary' : ''; ?>">
                                <td><?= html_escape($user->name); ?></td>
                                <td><?= html_escape($user->email); ?></td>
                                <td>
                                    <span class="badge text-bg-secondary"><?= html_escape($user->role_name); ?></span>
                                </td>
                                <td>
                                    <?php if ($is_deleted): ?>
                                        <span class="badge text-bg-danger">Archived</span>
                                    <?php elseif ($user->status === 'active'): ?>
                                        <span class="badge text-bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-warning">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ($is_deleted): ?>
                                        <a
                                            href="<?= site_url('admin/users/restore/' . $user->id . (!empty($filters['include_deleted']) ? '?include_deleted=1' : '')); ?>"
                                            class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Restore this user?');"
                                        >
                                            Restore
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('admin/users/edit/' . $user->id); ?>" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <a
                                            href="<?= site_url('admin/users/delete/' . $user->id); ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Archive this user? They can be restored later.');"
                                        >
                                            Archive
                                        </a>
                                    <?php endif; ?>
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
            <nav aria-label="Users pagination">
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
