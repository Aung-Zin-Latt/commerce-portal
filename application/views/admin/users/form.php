<?php $is_edit = !empty($user); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0"><?= html_escape($page_heading); ?></h3>
    </div>

    <div class="card-body">
        <?= form_open($form_action); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= html_escape($is_edit ? $user->name : ''); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= html_escape($is_edit ? $user->email : ''); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    Password<?= $is_edit ? ' <span class="text-muted">(leave blank to keep current)</span>' : ''; ?>
                </label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    <?= $is_edit ? '' : 'required'; ?>
                >
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select role</option>
                    <?php foreach ($roles as $role): ?>
                        <option
                            value="<?= html_escape($role->name); ?>"
                            <?= ($is_edit && $user->role_name === $role->name) ? 'selected' : ''; ?>
                        >
                            <?= html_escape(ucfirst($role->name)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active"<?= ($is_edit && $user->status === 'active') ? ' selected' : (!$is_edit ? ' selected' : ''); ?>>Active</option>
                    <option value="inactive"<?= ($is_edit && $user->status === 'inactive') ? ' selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= $is_edit ? 'Update User' : 'Create User'; ?>
                </button>
                <a href="<?= site_url('admin/users'); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
