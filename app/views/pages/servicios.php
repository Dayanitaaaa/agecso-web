<?php
// Título de la página
$title = 'Servicios - AGECSO';
?>

<style>
    /* Estilos Premium para el Carrusel de Servicios */
    .hero-carousel-section {
        overflow: hidden;
        min-height: 400px !important; /* Reducir altura para quitar el bloque negro */
        background: #fff !important; /* Cambiar fondo negro por blanco */
    }
    
    .carousel-item {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        min-height: 400px !important; /* Ajustar altura */
        transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1), opacity 1.2s ease-in-out !important;
    }

    .hero-carousel-section h2 {
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        line-height: 1.1;
        letter-spacing: -0.01em;
    }
    
    .hero-carousel-section .lead {
        font-weight: 500;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
    }

    /* Badges estilo Glassmorphism */
    .bg-primary-glass { background: rgba(0, 162, 255, 0.25); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); }
    .bg-info-glass { background: rgba(13, 202, 240, 0.25); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); }
    .bg-success-glass { background: rgba(25, 135, 84, 0.25); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); }

    /* Botones Premium */
    .btn-premium-blue {
        background: linear-gradient(135deg, #00a2ff 0%, #008ae0 100%);
        color: #fff;
        border: none;
        font-weight: 800;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 162, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-premium-blue:hover {
        background: linear-gradient(135deg, #33b5ff 0%, #0099ff 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 162, 255, 0.5);
        color: #fff;
    }

    .btn-premium-light {
        background: #fff;
        color: #002e53;
        border: none;
        font-weight: 800;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .btn-premium-light:hover {
        background: #f0f9ff;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
    }

    .hero-carousel-section .carousel-item.active h2,
    .hero-carousel-section .carousel-item.active p,
    .hero-carousel-section .carousel-item.active .badge,
    .hero-carousel-section .carousel-item.active .btn {
        animation: slideUpFade 0.9s cubic-bezier(0.215, 0.610, 0.355, 1) both;
    }

    @keyframes slideUpFade {
        0% { transform: translateY(30px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
</style>

<!-- Hero Carousel for Services Page -->

<section class="hero-carousel-section position-relative">
    <div id="servicesCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: linear-gradient(to right, rgba(0, 24, 48, 0.7), rgba(0, 95, 163, 0.2)), url('assets/img/2principal.jpeg'); background-size: cover; background-position: center; min-height: 400px;">
                <div class="container py-5 d-flex align-items-center" style="min-height: 400px;">
                    <div class="text-white animate__animated animate__fadeInLeft">
                        <span class="badge bg-primary-glass mb-3 px-3 py-2 text-uppercase tracking-wider">Crecimiento Empresarial</span>
                        <h2 class="display-4 fw-bold mb-3">Servicios Estratégicos</h2>
                        <p class="lead mb-4 text-white-75" style="max-width: 600px;">Impulsamos la competitividad de tu empresa a través de soluciones diseñadas para el mercado actual.</p>
                        <a href="#nuestros-servicios" class="btn btn-premium-blue px-4 py-2">Explorar servicios</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: linear-gradient(to right, rgba(0, 24, 48, 0.7), rgba(0, 162, 255, 0.2)), url('assets/img/3principal.jpeg'); background-size: cover; background-position: center; min-height: 400px;">
                <div class="container py-5 d-flex align-items-center" style="min-height: 400px;">
                    <div class="text-white animate__animated animate__fadeInLeft">
                        <span class="badge bg-info-glass mb-3 px-3 py-2 text-uppercase tracking-wider">Networking de Valor</span>
                        <h2 class="display-4 fw-bold mb-3">Conexiones Reales</h2>
                        <p class="lead mb-4 text-white-75" style="max-width: 600px;">Generamos espacios donde los negocios suceden. Tu marca en el centro del ecosistema regional.</p>
                        <a href="<?= APP_URL ?>/?page=contacto" class="btn btn-premium-light px-4 py-2">Solicitar asesoría</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: linear-gradient(to right, rgba(0, 24, 48, 0.7), rgba(0, 162, 255, 0.2)), url('assets/img/4principal.jpeg'); background-size: cover; background-position: center; min-height: 400px;">
                <div class="container py-5 d-flex align-items-center" style="min-height: 400px;">
                    <div class="text-white animate__animated animate__fadeInLeft">
                        <span class="badge bg-success-glass mb-3 px-3 py-2 text-uppercase tracking-wider">Formación Continua</span>
                        <h2 class="display-4 fw-bold mb-3">Educación de Alto Nivel</h2>
                        <p class="lead mb-4 text-white-75" style="max-width: 600px;">Capacitamos a tu equipo con expertos en las áreas más demandadas por la industria hoy.</p>
                        <a href="<?= APP_URL ?>/?page=cursos-webinar" class="btn btn-premium-blue px-4 py-2">Ver capacitaciones</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<section id="nuestros-servicios" class="section-padding bg-modern-light">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5 animate-fade-in">
            <span class="text-uppercase tracking-wider fw-bold text-primary mb-2 d-inline-block" style="font-size: 0.85rem; letter-spacing: 0.2em;">Propuesta de Valor</span>
            <h2 class="display-5 fw-black text-dark mt-2">Nuestros Servicios</h2>
            <div class="mx-auto mt-3" style="width: 50px; height: 4px; background: linear-gradient(90deg, #00a2ff, #008ae0); border-radius: 2px;"></div>
            <p class="lead text-muted mt-4 mx-auto" style="max-width: 700px;">Soluciones estratégicas diseñadas para fortalecer el crecimiento y la competitividad de los empresarios en Sabana Occidente.</p>
        </div>

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
