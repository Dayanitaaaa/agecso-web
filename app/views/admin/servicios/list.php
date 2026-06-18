<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/servicios.css">

<div class="page-header">
    <h5><i class="bi bi-briefcase"></i> Servicios <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=servicios&action=create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

<div class="admin-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Título</th>
                <th width="70">Orden</th>
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
                            <br><small class="text-muted"><?= htmlspecialchars(substr($item['descripcion'] ?? '', 0, 50)) ?>...</small>
                        </td>
                        <td><?= $item['orden'] ?></td>
                        <td>
                            <span class="badge bg-<?= $item['activo'] ? 'success' : 'secondary' ?>">
                                <?= $item['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=servicios&action=edit&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=servicios&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay servicios.<br>
                        <a href="<?= APP_URL ?>/?page=admin&section=servicios&action=create">Agregar</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
