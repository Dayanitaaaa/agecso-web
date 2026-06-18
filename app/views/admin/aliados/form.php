<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin/aliados.css">

<div class="page-header">
    <h5><i class="bi bi-people"></i> <?= $item ? 'Editar Aliado' : 'Nuevo Aliado' ?></h5>
    <a href="<?= APP_URL ?>/?page=admin&section=aliados" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="admin-card">
    <h5 class="mb-4"><?= $item ? 'Editar' : 'Nuevo' ?> Aliado</h5>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($item['nombre'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" value="<?= htmlspecialchars($item['categoria'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sitio web</label>
                <input type="url" name="sitio_web" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($item['sitio_web'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-control" value="<?= $item['orden'] ?? 0 ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*" id="logoInput">
                <?php if (!empty($item['logo'])): ?>
                    <div class="mt-2">
                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($item['logo']) ?>" alt="Logo actual" class="img-thumbnail" style="max-height: 100px;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" name="eliminar_logo" value="1" id="eliminarLogo">
                            <label class="form-check-label" for="eliminarLogo">Eliminar logo actual</label>
                        </div>
                    </div>
                <?php endif; ?>
                <div id="logoPreview" class="mt-2" style="display: none;">
                    <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 100px;">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="5"><?= htmlspecialchars($item['descripcion'] ?? '') ?></textarea>
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
                <a href="<?= APP_URL ?>/?page=admin&section=aliados" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('logoPreview');
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
</script>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
