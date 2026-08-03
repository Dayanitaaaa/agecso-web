<section class="section-padding">
    <div class="container">
        <h1 class="mb-4">Noticias</h1>
        <p class="lead">Espacio para publicar noticias, comunicados y avances de gestión institucional.</p>

        <?php if (!empty($data)): ?>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <?php foreach ($data as $item): ?>
                        <div class="news-article-card mb-5 animate-fade-in">
                            <!-- News Image/Carousel Section -->
                            <div class="news-media-wrapper">
                                <?php 
                                $extraImages = json_decode($item['imagenes'] ?? '[]', true);
                                $hasCarousel = !empty($extraImages);
                                $carouselId = 'carouselNews' . $item['id'];
                                
                                if ($hasCarousel): 
                                    array_unshift($extraImages, $item['imagen']); // Poner la principal de primera
                                    $extraImages = array_filter($extraImages); // Limpiar nulos
                                ?>
                                    <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            <?php foreach ($extraImages as $index => $img): ?>
                                                <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="carousel-inner">
                                            <?php foreach ($extraImages as $index => $img): ?>
                                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                    <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (count($extraImages) > 1): ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($item['imagen']): ?>
                                    <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" class="img-fluid w-100" alt="">
                                <?php endif; ?>
                            </div>
                            
                            <!-- News Content Section -->
                            <div class="news-content-wrapper p-4 p-md-5">
                                <div class="news-meta mb-3">
                                    <span class="news-date">
                                        <i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?>
                                    </span>
                                    <span class="news-category ms-3">
                                        <i class="bi bi-tag-fill me-2"></i>Institucional
                                    </span>
                                </div>
                                
                                <h2 class="news-title-long mb-4"><?= htmlspecialchars($item['titulo']) ?></h2>
                                
                                <div class="news-body-content">
                                    <?= $item['contenido'] ?>
                                </div>
                                
                                <div class="news-footer mt-5 pt-4 border-top">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="news-share">
                                            <span class="text-muted small me-3">Compartir:</span>
                                            <a href="#" class="share-icon"><i class="bi bi-facebook"></i></a>
                                            <a href="#" class="share-icon"><i class="bi bi-twitter-x"></i></a>
                                            <a href="#" class="share-icon"><i class="bi bi-whatsapp"></i></a>
                                        </div>
                                        <div class="news-author">
                                            <span class="text-muted small">Publicado por: <strong>AGECSO</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay noticias publicadas aún.</div>
        <?php endif; ?>
    </div>
</section>
