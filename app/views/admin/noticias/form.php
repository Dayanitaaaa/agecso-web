<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/noticias.css">

<div class="page-header">
    <h5><i class="bi bi-newspaper"></i> <?= $item ? 'Editar Noticia' : 'Nueva Noticia' ?></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=noticias" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <h5 class="mb-4"><?= $item ? 'Editar Noticia' : 'Nueva Noticia' ?></h5>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item['titulo'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="borrador" <?= ($item['estado'] ?? '') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
                    <option value="publicado" <?= ($item['estado'] ?? '') === 'publicado' ? 'selected' : '' ?>>Publicado</option>
                    <option value="archivado" <?= ($item['estado'] ?? '') === 'archivado' ? 'selected' : '' ?>>Archivado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha de publicación</label>
                <input type="date" name="fecha_publicacion" class="form-control" value="<?= $item['fecha_publicacion'] ?? date('Y-m-d') ?>">
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
                <label class="form-label">Resumen</label>
                <textarea name="resumen" class="form-control" rows="3"><?= htmlspecialchars($item['resumen'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Contenido</label>
                <div id="editor-contenido" style="height: 300px;"></div>
                <input type="hidden" name="contenido" id="contenido-hidden" value="<?= htmlspecialchars($item['contenido'] ?? '') ?>">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-admin-success">
                    <i class="bi bi-check-lg"></i> <?= $item ? 'Actualizar' : 'Guardar' ?>
                </button>
                <a href="<?= APP_URL ?>/?page=admin&section=noticias" class="btn btn-outline-secondary">Cancelar</a>
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

var quill = new Quill('#editor-contenido', {
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
var hiddenInput = document.getElementById('contenido-hidden');
quill.root.innerHTML = hiddenInput.value;

// Update hidden input on change
quill.on('text-change', function() {
    hiddenInput.value = quill.root.innerHTML;
});
</script>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
