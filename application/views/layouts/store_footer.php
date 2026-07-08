<footer class="store-footer mt-auto">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <h6 class="fw-semibold mb-2">Commerce Portal</h6>
                <p class="text-muted small mb-0">
                    Simple shopping experience for customers. Browse, cart, and checkout — all in one place.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-2">Customer</h6>
                <ul class="list-unstyled small mb-0">
                    <li><a href="<?= site_url(''); ?>" class="store-footer-link">Shop</a></li>
                    <li><a href="<?= site_url('user/cart'); ?>" class="store-footer-link">Cart</a></li>
                    <li><a href="<?= site_url('user/purchase'); ?>" class="store-footer-link">Orders</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-2">Support</h6>
                <p class="text-muted small mb-0">
                    Need help? Contact support@commerce-portal.test
                </p>
            </div>
        </div>
        <hr class="my-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span class="text-muted small">&copy; <?= date('Y'); ?> Commerce Portal</span>
            <span class="text-muted small">Version 1.0</span>
        </div>
    </div>
</footer>