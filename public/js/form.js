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

    // Upload de fotos - multiplas por item
    document.querySelectorAll('.foto-input').forEach(function (input) {
        // Store selected files in a DataTransfer to accumulate across selections
        var storedFiles = new DataTransfer();

        input.addEventListener('change', function () {
            var cell = this.closest('.foto-cell');
            var container = cell.querySelector('.foto-previews-container');
            var countSpan = cell.querySelector('.foto-count');

            if (this.files && this.files.length > 0) {
                for (var i = 0; i < this.files.length; i++) {
                    var file = this.files[i];
                    if (file.size > 5 * 1024 * 1024) {
                        alert('A imagem "' + file.name + '" excede 5MB e foi ignorada.');
                        continue;
                    }
                    storedFiles.items.add(file);
                    addPreview(container, file, storedFiles, input, countSpan);
                }
                // Update input files to accumulated list
                input.files = storedFiles.files;
                updateCount(countSpan, storedFiles.files.length);
            }
        });
    });

    function addPreview(container, file, storedFiles, input, countSpan) {
        var wrapper = document.createElement('div');
        wrapper.className = 'foto-preview';

        var img = document.createElement('img');
        img.alt = 'Preview';
        img.className = 'img-thumbnail';
        img.style.maxHeight = '60px';
        img.style.maxWidth = '60px';
        img.style.cursor = 'pointer';

        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);

        img.addEventListener('click', function () {
            var modalImg = document.getElementById('fotoModalImg');
            if (modalImg) {
                modalImg.src = this.src;
                new bootstrap.Modal(document.getElementById('fotoModal')).show();
            }
        });

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm btn-outline-danger remove-foto mt-1';
        removeBtn.title = 'Remover';
        removeBtn.innerHTML = '<i class="bi bi-x"></i>';

        removeBtn.addEventListener('click', function () {
            // Remove from DataTransfer
            var newDt = new DataTransfer();
            for (var j = 0; j < storedFiles.files.length; j++) {
                if (storedFiles.files[j] !== file) {
                    newDt.items.add(storedFiles.files[j]);
                }
            }
            // Replace storedFiles contents
            while (storedFiles.items.length > 0) storedFiles.items.remove(0);
            for (var k = 0; k < newDt.files.length; k++) {
                storedFiles.items.add(newDt.files[k]);
            }
            input.files = storedFiles.files;
            wrapper.remove();
            updateCount(countSpan, storedFiles.files.length);
        });

        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
    }

    function updateCount(countSpan, count) {
        if (countSpan) {
            countSpan.textContent = count > 0 ? '(' + count + ')' : '';
        }
    }

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