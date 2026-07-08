<nav class="app-header navbar navbar-expand-lg navbar-light bg-body">

    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto flex-row align-items-center gap-1">
            <li class="nav-item d-none d-lg-inline">
                <span class="nav-link text-muted py-1">
                    Commerce Portal
                </span>
            </li>
            <li class="nav-item">
                <span class="nav-link py-1 admin-user-label">
                    <i class="fas fa-user-circle me-1 d-inline d-md-none"></i>
                    <span class="admin-user-name"><?= html_escape($this->session->userdata('user_name')); ?></span>
                    <small class="text-muted admin-user-role d-none d-lg-inline">
                        (<?= html_escape($this->session->userdata('role_name')); ?>)
                    </small>
                </span>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1" href="<?= site_url('logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>

</nav>
