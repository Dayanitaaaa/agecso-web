<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/mensajes.css">

<div class="page-header">
    <h5><i class="bi bi-envelope"></i> Mensajes <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
</div>

<div class="admin-table">
    <table class="table mb-0">
        <thead>
            <tr>
                <th width="40"></th>
                <th>Remitente</th>
                <th>Asunto</th>
                <th width="120">Fecha</th>
                <th width="80">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr class="<?= !$item['leido'] ? 'fw-semibold' : '' ?>" style="<?= !$item['leido'] ? 'background:#f0f7ff;' : '' ?>">
                        <td>
                            <?php if (!$item['leido']): ?>
                                <span class="badge bg-primary rounded-circle p-0" style="width: 10px; height: 10px; display: inline-block;"></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                            <br><small class="text-muted"><?= htmlspecialchars($item['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($item['asunto'] ?: '(Sin asunto)') ?></td>
                        <td><small><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></small></td>
                        <td>
                            <a href="<?= APP_URL ?>/?page=admin&section=mensajes&action=view&id=<?= $item['id'] ?>" class="btn-action">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= APP_URL ?>/?page=admin&section=mensajes&action=delete&id=<?= $item['id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Eliminar este mensaje?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        No hay mensajes recibidos.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
