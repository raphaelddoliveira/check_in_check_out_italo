<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Spazio Italia - Check-in/Check-out' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php $basePath = config('app.base_path', ''); ?>
    <link href="<?= $basePath ?>/public/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-spazio">
        <div class="container">
            <a class="navbar-brand" href="<?= $basePath ?>/">
                <img src="<?= $basePath ?>/image/Livello_1.png" alt="Spazio Italia" height="50">
            </a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <?php include __DIR__ . '/partials/flash_messages.php'; ?>
        <?= $content ?>
    </div>

    <footer class="footer-spazio py-3">
        <div class="container text-center">
            <span>Spazio Italia &copy; <?= date('Y') ?> - Centro de Convencoes</span>
        </div>
    </footer>

    <!-- Modal: Confirmacao de Envio -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Confirmar envio</h5>
                    <p class="text-muted mb-0">
                        Ao confirmar, você declara que verificou todas as condições do espaço e concorda com o estado registrado.
                    </p>
                    <p class="text-muted small mt-2">
                        <i class="bi bi-exclamation-triangle"></i> Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Voltar
                    </button>
                    <button type="button" class="btn btn-success px-4" id="confirmSubmitBtn">
                        <i class="bi bi-check-lg"></i> Confirmar e Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Erro de validacao -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Campos incompletos</h5>
                    <p class="text-muted mb-0">
                        Por favor, preencha a condição de todos os itens antes de aceitar.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Visualizar Foto -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="modal-body text-center p-0">
                    <img id="fotoModalImg" src="" alt="Foto" class="img-fluid rounded" style="max-height: 80vh;">
                </div>
                <div class="text-center mt-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $basePath ?>/public/js/form.js?v=<?= time() ?>"></script>
</body>
</html>