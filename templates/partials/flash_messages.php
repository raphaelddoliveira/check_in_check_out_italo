<?php
$successMsg = \App\Core\Session::getFlash('success');
$errorMsg = \App\Core\Session::getFlash('error');
$warningMsg = \App\Core\Session::getFlash('warning');
?>

<?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($warningMsg): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($warningMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
