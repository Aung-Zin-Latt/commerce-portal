<?php $this->load->view('layouts/header'); ?>

<?php $this->load->view('layouts/navbar'); ?>

<?php $this->load->view('layouts/sidebar'); ?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center gy-2">
                <div class="col-12 col-md-6">
                    <h3 class="mb-0"><?= isset($title) ? html_escape($title) : 'Dashboard'; ?></h3>
                </div>
                <div class="col-12 col-md-6">
                    <?php $this->load->view('components/breadcrumb'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">

        <div class="container-fluid">

            <?php $this->load->view('components/flash_message'); ?>
            <?php $this->load->view('components/alerts'); ?>
            <?php $this->load->view($content); ?>

        </div>

    </div>

</main>

<?php $this->load->view('layouts/footer'); ?>

<?php $this->load->view('layouts/scripts'); ?>