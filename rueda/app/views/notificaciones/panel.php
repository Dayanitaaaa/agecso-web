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
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Mis Notificaciones</h1>
                <p class="text-gray-500 text-sm mt-1">Mantente al día con las actividades importantes</p>
            </div>
            <?php if (!empty($noLeidas)): ?>
                <button onclick="marcarTodasLeidas()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-xs font-bold transition">
                    Marcar todas como leídas
                </button>
            <?php endif; ?>
        </div>

        <!-- Lista de notificaciones -->
        <?php if (empty($notificaciones)): ?>
            <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium">No tienes notificaciones</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($notificaciones as $notif): ?>
                    <?php 
                        $icono = 'fa-info-circle';
                        $color = 'bg-blue-50 text-blue-600';
                        
                        switch ($notif['tipo']) {
                            case 'nueva_inscripcion':
                                $icono = 'fa-user-plus';
                                $color = 'bg-emerald-50 text-emerald-600';
                                break;
                            case 'nueva_demanda':
                                $icono = 'fa-shopping-cart';
                                $color = 'bg-amber-50 text-amber-600';
                                break;
                            case 'nueva_oferta':
                                $icono = 'fa-tags';
                                $color = 'bg-purple-50 text-purple-600';
                                break;
                            case 'cita_agendada':
                            case 'cita_aceptada':
                                $icono = 'fa-calendar-check';
                                $color = 'bg-blue-50 text-blue-600';
                                break;
                            case 'cita_rechazada':
                                $icono = 'fa-calendar-times';
                                $color = 'bg-red-50 text-red-600';
                                break;
                            case 'encuesta_completada':
                                $icono = 'fa-clipboard-check';
                                $color = 'bg-teal-50 text-teal-600';
                                break;
                            case 'nueva_pauta':
                                $icono = 'fa-bullhorn';
                                $color = 'bg-orange-50 text-orange-600';
                                break;
                            case 'pauta_aprobada':
                                $icono = 'fa-check-circle';
                                $color = 'bg-emerald-50 text-emerald-600';
                                break;
                            case 'pauta_rechazada':
                                $icono = 'fa-times-circle';
                                $color = 'bg-red-50 text-red-600';
                                break;
                        }
                    ?>
                    <div class="bg-white rounded-2xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 p-5 flex items-start gap-4 <?php echo $notif['leida'] == 0 ? 'bg-blue-50/30' : ''; ?>">
                        <div class="p-3 <?php echo $color; ?> rounded-xl shrink-0">
                            <i class="fas <?php echo $icono; ?>"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-black text-gray-900 text-sm <?php echo $notif['leida'] == 0 ? 'font-bold' : 'font-medium'; ?>">
                                        <?php echo htmlspecialchars($notif['titulo']); ?>
                                    </h3>
                                    <p class="text-gray-500 text-xs mt-1">
                                        <?php echo htmlspecialchars($notif['mensaje']); ?>
                                    </p>
                                </div>
                                <?php if ($notif['leida'] == 0): ?>
                                    <span class="w-2 h-2 bg-blue-500 rounded-full shrink-0 mt-2"></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-[10px] text-gray-400">
                                    <?php echo date('d M, Y - H:i', strtotime($notif['createdAt'])); ?>
                                </span>
                                <?php if (!empty($notif['link_destino'])): ?>
                                    <a href="<?php echo htmlspecialchars($notif['link_destino']); ?>" 
                                       onclick="marcarLeida(<?php echo $notif['id']; ?>)"
                                       class="text-[#00a2ff] text-xs font-bold hover:underline">
                                        Ver detalles
                                    </a>
                                <?php elseif ($notif['leida'] == 0): ?>
                                    <button onclick="marcarLeida(<?php echo $notif['id']; ?>)" class="text-gray-400 text-xs hover:text-gray-600">
                                        Marcar como leída
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
function marcarLeida(id) {
    fetch('index.php?controlador=notificacion&accion=marcarLeida', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function marcarTodasLeidas() {
    fetch('index.php?controlador=notificacion&accion=marcarTodasLeidas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
