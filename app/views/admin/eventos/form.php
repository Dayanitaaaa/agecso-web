<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/eventos.css">

<div class="page-header">
    <h5><i class="bi bi-calendar-event"></i> <?= $item ? 'Editar Evento' : 'Nuevo Evento' ?></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=eventos" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <h5 class="mb-4"><?= $item ? 'Editar Evento' : 'Nuevo Evento' ?></h5>
    
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
            <div class="col-12">
                <label class="form-label">Imagen</label>
                <input type="file" name="imagen" class="form-control" accept="image/*" id="imagenInput">
                <?php if (!empty($item['imagen'])): ?>
                    <div class="mt-2">
                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" name="eliminar_imagen" value="1" id="eliminarImagen">
                            <label class="form-check-label" for="eliminarImagen">Eliminar imagen actual</label>
                        </div>
                    </div>
                <?php endif; ?>
                <div id="imagenPreview" class="mt-2" style="display: none;">
                    <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 150px;">
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
                <a href="<?= APP_URL ?>/?page=admin&section=eventos" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('imagenInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagenPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
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
