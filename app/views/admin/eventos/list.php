<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/eventos.css">

<div class="page-header">
    <h5><i class="bi bi-calendar-event"></i> Eventos <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=eventos&action=create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

<div class="admin-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Título</th>
                <th width="90">Tipo</th>
                <th width="100">Fecha</th>
                <th width="90">Estado</th>
                <th width="80">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['titulo']) ?></strong>
                            <?php if ($item['lugar']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($item['lugar']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info"><?= ucfirst($item['tipo']) ?></span></td>
                        <td><?= $item['fecha_evento'] ? date('d/m/Y', strtotime($item['fecha_evento'])) : '-' ?></td>
                        <td>
                            <span class="badge bg-<?= $item['estado'] === 'realizado' ? 'success' : ($item['estado'] === 'programado' ? 'primary' : 'secondary') ?>">
                                <?= ucfirst($item['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=eventos&action=edit&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=eventos&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar este evento?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay eventos registrados.<br>
                        <a href="<?= APP_URL ?>/?page=admin&section=eventos&action=create">Crear el primero</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
