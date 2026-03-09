<?php
/**
 * Partial: linha de condicao na tabela do formulario.
 * Variaveis esperadas: $fieldName, $label, $options
 */
?>
<tr>
    <td class="item-name"><?= htmlspecialchars($label) ?></td>
    <td>
        <select name="<?= $fieldName ?>_status" class="form-select form-select-status" required>
            <option value="">Selecione...</option>
            <?php foreach ($options as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <td>
        <input type="text" name="<?= $fieldName ?>_obs" class="form-control form-control-obs mb-2"
               placeholder="Observações (opcional)">
        <div class="upload-wrapper">
            <label class="btn btn-sm btn-outline-secondary w-100 upload-btn">
                <i class="bi bi-camera"></i> Foto
                <input type="file" name="<?= $fieldName ?>_foto" accept="image/*" capture="environment" class="d-none foto-input">
            </label>
            <div class="foto-preview mt-1" style="display:none;">
                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 80px; cursor: pointer;">
                <button type="button" class="btn btn-sm btn-outline-danger remove-foto" title="Remover">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    </td>
</tr>
