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
        <input type="text" name="<?= $fieldName ?>_obs" class="form-control form-control-obs"
               placeholder="Observações (opcional)">
    </td>
    <td class="text-center foto-cell">
        <input type="file" name="<?= $fieldName ?>_foto" accept="image/*" capture="environment" class="d-none foto-input" id="foto_<?= $fieldName ?>">
        <label for="foto_<?= $fieldName ?>" class="btn btn-sm btn-outline-secondary upload-btn mb-0">
            <i class="bi bi-camera"></i>
        </label>
        <div class="foto-preview" style="display:none;">
            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 60px; max-width: 60px; cursor: pointer;">
            <button type="button" class="btn btn-sm btn-outline-danger remove-foto mt-1" title="Remover">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </td>
</tr>
