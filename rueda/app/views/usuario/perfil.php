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
    'admin' => 'bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white',
    'vendedor' => 'bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] text-white',
    'comprador' => 'bg-gradient-to-r from-sky-400 via-sky-500 to-blue-600 text-white'
];

$iconBgColors = [
    'admin' => 'bg-slate-100 text-slate-800 border border-slate-200',
    'vendedor' => 'bg-teal-50 text-[#0d9488] border border-teal-100/50',
    'comprador' => 'bg-sky-50 text-sky-600 border border-sky-100/50'
];

$textColors = [
    'admin' => 'text-slate-800',
    'vendedor' => 'text-[#0d9488]',
    'comprador' => 'text-sky-500'
];

$sidebarClasses = [
    'admin' => 'bg-gradient-to-br from-slate-900 to-slate-800 border border-slate-800 text-white',
    'vendedor' => 'bg-gradient-to-br from-[#0f766e] to-[#0d9488] border border-[#0d9488] text-white',
    'comprador' => 'bg-gradient-to-br from-sky-950 to-blue-900 border border-sky-900 text-white'
];

$sidebarAccentText = [
    'admin' => 'text-slate-200',
    'vendedor' => 'text-teal-200',
    'comprador' => 'text-sky-300'
];

$badgeColors = [
    'admin' => 'bg-slate-100 text-slate-800 border border-slate-200',
    'vendedor' => 'bg-teal-50 text-teal-800 border border-teal-200/50',
    'comprador' => 'bg-sky-50 text-sky-800 border border-sky-200/50'
];
?>

<div class="min-h-screen bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado del Perfil -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-10 relative overflow-hidden <?php echo $headerGradients[$theme]; ?> flex flex-col md:flex-row md:items-center md:justify-between gap-6">
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

                <div class="relative z-10">
                    <button onclick="abrirModalEditarPerfil()" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white text-white hover:text-slate-900 px-6 py-3 rounded-full text-xs font-black transition-all duration-300 border border-white/30 backdrop-blur-md shadow-lg">
                        <i class="fas fa-user-edit text-sm"></i> Editar Perfil
                    </button>
                </div>
            </div>
            
            <?php if (isset($_GET['msg'])): ?>
                <div class="px-8 py-4">
                    <?php if ($_GET['msg'] === 'perfil_actualizado'): ?>
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600"></i> Tu información de perfil ha sido actualizada exitosamente.
                        </div>
                    <?php elseif ($_GET['msg'] === 'ciiu_actualizado'): ?>
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600"></i> Código CIIU actualizado correctamente.
                        </div>
                    <?php elseif ($_GET['msg'] === 'error'): ?>
                        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-rose-600"></i> <?php echo htmlspecialchars($_GET['error_text'] ?? 'Hubo un error al actualizar los datos.'); ?>
                        </div>
                    <?php elseif ($_GET['msg'] === 'error_ciiu_vacio'): ?>
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-amber-600"></i> El código CIIU no puede estar vacío.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/10">
                <div class="flex flex-col sm:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-400">
                        <span class="flex items-center group relative">
                            <i class="fas fa-tag mr-2 <?php echo $textColors[$theme]; ?>"></i>
                            <span class="font-black text-gray-700"><?php echo htmlspecialchars($perfil['ciiu_personalizado'] ?: ($perfil['ciiu_clase'] ?? 'N/A')); ?></span>
                            <span class="mx-2">-</span>
                            <span class="text-gray-500"><?php echo htmlspecialchars($perfil['ciiu_nombre_personalizado'] ?: ($perfil['nombreSector'] ?? 'Sin sector')); ?></span>
                            
                            <?php if ($theme !== 'admin'): ?>
                                <button onclick="editarCiiu('<?php echo htmlspecialchars($perfil['ciiu_personalizado'] ?? ''); ?>', '<?php echo htmlspecialchars($perfil['ciiu_nombre_personalizado'] ?? ''); ?>')" 
                                        class="ml-3 text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 px-2 py-1 rounded-lg transition-all flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Cambiar CIIU
                                </button>
                            <?php endif; ?>
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

<!-- Modal para Editar Perfil (Todos los roles) -->
<div id="modalEditarPerfil" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="cerrarModalEditarPerfil()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 sm:px-8 py-5 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <i class="fas fa-user-edit text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black tracking-tight text-white">Editar Información de Perfil</h3>
                        <p class="text-xs text-slate-300">Actualiza los datos de tu cuenta en la plataforma</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalEditarPerfil()" class="text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form action="index.php?controlador=usuario&accion=actualizarPerfil" method="POST" class="p-6 sm:p-8 space-y-6">
                
                <?php if ($theme === 'admin'): ?>
                    <!-- Formulario Específico para Administrador -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nombre Completo <span class="text-red-500">*</span></label>
                            <input type="text" name="nombreUsuario" required 
                                   value="<?php echo htmlspecialchars($perfil['nombreUsuario'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required 
                                   value="<?php echo htmlspecialchars($perfil['email'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ubicación / Sede</label>
                            <input type="text" name="ubicacionGeografica" 
                                   value="<?php echo htmlspecialchars($perfil['ubicacionGeografica'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nueva Contraseña <span class="text-gray-400 font-normal lowercase">(opcional)</span></label>
                            <input type="password" name="password" placeholder="Dejar en blanco para no cambiar"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Descripción / Cargo</label>
                            <textarea name="descripcion" rows="3" 
                                      class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800 resize-none"><?php echo htmlspecialchars($perfil['descripcion'] ?? ''); ?></textarea>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Formulario para Empresas (Compradores y Vendedores) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Razón Social o Nombre de la Empresa <span class="text-red-500">*</span></label>
                            <input type="text" name="razon_social" required 
                                   value="<?php echo htmlspecialchars($perfil['razon_social'] ?? $perfil['nombreUsuario'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Representante Legal <span class="text-red-500">*</span></label>
                            <input type="text" name="representante_legal" required 
                                   value="<?php echo htmlspecialchars($perfil['representante_legal'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required 
                                   value="<?php echo htmlspecialchars($perfil['email'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Identificación / NIT</label>
                            <div class="flex gap-2">
                                <input type="text" name="nit" 
                                       value="<?php echo htmlspecialchars($perfil['nit'] ?? ''); ?>"
                                       class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                       placeholder="Ej: 900123456">
                                <input type="text" name="digito_verificacion" maxlength="1"
                                       value="<?php echo htmlspecialchars($perfil['digito_verificacion'] ?? ''); ?>"
                                       class="w-16 text-center border border-gray-200 rounded-2xl px-2 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                       placeholder="DV">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipo de Persona</label>
                            <select name="tipo_persona" class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                                <option value="juridica" <?php echo ($perfil['tipo_persona'] ?? '') === 'juridica' ? 'selected' : ''; ?>>Persona Jurídica</option>
                                <option value="natural" <?php echo ($perfil['tipo_persona'] ?? '') === 'natural' ? 'selected' : ''; ?>>Persona Natural</option>
                                <option value="esal" <?php echo ($perfil['tipo_persona'] ?? '') === 'esal' ? 'selected' : ''; ?>>ESAL / Sin Ánimo de Lucro</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Forma Jurídica</label>
                            <input type="text" name="tipo_asociacion" 
                                   value="<?php echo htmlspecialchars($perfil['tipo_asociacion'] ?? 'S.A.S.'); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                   placeholder="Ej: S.A.S., Ltda., S.A.">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Responsabilidad IVA</label>
                            <select name="responsable_iva" class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                                <option value="0" <?php echo ($perfil['responsable_iva'] ?? 0) == 0 ? 'selected' : ''; ?>>No Responsable de IVA</option>
                                <option value="1" <?php echo ($perfil['responsable_iva'] ?? 0) == 1 ? 'selected' : ''; ?>>Responsable de IVA</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tamaño de Empresa</label>
                            <select name="tamaño_empresa" class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                                <option value="micro" <?php echo strtolower($perfil['tamaño_empresa'] ?? '') === 'micro' ? 'selected' : ''; ?>>Microempresa</option>
                                <option value="pequeña" <?php echo strtolower($perfil['tamaño_empresa'] ?? '') === 'pequeña' ? 'selected' : ''; ?>>Pequeña Empresa</option>
                                <option value="mediana" <?php echo strtolower($perfil['tamaño_empresa'] ?? '') === 'mediana' ? 'selected' : ''; ?>>Mediana Empresa</option>
                                <option value="grande" <?php echo strtolower($perfil['tamaño_empresa'] ?? '') === 'grande' ? 'selected' : ''; ?>>Grande Empresa</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ubicación / Ciudad</label>
                            <input type="text" name="ubicacionGeografica" 
                                   value="<?php echo htmlspecialchars($perfil['ubicacionGeografica'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                   placeholder="Ej: Bogotá, Colombia">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Código CIIU</label>
                            <input type="text" name="ciiu_personalizado" 
                                   value="<?php echo htmlspecialchars($perfil['ciiu_personalizado'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                   placeholder="Ej: 6201">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nombre Actividad Económica</label>
                            <input type="text" name="ciiu_nombre_personalizado" 
                                   value="<?php echo htmlspecialchars($perfil['ciiu_nombre_personalizado'] ?? ''); ?>"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]"
                                   placeholder="Ej: Programación informática">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nueva Contraseña <span class="text-gray-400 font-normal lowercase">(opcional)</span></label>
                            <input type="password" name="password" placeholder="Dejar en blanco para no cambiar tu contraseña actual"
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-bold text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488]">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sobre la Empresa (Descripción Corporativa)</label>
                            <textarea name="descripcion" rows="3" 
                                      placeholder="Describe los productos, servicios, experiencia y propuesta de valor de tu empresa..."
                                      class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] resize-none"><?php echo htmlspecialchars($perfil['descripcion'] ?? ''); ?></textarea>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button type="button" onclick="cerrarModalEditarPerfil()" 
                            class="w-full sm:w-auto px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-900 hover:bg-black text-white text-sm font-black transition shadow-lg">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalEditarPerfil() {
    document.getElementById('modalEditarPerfil').classList.remove('hidden');
}

function cerrarModalEditarPerfil() {
    document.getElementById('modalEditarPerfil').classList.add('hidden');
}

function editarCiiu(ciiuActual, nombreActual) {
    Swal.fire({
        title: 'Actualizar Actividad CIIU',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Código CIIU</label>
                    <input id="swal-input1" class="swal2-input !m-0 !w-full" placeholder="Ej: 6201" value="${ciiuActual}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Nombre de la Actividad</label>
                    <input id="swal-input2" class="swal2-input !m-0 !w-full" placeholder="Ej: Programación informática" value="${nombreActual}">
                </div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Guardar cambios',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const ciiu = document.getElementById('swal-input1').value.trim();
            const nombre = document.getElementById('swal-input2').value.trim();
            if (!ciiu || !nombre) {
                Swal.showValidationMessage('¡Debes completar ambos campos!');
                return false;
            }
            return { ciiu: ciiu, nombre: nombre }
        },
        customClass: {
            popup: 'rounded-[2.5rem] p-10',
            confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm',
            cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?controlador=usuario&accion=actualizarCiiu';
            
            const inputCiiu = document.createElement('input');
            inputCiiu.type = 'hidden';
            inputCiiu.name = 'ciiu_personalizado';
            inputCiiu.value = result.value.ciiu;
            
            const inputNombre = document.createElement('input');
            inputNombre.type = 'hidden';
            inputNombre.name = 'ciiu_nombre_personalizado';
            inputNombre.value = result.value.nombre;
            
            form.appendChild(inputCiiu);
            form.appendChild(inputNombre);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

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
