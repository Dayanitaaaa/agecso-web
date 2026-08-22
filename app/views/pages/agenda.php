<!-- Secondary Page Hero Header Banner -->
<div class="py-5 text-center text-white" style="background: linear-gradient(135deg, #101c2c 0%, #005fa3 50%, #00a2ff 100%); border-bottom: 4px solid #00a2ff;">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-bold text-white-50" style="font-size: 0.85rem; letter-spacing: 0.15em;">Calendario y Actividades</span>
        <h1 class="display-4 fw-bold mt-2">Agenda Oficial AGECSO</h1>
        <p class="lead mb-0 text-white-75" style="max-width: 650px; margin: 0 auto; font-size: 1.1rem;">Conoce nuestras convocatorias, talleres y actividades programadas para la comunidad empresarial.</p>
    </div>
</div>

<section class="section-padding" style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); min-height: 60vh;">
    <div class="container">
        
        <!-- ACTIVIDADES DE LA AGENDA (ADMINISTRADAS DESDE EL PANEL PRINCIPAL) -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                <div>
                    <h2 class="h3 fw-black text-dark mb-1">
                        <i class="bi bi-calendar2-check-fill text-primary me-2"></i> Próximas Actividades en Agenda
                    </h2>
                    <p class="text-muted small mb-0">Afiches, capacitaciones y convocatorias abiertas de la asociación.</p>
                </div>
            </div>

            <?php if (empty($data['agenda'])): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white mb-5">
                    <i class="bi bi-calendar2-x text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold text-dark">No hay actividades activas en la agenda por el momento</h5>
                    <p class="text-muted small">Pronto publicaremos nuevas convocatorias.</p>
                </div>
            <?php else: ?>
                <div class="row g-4 mb-5">
                    <?php foreach ($data['agenda'] as $act): ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column justify-content-between hover-shadow transition bg-white">
                                <div>
                                    <!-- Afiche / Imagen Principal de la Agenda -->
                                    <div class="position-relative" style="height: 240px; background-color: #f8f9fa;">
                                        <?php if (!empty($act['imagen'])): ?>
                                            <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($act['imagen']) ?>" alt="<?= htmlspecialchars($act['titulo']) ?>" class="w-100 h-100 object-fit-contain p-2">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-calendar-event fs-1 text-primary opacity-50"></i>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($act['estado'] === 'destacado'): ?>
                                            <span class="position-absolute top-0 start-0 m-3 badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold shadow-sm">
                                                <i class="bi bi-star-fill me-1"></i> Destacado
                                            </span>
                                        <?php endif; ?>

                                        <span class="position-absolute top-0 end-0 m-3 badge bg-primary px-3 py-1.5 rounded-pill fw-bold text-capitalize shadow-sm">
                                            <?= htmlspecialchars($act['tipo'] ?? 'Actividad') ?>
                                        </span>
                                    </div>

                                    <div class="p-4">
                                        <h4 class="fw-bold text-dark mb-2"><?= htmlspecialchars($act['titulo']) ?></h4>
                                        
                                        <?php if (!empty($act['descripcion'])): ?>
                                            <p class="text-muted small mb-3">
                                                <?= nl2br(htmlspecialchars($act['descripcion'])) ?>
                                            </p>
                                        <?php endif; ?>

                                        <!-- Detalles de Fecha, Hora y Lugar -->
                                        <div class="bg-light rounded-3 p-3 mb-3">
                                            <?php if (!empty($act['fecha_inicio'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1.5 text-dark small">
                                                    <i class="bi bi-calendar-event text-primary fs-5"></i>
                                                    <div>
                                                        <strong class="d-block">Fecha:</strong>
                                                        <span>
                                                            <?= date('d/m/Y', strtotime($act['fecha_inicio'])) ?>
                                                            <?php if (!empty($act['fecha_fin']) && $act['fecha_fin'] !== $act['fecha_inicio']): ?>
                                                                - <?= date('d/m/Y', strtotime($act['fecha_fin'])) ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($act['hora_inicio'])): ?>
                                                <div class="d-flex align-items-center gap-2 mb-1.5 text-dark small">
                                                    <i class="bi bi-clock text-success fs-5"></i>
                                                    <div>
                                                        <strong class="d-block">Horario:</strong>
                                                        <span>
                                                            <?= date('h:i A', strtotime($act['hora_inicio'])) ?>
                                                            <?php if (!empty($act['hora_fin'])): ?>
                                                                - <?= date('h:i A', strtotime($act['hora_fin'])) ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($act['lugar'])): ?>
                                                <div class="d-flex align-items-center gap-2 text-dark small">
                                                    <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                                                    <div>
                                                        <strong class="d-block">Lugar:</strong>
                                                        <span><?= htmlspecialchars($act['lugar']) ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de Registro / Acción -->
                                <div class="px-4 pb-4 pt-0">
                                    <?php if (!empty($act['link_registro'])): ?>
                                        <a href="<?= htmlspecialchars($act['link_registro']) ?>" target="_blank" class="btn btn-primary w-100 fw-bold rounded-pill py-2.5 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-box-arrow-up-right"></i> <?= htmlspecialchars($act['texto_boton'] ?? 'Inscribirme') ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= APP_URL ?>/contacto" class="btn btn-outline-primary w-100 fw-bold rounded-pill py-2.5 d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-info-circle"></i> Más Información
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
