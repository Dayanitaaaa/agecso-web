<?php require __DIR__ . '/../layouts/admin-header.php'; ?>
<style>
    .media-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .media-preview {
        height: 150px;
        background-color: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .media-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .media-info {
        padding: 10px;
        font-size: 0.8rem;
    }
    .media-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }
    .media-actions {
        display: flex;
        gap: 5px;
        margin-top: 10px;
    }
</style>

<div class="page-header">
    <h5><i class="bi bi-images"></i> Biblioteca Multimedia <span class="badge bg-secondary ms-2" style="font-size:0.7rem;"><?= count($items) ?></span></h5>
    <button type="button" class="btn btn-admin-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload"></i> Subir Imagen
    </button>
</div>

<div class="alert alert-info d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-info-circle-fill fs-4"></i>
    <div>
        <strong>Importante sobre la persistencia:</strong> Si las imágenes desaparecen al actualizar el sitio, asegúrate de que el sistema de despliegue en Hostinger no esté sobrescribiendo la carpeta <code>public/uploads</code>. Esta biblioteca te permite gestionar los archivos que actualmente residen en el servidor.
    </div>
</div>

<div class="row g-4">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="media-card rounded-3 bg-white">
                    <div class="media-preview">
                        <img src="<?= $item['path'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
                    </div>
                    <div class="media-info border-top">
                        <div class="media-name" title="<?= htmlspecialchars($item['name']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </div>
                        <div class="text-muted small">
                            <?= round($item['size'] / 1024, 2) ?> KB<br>
                            <?= date('d/m/Y H:i', $item['date']) ?>
                        </div>
                        <div class="media-actions">
                            <a href="<?= $item['path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary flex-grow-1" title="Ver original">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-media" 
                                    data-url="<?= APP_URL ?>/admin/multimedia/delete/<?= urlencode($item['name']) ?>"
                                    data-name="<?= htmlspecialchars($item['name']) ?>"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-image-fill text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            <p class="mt-3 text-muted">No hay imágenes en la biblioteca.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Subir Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= APP_URL ?>/admin/multimedia/upload" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Selecciona un archivo</label>
                        <input type="file" name="file" class="form-control" accept="image/*" required>
                        <small class="text-muted">Máx 5MB. Formatos: JPG, PNG, GIF, WEBP.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Subir ahora</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-delete-media');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const name = this.getAttribute('data-name');
            
            Swal.fire({
                title: '¿Eliminar imagen?',
                text: `Estás a punto de eliminar "${name}". Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>

<?php require __DIR__ . '/../layouts/admin-footer.php'; ?>
