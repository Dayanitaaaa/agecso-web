<section class="section-padding bg-light">
    <div class="container">
        <h1 class="mb-4">Cursos y Webinars Realizados</h1>
        <p class="lead">Cursos, webinars y capacitaciones en los que AGECSO ha participado o realizado.</p>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-6">
                        <div class="card card-service p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h3><?= htmlspecialchars($item['titulo']) ?></h3>
                                <span class="badge bg-info"><?= ucfirst($item['tipo']) ?></span>
                            </div>
                            <p><?= htmlspecialchars($item['descripcion']) ?></p>
                            <?php if ($item['instructor']): ?>
                                <p class="mb-1"><small class="text-muted"><i class="bi bi-person"></i> <?= htmlspecialchars($item['instructor']) ?></small></p>
                            <?php endif; ?>
                            <?php if ($item['fecha_inicio']): ?>
                                <p class="mb-1"><small class="text-muted"><i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($item['fecha_inicio'])) ?></small></p>
                            <?php endif; ?>
                            <?php if ($item['duracion']): ?>
                                <p class="mb-0"><small class="text-muted"><i class="bi bi-clock"></i> <?= htmlspecialchars($item['duracion']) ?></small></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay cursos registrados aún.</div>
        <?php endif; ?>
    </div>
</section>
