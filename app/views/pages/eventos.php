<section class="section-padding">
    <div class="container">
        <p class="text-uppercase fw-bold text-success">¡Conectamos los Nodos del Desarrollo Económico en Sabana de Occidente!</p>
        <h1 class="mb-4">Nuestros Eventos</h1>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-service p-4 h-100">
                            <h3><?= htmlspecialchars($item['titulo']) ?></h3>
                            <p><?= htmlspecialchars(substr($item['descripcion'], 0, 120)) ?>...</p>
                            <?php if ($item['fecha_evento']): ?>
                                <p class="mb-0"><small class="text-muted"><i class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($item['fecha_evento'])) ?></small></p>
                            <?php endif; ?>
                            <?php if ($item['lugar']): ?>
                                <p class="mb-0"><small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['lugar']) ?></small></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay eventos registrados aún.</div>
        <?php endif; ?>
    </div>
</section>
