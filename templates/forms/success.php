<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card card-spazio">
            <div class="card-body success-card">
                <?php if (!empty($alreadyDone)): ?>
                    <i class="bi bi-info-circle-fill text-warning" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 mb-2">Formulário Já Enviado</h3>
                    <p class="text-muted">
                        O <?= htmlspecialchars($formType) ?> desta oportunidade já foi realizado anteriormente.
                    </p>
                <?php else: ?>
                    <i class="bi bi-check-circle-fill success-icon"></i>
                    <h3 class="mt-3 mb-2"><?= htmlspecialchars($formType) ?> Realizado!</h3>
                    <p class="text-muted">
                        O formulário foi enviado com sucesso e o PDF foi anexado à oportunidade.
                    </p>
                <?php endif; ?>

                <?php if (!empty($opportunity['name'])): ?>
                    <div class="mt-3 p-3 rounded" style="background-color: #f8f9fa;">
                        <small class="text-muted text-uppercase">Oportunidade</small><br>
                        <strong><?= htmlspecialchars($opportunity['name']) ?></strong>
                        <?php if (!empty($opportunity['accountName'])): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($opportunity['accountName']) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i>
                        <?= date('d/m/Y \à\s H:i') ?>
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>
