<?php
$current_uri = uri_string();
$is_stripe = (strpos($current_uri, 'admin/stripe-transactions') === 0);
$is_dashboard = ($current_uri === 'admin/dashboard' || $current_uri === 'admin');
$is_users = (strpos($current_uri, 'admin/users') === 0);
$is_products = (strpos($current_uri, 'admin/products') === 0);

// Invoices
$is_invoices = (strpos($current_uri, 'admin/invoices') === 0);
// Receipts
$is_receipts = (strpos($current_uri, 'admin/receipts') === 0);
// Orders
$is_orders = (strpos($current_uri, 'admin/orders') === 0);
// Audit Logs
$is_audit_logs = (strpos($current_uri, 'admin/audit-logs') === 0);
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
                <li class="nav-header">Overview</li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard'); ?>" class="nav-link<?= $is_dashboard ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-gauge"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Catalog</li>
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

                <li class="nav-header">Commerce</li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/orders'); ?>" class="nav-link<?= $is_orders ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/invoices'); ?>" class="nav-link<?= $is_invoices ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Invoices</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/receipts'); ?>" class="nav-link<?= $is_receipts ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Receipts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/stripe-transactions'); ?>" class="nav-link<?= $is_stripe ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Stripe Transactions</p>
                    </a>
                </li>

                <li class="nav-header">System</li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/audit-logs'); ?>" class="nav-link<?= $is_audit_logs ? ' active' : ''; ?>">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Audit Logs</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</aside>