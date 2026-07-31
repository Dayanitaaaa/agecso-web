<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layout/header.php';

// Detectar el rol y asignar la paleta de colores corporativa correspondiente
$role = strtolower($_SESSION['nombreRole'] ?? 'comprador');
if (strpos($role, 'admin') !== false) {
    $theme = 'admin';
} elseif (strpos($role, 'vendedor') !== false || strpos($role, 'venta') !== false || strpos($role, 'proveedor') !== false) {
    $theme = 'vendedor';
} else {
    $theme = 'comprador';
}

// Configurar clases de Tailwind dinámicamente según el rol
$headerGradients = [
    'admin' => 'bg-gradient-to-r from-[#fede32] via-[#ffe082] to-[#ffd54f] text-white',
    'vendedor' => 'bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] text-white',
    'comprador' => 'bg-gradient-to-r from-sky-400 via-sky-500 to-blue-600 text-white'
];

$iconBgColors = [
    'admin' => 'bg-amber-50 text-amber-600 border border-amber-100/50',
    'vendedor' => 'bg-teal-50 text-[#0d9488] border border-teal-100/50',
    'comprador' => 'bg-sky-50 text-sky-600 border border-sky-100/50'
];

$textColors = [
    'admin' => 'text-amber-500',
    'vendedor' => 'text-[#0d9488]',
    'comprador' => 'text-sky-500'
];

$sidebarClasses = [
    'admin' => 'bg-gradient-to-br from-slate-900 to-slate-800 border border-slate-800 text-white',
    'vendedor' => 'bg-gradient-to-br from-[#0f766e] to-[#0d9488] border border-[#0d9488] text-white',
    'comprador' => 'bg-gradient-to-br from-sky-950 to-blue-900 border border-sky-900 text-white'
];

$sidebarAccentText = [
    'admin' => 'text-[#fede32]',
    'vendedor' => 'text-teal-200',
    'comprador' => 'text-sky-300'
];

$badgeColors = [
    'admin' => 'bg-amber-50 text-amber-800 border border-amber-200/50',
    'vendedor' => 'bg-teal-50 text-teal-800 border border-teal-200/50',
    'comprador' => 'bg-sky-50 text-sky-800 border border-sky-200/50'
];
?>

<div class="min-h-screen bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado del Perfil -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-10 relative overflow-hidden <?php echo $headerGradients[$theme]; ?>">
                <!-- Círculos decorativos de fondo -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute left-1/3 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                
                <div class="relative z-10">
                    <span class="bg-black/10 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-white/10 drop-shadow-sm">
                        <?php echo $theme === 'admin' ? 'Perfil Administrativo' : 'Perfil de Empresa'; ?>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black mt-4 tracking-tight drop-shadow-[0_2px_4px_rgba(15,23,42,0.18)]">
                        <?php echo htmlspecialchars($perfil['razon_social'] ?? $perfil['nombreUsuario']); ?>
                    </h1>
                </div>
            </div>
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/10">
                <div class="flex flex-col sm:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-400">
                        <span class="flex items-center">
                            <i class="fas fa-tag mr-2 <?php echo $textColors[$theme]; ?>"></i>
                            <?php echo htmlspecialchars($perfil['ciiu_clase'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($perfil['nombreSector'] ?? 'Sin sector'); ?>
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 <?php echo $textColors[$theme]; ?>"></i>
                            <?php echo htmlspecialchars($perfil['ubicacionGeografica'] ?? 'Ubicación no especificada'); ?>
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <?php if(($perfil['verificada'] ?? 0) == 1): ?>
                            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i> VERIFICADA
                            </span>
                        <?php endif; ?>
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-[10px] font-extrabold <?php echo $badgeColors[$theme]; ?> uppercase shadow-sm">
                            <?php echo ucfirst(htmlspecialchars($perfil['tamaño_empresa'] ?? 'micro')); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Información Legal -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-lg font-extrabold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-50 pb-4">
                        <i class="fas fa-file-contract <?php echo $textColors[$theme]; ?>"></i>
                        Información Legal y Tributaria
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Identificación (NIT)</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php echo htmlspecialchars($perfil['nit'] ?? 'No registrado'); ?>
                                <?php if(!empty($perfil['digito_verificacion'])): ?>
                                    - <?php echo htmlspecialchars($perfil['digito_verificacion']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Tipo de Persona</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php 
                                    $tp = $perfil['tipo_persona'] ?? 'juridica';
                                    echo ($tp == 'natural') ? 'Persona Natural' : (($tp == 'juridica') ? 'Persona Jurídica' : 'ESAL / Otros');
                                ?>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Forma Jurídica</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php echo htmlspecialchars($perfil['tipo_asociacion'] ?? 'N/A'); ?>
                                <?php if(!empty($perfil['sub_tipo_asociacion'])): ?>
                                    <span class="text-gray-400 text-xs font-semibold">(<?php echo htmlspecialchars($perfil['sub_tipo_asociacion']); ?>)</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Responsabilidad IVA</p>
                            <p class="text-gray-800 font-bold text-sm">
                                <?php echo ($perfil['responsable_iva'] ?? 0) == 1 ? 'Sujeto Responsable' : 'No Responsable'; ?>
                            </p>
                        </div>

                        <div class="col-span-full pt-6 border-t border-gray-100 flex flex-col gap-3">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Representante Legal</p>
                            <div class="flex items-center gap-3 bg-gray-50/50 p-3 rounded-2xl border border-gray-100/50 w-fit pr-6">
                                <div class="w-10 h-10 rounded-xl <?php echo $iconBgColors[$theme]; ?> flex items-center justify-center font-extrabold">
                                    <?php echo strtoupper(substr($perfil['representante_legal'] ?? 'R', 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-bold text-sm"><?php echo htmlspecialchars($perfil['representante_legal'] ?? 'No asignado'); ?></p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase">Representante Oficial</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sobre la Empresa -->
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-lg font-extrabold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                        <i class="fas fa-info-circle <?php echo $textColors[$theme]; ?>"></i>
                        Sobre la Empresa
                    </h2>
                    <p class="text-gray-500 leading-relaxed italic text-sm font-medium">
                        "<?php echo htmlspecialchars($perfil['descripcion'] ?? 'Esta empresa aún no ha agregado una descripción corporativa.'); ?>"
                    </p>
                </div>
            </div>

            <!-- Columna Derecha: Datos de Contacto y Rol -->
            <div class="space-y-8">
                <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 p-8 hover:shadow-[0_8px_30px_rgba(0,0,0,0.02)] transition-all duration-300">
                    <h2 class="text-md font-black text-gray-800 mb-6 flex items-center gap-1.5"><i class="fas fa-address-card <?php echo $textColors[$theme]; ?>"></i> Información de Contacto</h2>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl <?php echo $iconBgColors[$theme]; ?> flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Correo Electrónico</p>
                                <p class="text-xs text-gray-800 font-bold mt-0.5 break-all"><?php echo htmlspecialchars($perfil['email']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl <?php echo $iconBgColors[$theme]; ?> flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-alt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-wider">Miembro desde</p>
                                <p class="text-xs text-gray-800 font-bold mt-0.5"><?php echo date('d M, Y', strtotime($perfil['createdAt'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Estado (Diferenciada según Rol) -->
                <div class="rounded-3xl shadow-xl p-8 relative overflow-hidden <?php echo $sidebarClasses[$theme]; ?>">
                    <!-- Círculo de fondo decorativo -->
                    <div class="absolute -right-12 -bottom-12 w-28 h-28 bg-white/5 rounded-full blur-2xl"></div>
                    
                    <h3 class="font-extrabold text-md mb-6 flex items-center gap-2 border-b border-white/5 pb-4">
                        <i class="fas fa-user-shield <?php echo $sidebarAccentText[$theme]; ?>"></i> 
                        Estado en la Rueda
                    </h3>
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-white/60 text-xs font-bold">Perfil Actual</span>
                            <span class="font-black text-xs uppercase tracking-wider <?php echo $sidebarAccentText[$theme]; ?>">
                                <?php echo htmlspecialchars($_SESSION['nombreRole']); ?>
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-white/60 text-xs font-bold">ID de Registro</span>
                            <span class="font-mono text-xs font-extrabold text-white/90">
                                #<?php echo str_pad($perfil['id'], 5, '0', STR_PAD_LEFT); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if (($_SESSION['slugRole'] ?? '') === 'comprador'): ?>
                    <!-- Tarjeta para convertirse en Vendedor -->
                    <div class="rounded-3xl shadow-xl p-8 bg-gradient-to-br from-[#0d9488]/10 via-white to-white border border-[#0d9488]/20 relative overflow-hidden mt-6">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-[#0d9488]/5 rounded-full blur-xl"></div>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-[#0d9488]/10 text-[#0d9488] uppercase mb-4 tracking-wider">
                            <i class="fas fa-rocket mr-1"></i> NUEVA OPORTUNIDAD
                        </span>
                        <h3 class="font-extrabold text-gray-900 text-base mb-2">¿Quieres vender tus productos?</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6 font-medium">
                            Actualiza tu perfil a <b>Vendedor/Proveedor</b> para publicar tus productos, postularte a las ruedas de negocios y recibir solicitudes de citas comerciales.
                        </p>
                        
                        <form action="index.php?controlador=comprador&accion=convertirseEnVendedor" method="POST" id="formConvertirVendedor">
                            <button type="button" onclick="confirmarVendedor()" class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white text-xs font-extrabold py-3.5 px-4 rounded-2xl transition duration-200 shadow-lg shadow-[#0d9488]/10 flex items-center justify-center gap-2">
                                <i class="fas fa-user-tag text-xs"></i> Convertirme en Vendedor
                            </button>
                        </form>
                    </div>
                <?php elseif (($_SESSION['slugRole'] ?? '') === 'proveedor' || ($_SESSION['slugRole'] ?? '') === 'vendedor'): ?>
                    <!-- Tarjeta para convertirse en Comprador -->
                    <div class="rounded-3xl shadow-xl p-8 bg-gradient-to-br from-teal-500/10 via-white to-white border border-teal-200 relative overflow-hidden mt-6">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-teal-500/5 rounded-full blur-xl"></div>
                        
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-teal-100 text-teal-700 uppercase mb-4 tracking-wider">
                            <i class="fas fa-sync mr-1"></i> CAMBIAR DE PERFIL
                        </span>
                        <h3 class="font-extrabold text-gray-900 text-base mb-2">¿Quieres ser Comprador?</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6 font-medium">
                            Cambia tu perfil de vuelta a <b>Comprador</b> si deseas buscar y adquirir productos/servicios en las ruedas de negocios de manera gratuita.
                        </p>
                        
                        <form action="index.php?controlador=vendedor&accion=convertirseEnComprador" method="POST" id="formConvertirComprador">
                            <button type="button" onclick="confirmarComprador()" class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white text-xs font-extrabold py-3.5 px-4 rounded-2xl transition duration-200 shadow-lg shadow-teal-500/10 flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart text-xs"></i> Volver a ser Comprador
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarVendedor() {
    Swal.fire({
        title: '¿Convertirte en Vendedor?',
        text: 'Este proceso actualizará tu cuenta y habilitará tus opciones comerciales de Vendedor/Proveedor.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d9488',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Sí, Convertirme',
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-3xl',
            confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm',
            cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formConvertirVendedor').submit();
        }
    });
}

function confirmarComprador() {
    Swal.fire({
        title: '¿Regresar a ser Comprador?',
        text: 'Este proceso actualizará tu rol y desactivará las funciones de venta. No requerirás pagar membresía.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Sí, de vuelta a Comprador',
        cancelButtonText: 'Cancelar',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-3xl',
            confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm',
            cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formConvertirComprador').submit();
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
