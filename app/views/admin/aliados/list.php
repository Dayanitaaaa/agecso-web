<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/aliados.css">

<div class="page-header">
    <h5><i class="bi bi-people"></i> Aliados <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=aliados&action=create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Nuevo
    </a>
</div>

<div class="admin-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th width="120">Categoría</th>
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
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                            <?php if ($item['sitio_web']): ?>
                                <br><a href="<?= htmlspecialchars($item['sitio_web']) ?>" target="_blank" class="small"><?= htmlspecialchars($item['sitio_web']) ?></a>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['categoria'] ?: '-') ?></td>
                        <td><?= $item['orden'] ?></td>
                        <td>
                            <span class="badge bg-<?= $item['activo'] ? 'success' : 'secondary' ?>">
                                <?= $item['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=aliados&action=edit&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=aliados&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay aliados.<br>
                        <a href="<?= APP_URL ?>/?page=admin&section=aliados&action=create">Agregar</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
