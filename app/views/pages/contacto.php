<!-- Header de la Página con Gradiente AGECSO -->
<div class="contact-hero-banner py-5 text-center text-white" style="background: linear-gradient(135deg, #002e53 0%, #005fa3 50%, #00a2ff 100%); border-bottom: 5px solid #00a2ff; position: relative; overflow: hidden;">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-10">
        <div style="position: absolute; top: -20%; right: -10%; width: 300px; height: 300px; background: white; border-radius: 50%; filter: blur(80px);"></div>
    </div>
    
    <div class="container py-5 position-relative z-1">
        <span class="badge bg-white text-primary mb-3 px-3 py-2 text-uppercase fw-bold tracking-widest shadow-sm" style="font-size: 0.75rem; letter-spacing: 2px;">Atención al Asociado</span>
        <h1 class="display-3 fw-black mb-3 text-white">¿Cómo podemos <span class="text-info">ayudarte?</span></h1>
        <p class="lead mb-0 text-white-75 mx-auto" style="max-width: 700px; font-weight: 500;">
            Estamos aquí para resolver tus dudas, atender tus solicitudes y acompañar el crecimiento de tu negocio en Sabana Occidente.
        </p>
    </div>
</div>

<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Columna Izquierda: Información y Mapa -->
            <div class="col-lg-5">
                <div class="pe-lg-4">
                    <h2 class="fw-bold text-dark mb-4 position-relative pb-2">
                        Canales Directos
                        <div style="position: absolute; bottom: 0; left: 0; width: 50px; height: 4px; background: #00a2ff; border-radius: 2px;"></div>
                    </h2>
                    <p class="text-muted mb-5">Utiliza cualquiera de nuestros medios oficiales para comunicarte con nosotros. Estamos listos para escucharte.</p>
                    
                    <!-- Tarjeta: Correo -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 contact-card-hover transition-all">
                        <div class="card-body p-4 d-flex align-items-center gap-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                                <i class="bi bi-envelope-paper-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Correo Electrónico</h6>
                                <a href="mailto:info@agecso.org" class="text-dark fw-bold text-decoration-none fs-5 hover-blue">info@agecso.org</a>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta: Teléfono -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 contact-card-hover transition-all">
                        <div class="card-body p-4 d-flex align-items-center gap-4">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info">
                                <i class="bi bi-whatsapp fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">WhatsApp / Teléfono</h6>
                                <a href="tel:+573118772577" class="text-dark fw-bold text-decoration-none fs-5 hover-blue">+57 311 877 2577</a>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta: Ubicación -->
                    <div class="card border-0 shadow-sm rounded-4 mb-5 contact-card-hover transition-all">
                        <div class="card-body p-4 d-flex align-items-center gap-4">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                                <i class="bi bi-geo-alt-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Nuestra Sede</h6>
                                <p class="text-dark fw-bold mb-0" style="line-height: 1.4;">Parque Industrial San Jorge, Of. 244<br><small class="text-muted">Mosquera, Cundinamarca</small></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa de Google Maps -->
                    <div class="rounded-4 overflow-hidden shadow-sm border border-light">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.4382894371465!2d-74.2255555!3d4.7000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9dbb55555555%3A0x5555555555555555!2sParque%20Industrial%20San%20Jorge!5e0!3m2!1ses!2sco!4v1700000000000!5m2!1ses!2sco" 
                            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario Premium -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-light border-0 p-4 p-md-5">
                        <h3 class="fw-bold text-dark mb-2">Envíanos un mensaje</h3>
                        <p class="text-muted mb-0">Completa el formulario y un asesor se pondrá en contacto contigo a la brevedad posible.</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 bg-white">
                        <?php if ($message): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                                <i class="bi <?= $messageType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> fs-4 me-3"></i>
                                <div><?= htmlspecialchars($message) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= APP_URL ?>/?page=contacto" class="row g-4 needs-validation" novalidate>
                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-primary px-3"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nombre" class="form-control bg-light border-0 py-3 px-3 rounded-end-3" placeholder="Tu nombre..." required>
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Correo Corporativo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-primary px-3"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-0 py-3 px-3 rounded-end-3" placeholder="correo@empresa.com" required>
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Teléfono / Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-primary px-3"><i class="bi bi-phone"></i></span>
                                    <input type="tel" name="telefono" class="form-control bg-light border-0 py-3 px-3 rounded-end-3" placeholder="Ej. 311 000 0000">
                                </div>
                            </div>

                            <div class="col-md-6 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">Asunto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-primary px-3"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="asunto" class="form-control bg-light border-0 py-3 px-3 rounded-end-3" placeholder="Motivo de tu mensaje">
                                </div>
                            </div>

                            <div class="col-12 text-start">
                                <label class="form-label text-dark fw-bold small text-uppercase tracking-wider">¿En qué podemos ayudarte?</label>
                                <textarea name="mensaje" class="form-control bg-light border-0 py-3 px-4 rounded-3" rows="5" placeholder="Cuéntanos más detalles sobre tu solicitud..." required></textarea>
                            </div>

                            <!-- Honeypot field (Antispam) - Invisible para humanos -->
                            <div style="display:none !important;">
                                <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="col-12 pt-3">
                                <button type="submit" class="btn btn-footer-cta w-100 py-3 rounded-pill text-uppercase fw-black tracking-widest shadow-lg">
                                    <i class="bi bi-send-fill me-2"></i>Enviar Solicitud
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
    .contact-card-hover { cursor: pointer; border: 1px solid transparent !important; }
    .contact-card-hover:hover { 
        transform: translateX(5px); 
        box-shadow: 0 10px 25px rgba(0, 162, 255, 0.1) !important;
        border-left: 4px solid #00a2ff !important;
    }
    .transition-all { transition: all 0.3s ease; }
    .hover-blue:hover { color: #00a2ff !important; }
    .input-group:focus-within .input-group-text { background-color: #e9ecef !important; color: #00a2ff !important; }
    .input-group:focus-within .form-control { background-color: #fff !important; }
</style>

