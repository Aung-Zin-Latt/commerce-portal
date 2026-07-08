<?php $cart_item_count = isset($cart_item_count) ? (int) $cart_item_count : 0; ?>

<header class="store-header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light store-navbar">
        <div class="container">
            <a class="navbar-brand store-brand" href="<?= site_url(''); ?>">
                <i class="fas fa-store"></i>
                <span class="store-brand-text">Commerce Portal</span>
            </a>

            <div class="store-mobile-actions d-flex d-lg-none align-items-center gap-2">
                <a class="btn btn-link store-mobile-icon-btn" href="<?= site_url('user/cart'); ?>" aria-label="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge rounded-pill text-bg-primary store-mobile-badge"><?= $cart_item_count; ?></span>
                </a>
                <button
                    class="navbar-toggler store-navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#storeNavbar"
                    aria-controls="storeNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="storeNavbar">
                <form class="store-search mx-lg-4 my-3 my-lg-0 flex-grow-1" role="search">
                    <div class="input-group">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="Search products..."
                            aria-label="Search products"
                        >
                        <button class="btn btn-primary" type="button" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav store-nav-actions ms-lg-auto align-items-lg-center">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link store-nav-link" href="<?= site_url('user/cart'); ?>">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="ms-1">Cart</span>
                            <span class="badge rounded-pill text-bg-primary ms-1"><?= $cart_item_count; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link store-nav-link" href="<?= site_url('user/purchase'); ?>">
                            <i class="fas fa-receipt"></i>
                            <span class="ms-1">Orders</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link store-nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fas fa-user-circle"></i>
                            <span class="ms-1 store-user-name">
                                <?= html_escape($this->session->userdata('user_name')); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text text-muted small">
                                    <?= html_escape($this->session->userdata('user_email')); ?>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= site_url('user/invoices'); ?>">
                                    <i class="fas fa-file-invoice me-2"></i>Invoices
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= site_url('user/receipts'); ?>">
                                    <i class="fas fa-file-lines me-2"></i>Receipts
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= site_url('logout'); ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
