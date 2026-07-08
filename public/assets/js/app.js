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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAuthFormValidation);
    } else {
        initAuthFormValidation();
    }
})();
