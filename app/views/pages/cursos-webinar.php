<?php
// Título de la página
$title = 'Academia AGECSO - Cursos y Webinars';
?>

<style>
    .academy-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        position: relative;
        overflow: hidden;
        padding: 80px 0;
        border-bottom: 4px solid #00a2ff;
    }

    .academy-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('assets/img/pattern.png'); /* Si existe un patrón, si no, usaremos círculos CSS */
        opacity: 0.1;
    }

    .floating-circle {
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(0, 162, 255, 0.1);
        border-radius: 50%;
        filter: blur(80px);
        z-index: 0;
    }

    .academy-title {
        font-weight: 900;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    .course-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .course-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: rgba(0, 162, 255, 0.2);
    }

    .course-type-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .badge-curso { background: rgba(0, 162, 255, 0.1); color: #00a2ff; }
    .badge-webinar { background: rgba(88, 165, 92, 0.1); color: #58a55c; }

    .course-icon-wrapper {
        width: 60px;
        height: 60px;
        background: #f8fafc;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: #00a2ff;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .course-card:hover .course-icon-wrapper {
        background: #00a2ff;
        color: #fff;
        transform: rotate(-5deg) scale(1.1);
    }

    .course-meta {
        font-size: 0.85rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .course-meta i {
        color: #00a2ff;
    }

    .filter-btn {
        padding: 10px 25px;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .filter-btn.active {
        background: #00a2ff;
        color: #fff;
        border-color: #00a2ff;
        box-shadow: 0 4px 12px rgba(0, 162, 255, 0.2);
    }
</style>

<!-- Hero Section -->
<div class="academy-hero text-white">
    <div class="floating-circle" style="top: -100px; right: -50px;"></div>
    <div class="floating-circle" style="bottom: -150px; left: -100px; background: rgba(88, 165, 92, 0.05);"></div>
    
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-primary-glass mb-3 px-3 py-2 text-uppercase tracking-wider animate__animated animate__fadeInDown">Formación y Conocimiento</span>
                <h1 class="display-3 academy-title mb-4 animate__animated animate__fadeInLeft">Academia <span class="text-info">AGECSO</span></h1>
                <p class="lead text-white-75 mb-0 animate__animated animate__fadeInUp">Explora nuestro histórico de capacitaciones, cursos y webinars diseñados para potenciar el talento de la región Sabana Occidente.</p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <i class="bi bi-mortarboard text-white-50" style="font-size: 10rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<section class="section-padding bg-modern-light">
    <div class="container">
        <!-- Filters (Visual only for now, can be implemented with JS) -->
        <div class="text-center mb-5">
            <button class="filter-btn active">Todos</button>
            <button class="filter-btn">Cursos</button>
            <button class="filter-btn">Webinars</button>
        </div>

        <?php if (!empty($data)): ?>
            <div class="row g-4">
                <?php foreach ($data as $item): ?>
                    <div class="col-lg-6">
                        <div class="course-card p-4 h-100">
                            <span class="course-type-badge badge-<?= strtolower($item['tipo']) ?>">
                                <?= ucfirst($item['tipo']) ?>
                            </span>
                            
                            <div class="course-icon-wrapper">
                                <i class="bi <?= $item['tipo'] === 'curso' ? 'bi-journal-bookmark-fill' : 'bi-camera-video-fill' ?>"></i>
                            </div>

                            <h3 class="h4 fw-bold text-dark mb-3"><?= htmlspecialchars($item['titulo']) ?></h3>
                            <p class="text-muted small mb-4"><?= htmlspecialchars($item['descripcion']) ?></p>

                            <div class="course-meta">
                                <?php if ($item['instructor']): ?>
                                    <span><i class="bi bi-person-badge me-2"></i><?= htmlspecialchars($item['instructor']) ?></span>
                                <?php endif; ?>
                                
                                <?php if ($item['fecha_inicio']): ?>
                                    <span><i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($item['fecha_inicio'])) ?></span>
                                <?php endif; ?>

                                <?php if ($item['duracion']): ?>
                                    <span><i class="bi bi-clock-history me-2"></i><?= htmlspecialchars($item['duracion']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="text-muted">No hay cursos registrados aún.</h4>
                <p class="text-muted">Vuelve pronto para conocer nuestras próximas capacitaciones.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

