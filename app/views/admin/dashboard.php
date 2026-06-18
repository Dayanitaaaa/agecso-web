<?php require __DIR__ . '/layouts/admin-header.php'; ?>

<div class="row g-4 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-newspaper"></i></div>
            <div class="stat-number"><?= $stats['noticias'] ?></div>
            <div class="stat-label">Noticias</div>
            <a href="<?= APP_URL ?>/?page=admin&section=noticias" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="stat-number"><?= $stats['eventos'] ?></div>
            <div class="stat-label">Eventos</div>
            <a href="<?= APP_URL ?>/?page=admin&section=eventos" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-mortarboard"></i></div>
            <div class="stat-number"><?= $stats['cursos'] ?></div>
            <div class="stat-label">Cursos</div>
            <a href="<?= APP_URL ?>/?page=admin&section=cursos" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-teal">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-number"><?= $stats['aliados'] ?></div>
            <div class="stat-label">Aliados</div>
            <a href="<?= APP_URL ?>/?page=admin&section=aliados" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-indigo">
            <div class="stat-icon"><i class="bi bi-briefcase"></i></div>
            <div class="stat-number"><?= $stats['servicios'] ?></div>
            <div class="stat-label">Servicios</div>
            <a href="<?= APP_URL ?>/?page=admin&section=servicios" class="btn btn-sm btn-outline-primary mt-2">Gestionar</a>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card stat-dark">
            <div class="stat-icon"><i class="bi bi-envelope"></i></div>
            <div class="stat-number"><?= $stats['mensajes'] ?></div>
            <div class="stat-label">Mensajes</div>
            <a href="<?= APP_URL ?>/?page=admin&section=mensajes" class="btn btn-sm btn-outline-light mt-2">Ver</a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <h5 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Resumen General</h5>
            <div style="height: 250px;">
                <canvas id="mainChart"></canvas>
            </div>
        </div>
        <div class="admin-card">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="welcome-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <h5 class="mb-1">Bienvenido al Panel</h5>
                    <p class="text-muted mb-0 small">Gestiona todo el contenido de AGECSO desde aquí</p>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=noticias" class="quick-link">
                        <div class="ql-icon ql-blue"><i class="bi bi-newspaper"></i></div>
                        <div class="ql-text"><strong>Noticias</strong><span>Gestionar noticias institucionales</span></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=eventos" class="quick-link">
                        <div class="ql-icon ql-purple"><i class="bi bi-calendar-event"></i></div>
                        <div class="ql-text"><strong>Eventos</strong><span>Administrar eventos y talleres</span></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=cursos" class="quick-link">
                        <div class="ql-icon ql-orange"><i class="bi bi-mortarboard"></i></div>
                        <div class="ql-text"><strong>Cursos</strong><span>Cursos, webinars y capacitaciones</span></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=aliados" class="quick-link">
                        <div class="ql-icon ql-teal"><i class="bi bi-people"></i></div>
                        <div class="ql-text"><strong>Aliados</strong><span>Aliados estratégicos</span></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=servicios" class="quick-link">
                        <div class="ql-icon ql-indigo"><i class="bi bi-briefcase"></i></div>
                        <div class="ql-text"><strong>Servicios</strong><span>Actualizar información</span></div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?= APP_URL ?>/?page=admin&section=mensajes" class="quick-link">
                        <div class="ql-icon ql-dark"><i class="bi bi-envelope"></i></div>
                        <div class="ql-text"><strong>Mensajes</strong><span>Formulario de contacto</span></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card system-card">
            <h5 class="mb-4"><i class="bi bi-info-circle me-2"></i>Información del sistema</h5>
            <div class="system-item">
                <i class="bi bi-person-circle"></i>
                <div>
                    <small class="text-muted">Usuario</small>
                    <div class="fw-semibold"><?= htmlspecialchars($_SESSION['admin_nombre'] ?? '') ?></div>
                </div>
            </div>
            <div class="system-item">
                <i class="bi bi-shield"></i>
                <div>
                    <small class="text-muted">Rol</small>
                    <div class="fw-semibold"><?= htmlspecialchars($_SESSION['admin_rol'] ?? '') ?></div>
                </div>
            </div>
            <div class="system-item">
                <i class="bi bi-calendar3"></i>
                <div>
                    <small class="text-muted">Fecha</small>
                    <div class="fw-semibold"><?= date('d/m/Y') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('mainChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Noticias', 'Eventos', 'Cursos', 'Aliados', 'Servicios', 'Mensajes'],
        datasets: [{
            label: 'Total de Registros',
            data: [<?= $stats['noticias'] ?>, <?= $stats['eventos'] ?>, <?= $stats['cursos'] ?>, <?= $stats['aliados'] ?>, <?= $stats['servicios'] ?>, <?= $stats['mensajes'] ?>],
            backgroundColor: [
                'rgba(15, 76, 129, 0.8)',
                'rgba(124, 58, 237, 0.8)',
                'rgba(234, 88, 12, 0.8)',
                'rgba(13, 148, 136, 0.8)',
                'rgba(79, 70, 229, 0.8)',
                'rgba(30, 42, 58, 0.8)'
            ],
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<?php require __DIR__ . '/layouts/admin-footer.php'; ?>
