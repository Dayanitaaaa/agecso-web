<section class="section-padding">
    <div class="container">
        <h1 class="mb-4">Noticias</h1>
        <p class="lead">Espacio para publicar noticias, comunicados y avances de gestión institucional.</p>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <?php 
                            $extraImages = json_decode($item['imagenes'] ?? '[]', true);
                            $hasCarousel = !empty($extraImages);
                            $carouselId = 'carouselNews' . $item['id'];
                            
                            if ($hasCarousel): 
                                array_unshift($extraImages, $item['imagen']); // Poner la principal de primera
                                $extraImages = array_filter($extraImages); // Limpiar nulos
                            ?>
                                <div id="<?= $carouselId ?>" class="carousel slide card-img-top" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($extraImages as $index => $img): ?>
                                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="" style="height: 250px; object-fit: cover;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($extraImages) > 1): ?>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true" style="width: 20px; height: 20px;"></span>
                                            <span class="visually-hidden">Siguiente</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($item['imagen']): ?>
                                <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="" style="height: 250px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-soft-primary text-primary border-0 px-3 py-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?>
                                    </span>
                                </div>
                                <h4 class="card-title fw-bold text-dark mb-3"><?= htmlspecialchars($item['titulo']) ?></h4>
                                <div class="news-content"><?= $item['contenido'] ?></div>
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
