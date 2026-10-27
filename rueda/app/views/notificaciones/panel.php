<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layout/header.php';
?>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Notificaciones</h1>
                <p class="text-gray-500 text-sm mt-1">Mantente al día con las actividades de tu cuenta</p>
            </div>
            <button onclick="marcarTodasLeidas()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-xs font-bold transition flex items-center gap-2">
                <i class="fas fa-check-double"></i> Marcar todas como leídas
            </button>
        </div>

        <!-- Lista de notificaciones -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
            <?php if (empty($notificaciones)): ?>
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No tienes notificaciones</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-50">
                    <?php foreach ($notificaciones as $notif): ?>
                        <?php 
                            $esNoLeida = $notif['leida'] == 0;
                            $icono = 'fa-info-circle';
                            $colorIcono = 'text-blue-500';
                            $colorFondo = 'bg-blue-50';
                            
                            // Iconos según tipo
                            switch($notif['tipo']) {
                                case 'nueva_inscripcion':
                                    $icono = 'fa-user-plus';
                                    $colorIcono = 'text-emerald-500';
                                    $colorFondo = 'bg-emerald-50';
                                    break;
                                case 'nueva_demanda':
                                    $icono = 'fa-shopping-cart';
                                    $colorIcono = 'text-amber-500';
                                    $colorFondo = 'bg-amber-50';
                                    break;
                                case 'nueva_oferta':
                                    $icono = 'fa-box';
                                    $colorIcono = 'text-purple-500';
                                    $colorFondo = 'bg-purple-50';
                                    break;
                                case 'cita_agendada':
                                    $icono = 'fa-calendar-check';
                                    $colorIcono = 'text-sky-500';
                                    $colorFondo = 'bg-sky-50';
                                    break;
                                case 'cita_aceptada':
                                    $icono = 'fa-check-circle';
                                    $colorIcono = 'text-green-500';
                                    $colorFondo = 'bg-green-50';
                                    break;
                                case 'cita_rechazada':
                                    $icono = 'fa-times-circle';
                                    $colorIcono = 'text-red-500';
                                    $colorFondo = 'bg-red-50';
                                    break;
                                case 'encuesta_completada':
                                    $icono = 'fa-star';
                                    $colorIcono = 'text-yellow-500';
                                    $colorFondo = 'bg-yellow-50';
                                    break;
                            }
                        ?>
                        <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors <?php echo $esNoLeida ? 'bg-blue-50/30' : ''; ?>">
                            <div class="w-10 h-10 rounded-xl <?php echo $colorFondo; ?> flex items-center justify-center flex-shrink-0">
                                <i class="fas <?php echo $icono; ?> <?php echo $colorIcono; ?>"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-bold text-gray-900 text-sm <?php echo $esNoLeida ? 'font-extrabold' : ''; ?>">
                                        <?php echo htmlspecialchars($notif['titulo']); ?>
                                    </h3>
                                    <?php if ($esNoLeida): ?>
                                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($notif['mensaje']); ?></p>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-xs text-gray-400">
                                        <?php echo date('d M, Y - h:i A', strtotime($notif['createdAt'])); ?>
                                    </span>
                                    <?php if (!empty($notif['link_destino'])): ?>
                                        <a href="<?php echo htmlspecialchars($notif['link_destino']); ?>" class="text-sm text-blue-600 font-bold hover:underline">
                                            Ver detalle
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function marcarTodasLeidas() {
    fetch('index.php?controlador=notificacion&accion=marcarTodasLeidas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al marcar notificaciones');
        }
    });
}

function marcarComoLeida(id) {
    fetch('index.php?controlador=notificacion&accion=marcarLeida', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar contador en el DOM
            const contadorElement = document.getElementById('notif-contador');
            if (contadorElement) {
                contadorElement.textContent = data.contador;
                if (data.contador == 0) {
                    contadorElement.style.display = 'none';
                }
            }
            // Recargar la notificación visual
            location.reload();
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
