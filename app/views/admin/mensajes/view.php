<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/mensajes.css">

<div class="page-header">
    <h5><i class="bi bi-envelope"></i> Mensaje de Contacto</h5>
    <a href="<?= APP_URL ?>/?page=admin&section=mensajes" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <h5 class="mb-0">Detalle del Mensaje</h5>
    </div>
    
    <?php if ($item): ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Nombre</label>
                <p><?= htmlspecialchars($item['nombre']) ?></p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Correo</label>
                <p><a href="mailto:<?= htmlspecialchars($item['email']) ?>"><?= htmlspecialchars($item['email']) ?></a></p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Teléfono</label>
                <p><?= htmlspecialchars($item['telefono'] ?: 'No proporcionado') ?></p>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Asunto</label>
                <p><?= htmlspecialchars($item['asunto'] ?: 'Sin asunto') ?></p>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Mensaje</label>
                <div class="p-3 bg-light rounded">
                    <?= nl2br(htmlspecialchars($item['mensaje'])) ?>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Fecha de recepción</label>
                <p><?= date('d/m/Y H:i:s', strtotime($item['created_at'])) ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Mensaje no encontrado.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
