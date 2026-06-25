<!-- Secondary Page Hero Header Banner -->
<div class="contact-hero-banner py-5 text-center text-white" style="background: linear-gradient(135deg, #101c2c 0%, #005fa3 50%, #00a2ff 100%); border-bottom: 4px solid #00a2ff;">
    <div class="container py-4">
        <span class="text-uppercase tracking-wider fw-bold text-white-50" style="font-size: 0.85rem; letter-spacing: 0.15em;">Canales de Atención</span>
        <h1 class="display-4 fw-bold mt-2">Ponte en Contacto</h1>
        <p class="lead mb-0 text-white-75" style="max-width: 650px; margin: 0 auto; font-size: 1.1rem;">Estamos aquí para resolver tus dudas, atender tus solicitudes y acompañar el crecimiento de tu negocio.</p>
    </div>
</div>

<section class="section-padding bg-light">
    <div class="container">
        <div class="row g-5">
            <!-- Left Column: Contact Cards -->
            <div class="col-lg-5 text-start">
                <h2 class="fw-bold text-dark mb-3">Información de Contacto</h2>
                <p class="text-muted leading-relaxed mb-4">Comunícate con la Asociación Gremial de Empresarios de Sabana Occidente por medio de nuestros canales oficiales.</p>
                
                <!-- Card Contact Info Block 1 -->
                <div class="contact-info-block p-4 rounded-4 bg-white border mb-3 d-flex align-items-start gap-3">
                    <div class="contact-info-icon-box blue-glow">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Correo Electrónico</span>
                        <h5 class="fw-bold text-dark mt-1 mb-1" style="font-size: 1.1rem;">Escríbenos</h5>
                        <a href="mailto:info@agecso.org" class="text-primary fw-semibold" style="font-size: 0.95rem; text-decoration: none;">info@agecso.org</a>
                    </div>
                </div>

                <!-- Card Contact Info Block 2 -->
                <div class="contact-info-block p-4 rounded-4 bg-white border mb-3 d-flex align-items-start gap-3">
                    <div class="contact-info-icon-box blue-glow">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Teléfono Celular</span>
                        <h5 class="fw-bold text-dark mt-1 mb-1" style="font-size: 1.1rem;">Llámanos o Escríbenos</h5>
                        <a href="tel:+573118772577" class="text-primary fw-semibold" style="font-size: 0.95rem; text-decoration: none;">+57 311 877 2577</a>
                    </div>
                </div>

                <!-- Card Contact Info Block 3 -->
                <div class="contact-info-block p-4 rounded-4 bg-white border d-flex align-items-start gap-3">
                    <div class="contact-info-icon-box blue-glow">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <span class="d-block text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Ubicación Física</span>
                        <h5 class="fw-bold text-dark mt-1 mb-1" style="font-size: 1.1rem;">Visítanos</h5>
                        <p class="text-muted mb-0" style="font-size: 0.92rem; line-height: 1.6;">Avenida Troncal Occidente # 18-76, Parque Industrial Santo Domingo, Bodega J3, Mosquera, Cundinamarca.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Premium Form Card -->
            <div class="col-lg-7">
                <div class="card card-contact-form p-4 p-md-5 border-0 rounded-4 shadow-sm">
                    <h3 class="fw-bold text-dark mb-2">Formulario de Contacto</h3>
                    <p class="text-muted mb-4" style="font-size: 0.95rem;">Completa el formulario y nos pondremos en contacto contigo en menos de 24 horas hábiles.</p>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show rounded-3 mb-4" role="alert">
                            <i class="bi <?= $messageType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= APP_URL ?>/?page=contacto" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6 text-start">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Nombre Completo *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" name="nombre" class="form-control bg-light border-start-0 ps-0" placeholder="Ej. Juan Pérez" required>
                                </div>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Correo Electrónico *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0 ps-0" placeholder="Ej. juan@empresa.com" required>
                                </div>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Número de Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone-fill"></i></span>
                                    <input type="tel" name="telefono" class="form-control bg-light border-start-0 ps-0" placeholder="Ej. 311 123 4567">
                                </div>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Asunto del Mensaje</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-chat-left-text-fill"></i></span>
                                    <input type="text" name="asunto" class="form-control bg-light border-start-0 ps-0" placeholder="Ej. Inscripción de empresa">
                                </div>
                            </div>
                            <div class="col-12 text-start">
                                <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Mensaje / Solicitud *</label>
                                <textarea name="mensaje" class="form-control bg-light" rows="5" placeholder="Escribe aquí tu consulta o mensaje en detalle..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-footer-cta rounded-pill px-4 py-3 fw-bold shadow-lg w-100 fs-6">
                                    <i class="bi bi-send-fill me-2 text-white"></i>Enviar Mensaje de Contacto
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
