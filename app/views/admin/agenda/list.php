<?php require __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><i class="bi bi-calendar2-check text-primary"></i> <?= $title ?></h5>
        <p class="text-muted small mb-0">Administra las actividades, afiches, pósters y convocatorias de la Agenda.</p>
    </div>
    <a href="<?= APP_URL ?>/admin/agenda/create" class="btn btn-primary rounded-pill px-3 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Nueva Actividad
    </a>
</div>

<div class="admin-card">
    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar2-x text-muted fs-1 d-block mb-3"></i>
            <h5 class="text-muted">No hay actividades registradas en la agenda</h5>
            <p class="text-muted small">Crea la primera publicación o afiche promocional para la agenda.</p>
            <a href="<?= APP_URL ?>/admin/agenda/create" class="btn btn-sm btn-primary rounded-pill mt-2">
                <i class="bi bi-plus-lg"></i> Agregar Actividad
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Afiche / Foto</th>
                        <th>Título / Actividad</th>
                        <th>Fechas</th>
                        <th>Horario</th>
                        <th>Lugar / Tipo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['imagen'])): ?>
                                    <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" alt="Afiche" class="rounded-3 object-fit-cover shadow-sm border" style="width: 70px; height: 50px;">
                                <?php else: ?>
                                    <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 50px;">
                                        <i class="bi bi-image fs-5 text-secondary opacity-50"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong class="d-block text-dark"><?= htmlspecialchars($item['titulo']) ?></strong>
                                <?php if (!empty($item['descripcion'])): ?>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        <?= htmlspecialchars(strip_tags($item['descripcion'])) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small">
                                    <?php if (!empty($item['fecha_inicio'])): ?>
                                        <i class="bi bi-calendar-event text-primary me-1"></i> <?= date('d/m/Y', strtotime($item['fecha_inicio'])) ?>
                                        <?php if (!empty($item['fecha_fin']) && $item['fecha_fin'] !== $item['fecha_inicio']): ?>
                                            - <?= date('d/m/Y', strtotime($item['fecha_fin'])) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Por definir</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <?php if (!empty($item['hora_inicio'])): ?>
                                        <i class="bi bi-clock me-1"></i> <?= date('h:i A', strtotime($item['hora_inicio'])) ?>
                                        <?php if (!empty($item['hora_fin'])): ?>
                                            - <?= date('h:i A', strtotime($item['hora_fin'])) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="badge bg-light text-dark border me-1"><?= ucfirst($item['tipo'] ?? 'evento') ?></span>
                                    <?php if (!empty($item['lugar'])): ?>
                                        <span class="text-muted d-block mt-1"><i class="bi bi-geo-alt text-danger me-1"></i><?= htmlspecialchars($item['lugar']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($item['estado'] === 'activo'): ?>
                                    <span class="badge bg-success px-2.5 py-1.5 rounded-pill">Activo</span>
                                <?php elseif ($item['estado'] === 'destacado'): ?>
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill"><i class="bi bi-star-fill me-1"></i> Destacado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary px-2.5 py-1.5 rounded-pill">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= APP_URL ?>/admin/agenda/edit/<?= $item['id'] ?>" class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/admin/agenda/delete/<?= $item['id'] ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta actividad de la agenda?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
