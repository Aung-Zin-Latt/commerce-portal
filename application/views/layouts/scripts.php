<!-- Bootstrap -->
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- AdminLTE -->
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js'); ?>"></script>

<!-- Application JS -->
<script src="<?= base_url('assets/js/app.js?v=2'); ?>"></script>

<?php if (!empty($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= base_url($script); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>