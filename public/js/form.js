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

    // Upload de fotos - preview e remover
    document.querySelectorAll('.foto-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var cell = this.closest('.foto-cell');
            var preview = cell.querySelector('.foto-preview');
            var img = preview.querySelector('img');
            var uploadBtn = cell.querySelector('.upload-btn');

            if (this.files && this.files[0]) {
                var file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 5MB.');
                    this.value = '';
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    preview.style.display = 'flex';
                    uploadBtn.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.querySelectorAll('.remove-foto').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cell = this.closest('.foto-cell');
            var input = cell.querySelector('.foto-input');
            var preview = cell.querySelector('.foto-preview');
            var uploadBtn = cell.querySelector('.upload-btn');
            input.value = '';
            preview.querySelector('img').src = '';
            preview.style.display = 'none';
            uploadBtn.style.display = 'inline-block';
        });
    });

    document.querySelectorAll('.foto-preview img').forEach(function (img) {
        img.addEventListener('click', function () {
            var modalImg = document.getElementById('fotoModalImg');
            if (modalImg) {
                modalImg.src = this.src;
                new bootstrap.Modal(document.getElementById('fotoModal')).show();
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