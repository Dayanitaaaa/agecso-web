<section class="section-padding bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5 animate-fade-in">
            <span class="text-uppercase tracking-wider fw-bold text-success" style="font-size: 0.85rem; letter-spacing: 0.15em;">Agenda Institucional</span>
            <h2 class="display-5 fw-bold text-dark mt-2">Nuestros Eventos</h2>
            <div class="mx-auto mt-3" style="width: 60px; height: 4px; background: linear-gradient(90deg, #198754, #146c43); border-radius: 2px;"></div>
        </div>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="news-modern-card h-100 shadow-sm border-0">
                            <!-- Event Media -->
                            <div class="news-card-media">
                                <?php 
                                $mainImg = $item['imagen'];
                                if (!$mainImg && !empty($item['imagenes'])) {
                                    $galeria = json_decode($item['imagenes'], true);
                                    if (!empty($galeria)) $mainImg = $galeria[0];
                                }
                                
                                if ($mainImg): ?>
                                    <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($mainImg) ?>" class="card-img-top" alt="">
                                <?php else: ?>
                                    <div class="no-image-placeholder d-flex align-items-center justify-content-center bg-white" style="height: 250px;">
                                        <i class="bi bi-calendar-event text-success opacity-50" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($item['estado'] === 'realizado'): ?>
                                    <div class="news-card-date bg-secondary shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> Realizado
                                    </div>
                                <?php else: ?>
                                    <div class="news-card-date bg-success">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= ($item['fecha_evento'] && $item['fecha_evento'] !== '0000-00-00') ? date('d/m/Y', strtotime($item['fecha_evento'])) : 'Próximamente' ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="event-type-badge shadow-sm">
                                    <?= ucfirst($item['tipo'] ?? 'Evento') ?>
                                </div>
                            </div>
                            
                            <!-- Event Body -->
                            <div class="news-card-body p-4">
                                <h5 class="news-card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                                
                                <div class="event-info-list mb-3">
                                    <?php if ($item['lugar']): ?>
                                        <div class="event-info-item">
                                            <i class="bi bi-geo-alt text-success me-2"></i>
                                            <span><?= htmlspecialchars($item['lugar']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['estado'] !== 'realizado' && $item['hora_inicio']): ?>
                                        <div class="event-info-item mt-1">
                                            <i class="bi bi-clock text-success me-2"></i>
                                            <span><?= date('g:i A', strtotime($item['hora_inicio'])) ?> <?= $item['hora_fin'] ? ' - ' . date('g:i A', strtotime($item['hora_fin'])) : '' ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="news-card-excerpt">
                                    <?php 
                                    $plainText = strip_tags($item['descripcion']);
                                    echo htmlspecialchars(substr($plainText, 0, 100)) . (strlen($plainText) > 100 ? '...' : ''); 
                                    ?>
                                </div>
                                
                                <button type="button" class="btn btn-link news-read-more text-success p-0 mt-3" data-bs-toggle="modal" data-bs-target="#modalEvent<?= $item['id'] ?>">
                                    Ver detalles <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para Evento Detallado -->
                    <div class="modal fade news-modal" id="modalEvent<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header border-0 pb-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 p-md-5 pt-0">
                                    <!-- Carousel in Modal -->
                                    <?php 
                                    $extraImages = json_decode($item['imagenes'] ?? '[]', true);
                                    $carouselId = 'carouselEvent' . $item['id'];
                                    $allImages = array_filter(array_merge([$item['imagen']], $extraImages));
                                    
                                    if (count($allImages) > 1): ?>
                                        <div id="<?= $carouselId ?>" class="carousel slide news-modal-carousel mb-4" data-bs-ride="carousel">
                                            <div class="carousel-inner rounded-4 shadow-sm">
                                                <?php foreach ($allImages as $index => $img): ?>
                                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100" style="height: 400px; object-fit: cover; object-position: top;">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        </div>
                                    <?php elseif (!empty($allImages)): ?>
                                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($allImages[0]) ?>" class="img-fluid rounded-4 shadow-sm mb-4 w-100" style="height: 400px; object-fit: cover; object-position: top;">
                                    <?php endif; ?>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-tag me-1"></i> <?= ucfirst($item['tipo'] ?? 'Evento') ?>
                                        </span>
                                        <?php if ($item['estado'] !== 'realizado'): ?>
                                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-calendar3 me-1"></i> <?= ($item['fecha_evento'] && $item['fecha_evento'] !== '0000-00-00') ? date('d/M/Y', strtotime($item['fecha_evento'])) : 'Próximamente' ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="badge bg-soft-secondary text-secondary px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i> Finalizado
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h2 class="news-modal-title mb-4"><?= htmlspecialchars($item['titulo']) ?></h2>
                                    
                                    <div class="event-details-grid p-4 bg-light rounded-4 mb-4">
                                        <div class="row g-3">
                                            <?php if ($item['lugar']): ?>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt-fill text-success fs-4 me-3"></i>
                                                    <div>
                                                        <div class="small text-muted fw-bold">Lugar</div>
                                                        <div class="text-dark"><?= htmlspecialchars($item['lugar']) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($item['estado'] !== 'realizado' && $item['hora_inicio']): ?>
                                            <div class="col-sm-6">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-clock-fill text-success fs-4 me-3"></i>
                                                    <div>
                                                        <div class="small text-muted fw-bold">Horario</div>
                                                        <div class="text-dark">
                                                            <?= date('g:i A', strtotime($item['hora_inicio'])) ?> 
                                                            <?= $item['hora_fin'] ? ' - ' . date('g:i A', strtotime($item['hora_fin'])) : '' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="news-modal-content">
                                        <?= $item['descripcion'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 text-center">
                <i class="bi bi-calendar-x fs-1 opacity-25 d-block mb-3"></i>
                No hay eventos programados en este momento. Vuelve pronto para enterarte de nuestras actividades.
            </div>
        <?php endif; ?>
    </div>
</section>
