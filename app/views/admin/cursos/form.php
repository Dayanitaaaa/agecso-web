<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/cursos.css">

<div class="page-header">
    <h5><i class="bi bi-mortarboard"></i> <?= $item ? 'Editar Curso' : 'Nuevo Curso' ?></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=cursos" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <h5 class="mb-4"><?= $item ? 'Editar' : 'Nuevo' ?> Curso/Webinar</h5>
    
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
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="curso" <?= ($item['tipo'] ?? '') === 'curso' ? 'selected' : '' ?>>Curso</option>
                    <option value="webinar" <?= ($item['tipo'] ?? '') === 'webinar' ? 'selected' : '' ?>>Webinar</option>
                    <option value="capacitacion" <?= ($item['tipo'] ?? '') === 'capacitacion' ? 'selected' : '' ?>>Capacitación</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?= $item['fecha_inicio'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="<?= $item['fecha_fin'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="finalizado" <?= ($item['estado'] ?? '') === 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                    <option value="programado" <?= ($item['estado'] ?? '') === 'programado' ? 'selected' : '' ?>>Programado</option>
                    <option value="cancelado" <?= ($item['estado'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Duración</label>
                <input type="text" name="duracion" class="form-control" placeholder="Ej: 4 horas" value="<?= htmlspecialchars($item['duracion'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Instructor / Organizador</label>
                <input type="text" name="instructor" class="form-control" value="<?= htmlspecialchars($item['instructor'] ?? '') ?>">
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
                <a href="<?= APP_URL ?>/?page=admin&section=cursos" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
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
