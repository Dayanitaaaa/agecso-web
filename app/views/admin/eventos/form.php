<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/eventos.css">

<div class="page-header">
    <h5><i class="bi bi-calendar-event"></i> <?= $item ? 'Editar Evento' : 'Nuevo Evento' ?></h5>
    <a href="<?= APP_URL ?>/admin/eventos" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
            <i class="bi bi-calendar-event fs-4"></i>
        </div>
        <h5 class="mb-0 fw-bold"><?= $item ? 'Editar Evento' : 'Nuevo Evento' ?></h5>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item['titulo'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="evento" <?= ($item['tipo'] ?? '') === 'evento' ? 'selected' : '' ?>>Evento</option>
                    <option value="taller" <?= ($item['tipo'] ?? '') === 'taller' ? 'selected' : '' ?>>Taller</option>
                    <option value="conversatorio" <?= ($item['tipo'] ?? '') === 'conversatorio' ? 'selected' : '' ?>>Conversatorio</option>
                    <option value="feria" <?= ($item['tipo'] ?? '') === 'feria' ? 'selected' : '' ?>>Feria</option>
                    <option value="rueda" <?= ($item['tipo'] ?? '') === 'rueda' ? 'selected' : '' ?>>Rueda de Negocios</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="programado" <?= ($item['estado'] ?? '') === 'programado' ? 'selected' : '' ?>>Programado</option>
                    <option value="realizado" <?= ($item['estado'] ?? '') === 'realizado' ? 'selected' : '' ?>>Realizado</option>
                    <option value="cancelado" <?= ($item['estado'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha del evento</label>
                <input type="date" name="fecha_evento" class="form-control" value="<?= $item['fecha_evento'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hora inicio</label>
                <input type="time" name="hora_inicio" class="form-control" value="<?= $item['hora_inicio'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hora fin</label>
                <input type="time" name="hora_fin" class="form-control" value="<?= $item['hora_fin'] ?? '' ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Lugar</label>
                <input type="text" name="lugar" class="form-control" value="<?= htmlspecialchars($item['lugar'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Orden de visualización</label>
                <input type="number" name="orden" class="form-control" value="<?= $item['orden'] ?? 999 ?>" min="1">
                <small class="text-muted">1 es primero, 2 segundo, etc. Por defecto: 999</small>
            </div>
            <div class="col-md-6">
                <div class="image-upload-wrapper h-100">
                    <label class="form-label fw-bold">Imagen Principal</label>
                    <div class="image-preview-box main-preview mb-3" id="mainImagePreview">
                        <?php if (!empty($item['imagen'])): ?>
                            <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" class="preview-img">
                            <div class="image-overlay">
                                <label for="imagenInput" class="btn btn-sm btn-light">Cambiar</label>
                            </div>
                        <?php else: ?>
                            <div class="no-image">
                                <i class="bi bi-image fs-1 opacity-25"></i>
                                <p class="small text-muted mt-2">Imagen de portada</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="imagen" id="imagenInput" class="form-control d-none" accept="image/*">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="document.getElementById('imagenInput').click()">
                        <i class="bi bi-cloud-upload me-2"></i> Seleccionar Portada
                    </button>
                    <?php if (!empty($item['imagen'])): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="eliminar_imagen" value="1" id="delMain">
                            <label class="form-check-label text-danger" for="delMain small">Eliminar imagen actual</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="image-upload-wrapper h-100">
                    <label class="form-label fw-bold">Galería / Carrusel (Múltiples)</label>
                    <div class="gallery-upload-area" id="galleryDropArea">
                        <i class="bi bi-images fs-2 opacity-25"></i>
                        <p class="small text-muted mb-0">Subir varias imágenes</p>
                        <input type="file" name="imagenes[]" id="galleryInput" class="d-none" multiple accept="image/*">
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="document.getElementById('galleryInput').click()">
                            Añadir Fotos
                        </button>
                    </div>
                    <div id="galleryPreview" class="gallery-preview-grid mt-3">
                        <?php 
                        $extraImages = json_decode($item['imagenes'] ?? '[]', true);
                        foreach ($extraImages as $img): ?>
                            <div class="gallery-item">
                                <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($img) ?>">
                                <div class="gallery-item-remove">
                                    <input type="checkbox" name="eliminar_imagenes[]" value="<?= htmlspecialchars($img) ?>" class="btn-check" id="del_<?= md5($img) ?>">
                                    <label class="btn btn-sm btn-danger p-0 rounded-circle" for="del_<?= md5($img) ?>"><i class="bi bi-x"></i></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <div id="editor-descripcion" style="height: 250px;"></div>
                <input type="hidden" name="descripcion" id="descripcion-hidden" value="<?= htmlspecialchars($item['descripcion'] ?? '') ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-admin-success">
                    <i class="bi bi-check-lg"></i> <?= $item ? 'Actualizar' : 'Guardar' ?>
                </button>
                <a href="<?= APP_URL ?>/admin/eventos" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
// Previsualización de Imagen Principal
document.getElementById('imagenInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewBox = document.getElementById('mainImagePreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewBox.innerHTML = `
                <img src="${e.target.result}" class="preview-img">
                <div class="image-overlay">
                    <label for="imagenInput" class="btn btn-sm btn-light">Cambiar</label>
                </div>
            `;
        }
        reader.readAsDataURL(file);
    }
});

// Previsualización de Galería
document.getElementById('galleryInput').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewGrid = document.getElementById('galleryPreview');
    
    // No borramos las imágenes existentes (las que vienen de DB)
    // Solo añadimos las nuevas para previsualización
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'gallery-item new-upload';
            div.innerHTML = `
                <img src="${e.target.result}">
                <div class="gallery-badge">Nuevo</div>
            `;
            previewGrid.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
});

var quill = new Quill('#editor-descripcion', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image'],
            ['clean']
        ]
    }
});

// Set initial content
var hiddenInput = document.getElementById('descripcion-hidden');
quill.root.innerHTML = hiddenInput.value;

// Update hidden input on change
quill.on('text-change', function() {
    hiddenInput.value = quill.root.innerHTML;
});
</script>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
