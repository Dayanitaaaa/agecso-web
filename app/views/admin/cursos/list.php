<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/cursos.css">

<div class="page-header">
    <h5><i class="bi bi-mortarboard"></i> Cursos <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=cursos&action=create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Agregar
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
                            <?php if ($item['instructor']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($item['instructor']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info"><?= ucfirst($item['tipo']) ?></span></td>
                        <td><?= $item['fecha_inicio'] ? date('d/m/Y', strtotime($item['fecha_inicio'])) : '-' ?></td>
                        <td>
                            <span class="badge bg-<?= $item['estado'] === 'finalizado' ? 'success' : ($item['estado'] === 'programado' ? 'primary' : 'secondary') ?>">
                                <?= ucfirst($item['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=cursos&action=edit&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=cursos&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay registros.<br>
                        <a href="<?= APP_URL ?>/?page=admin&section=cursos&action=create">Agregar el primero</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
