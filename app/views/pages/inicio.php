<section class="hero-carousel-section position-relative">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <!-- Indicators/Dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.75), rgba(0, 162, 255, 0.35)), url('<?= APP_URL ?>/assets/img/1principal.jpeg');">
                <div class="container hero-content-container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8 text-start">
                            <span class="badge bg-primary mb-3 text-uppercase px-3 py-2" style="font-weight: 700; letter-spacing: 1px;">Asociación Gremial</span>
                            <h1 class="display-3 fw-black mb-4 text-white">Crecimiento <span class="highlight-text">sostenible y rentable</span> en Sabana Occidente</h1>
                            <p class="lead mb-4 text-white-50">AGECSO impulsa la conexión empresarial, la visibilidad, el networking y la generación de oportunidades para sus miembros.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="<?= APP_URL ?>/?page=servicios" class="btn btn-light btn-lg px-4 py-2">Conocer servicios</a>
                                <a href="<?= BUSINESS_PLATFORM_URL ?>" class="btn btn-agecso btn-lg px-4 py-2">Rueda de Negocios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.75), rgba(0, 162, 255, 0.35)), url('<?= APP_URL ?>/assets/img/2principal.jpeg');">
                <div class="container hero-content-container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8 text-start">
                            <span class="badge bg-info mb-3 text-uppercase px-3 py-2 text-dark" style="font-weight: 700; letter-spacing: 1px;">Sinergia y Alianzas</span>
                            <h1 class="display-3 fw-black mb-4 text-white">Conectamos los nodos del <span class="highlight-text">desarrollo económico</span></h1>
                            <p class="lead mb-4 text-white-50">Facilitamos espacios de integración estratégica y alianzas con agencias públicas y privadas para potenciar tu marca.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="<?= APP_URL ?>/?page=nosotros" class="btn btn-light btn-lg px-4 py-2">Quiénes Somos</a>
                                <a href="<?= BUSINESS_PLATFORM_URL ?>" class="btn btn-agecso btn-lg px-4 py-2">Rueda de Negocios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.75), rgba(0, 162, 255, 0.35)), url('<?= APP_URL ?>/assets/img/3principal.jpeg');">
                <div class="container hero-content-container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8 text-start">
                            <span class="badge bg-success mb-3 text-uppercase px-3 py-2" style="font-weight: 700; letter-spacing: 1px;">Educación Corporativa</span>
                            <h1 class="display-3 fw-black mb-4 text-white">Fortaleciendo el <span class="highlight-text">tejido empresarial</span></h1>
                            <p class="lead mb-4 text-white-50">Capacitaciones mensuales, webinars, conferencias y talleres de la mano con agencias y aliados estratégicos del más alto nivel.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="<?= APP_URL ?>/?page=servicios" class="btn btn-light btn-lg px-4 py-2">Ver Servicios</a>
                                <a href="<?= BUSINESS_PLATFORM_URL ?>" class="btn btn-agecso btn-lg px-4 py-2">Rueda de Negocios</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="carousel-item" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.75), rgba(0, 162, 255, 0.35)), url('<?= APP_URL ?>/assets/img/4principal.jpeg');">
                <div class="container hero-content-container">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8 text-start">
                            <span class="badge bg-warning mb-3 text-dark text-uppercase px-3 py-2" style="font-weight: 700; letter-spacing: 1px;">Rueda de Negocios</span>
                            <h1 class="display-3 fw-black mb-4 text-white">El <span class="highlight-text">encuentro comercial</span> del año</h1>
                            <p class="lead mb-4 text-white-50">Genera sinergia, amplía tu red de contactos y haz negocios reales con grandes empresas compradoras de la región.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="<?= BUSINESS_PLATFORM_URL ?>" class="btn btn-light btn-lg px-4 py-2">Ingresar a Rueda</a>
                                <a href="<?= APP_URL ?>/?page=contacto" class="btn btn-agecso btn-lg px-4 py-2">Contáctanos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<section class="section-padding bg-light">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5 animate-fade-in">
            <span class="text-uppercase tracking-wider fw-bold text-primary" style="font-size: 0.85rem; letter-spacing: 0.15em;">Nuestros Pilares</span>
            <h2 class="display-5 fw-bold text-dark mt-2">¿Cómo impulsamos tu empresa?</h2>
            <div class="mx-auto mt-3" style="width: 60px; height: 4px; background: linear-gradient(90deg, #00a2ff, #008ae0); border-radius: 2px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card card-service p-4 h-100 border-0">
                    <div class="card-icon-box blue-glow">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mt-3 mb-2">Generamos Negocios</h4>
                    <p class="text-muted leading-relaxed mb-3" style="font-size: 0.95rem;">Promovemos conexiones estratégicas y de valor entre empresarios, aliados y entidades públicas de la región.</p>
                    <a href="<?= APP_URL ?>/?page=servicios" class="card-link mt-auto">Conocer más <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card card-service p-4 h-100 border-0">
                    <div class="card-icon-box blue-glow">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mt-3 mb-2">Educación Corporativa</h4>
                    <p class="text-muted leading-relaxed mb-3" style="font-size: 0.95rem;">Impulsamos cursos prácticos, webinars de actualidad y talleres especializados para fortalecer el capital humano.</p>
                    <a href="<?= APP_URL ?>/?page=servicios" class="card-link mt-auto">Conocer más <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card card-service p-4 h-100 border-0">
                    <div class="card-icon-box blue-glow">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mt-3 mb-2">Representación Gremial</h4>
                    <p class="text-muted leading-relaxed mb-3" style="font-size: 0.95rem;">Acompañamos iniciativas gubernamentales y políticas que dinamizan el desarrollo económico local.</p>
                    <a href="<?= APP_URL ?>/?page=servicios" class="card-link mt-auto">Conocer más <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="rueda-cta-section py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <!-- Left Column: Content and benefits -->
            <div class="col-lg-6 text-start">
                <span class="text-uppercase tracking-wider fw-bold text-primary" style="font-size: 0.85rem; letter-spacing: 0.15em;">Plataforma de Matchmaking</span>
                <h2 class="display-5 fw-bold text-dark mt-2 mb-3">Rueda de Negocios AGECSO</h2>
                <p class="text-muted leading-relaxed mb-4" style="font-size: 1.05rem;">
                    Nuestra plataforma digital está diseñada para que <strong>empresarios, de todo nivel, compradores y vendedores</strong> de Sabana Occidente conecten, agenden reuniones comerciales y cierren alianzas estratégicas de alto valor.
                </p>
                
                <!-- Benefit list -->
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="benefit-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Conexión Estratégica</h5>
                            <p class="text-muted mb-0" style="font-size: 0.92rem;">Establece relaciones comerciales directas con grandes compradores y proveedores de la región.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="benefit-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Agendamiento Inteligente</h5>
                            <p class="text-muted mb-0" style="font-size: 0.92rem;">Programa citas de negocios automáticas de 20 minutos según los perfiles de tu interés.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="benefit-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Visibilidad y Crecimiento</h5>
                            <p class="text-muted mb-0" style="font-size: 0.92rem;">Publica tus ofertas y demandas comerciales, y haz seguimiento a tus reuniones agendadas.</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BUSINESS_PLATFORM_URL ?>/index.php?controlador=usuario&accion=registro" class="btn btn-primary btn-lg rounded-pill px-4 py-2.5 fw-bold shadow-lg" style="background: linear-gradient(135deg, #00a2ff 0%, #008ae0 100%); border: none;">
                        <i class="bi bi-user-plus-fill me-2"></i>Registrar mi Empresa
                    </a>
                    <a href="<?= BUSINESS_PLATFORM_URL ?>/index.php?controlador=usuario&accion=login" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-2.5 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>

            <!-- Right Column: Pure CSS Dashboard Mockup -->
            <div class="col-lg-6">
                <div class="rueda-mockup-wrapper position-relative">
                    <!-- Ambient Glow in the background -->
                    <div class="mockup-bg-glow"></div>
                    
                    <!-- Dashboard Card -->
                    <div class="dashboard-mockup-card shadow-2xl rounded-2xl overflow-hidden border">
                        <!-- Header of mockup -->
                        <div class="mockup-header d-flex align-items-center justify-content-between p-3 bg-dark text-white">
                            <div class="d-flex align-items-center gap-2">
                                <div class="mockup-dot red"></div>
                                <div class="mockup-dot yellow"></div>
                                <div class="mockup-dot green"></div>
                                <span class="text-white-50 ms-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">agecso.org/rueda/panel</span>
                            </div>
                            <div class="badge bg-primary px-2 py-1" style="font-size: 0.65rem;">EN VIVO</div>
                        </div>
                        
                        <!-- Content of mockup -->
                        <div class="mockup-body p-4 bg-light">
                            <!-- Welcome Banner -->
                            <div class="mockup-banner p-3 rounded-xl mb-3 text-white d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">¡Bienvenido al Panel de Negocios!</h6>
                                    <p class="mb-0 text-white-50" style="font-size: 0.75rem;">Completa tu perfil para iniciar agendamientos.</p>
                                </div>
                                <i class="bi bi-star-fill text-warning fs-5"></i>
                            </div>

                            <!-- Stat Grid -->
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="mockup-stat-card p-2 rounded-xl bg-white border text-center">
                                        <span class="d-block text-muted" style="font-size: 0.65rem;">Empresas</span>
                                        <strong class="d-block text-primary" style="font-size: 1.1rem;">150+</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mockup-stat-card p-2 rounded-xl bg-white border text-center">
                                        <span class="d-block text-muted" style="font-size: 0.65rem;">Ofertas</span>
                                        <strong class="d-block text-success" style="font-size: 1.1rem;">210+</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mockup-stat-card p-2 rounded-xl bg-white border text-center">
                                        <span class="d-block text-muted" style="font-size: 0.65rem;">Reuniones</span>
                                        <strong class="d-block text-warning" style="font-size: 1.1rem;">380+</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Meeting Schedule List Mockup -->
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Agenda de Reuniones</h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="mockup-meeting-row p-2 rounded-xl bg-white border d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="mockup-avatar blue">A</div>
                                        <div>
                                            <strong class="d-block text-dark" style="font-size: 0.75rem;">Alimentos del Campo S.A.</strong>
                                            <span class="text-muted" style="font-size: 0.65rem;">Proveedor de Insumos</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success text-white" style="font-size: 0.65rem; border: none;">Aprobada</span>
                                </div>
                                <div class="mockup-meeting-row p-2 rounded-xl bg-white border d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="mockup-avatar green">B</div>
                                        <div>
                                            <strong class="d-block text-dark" style="font-size: 0.75rem;">Distribuidora Occidente</strong>
                                            <span class="text-muted" style="font-size: 0.65rem;">Comprador Mayorista</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem; border: none;">Pendiente</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Glass Badge on top of mockup -->
                    <div class="mockup-floating-badge shadow-lg rounded-2xl p-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-check-circle-fill fs-5 text-white"></i>
                        </div>
                        <div>
                            <span class="d-block text-muted" style="font-size: 0.7rem; font-weight: 600;">Match de Negocio</span>
                            <strong class="d-block text-dark" style="font-size: 0.85rem;">¡Nueva cita agendada!</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="logo-marquee-section">
    <div class="container">
        <div class="text-center mb-4">
            <h4 class="text-uppercase tracking-wider fw-bold text-secondary" style="font-size: 0.9rem; letter-spacing: 0.15em;">Nuestros Miembros y Aliados</h4>
            <div class="mx-auto mt-2" style="width: 50px; height: 3px; background: linear-gradient(90deg, #00a2ff, #008ae0); border-radius: 2px;"></div>
        </div>
        <div class="logo-slider-container">
            <div class="logo-slider-track">
                <?php
                $logos = [
                    '1logo.jpeg', '2logo.jpeg', '3logo.jpeg', '4logo.jpeg', '5logo.jpeg',
                    '6logo.jpeg', '7logo.jpeg', '8logo.jpeg', '9logo.jpeg', '10logo.png',
                    '11logo.jpg', '12logo.png', '13logo.png'
                ];
                // Loop twice to ensure infinite, seamless scrolling
                for ($i = 0; $i < 2; $i++) {
                    foreach ($logos as $logo) {
                        echo '<div class="logo-slide">';
                        echo '<img src="' . APP_URL . '/assets/img/' . $logo . '" alt="Logo Aliado">';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($data['noticias'])): ?>
<section class="section-padding">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Últimas Noticias</h2>
            <a href="<?= APP_URL ?>/?page=noticias" class="btn btn-outline-primary">Ver todas</a>
        </div>
        <div class="row g-4">
            <?php foreach ($data['noticias'] as $item): ?>
                <div class="col-md-4">
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
    </div>
</section>
<?php endif; ?>

<?php if (!empty($data['eventos'])): ?>
<section class="section-padding bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Próximos Eventos</h2>
            <a href="<?= APP_URL ?>/?page=eventos" class="btn btn-outline-primary">Ver todos</a>
        </div>
        <div class="row g-4">
            <?php foreach ($data['eventos'] as $item): ?>
                <div class="col-md-4">
                    <div class="card card-service p-4 h-100">
                        <h5><?= htmlspecialchars($item['titulo']) ?></h5>
                        <p><?= htmlspecialchars(substr($item['descripcion'], 0, 100)) ?>...</p>
                        <?php if ($item['fecha_evento']): ?>
                            <p class="mb-0"><small class="text-muted"><i class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($item['fecha_evento'])) ?></small></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
