<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <span class="nav-link">
                    Commerce Portal
                </span>
            </li>
            <li class="nav-item">
                <span class="nav-link">
                    <?= html_escape($this->session->userdata('user_name')); ?>
                    <small class="text-muted">
                        (<?= html_escape($this->session->userdata('role_name')); ?>)
                    </small>
                </span>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('logout'); ?>">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

</nav>