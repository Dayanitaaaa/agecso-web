<?php require __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><i class="bi bi-calendar2-check text-primary"></i> <?= $title ?></h5>
        <p class="text-muted small mb-0">Publica un afiche, póster o convocatoria para mostrar en la Agenda del sitio.</p>
    </div>
    <a href="<?= APP_URL ?>/admin/agenda" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
    </a>
</div>

<div class="admin-card">
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-4">
            
            <!-- Columna Izquierda: Datos Principales -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Título de la Actividad / Convocatoria *</label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item['titulo'] ?? '') ?>" placeholder="Ej: Convocatoria Taller de Exportación Sabana 2026" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción / Información Detallada</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Describe los objetivos, requisitos y detalles de la actividad..."><?= htmlspecialchars($item['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="<?= $item['fecha_inicio'] ?? '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Finalización (Opcional)</label>
                        <input type="date" name="fecha_fin" class="form-control" value="<?= $item['fecha_fin'] ?? '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora Inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" value="<?= $item['hora_inicio'] ?? '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Hora Fin</label>
                        <input type="time" name="hora_fin" class="form-control" value="<?= $item['hora_fin'] ?? '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Lugar o Modalidad</label>
                        <input type="text" name="lugar" class="form-control" value="<?= htmlspecialchars($item['lugar'] ?? '') ?>" placeholder="Ej: Madrid, Cundinamarca / Virtual Zoom">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipo de Actividad</label>
                        <select name="tipo" class="form-select">
                            <option value="convocatoria" <?= ($item['tipo'] ?? '') === 'convocatoria' ? 'selected' : '' ?>>Convocatoria</option>
                            <option value="taller" <?= ($item['tipo'] ?? '') === 'taller' ? 'selected' : '' ?>>Taller o Capacitación</option>
                            <option value="feria" <?= ($item['tipo'] ?? '') === 'feria' ? 'selected' : '' ?>>Feria / Encuentro</option>
                            <option value="evento" <?= ($item['tipo'] ?? '') === 'evento' ? 'selected' : '' ?>>Evento Especial</option>
                            <option value="general" <?= ($item['tipo'] ?? '') === 'general' ? 'selected' : '' ?>>General</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold">Enlace del Botón de Acción / Registro (Opcional)</label>
                        <input type="url" name="link_registro" class="form-control" value="<?= htmlspecialchars($item['link_registro'] ?? '') ?>" placeholder="https://forms.gle/... o https://rueda.agecso.org">
                        <small class="text-muted">Si lo dejas vacío, el botón no se mostrará o llevará al formulario general.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Texto del Botón</label>
                        <input type="text" name="texto_boton" class="form-control" value="<?= htmlspecialchars($item['texto_boton'] ?? 'Inscribirme') ?>" placeholder="Inscribirme / Más Información">
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Imagen del Afiche y Configuración -->
            <div class="col-lg-4">
                <div class="p-4 bg-light rounded-4 border">
                    <label class="form-label fw-bold d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-image text-primary"></i> Imagen o Afiche Promocional *
                    </label>

                    <div class="text-center mb-3">
                        <div id="previewBox" class="rounded-3 overflow-hidden border bg-white d-flex align-items-center justify-content-center mx-auto shadow-sm" style="height: 220px; width: 100%;">
                            <?php if (!empty($item['imagen'])): ?>
                                <img id="imgPreview" src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" class="w-100 h-100 object-fit-contain" alt="Afiche">
                            <?php else: ?>
                                <div id="imgPlaceholder" class="text-muted p-4">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 d-block mb-2"></i>
                                    <span class="small">Sube el afiche o imagen de la actividad</span>
                                </div>
                                <img id="imgPreview" src="" class="w-100 h-100 object-fit-contain d-none" alt="Vista previa">
                            <?php endif; ?>
                        </div>
                    </div>

                    <input type="file" name="imagen" id="imagenInput" class="form-control mb-2" accept="image/*" onchange="previewAgendaImage(this)">
                    <small class="text-muted d-block mb-3">Formatos: JPG, PNG, WEBP. Se recomienda formato afiche o banner.</small>

                    <?php if (!empty($item['imagen'])): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="eliminar_imagen" value="1" id="eliminarImg">
                            <label class="form-check-label text-danger small fw-bold" for="eliminarImg">
                                <i class="bi bi-trash"></i> Eliminar imagen actual
                            </label>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado de Publicación</label>
                        <select name="estado" class="form-select">
                            <option value="activo" <?= ($item['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo (Visible en Agenda)</option>
                            <option value="destacado" <?= ($item['estado'] ?? '') === 'destacado' ? 'selected' : '' ?>>Destacado (Prioridad alta)</option>
                            <option value="inactivo" <?= ($item['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo (Oculto)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Orden de Visualización</label>
                        <input type="number" name="orden" class="form-control" value="<?= $item['orden'] ?? 999 ?>" min="1">
                        <small class="text-muted">1 aparece primero, 2 segundo, etc.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm mt-3">
                        <i class="bi bi-check-lg me-1"></i> <?= $item ? 'Guardar Cambios' : 'Publicar en Agenda' ?>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function previewAgendaImage(input) {
    const preview = document.getElementById('imgPreview');
    const placeholder = document.getElementById('imgPlaceholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.classList.add('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
