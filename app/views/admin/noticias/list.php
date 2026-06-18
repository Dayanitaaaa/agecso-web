<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/noticias.css">

<div class="page-header">
    <h5><i class="bi bi-newspaper"></i> Noticias <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=noticias&action=create" class="btn btn-admin-success">
        <i class="bi bi-plus-lg"></i> Nueva
    </a>
</div>

<div class="admin-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Título</th>
                <th width="90">Estado</th>
                <th width="100">Fecha</th>
                <th width="80">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['titulo']) ?></strong>
                            <?php if ($item['resumen']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars(substr($item['resumen'], 0, 70)) ?>...</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $item['estado'] === 'publicado' ? 'success' : ($item['estado'] === 'borrador' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($item['estado']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=noticias&action=edit&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=noticias&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar esta noticia?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay noticias registradas.<br>
                        <a href="<?= APP_URL ?>/?page=admin&section=noticias&action=create">Crear la primera</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
