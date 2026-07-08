<?php
$current_uri = uri_string();
$is_dashboard = ($current_uri === 'admin/dashboard' || $current_uri === 'admin');
$is_users = (strpos($current_uri, 'admin/users') === 0);
$is_products = (strpos($current_uri, 'admin/products') === 0);
?>
<aside class="app-sidebar bg-body-secondary shadow">

    <div class="sidebar-brand">
        <a href="<?= site_url('admin/dashboard'); ?>" class="brand-link">
            <span class="brand-text fw-light">Commerce Portal</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav>
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard'); ?>" class="nav-link<?= $is_dashboard ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-gauge"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/users'); ?>" class="nav-link<?= $is_users ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/products'); ?>" class="nav-link<?= $is_products ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Products</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</aside>