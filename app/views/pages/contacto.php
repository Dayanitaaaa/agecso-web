<section class="section-padding bg-light">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <h1 class="mb-4">Contacto</h1>
                <p class="lead">Comunícate con AGECSO para conocer servicios, eventos, alianzas e inscripciones.</p>
                <p><strong>Correo:</strong> info@agecso.org</p>
                <p><strong>Teléfono:</strong> +57 311 8772577</p>
                <p><strong>Ubicación:</strong> Avenida Troncal Occidente # 18-76, Parque Industrial Santo Domingo, Bodega J3, Mosquera, Cundinamarca.</p>
            </div>
            <div class="col-lg-6">
                <div class="card card-service p-4">
                    <h2 class="h4 mb-3">Formulario de contacto</h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="<?= APP_URL ?>/?page=contacto">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asunto</label>
                            <input type="text" name="asunto" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mensaje *</label>
                            <textarea name="mensaje" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-agecso">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
