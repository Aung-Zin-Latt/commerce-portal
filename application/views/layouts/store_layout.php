<?php $this->load->view('layouts/store_header'); ?>

<?php $this->load->view('layouts/store_navbar'); ?>

<main class="store-main">
    <div class="container py-4">
        <?php $this->load->view('components/flash_message'); ?>
        <?php $this->load->view('components/alerts'); ?>
        <?php $this->load->view($content); ?>
    </div>
</main>

<?php $this->load->view('layouts/store_footer'); ?>
<?php $this->load->view('layouts/scripts'); ?>