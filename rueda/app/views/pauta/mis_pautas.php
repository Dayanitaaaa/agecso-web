<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layout/header.php';
?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Mis Pautas Publicitarias</h1>
                <p class="text-gray-500 text-sm mt-1">Gestiona tus pautas publicitarias activas y pendientes</p>
            </div>
            <a href="index.php?controlador=pauta&accion=crear" class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg hover:shadow-xl transition-all">
                + Nueva Pauta
            </a>
        </div>

        <!-- Notificaciones -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creada'): ?>
            <div class="bg-emerald-50 border-2 border-emerald-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-emerald-900">¡Pauta Creada Exitosamente!</h3>
                    <p class="text-xs text-emerald-700 mt-1">Tu solicitud ha sido enviada al administrador. Te contactaremos pronto para procesar el pago.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'eliminada'): ?>
            <div class="bg-blue-50 border-2 border-blue-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-trash text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-blue-900">Pauta Eliminada</h3>
                    <p class="text-xs text-blue-700 mt-1">La pauta ha sido eliminada correctamente.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
            <div class="bg-red-50 border-2 border-red-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-red-100 text-red-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-red-900">Error</h3>
                    <p class="text-xs text-red-700 mt-1"><?php echo htmlspecialchars($_GET['error_text'] ?? 'Ocurrió un error.'); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Lista de pautas -->
        <?php if (empty($pautas)): ?>
            <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullhorn text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium">No tienes pautas publicitarias</p>
                <a href="index.php?controlador=pauta&accion=crear" class="inline-block mt-4 text-[#00a2ff] font-bold text-sm hover:underline">
                    Crear mi primera pauta
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($pautas as $pauta): ?>
                    <?php 
                        $estadoColors = [
                            'pendiente' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'aprobada' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'activa' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'finalizada' => 'bg-gray-50 text-gray-600 border-gray-200',
                            'rechazada' => 'bg-red-50 text-red-600 border-red-200'
                        ];
                        $color = $estadoColors[$pauta['estado']] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                    ?>
                    <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
                        <!-- Imagen -->
                        <div class="h-40 bg-gray-100 relative">
                            <img src="../../public/uploads/pautas/<?php echo htmlspecialchars($pauta['imagen']); ?>" 
                                 alt="<?php echo htmlspecialchars($pauta['titulo']); ?>" 
                                 class="w-full h-full object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo $color; ?>">
                                    <?php echo htmlspecialchars($pauta['estado']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-5">
                            <h3 class="font-black text-gray-900 text-sm mb-2"><?php echo htmlspecialchars($pauta['titulo']); ?></h3>
                            <p class="text-gray-500 text-xs mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($pauta['descripcion'], 0, 100)); ?>...</p>
                            
                            <div class="flex items-center justify-between text-xs text-gray-400 mb-4">
                                <span><i class="fas fa-calendar mr-1"></i> <?php echo date('d M, Y', strtotime($pauta['fecha_inicio'])); ?></span>
                                <span>Posición <?php echo $pauta['posicion']; ?></span>
                            </div>
                            
                            <?php if ($pauta['estado'] === 'rechazada' && !empty($pauta['comentario_rechazo'])): ?>
                                <div class="bg-red-50 p-3 rounded-xl mb-4">
                                    <p class="text-xs text-red-700"><strong>Comentario:</strong> <?php echo htmlspecialchars($pauta['comentario_rechazo']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Acciones -->
                            <div class="flex gap-2">
                                <?php if ($pauta['estado'] === 'pendiente'): ?>
                                    <button onclick="eliminarPauta(<?php echo $pauta['id']; ?>)" class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-xl text-xs font-bold transition">
                                        Eliminar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function eliminarPauta(id) {
    Swal.fire({
        title: '¿Eliminar Pauta?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controlador=pauta&accion=eliminar';
            
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            
            form.appendChild(inputId);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
