<?php $is_edit = !empty($product); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0"><?= html_escape($page_heading); ?></h3>
    </div>

    <div class="card-body">
        <?= form_open($form_action); ?>
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input
                    type="text"
                    class="form-control"
                    id="sku"
                    name="sku"
                    value="<?= html_escape($is_edit ? ($product->sku ?: '') : ''); ?>"
                    placeholder="Optional"
                >
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= html_escape($is_edit ? $product->name : ''); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    class="form-control"
                    id="description"
                    name="description"
                    rows="4"
                ><?= html_escape($is_edit ? ($product->description ?: '') : ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input
                    type="number"
                    class="form-control"
                    id="price"
                    name="price"
                    min="0"
                    step="0.01"
                    value="<?= html_escape($is_edit ? $product->price : ''); ?>"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active"<?= ($is_edit && $product->status === 'active') ? ' selected' : (!$is_edit ? ' selected' : ''); ?>>Active</option>
                    <option value="inactive"<?= ($is_edit && $product->status === 'inactive') ? ' selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= $is_edit ? 'Update Product' : 'Create Product'; ?>
                </button>
                <a href="<?= site_url('admin/products'); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        <?= form_close(); ?>
    </div>
</div>
