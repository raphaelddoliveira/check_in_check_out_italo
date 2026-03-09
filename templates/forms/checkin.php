<?php
$items = \App\Services\FormDataMapper::getCheckInItems();
?>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="card card-spazio">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-box-arrow-in-right me-2 fs-5"></i>
                <div>
                    <h4 class="mb-0">Check-in (Entrada no Espaço)</h4>
                    <?php if (!empty($opportunity['name'])): ?>
                        <small class="opacity-75"><?= htmlspecialchars($opportunity['name']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="<?= $formAction ?>" id="checkinForm" enctype="multipart/form-data">
                    <?= $csrfField ?>

                    <!-- Data e Horario -->
                    <div class="form-datetime-row">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="data" class="form-label">Data</label>
                                <input type="date" class="form-control" id="data" name="data" required>
                            </div>
                            <div class="col-md-6">
                                <label for="horario" class="form-label">Horário</label>
                                <input type="time" class="form-control" id="horario" name="horario" required>
                            </div>
                        </div>
                    </div>

                    <!-- Condicoes do Espaco -->
                    <h5 class="mb-3">
                        <i class="bi bi-clipboard-check"></i>
                        Condições do Espaço no Check-in
                    </h5>
                    <p class="text-muted small mb-3">Preenchido pela equipe do centro de convenções</p>

                    <div class="table-responsive">
                        <table class="table table-conditions">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Item</th>
                                    <th style="width: 30%">Condição</th>
                                    <th style="width: 40%">Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $fieldName => $item): ?>
                                    <?php
                                    $label = $item['label'];
                                    $options = $item['options'];
                                    include dirname(__DIR__) . '/partials/condition_row.php';
                                    ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Botao Aceito -->
                    <div class="mt-4 text-center">
                        <p class="text-muted small mb-3">
                            Ao clicar em "Aceito", você confirma que verificou todas as condições do espaço
                            e concorda com o estado atual descrito acima.
                        </p>
                        <button type="submit" class="btn btn-aceito w-100" id="btnAceito">
                            <i class="bi bi-check-circle"></i> Aceito
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>