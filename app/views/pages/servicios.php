<section class="section-padding bg-light">
    <div class="container">
        <h1 class="mb-4">Servicios</h1>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-service p-4 h-100">
                            <i class="bi <?= htmlspecialchars($item['icono']) ?> fs-1 text-primary mb-3"></i>
                            <h3><?= htmlspecialchars($item['titulo']) ?></h3>
                            <p><?= htmlspecialchars($item['descripcion']) ?></p>
                            <?php if ($item['contenido']): ?>
                                <a href="#" class="btn btn-sm btn-outline-primary mt-auto">Ver más</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay servicios registrados aún.</div>
        <?php endif; ?>
    </div>
</section>
