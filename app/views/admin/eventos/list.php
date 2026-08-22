<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/eventos.css">

<div class="page-header">
    <h5><i class="bi bi-calendar-event"></i> Eventos <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/admin/eventos/create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

<div class="admin-table shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th width="60" class="text-center">#</th>
                <th>Información del Evento</th>
                <th width="120" class="text-center">Tipo</th>
                <th width="120" class="text-center">Fecha</th>
                <th width="120" class="text-center">Estado</th>
                <th width="100" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary fw-bold px-3">
                                <?= $item['orden'] ?? '999' ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if ($item['imagen']): ?>
                                    <div class="bg-dark rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; overflow: hidden;">
                                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                <?php else: ?>
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="bi bi-calendar-event fs-4"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['titulo']) ?></h6>
                                    <?php if ($item['lugar']): ?>
                                        <small class="text-muted d-block"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($item['lugar']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3">
                                <?= ucfirst($item['tipo']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="small fw-bold text-dark">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                <?= $item['fecha_evento'] ? date('d/m/Y', strtotime($item['fecha_evento'])) : '-' ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-<?= $item['estado'] === 'realizado' ? 'success' : ($item['estado'] === 'programado' ? 'primary' : 'secondary') ?> px-3">
                                <?= ucfirst($item['estado']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="<?= APP_URL ?>/admin/eventos/edit/<?= $item['id'] ?>" class="btn-action shadow-sm" title="Editar">
                                    <i class="bi bi-pencil-fill text-primary"></i>
                                </a>
                                <button type="button" class="btn-action btn-delete delete-btn shadow-sm" 
                                        data-url="<?= APP_URL ?>/admin/eventos/delete/<?= $item['id'] ?>"
                                        data-title="<?= htmlspecialchars($item['titulo']) ?>"
                                        title="Eliminar">
                                    <i class="bi bi-trash-fill text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        No hay eventos registrados.<br>
                        <a href="<?= APP_URL ?>/admin/eventos/create">Crear el primero</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
