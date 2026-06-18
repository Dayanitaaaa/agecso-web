<section class="section-padding">
    <div class="container">
        <h1 class="mb-4">Noticias</h1>
        <p class="lead">Espacio para publicar noticias, comunicados y avances de gestión institucional.</p>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <?php if ($item['imagen']): ?>
                                <img src="<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="" style="height: 180px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <small class="text-muted"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></small>
                                <h5 class="card-title mt-2"><?= htmlspecialchars($item['titulo']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($item['resumen'] ?? $item['contenido'], 0, 120)) ?>...</p>
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
