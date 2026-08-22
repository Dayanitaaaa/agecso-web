<!-- Header de la Página con Gradiente AGECSO Premium -->
<div class="contact-hero-banner py-5 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #00a2ff 100%); border-bottom: 5px solid #00a2ff; position: relative; overflow: hidden;">
    <!-- Efectos de luz flotantes -->
    <div class="floating-circle" style="top: -100px; right: -50px; width: 400px; height: 400px; background: rgba(0, 162, 255, 0.15);"></div>
    <div class="floating-circle" style="bottom: -150px; left: -100px; width: 300px; height: 300px; background: rgba(0, 162, 255, 0.1);"></div>
    
    <div class="container py-5 position-relative z-1">
        <span class="badge bg-primary-glass mb-3 px-4 py-2 text-uppercase fw-bold tracking-widest animate__animated animate__fadeInDown" style="font-size: 0.75rem; letter-spacing: 2px;">Atención al Asociado</span>
        <h1 class="display-3 fw-black mb-3 text-white animate__animated animate__fadeInLeft">Conectemos <span class="text-info">hoy mismo</span></h1>
        <p class="lead mb-0 text-white-75 mx-auto animate__animated animate__fadeInUp" style="max-width: 700px; font-weight: 500;">
            Tu crecimiento es nuestra prioridad. Estamos listos para escuchar tus proyectos y brindarte el respaldo gremial que necesitas.
        </p>
    </div>
</div>

<section class="section-padding bg-modern-light">
    <div class="container">
        <div class="row g-5">
            <!-- Columna Izquierda: Información de Contacto -->
            <div class="col-lg-5">
                <div class="pe-lg-5">
                    <h2 class="fw-black text-dark mb-4 position-relative pb-2">
                        Canales Directos
                        <div class="accent-line"></div>
                    </h2>
                    <p class="text-muted mb-5">Elige el medio que prefieras para comunicarte con nosotros. Un asesor especializado te atenderá.</p>
                    
                    <!-- Tarjeta: Correo -->
                    <div class="contact-info-card mb-4 animate-on-scroll">
                        <div class="icon-box bg-primary-soft">
                            <i class="bi bi-envelope-at-fill"></i>
                        </div>
                        <div class="content">
                            <h6>Correo Electrónico</h6>
                            <a href="mailto:info@agecso.org" class="hover-blue">info@agecso.org</a>
                        </div>
                    </div>

                    <!-- Tarjeta: Teléfono -->
                    <div class="contact-info-card mb-4 animate-on-scroll">
                        <div class="icon-box bg-info-soft">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="content">
                            <h6>WhatsApp / Teléfono</h6>
                            <a href="tel:+573118772577" class="hover-blue">+57 311 877 2577</a>
                        </div>
                    </div>

                    <!-- Tarjeta: Ubicación -->
                    <div class="contact-info-card mb-5 animate-on-scroll">
                        <div class="icon-box bg-success-soft">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="content">
                            <h6>Nuestra Sede</h6>
                            <p>Parque Industrial San Jorge, Of. 244<br><small>Mosquera, Cundinamarca</small></p>
                        </div>
                    </div>

                    <!-- Mapa de Google Maps con Estilo -->
                    <div class="rounded-4 overflow-hidden shadow-premium border-0 mt-2">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.4382894371465!2d-74.2255555!3d4.7000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9dbb55555555%3A0x5555555555555555!2sParque%20Industrial%20San%20Jorge!5e0!3m2!1ses!2sco!4v1700000000000!5m2!1ses!2sco" 
                            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario Moderno -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                    <div class="card-header bg-dark p-4 p-md-5 text-white position-relative">
                        <div class="floating-circle" style="top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(0, 162, 255, 0.2);"></div>
                        <h3 class="fw-bold mb-2 position-relative z-1">Envíanos un mensaje</h3>
                        <p class="text-white-50 mb-0 position-relative z-1 small">Responderemos a tu solicitud en menos de 24 horas hábiles.</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 bg-white">
                        <?php if ($message): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
                                <i class="bi <?= $messageType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> fs-4 me-3"></i>
                                <div><?= htmlspecialchars($message) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= APP_URL ?>/?page=contacto" class="row g-4 needs-validation" novalidate>
                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Nombre Completo</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-person icon"></i>
                                    <input type="text" name="nombre" class="form-control-modern" placeholder="Ej. Juan Pérez" required>
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Correo Corporativo</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-envelope icon"></i>
                                    <input type="email" name="email" class="form-control-modern" placeholder="correo@empresa.com" required>
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Teléfono / Celular</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-phone icon"></i>
                                    <input type="tel" name="telefono" class="form-control-modern" placeholder="311 000 0000">
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Asunto</label>
                                <div class="input-group-modern">
                                    <i class="bi bi-tag icon"></i>
                                    <input type="text" name="asunto" class="form-control-modern" placeholder="Motivo del contacto">
                                </div>
                            </div>

                            <div class="col-12 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">¿En qué podemos ayudarte?</label>
                                <textarea name="mensaje" class="form-control-modern" rows="5" placeholder="Cuéntanos más detalles sobre tu solicitud..." required></textarea>
                            </div>

                            <!-- Honeypot field (Antispam) - Invisible para humanos -->
                            <div style="display:none !important;">
                                <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="col-12 pt-3">
                                <button type="submit" class="btn btn-premium-blue w-100 py-3 rounded-pill text-uppercase fw-black tracking-widest shadow-lg">
                                    <i class="bi bi-send-check-fill me-2"></i>Enviar Mensaje
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .fw-black { font-weight: 900; }
    .tracking-widest { letter-spacing: 0.15em; }
    
    .floating-circle {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        z-index: 0;
    }

    .accent-line {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 5px;
        background: linear-gradient(90deg, #00a2ff, #005fa3);
        border-radius: 10px;
    }

    .contact-info-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .contact-info-card:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 30px rgba(0,162,255,0.08);
        border-color: rgba(0,162,255,0.1);
    }

    .icon-box {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .bg-primary-soft { background: rgba(0, 162, 255, 0.1); color: #00a2ff; }
    .bg-info-soft { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .bg-success-soft { background: rgba(25, 135, 84, 0.1); color: #198754; }

    .contact-info-card h6 {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .contact-info-card a, .contact-info-card p {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        margin-bottom: 0;
        line-height: 1.3;
    }

    .shadow-premium {
        box-shadow: 0 20px 50px rgba(0,0,0,0.05) !important;
    }

    .input-group-modern {
        position: relative;
        margin-bottom: 5px;
    }

    .input-group-modern .icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #00a2ff;
        font-size: 1.2rem;
        z-index: 10;
    }

    .form-control-modern {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .form-control-modern:focus {
        background: #fff;
        border-color: #00a2ff;
        box-shadow: 0 0 0 4px rgba(0, 162, 255, 0.1);
        outline: none;
    }

    textarea.form-control-modern {
        padding-left: 15px;
    }

    .bg-modern-light {
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    }
</style>

