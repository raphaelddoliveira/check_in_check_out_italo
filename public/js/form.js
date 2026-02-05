document.addEventListener('DOMContentLoaded', function () {

    // Auto-fill data e horario
    var dataInput = document.getElementById('data');
    var horarioInput = document.getElementById('horario');

    if (dataInput && !dataInput.value) {
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        dataInput.value = yyyy + '-' + mm + '-' + dd;
    }

    if (horarioInput && !horarioInput.value) {
        var now = new Date();
        var hh = String(now.getHours()).padStart(2, '0');
        var min = String(now.getMinutes()).padStart(2, '0');
        horarioInput.value = hh + ':' + min;
    }

    // Colorir selects de acordo com o status
    document.querySelectorAll('.form-select-status').forEach(function (select) {
        select.addEventListener('change', function () {
            this.classList.remove('status-ok', 'status-problem');
            if (this.value === 'OK') {
                this.classList.add('status-ok');
            } else if (this.value && this.value !== '') {
                this.classList.add('status-problem');
            }
        });
    });

    // Modais
    var confirmModal = document.getElementById('confirmModal');
    var errorModal = document.getElementById('errorModal');
    var confirmBtn = document.getElementById('confirmSubmitBtn');
    var pendingForm = null;
    var bsConfirmModal = confirmModal ? new bootstrap.Modal(confirmModal) : null;
    var bsErrorModal = errorModal ? new bootstrap.Modal(errorModal) : null;

    var forms = document.querySelectorAll('#checkinForm, #checkoutForm');
    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            // Validar selects
            var allSelects = form.querySelectorAll('.form-select-status');
            var allFilled = true;

            allSelects.forEach(function (select) {
                if (!select.value) {
                    allFilled = false;
                    select.classList.add('is-invalid');
                } else {
                    select.classList.remove('is-invalid');
                }
            });

            if (!allFilled) {
                e.preventDefault();
                if (bsErrorModal) bsErrorModal.show();
                return;
            }

            // Mostrar modal de confirmacao
            if (!form.dataset.confirmed) {
                e.preventDefault();
                pendingForm = form;
                if (bsConfirmModal) bsConfirmModal.show();
                return;
            }
        });
    });

    // Ao confirmar no modal
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (pendingForm) {
                pendingForm.dataset.confirmed = 'true';

                var btn = pendingForm.querySelector('#btnAceito');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
                }

                bsConfirmModal.hide();
                pendingForm.submit();
            }
        });
    }
});