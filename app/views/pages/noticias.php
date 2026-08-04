<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5 animate-fade-in">
            <span class="text-uppercase tracking-wider fw-bold text-primary" style="font-size: 0.85rem; letter-spacing: 0.15em;">Actualidad</span>
            <h2 class="display-5 fw-bold text-dark mt-2">Noticias</h2>
            <div class="mx-auto mt-3" style="width: 60px; height: 4px; background: linear-gradient(90deg, #00a2ff, #008ae0); border-radius: 2px;"></div>
        </div>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="news-modern-card h-100 shadow-sm border-0">
                            <!-- News Media -->
                            <div class="news-card-media">
                                <?php if ($item['imagen']): ?>
                                    <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="">
                                <?php else: ?>
                                    <div class="no-image-placeholder"><i class="bi bi-newspaper"></i></div>
                                <?php endif; ?>
                                <div class="news-card-date"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></div>
                            </div>
                            
                            <!-- News Body -->
                            <div class="news-card-body p-4">
                                <h5 class="news-card-title"><?= htmlspecialchars($item['titulo']) ?></h5>
                                <div class="news-card-excerpt">
                                    <?php 
                                    // Limpiar HTML y mostrar resumen corto
                                    $plainText = strip_tags($item['contenido']);
                                    echo htmlspecialchars(substr($plainText, 0, 120)) . '...'; 
                                    ?>
                                </div>
                                <button type="button" class="btn btn-link news-read-more p-0 mt-3" data-bs-toggle="modal" data-bs-target="#modalNews<?= $item['id'] ?>">
                                    Leer noticia completa <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para la Noticia Completa -->
                    <div class="modal fade news-modal" id="modalNews<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header border-0 pb-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 p-md-5 pt-0">
                                    <!-- Carousel in Modal -->
                                    <?php 
                                    $extraImages = json_decode($item['imagenes'] ?? '[]', true);
                                    $carouselId = 'carouselModal' . $item['id'];
                                    $allImages = array_filter(array_merge([$item['imagen']], $extraImages));
                                    
                                    if (count($allImages) > 1): ?>
                                        <div id="<?= $carouselId ?>" class="carousel slide news-modal-carousel mb-4" data-bs-ride="carousel">
                                            <div class="carousel-inner rounded-4 shadow-sm">
                                                <?php foreach ($allImages as $index => $img): ?>
                                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100" style="height: 400px; object-fit: cover;">
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
                                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($allImages[0]) ?>" class="img-fluid rounded-4 shadow-sm mb-4 w-100" style="height: 400px; object-fit: cover;">
                                    <?php endif; ?>

                                    <div class="news-modal-meta mb-3">
                                        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                                            <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?>
                                        </span>
                                    </div>
                                    
                                    <h2 class="news-modal-title mb-4"><?= htmlspecialchars($item['titulo']) ?></h2>
                                    
                                    <div class="news-modal-content">
                                        <?= $item['contenido'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay noticias publicadas aún.</div>
        <?php endif; ?>
    </div>
</section>
