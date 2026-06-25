<?php
$currentPage = $_GET['page'] ?? 'inicio';
$menu = [
    'inicio' => 'Inicio',
    'nosotros' => 'Nosotros',
    'servicios' => 'Servicios',
    'eventos' => 'Eventos',
    'cursos-webinar' => 'Cursos',
    'agenda' => 'Agenda',
    'somos-agecso' => 'Somos AGECSO',
    'noticias' => 'Noticias',
    'contacto' => 'Contacto',
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?> | <?= APP_NAME ?></title>
    <meta name="description" content="AGECSO conecta empresas, aliados y oportunidades de crecimiento en Sabana Occidente.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
</head>
<body>
    <div class="page-loader" id="pageLoader">
        <img src="<?= APP_URL ?>/assets/img/AGECSO.jpg" alt="AGECSO">
        <div class="loader-spinner"></div>
        <span class="loader-text">Cargando</span>
    </div>

    <nav class="navbar navbar-expand-xl main-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand brand-wrap" href="<?= APP_URL ?>/?page=inicio">
                <img class="brand-logo" src="<?= APP_URL ?>/assets/img/AGECSO.jpg" alt="Logo AGECSO">
                <span class="brand-text text-white">AGECSO</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <?php foreach ($menu as $slug => $label): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === $slug ? 'active fw-bold' : '' ?>" href="<?= APP_URL ?>/?page=<?= $slug ?>"><?= $label ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-agecso btn-header" href="<?= BUSINESS_PLATFORM_URL ?>">Rueda de Negocios</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link nav-link-user d-flex align-items-center justify-content-center" href="<?= APP_URL ?>/?page=login" title="Panel Admin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <?php if (file_exists($viewPath)): ?>
            <?php require $viewPath; ?>
        <?php else: ?>
            <section class="section-padding"><div class="container"><h1>Página no encontrada</h1></div></section>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-main">
                <div class="row g-5">
                    <!-- Brand Column -->
                    <div class="col-lg-4 text-start">
                        <div class="footer-brand align-items-center mb-3">
                            <img class="footer-logo" src="<?= APP_URL ?>/assets/img/AGECSO.jpg" alt="Logo AGECSO">
                            <span class="brand-text text-white">AGECSO</span>
                        </div>
                        <p class="text-white-50 mb-3" style="font-size: 0.92rem; line-height: 1.7;">Conectamos el tejido empresarial, fomentamos el crecimiento sostenible y generamos sinergias comerciales en toda la provincia de Sabana Occidente.</p>
                        <!-- Social links -->
                        <div class="d-flex gap-2 mt-3 social-links-container">
                            <a href="https://www.facebook.com/" target="_blank" class="social-link-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/" target="_blank" class="social-link-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.linkedin.com/" target="_blank" class="social-link-btn" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="https://www.youtube.com/" target="_blank" class="social-link-btn" title="YouTube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                    
                    <!-- Institutional Column -->
                    <div class="col-6 col-lg-2 text-start">
                        <h5>Institucional</h5>
                        <ul class="footer-links">
                            <li><a href="<?= APP_URL ?>/?page=nosotros"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Nosotros</a></li>
                            <li><a href="<?= APP_URL ?>/?page=servicios"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Servicios</a></li>
                            <li><a href="<?= APP_URL ?>/?page=cursos-webinar"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Cursos</a></li>
                            <li><a href="<?= APP_URL ?>/?page=contacto"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Contacto</a></li>
                        </ul>
                    </div>
                    
                    <!-- Actualidad Column -->
                    <div class="col-6 col-lg-2 text-start">
                        <h5>Actualidad</h5>
                        <ul class="footer-links">
                            <li><a href="<?= APP_URL ?>/?page=eventos"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Eventos</a></li>
                            <li><a href="<?= APP_URL ?>/?page=agenda"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Agenda</a></li>
                            <li><a href="<?= APP_URL ?>/?page=noticias"><i class="bi bi-chevron-right me-1 text-primary-light"></i> Noticias</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Column -->
                    <div class="col-lg-4 text-start">
                        <h5>Contacto</h5>
                        <ul class="footer-contact-list">
                            <li>
                                <i class="bi bi-geo-alt-fill contact-icon"></i>
                                <span>Avenida Troncal Occidente # 18-76, Parque Industrial Santo Domingo, Bodega J3, Mosquera, Cundinamarca.</span>
                            </li>
                            <li>
                                <i class="bi bi-telephone-fill contact-icon"></i>
                                <a href="tel:+573118772577">+57 311 877 2577</a>
                            </li>
                            <li>
                                <i class="bi bi-envelope-fill contact-icon"></i>
                                <a href="mailto:info@agecso.org">info@agecso.org</a>
                            </li>
                        </ul>
                        <a class="btn btn-footer-cta rounded-pill mt-3 shadow-sm px-4 py-2" href="<?= APP_URL ?>/?page=contacto">
                            <i class="bi bi-chat-dots-fill me-2"></i>Escríbenos
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span>© <?= date('Y') ?> <strong>AGECSO</strong>. Todos los derechos reservados.</span>
                <span>Desarrollo Web Institucional Premium</span>
            </div>
        </div>
    </footer>

    <script>
        // Page loader
        const pageLoader = document.getElementById('pageLoader');

        // Intercept clicks to show loader before navigating
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href') || '';
                if (href.startsWith('#')) return;
                if (href.startsWith('mailto:') || href.startsWith('tel:')) return;
                if (href.startsWith('javascript:')) return;
                if (this.hasAttribute('data-no-loader')) return;
                if (this.target === '_blank') return;
                if (e.ctrlKey || e.metaKey || e.button !== 0) return;

                e.preventDefault();
                pageLoader.classList.add('active');

                setTimeout(() => {
                    window.location.href = href;
                }, 500);
            });
        });

        // Hide loader when page loads
        window.addEventListener('load', () => {
            setTimeout(() => {
                pageLoader.classList.remove('active');
            }, 300);
        });

        // Navbar shadow
        const navbar = document.querySelector('.main-navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('stuck', window.scrollY > 10);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
