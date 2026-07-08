<div class="login-box">
    <div class="login-logo">
        <a href="<?= site_url(); ?>">Commerce Portal</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <?php $this->load->view('components/flash_message'); ?>

            <?php
            $old_input = $this->session->flashdata('old_input') ?: array();
            $field_errors = $this->session->flashdata('field_errors') ?: array();
            ?>

            <?= form_open('login/authenticate', array('class' => 'js-validate-form', 'novalidate' => 'novalidate')); ?>
                <div class="mb-3 js-field-group">
                    <div class="input-group<?= !empty($field_errors['email']) ? ' is-invalid' : ''; ?>">
                        <input
                            type="email"
                            class="form-control<?= !empty($field_errors['email']) ? ' is-invalid' : ''; ?>"
                            name="email"
                            value="<?= html_escape(isset($old_input['email']) ? $old_input['email'] : ''); ?>"
                            placeholder="Email"
                        >
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <?php $this->load->view('components/field_error', array('message' => isset($field_errors['email']) ? $field_errors['email'] : '')); ?>
                </div>

                <div class="mb-3 js-field-group">
                    <div class="input-group<?= !empty($field_errors['password']) ? ' is-invalid' : ''; ?>">
                        <input
                            type="password"
                            class="form-control<?= !empty($field_errors['password']) ? ' is-invalid' : ''; ?>"
                            name="password"
                            placeholder="Password"
                        >
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <?php $this->load->view('components/field_error', array('message' => isset($field_errors['password']) ? $field_errors['password'] : '')); ?>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            <?= form_close(); ?>

            <p class="mb-0 mt-3 text-center">
                <a href="<?= site_url('register'); ?>">Create a customer account</a>
            </p>
        </div>
    </div>
</div>
