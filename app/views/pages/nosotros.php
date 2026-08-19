<!-- Hero Section for Nosotros -->
<section class="about-hero-section position-relative overflow-hidden" style="height: 500px; background-image: linear-gradient(rgba(0, 24, 48, 0.7), rgba(0, 48, 96, 0.5)), url('assets/img/carruselN1.png'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container h-100 d-flex align-items-center justify-content-center text-center">
        <div class="animate__animated animate__fadeInDown">
            <span class="badge bg-info-glass text-white mb-3 px-3 py-2 text-uppercase fw-bold tracking-widest" style="font-size: 0.8rem; backdrop-filter: blur(5px);">Trayectoria y Liderazgo</span>
            <h1 class="display-2 fw-black text-white text-uppercase tracking-widest mb-0">Nuestra <span class="text-info">Historia</span></h1>
            <div class="mx-auto mt-3" style="width: 100px; height: 5px; background: #00a2ff; border-radius: 5px; box-shadow: 0 0 15px rgba(0, 162, 255, 0.5);"></div>
        </div>
    </div>
    
    <!-- Elementos decorativos -->
    <div class="position-absolute bottom-0 start-0 w-100" style="height: 100px; background: linear-gradient(to top, #fff, transparent);"></div>
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
                    <div class="col-md-12">
                        <div class="card border-0 bg-light p-4 rounded-4 h-100 mission-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                                    <i class="bi bi-eye-fill fs-3"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-0">MISIÓN</h4>
                            </div>
                            <p class="text-muted leading-relaxed mb-0">
                                En <strong>AGECSO</strong> conectamos empresarios y organizaciones para generar negocios, crear oportunidades y contribuir a su crecimiento. Fortalecemos el tejido empresarial de Sabana de Occidente mediante alianzas estratégicas, cooperación y conexiones comerciales que impulsan la productividad, la competitividad y el desarrollo sostenible de nuestra región.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-0 bg-light p-4 rounded-4 h-100 vision-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-box bg-info bg-opacity-10 p-3 rounded-circle text-info">
                                    <i class="bi bi-bullseye fs-3"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-0">VISIÓN 2028</h4>
                            </div>
                            <p class="text-muted leading-relaxed mb-0">
                                En <strong>2028</strong>, seremos la agremiación empresarial referente de Sabana de Occidente, reconocida por conectar empresarios, generar oportunidades y facilitar negocios que impulsen el crecimiento de las organizaciones, promoviendo empleo, competitividad, innovación y desarrollo sostenible para transformar positivamente nuestra región.
                            </p>
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
    .bg-info-glass { background: rgba(13, 202, 240, 0.25); }
    .leading-relaxed { line-height: 1.8; }
    
    .mission-card, .vision-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    .mission-card:hover, .vision-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 162, 255, 0.1) !important;
        background: #fff !important;
        border-color: rgba(0, 162, 255, 0.2) !important;
    }
    
    .icon-box {
        transition: all 0.3s ease;
    }
    
    .mission-card:hover .icon-box {
        background-color: var(--bs-primary) !important;
        color: white !important;
    }
    
    .vision-card:hover .icon-box {
        background-color: var(--bs-info) !important;
        color: white !important;
    }
</style>

<!-- Importar Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
