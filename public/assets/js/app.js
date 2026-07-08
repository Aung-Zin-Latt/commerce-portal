'use strict';

(function () {
    function initAuthFormValidation() {
        document.querySelectorAll('.js-validate-form').forEach(function (form) {
            form.setAttribute('novalidate', 'novalidate');

            if (form.dataset.validationBound === 'true') {
                return;
            }

            form.dataset.validationBound = 'true';

            form.addEventListener('input', function (event) {
                var input = event.target;

                if (!input.classList.contains('form-control')) {
                    return;
                }

                input.classList.remove('is-invalid');

                var fieldGroup = input.closest('.js-field-group');

                if (!fieldGroup) {
                    return;
                }

                var inputGroup = fieldGroup.querySelector('.input-group');

                if (inputGroup) {
                    inputGroup.classList.remove('is-invalid');
                }

                var error = fieldGroup.querySelector('[data-field-error]');

                if (error) {
                    error.remove();
                }
            });
        });
    }

    function initClickableRows() {
        document.querySelectorAll('.order-row-clickable[data-href]').forEach(function (row) {
            if (row.dataset.rowClickBound === 'true') {
                return;
            }

            row.dataset.rowClickBound = 'true';

            row.addEventListener('click', function () {
                window.location.href = row.dataset.href;
            });

            row.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    window.location.href = row.dataset.href;
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAuthFormValidation();
            initClickableRows();
        });
    } else {
        initAuthFormValidation();
        initClickableRows();
    }
})();
