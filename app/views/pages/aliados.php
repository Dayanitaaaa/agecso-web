<section class="section-padding bg-light">
    <div class="container">
        <h1 class="mb-4">Aliados</h1>
        <p class="lead">AGECSO trabaja con aliados estratégicos para impulsar el desarrollo económico regional.</p>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-service p-4 h-100">
                            <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                            <?php if ($item['categoria']): ?>
                                <span class="badge bg-primary mb-2"><?= htmlspecialchars($item['categoria']) ?></span>
                            <?php endif; ?>
                            <p><?= htmlspecialchars($item['descripcion']) ?></p>
                            <?php if ($item['sitio_web']): ?>
                                <a href="<?= htmlspecialchars($item['sitio_web']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-auto">Visitar sitio</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay aliados registrados aún.</div>
        <?php endif; ?>
    </div>
</section>
