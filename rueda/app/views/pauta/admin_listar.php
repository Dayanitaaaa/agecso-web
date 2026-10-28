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
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Gestión de Pautas Publicitarias</h1>
                <p class="text-gray-500 text-sm mt-1">Administra las solicitudes de pautas de las empresas</p>
            </div>
            <div class="flex gap-2">
                <a href="index.php?controlador=pauta&accion=listarAdmin&estado=pendiente" class="bg-amber-100 hover:bg-amber-200 text-amber-700 px-4 py-2 rounded-full text-xs font-bold transition">
                    Pendientes
                </a>
                <a href="index.php?controlador=pauta&accion=listarAdmin&estado=activa" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-4 py-2 rounded-full text-xs font-bold transition">
                    Activas
                </a>
                <a href="index.php?controlador=pauta&accion=listarAdmin" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-xs font-bold transition">
                    Todas
                </a>
            </div>
        </div>

        <!-- Notificaciones -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'aprobada'): ?>
            <div class="bg-emerald-50 border-2 border-emerald-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-emerald-900">¡Pauta Aprobada!</h3>
                    <p class="text-xs text-emerald-700 mt-1">La pauta ha sido activada y ahora se mostrará en la página principal.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'rechazada'): ?>
            <div class="bg-red-50 border-2 border-red-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-red-100 text-red-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-red-900">Pauta Rechazada</h3>
                    <p class="text-xs text-red-700 mt-1">La pauta ha sido rechazada y se notificó a la empresa.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'finalizada'): ?>
            <div class="bg-gray-50 border-2 border-gray-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-gray-100 text-gray-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-flag text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">Pauta Finalizada</h3>
                    <p class="text-xs text-gray-700 mt-1">La pauta ha sido finalizada y ya no se mostrará en la página principal.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Lista de pautas -->
        <?php if (empty($pautas)): ?>
            <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 p-12 text-center">
                <p class="text-gray-500 font-medium">No hay pautas en este estado</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">Fechas</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($pautas as $pauta): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($pauta['razon_social']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700"><?php echo htmlspecialchars($pauta['titulo']); ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-500">
                                        <?php echo date('d M, Y', strtotime($pauta['fecha_inicio'])); ?> - <?php echo date('d M, Y', strtotime($pauta['fecha_fin'])); ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                        <?php 
                                        $estadoColors = [
                                            'pendiente' => 'bg-amber-100 text-amber-700',
                                            'aprobada' => 'bg-blue-100 text-blue-700',
                                            'activa' => 'bg-emerald-100 text-emerald-700',
                                            'finalizada' => 'bg-gray-100 text-gray-700',
                                            'rechazada' => 'bg-red-100 text-red-700'
                                        ];
                                        echo $estadoColors[$pauta['estado']] ?? 'bg-gray-100 text-gray-700';
                                        ?>">
                                        <?php echo htmlspecialchars($pauta['estado']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <?php if ($pauta['estado'] === 'pendiente'): ?>
                                            <button onclick="aprobarPauta(<?php echo $pauta['id']; ?>)" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                                                Aprobar
                                            </button>
                                            <button onclick="rechazarPauta(<?php echo $pauta['id']; ?>)" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                                                Rechazar
                                            </button>
                                        <?php elseif ($pauta['estado'] === 'activa'): ?>
                                            <button onclick="finalizarPauta(<?php echo $pauta['id']; ?>)" class="bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                                                Finalizar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function aprobarPauta(id) {
    Swal.fire({
        title: '¿Aprobar Pauta?',
        text: 'La pauta se activará y se mostrará en la página principal.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, Aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controlador=pauta&accion=aprobar';
            
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

function rechazarPauta(id) {
    Swal.fire({
        title: '¿Rechazar Pauta?',
        input: 'textarea',
        inputLabel: 'Comentario de rechazo',
        inputPlaceholder: 'Explica por qué rechazas esta pauta...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Rechazar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debes proporcionar un comentario.'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controlador=pauta&accion=rechazar';
            
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            
            const inputComentario = document.createElement('input');
            inputComentario.type = 'hidden';
            inputComentario.name = 'comentario_rechazo';
            inputComentario.value = result.value;
            
            form.appendChild(inputId);
            form.appendChild(inputComentario);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function finalizarPauta(id) {
    Swal.fire({
        title: '¿Finalizar Pauta?',
        text: 'La pauta dejará de mostrarse en la página principal.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#64748b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, Finalizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controlador=pauta&accion=finalizar';
            
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
