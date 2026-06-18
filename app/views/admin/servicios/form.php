<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/servicios.css">

<div class="page-header">
    <h5><i class="bi bi-briefcase"></i> <?= $item ? 'Editar Servicio' : 'Nuevo Servicio' ?></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=servicios" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <h5 class="mb-4"><?= $item ? 'Editar' : 'Nuevo' ?> Servicio</h5>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Título *</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item['titulo'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-control" value="<?= $item['orden'] ?? 0 ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Icono Bootstrap</label>
                <input type="text" name="icono" class="form-control" value="<?= htmlspecialchars($item['icono'] ?? 'bi-star') ?>">
                <small class="text-muted">Ej: bi-briefcase, bi-people, bi-book</small>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción corta</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($item['descripcion'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Contenido completo</label>
                <div id="editor-contenido" style="height: 300px;"></div>
                <input type="hidden" name="contenido" id="contenido-hidden" value="<?= htmlspecialchars($item['contenido'] ?? '') ?>">
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="activo" value="1" <?= ($item['activo'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label">Activo</label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-admin-success">
                    <i class="bi bi-check-lg"></i> <?= $item ? 'Actualizar' : 'Guardar' ?>
                </button>
                <a href="<?= APP_URL ?>/?page=admin&section=servicios" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
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
