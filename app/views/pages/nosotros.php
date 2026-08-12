<!-- Hero Carousel for Nosotros -->
<section class="about-hero-section position-relative">
    <div id="aboutCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="5" aria-label="Slide 6"></button>
        </div>

        <div class="carousel-inner shadow-lg">
            <?php for($i = 1; $i <= 6; $i++): ?>
            <div class="carousel-item <?= $i === 1 ? 'active' : '' ?>" style="height: 500px; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/img/carruselN<?= $i ?>.jpeg'); background-size: cover; background-position: center;">
                <div class="container h-100 d-flex align-items-center justify-content-center text-center">
                    <div class="animate__animated animate__fadeIn">
                        <h1 class="display-3 fw-black text-white text-uppercase tracking-widest mb-0">Nuestra <span class="text-info">Historia</span></h1>
                        <div class="mx-auto mt-2" style="width: 80px; height: 5px; background: #00a2ff; border-radius: 5px;"></div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<!-- Institutional Content -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 text-start">
                <span class="badge bg-primary-glass text-primary mb-3 px-3 py-2 text-uppercase fw-bold tracking-widest" style="font-size: 0.75rem;">¿Quiénes Somos?</span>
                <h2 class="display-5 fw-black text-dark mb-4">Lideramos el <span class="text-info">Fortalecimiento</span> Empresarial</h2>
                <p class="lead text-muted mb-4">
                    La Asociación Gremial de Empresarios de Sabana Occidente (AGECSO) es el motor que impulsa el crecimiento colaborativo en nuestra región.
                </p>
                <p class="text-muted leading-relaxed mb-5">
                    [ESPACIO PARA DESCRIPCIÓN DETALLADA DE LA ASOCIACIÓN: Aquí puedes incluir cuándo se fundó, cuál fue el motivo de su creación y qué impacto ha tenido en la región hasta el día de hoy. AGECSO se enfoca en ser el puente entre el sector privado y las oportunidades de desarrollo.]
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light p-4 rounded-4 h-100">
                            <i class="bi bi-eye-fill text-primary fs-2 mb-3"></i>
                            <h5 class="fw-bold text-dark">Misión</h5>
                            <p class="text-muted small mb-0">[Aquí va la Misión oficial de AGECSO: El propósito fundamental de la organización.]</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light p-4 rounded-4 h-100">
                            <i class="bi bi-bullseye text-primary fs-2 mb-3"></i>
                            <h5 class="fw-bold text-dark">Visión</h5>
                            <p class="text-muted small mb-0">[Aquí va la Visión oficial de AGECSO: Hacia dónde quieren llegar en el futuro.]</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="position-relative p-4">
                    <!-- Imagen decorativa o representativa -->
                    <img src="assets/img/AGECSO.jpg" alt="AGECSO" class="img-fluid rounded-5 shadow-lg w-100" style="max-height: 450px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 bg-primary p-4 rounded-circle shadow-lg d-none d-md-block" style="transform: translate(20%, -20%);">
                        <span class="text-white fw-bold d-block text-center" style="font-size: 1.2rem;">+100<br><small style="font-size: 0.7rem;">ASOCIADOS</small></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .fw-black { font-weight: 900; }
    .tracking-widest { letter-spacing: 0.2em; }
    .bg-primary-glass { background: rgba(0, 162, 255, 0.1); }
    .leading-relaxed { line-height: 1.8; }
    
    #aboutCarousel .carousel-item {
        background-attachment: scroll;
    }
    
    #aboutCarousel .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255,255,255,0.5);
    }
    
    #aboutCarousel .carousel-indicators .active {
        background-color: #00a2ff;
        width: 30px;
        border-radius: 10px;
    }
</style>

<!-- Importar Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
