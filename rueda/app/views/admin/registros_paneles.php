<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="min-h-screen bg-slate-50/50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER PREMIUM (Tema Slate Admin) -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-8 mb-8 shadow-[0_10px_40px_rgba(15,23,42,0.25)] text-white relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="bg-white/10 text-slate-300 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-white/10 backdrop-blur-md">
                        Supervisión General
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-black mt-4 tracking-tight text-white drop-shadow-sm">Empresas y Registros del Sistema</h1>
                    <p class="text-slate-300 mt-2 font-medium">Auditoría completa de empresas registradas, citas comerciales, ruedas de negocios y encuestas.</p>
                </div>
            </div>

            <!-- Accesos rápidos a secciones -->
            <div class="relative z-10 flex flex-wrap items-center gap-2.5 mt-8 pt-6 border-t border-white/10">
                <a href="#empresas" class="inline-flex items-center gap-2 px-4 py-2 bg-white/15 hover:bg-white text-white hover:text-slate-900 rounded-full text-xs font-bold transition shadow-sm border border-white/10">
                    <i class="fas fa-building text-[10px]"></i> Empresas <span class="bg-white/20 text-white hover:bg-slate-900 px-2 py-0.5 rounded-full text-[10px] font-black ml-1"><?php echo (int)($total_empresas ?? 0); ?></span>
                </a>
                <a href="#seguimiento" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white text-white hover:text-slate-900 rounded-full text-xs font-bold transition border border-white/10">
                    <i class="fas fa-handshake text-[10px]"></i> Citas <span class="bg-white/20 text-white hover:bg-slate-900 px-2 py-0.5 rounded-full text-[10px] font-black ml-1"><?php echo (int)($total_reuniones ?? 0); ?></span>
                </a>
                <a href="#ruedas" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white text-white hover:text-slate-900 rounded-full text-xs font-bold transition border border-white/10">
                    <i class="fas fa-calendar-alt text-[10px]"></i> Ruedas <span class="bg-white/20 text-white hover:bg-slate-900 px-2 py-0.5 rounded-full text-[10px] font-black ml-1"><?php echo (int)($total_ruedas ?? 0); ?></span>
                </a>
                <a href="#encuestas" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white text-white hover:text-slate-900 rounded-full text-xs font-bold transition border border-white/10">
                    <i class="fas fa-star-half-alt text-[10px]"></i> Encuestas <span class="bg-white/20 text-white hover:bg-slate-900 px-2 py-0.5 rounded-full text-[10px] font-black ml-1"><?php echo (int)($total_encuestas ?? 0); ?></span>
                </a>
            </div>
        </div>

        <div class="space-y-12">
            
            <!-- SECCIÓN 0: DIRECTORIO DE EMPRESAS REGISTRADAS -->
            <div id="empresas" class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-[0_10px_40px_rgba(0,0,0,0.06)]">
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-8 py-7 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white text-xl backdrop-blur-sm border border-white/10">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Directorio General de Empresas</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-slate-300 text-xs font-bold uppercase opacity-80">Total Registradas:</span>
                                <span class="bg-white/20 text-white text-xs font-black px-2.5 py-0.5 rounded-full"><?php echo (int)($total_empresas ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="relative">
                            <input type="text" id="buscarEmpresaInput" onkeyup="filtrarEmpresasTabla()" placeholder="Buscar empresa, NIT, email..." 
                                   class="bg-white/10 text-white placeholder-slate-400 text-xs font-bold rounded-xl pl-9 pr-4 py-2.5 border border-white/15 focus:outline-none focus:bg-white focus:text-slate-900 focus:placeholder-gray-400 transition-all w-full sm:w-64">
                            <i class="fas fa-search text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xs pointer-events-none"></i>
                        </div>
                        <span class="text-white/80 text-xs font-bold bg-white/10 px-4 py-2.5 rounded-xl backdrop-blur-sm border border-white/10 text-center shrink-0">
                            Pág. <?php echo (int)($pageEmpresas ?? 1); ?> de <?php echo (int)($totalPagesEmpresas ?? 1); ?>
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100" id="tablaEmpresas">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Empresa / Razón Social</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Identificación (NIT)</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Rol</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Actividad Económica / CIIU</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Estado</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <?php if (empty($empresas_registradas)): ?>
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-extrabold italic">No hay empresas registradas</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($empresas_registradas as $emp): 
                                    $est = strtolower($emp['estado_verificacion'] ?? 'pendiente');
                                    $rol = strtolower($emp['slugRole'] ?? ($emp['roleId'] == 2 ? 'comprador' : ($emp['roleId'] == 3 ? 'vendedor' : 'admin')));
                                    $rolBadge = match($rol) {
                                        'comprador' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'vendedor', 'proveedor' => 'bg-teal-50 text-teal-700 border-teal-100',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                    $estadoBadge = match($est) {
                                        'aprobada' => 'bg-emerald-500 text-white shadow-emerald-200',
                                        'rechazada' => 'bg-rose-500 text-white shadow-rose-200',
                                        default => 'bg-amber-500 text-white shadow-amber-200'
                                    };
                                ?>
                                    <tr class="empresa-row group hover:bg-slate-50/80 transition-all duration-300">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 font-black flex items-center justify-center text-sm border border-slate-200 shrink-0">
                                                    <?php echo strtoupper(substr($emp['razon_social'] ?? $emp['nombreUsuario'] ?? 'E', 0, 1)); ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-black text-slate-900 truncate"><?php echo htmlspecialchars($emp['razon_social'] ?? $emp['nombreUsuario']); ?></p>
                                                    <p class="text-[11px] font-bold text-slate-400 mt-0.5 truncate"><?php echo htmlspecialchars($emp['email']); ?></p>
                                                    <?php if (!empty($emp['representante_legal'])): ?>
                                                        <p class="text-[10px] text-slate-400 font-medium truncate">Rep: <?php echo htmlspecialchars($emp['representante_legal']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span class="font-mono text-xs font-extrabold text-slate-700">
                                                <?php echo htmlspecialchars($emp['nit'] ?? 'N/A'); ?>
                                                <?php if (!empty($emp['digito_verificacion'])): ?>
                                                    - <?php echo htmlspecialchars($emp['digito_verificacion']); ?>
                                                <?php endif; ?>
                                            </span>
                                            <p class="text-[10px] text-slate-400 font-bold"><?php echo htmlspecialchars($emp['tipo_asociacion'] ?? 'S.A.S.'); ?></p>
                                        </td>
                                        <td class="px-8 py-5 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase border <?php echo $rolBadge; ?>">
                                                <?php echo ucfirst($rol); ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5">
                                            <p class="text-xs font-extrabold text-slate-800 line-clamp-1" title="<?php echo htmlspecialchars($emp['ciiu_nombre_personalizado'] ?: ($emp['nombreSector'] ?? 'Sin sector')); ?>">
                                                <?php echo htmlspecialchars($emp['ciiu_nombre_personalizado'] ?: ($emp['nombreSector'] ?? 'Sin sector')); ?>
                                            </p>
                                            <p class="text-[10px] font-mono text-slate-400 font-bold mt-0.5">
                                                CIIU: <?php echo htmlspecialchars($emp['ciiu_personalizado'] ?: ($emp['ciiu_clase'] ?? 'N/A')); ?>
                                            </p>
                                        </td>
                                        <td class="px-8 py-5 text-center whitespace-nowrap">
                                            <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm <?php echo $estadoBadge; ?>">
                                                <?php echo ucfirst($est); ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center whitespace-nowrap">
                                            <a href="index.php?controlador=admin&accion=verPerfilEmpresa&id=<?php echo (int)$emp['id']; ?>" 
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-black text-white rounded-full font-black text-xs transition duration-200 shadow-sm">
                                                <i class="fas fa-eye text-[10px]"></i> Ver Perfil
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación Empresas -->
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        Empresas en vista: <?php echo count($empresas_registradas ?? []); ?> de <?php echo (int)$total_empresas; ?>
                    </span>
                    <div class="flex items-center gap-2">
                        <?php if (($pageEmpresas ?? 1) > 1): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_empresas=<?php echo (int)($pageEmpresas - 1); ?>&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>#empresas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                            </a>
                        <?php endif; ?>
                        <?php if (($pageEmpresas ?? 1) < ($totalPagesEmpresas ?? 1)): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_empresas=<?php echo (int)($pageEmpresas + 1); ?>&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>#empresas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN 1: CITAS Y NEGOCIOS -->
            <div id="seguimiento" class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-[0_10px_40px_rgba(0,0,0,0.06)]">
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-8 py-7 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white text-xl backdrop-blur-sm border border-white/10">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Seguimiento de Citas y Negocios</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-slate-300 text-xs font-bold uppercase opacity-80">Volumen Total:</span>
                                <span class="bg-white/20 text-white text-xs font-black px-2.5 py-0.5 rounded-full"><?php echo (int)($total_reuniones ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-white/80 text-xs font-bold bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                            Página <?php echo (int)($pageReuniones ?? 1); ?> de <?php echo (int)($totalPagesReuniones ?? 1); ?>
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Evento</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Comprador</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Vendedor</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Estado</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Monto Estimado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <?php if (empty($reuniones_detalladas)): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-calendar-times text-slate-200 text-5xl mb-4"></i>
                                            <p class="text-slate-400 font-extrabold text-sm tracking-tight">No se han registrado citas aún</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reuniones_detalladas as $rd): ?>
                                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                        <td class="px-8 py-5">
                                            <p class="text-xs font-black text-slate-600 max-w-[250px] leading-relaxed"><?php echo htmlspecialchars($rd['rueda'] ?? ''); ?></p>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-black">
                                                    <?php echo strtoupper(substr($rd['comprador'] ?? 'N', 0, 1)); ?>
                                                </div>
                                                <p class="text-sm font-black text-slate-900"><?php echo htmlspecialchars($rd['comprador'] ?? 'N/A'); ?></p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs font-black">
                                                    <?php echo strtoupper(substr($rd['vendedor'] ?? 'N', 0, 1)); ?>
                                                </div>
                                                <p class="text-sm font-black text-slate-900"><?php echo htmlspecialchars($rd['vendedor'] ?? 'N/A'); ?></p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <?php 
                                            $estadoCita = $rd['estadoCita'] ?? 'agendada';
                                            $badgeClass = match($estadoCita) {
                                                'aceptada', 'agendada' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'realizada' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'rechazada', 'cancelada' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                default => 'bg-amber-50 text-amber-600 border-amber-100',
                                            };
                                            ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider <?php echo $badgeClass; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full mr-2 bg-current opacity-70"></span>
                                                <?php echo htmlspecialchars($estadoCita); ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <span class="text-sm font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-xl">
                                                $<?php echo number_format((float)($rd['montoEstimado'] ?? 0), 0); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        Registros en vista: <?php echo count($reuniones_detalladas ?? []); ?>
                    </span>
                    <div class="flex items-center gap-2">
                        <?php if (($pageReuniones ?? 1) > 1): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_reuniones=<?php echo (int)($pageReuniones - 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>#seguimiento"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                            </a>
                        <?php endif; ?>
                        <?php if (($pageReuniones ?? 1) < ($totalPagesReuniones ?? 1)): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_reuniones=<?php echo (int)($pageReuniones + 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>#seguimiento"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: RUEDAS DE NEGOCIOS -->
            <div id="ruedas" class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-[0_10px_40px_rgba(0,0,0,0.06)]">
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-8 py-7 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white text-xl backdrop-blur-sm border border-white/10">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Ruedas de Negocios</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-slate-300 text-xs font-bold uppercase opacity-80">Gestionadas:</span>
                                <span class="bg-white/20 text-white text-xs font-black px-2.5 py-0.5 rounded-full"><?php echo (int)($total_ruedas ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-white/80 text-xs font-bold bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                        Página <?php echo (int)($pageRuedas ?? 1); ?> de <?php echo (int)($totalPagesRuedas ?? 1); ?>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Título de la Rueda</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Cronograma</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Modalidad y Ubicación</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Estado</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <?php if (empty($ruedas)): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-extrabold italic">No hay ruedas registradas</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ruedas as $r): ?>
                                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                        <td class="px-8 py-5">
                                            <p class="text-sm font-black text-slate-900"><?php echo htmlspecialchars($r['tituloRueda'] ?? 'Sin título'); ?></p>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="inline-flex items-center px-3 py-1.5 bg-slate-100 rounded-xl text-[11px] font-black text-slate-600 border border-slate-200">
                                                <i class="far fa-calendar-alt mr-2 text-amber-500"></i>
                                                <?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?> - <?php echo date('d/m/Y', strtotime($r['fechaFin'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <?php if (($r['modalidad'] ?? 'virtual') === 'virtual'): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-purple-50 text-purple-600 border border-purple-100 uppercase tracking-wider">
                                                    <i class="fas fa-video mr-1.5"></i> Virtual
                                                </span>
                                            <?php else: ?>
                                                <div class="flex flex-col gap-1.5">
                                                    <span class="w-fit inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wider">
                                                        <i class="fas fa-map-marker-alt mr-1.5"></i> Presencial
                                                    </span>
                                                    <p class="text-[10px] text-slate-500 font-bold max-w-[250px] line-clamp-1" title="<?php echo htmlspecialchars($r['ubicacion'] ?? ''); ?>">
                                                        <?php echo htmlspecialchars($r['ubicacion'] ?? ''); ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <?php 
                                            $estado = strtolower($r['estadoRueda'] ?? 'planeacion');
                                            $statusClass = match($estado) {
                                                'activa', 'abierta' => 'bg-emerald-500 text-white shadow-emerald-200',
                                                'finalizada' => 'bg-blue-500 text-white shadow-blue-200',
                                                default => 'bg-amber-500 text-white shadow-amber-200',
                                            };
                                            ?>
                                            <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-md <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($estado); ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <button type="button"
                                               onclick="confirmarEliminarRueda(<?php echo (int)$r['id']; ?>, '<?php echo addslashes(htmlspecialchars($r['tituloRueda'] ?? 'Rueda')); ?>')"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-full font-black text-xs transition duration-200 border border-rose-200 hover:border-rose-600 shadow-sm cursor-pointer">
                                                <i class="fas fa-trash-alt text-[10px]"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación Ruedas -->
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        Total en esta vista: <?php echo count($ruedas ?? []); ?>
                    </span>
                    <div class="flex items-center gap-2">
                        <?php if (($pageRuedas ?? 1) > 1): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_ruedas=<?php echo (int)($pageRuedas - 1); ?>&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>#ruedas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                            </a>
                        <?php endif; ?>
                        <?php if (($pageRuedas ?? 1) < ($totalPagesRuedas ?? 1)): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_ruedas=<?php echo (int)($pageRuedas + 1); ?>&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas ?? 1); ?>#ruedas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: ENCUESTAS DE SATISFACCIÓN -->
            <div id="encuestas" class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-[0_10px_40px_rgba(0,0,0,0.06)]">
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-8 py-7 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white text-xl backdrop-blur-sm border border-white/10">
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Encuestas de Satisfacción</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-slate-300 text-xs font-bold uppercase opacity-80">Retroalimentación:</span>
                                <span class="bg-white/20 text-white text-xs font-black px-2.5 py-0.5 rounded-full"><?php echo (int)($total_encuestas ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-white/80 text-xs font-bold bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                        Página <?php echo (int)($pageEncuestas ?? 1); ?> de <?php echo (int)($totalPagesEncuestas ?? 1); ?>
                    </div>
                </div>
                
                <div class="overflow-x-auto text-nowrap">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Fecha Cita</th>
                                <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Calificador (Empresa)</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Rol</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Puntuación</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Expectativas</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Negocio Proyectado</th>
                                <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <?php if (empty($encuestas_recientes)): ?>
                                <tr>
                                    <td colspan="7" class="px-8 py-20 text-center text-slate-400 font-extrabold italic">No hay encuestas registradas aún</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($encuestas_recientes as $enc): ?>
                                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                                        <td class="px-8 py-5">
                                            <p class="text-xs font-black text-slate-500 uppercase tracking-tight"><?php echo date('d M, Y', strtotime($enc['fechaHora'])); ?></p>
                                            <p class="text-[10px] font-bold text-slate-400"><?php echo date('H:i', strtotime($enc['fechaHora'])); ?></p>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div>
                                                <p class="text-sm font-black text-slate-900"><?php echo htmlspecialchars($enc['nombreUsuario'] ?? ''); ?></p>
                                                <p class="text-[10px] font-extrabold text-slate-500 uppercase"><?php echo htmlspecialchars($enc['razon_social'] ?? 'Sin empresa'); ?></p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <?php 
                                            $rol = strtolower($enc['rolCalificador'] ?? 'n/a');
                                            $rolColor = $rol == 'comprador' ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-emerald-600 bg-emerald-50 border-emerald-100';
                                            ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase border <?php echo $rolColor; ?>">
                                                <?php echo $rol; ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <div class="flex items-center justify-center gap-0.5 text-xs text-amber-400">
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <i class="<?php echo $i <= ($enc['calificacion'] ?? 0) ? 'fas' : 'far'; ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="text-[9px] font-black text-slate-700 mt-1"><?php echo ($enc['calificacion'] ?? 0); ?> / 5.0</p>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <?php if (!empty($enc['expectativaCumplida']) && $enc['expectativaCumplida'] !== 'ninguno'): ?>
                                                <span class="inline-flex items-center text-emerald-600 text-[10px] font-black uppercase tracking-wider">
                                                    <i class="fas fa-check-circle mr-1.5"></i>
                                                    <?php echo str_replace('_', ' ', $enc['expectativaCumplida']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center text-rose-500 text-[10px] font-black uppercase tracking-wider">
                                                    <i class="fas fa-times-circle mr-1.5"></i> No cumplidas
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <?php $monto = (float)($enc['posibilidadNegocio'] ?? 0); ?>
                                            <span class="text-xs font-black <?php echo $monto > 0 ? 'text-emerald-600' : 'text-slate-300'; ?>">
                                                $<?php echo number_format($monto, 0); ?>
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <a href="index.php?controlador=admin&accion=verDetalleEncuesta&id=<?php echo $enc['id']; ?>" 
                                               class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-900 hover:text-white transition-all duration-300 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1"
                                               title="Ver Auditoría Completa">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación Encuestas -->
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        Registros en vista: <?php echo count($encuestas_recientes ?? []); ?>
                    </span>
                    <div class="flex items-center gap-2">
                        <?php if (($pageEncuestas ?? 1) > 1): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas - 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>#encuestas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                <i class="fas fa-chevron-left mr-2"></i> Anterior
                            </a>
                        <?php endif; ?>
                        <?php if (($pageEncuestas ?? 1) < ($totalPagesEncuestas ?? 1)): ?>
                            <a href="index.php?controlador=admin&accion=verRegistrosPaneles&page_reuniones=<?php echo (int)($pageReuniones ?? 1); ?>&page_encuestas=<?php echo (int)($pageEncuestas + 1); ?>&page_ruedas=<?php echo (int)($pageRuedas ?? 1); ?>#encuestas"
                               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-700 hover:bg-slate-100 hover:border-slate-300 transition-all duration-300 shadow-sm">
                                Siguiente <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Estilos para scrollbars personalizados */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: transparent;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

<script>
function filtrarEmpresasTabla() {
    const input = document.getElementById('buscarEmpresaInput');
    const filter = input.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#tablaEmpresas .empresa-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmarEliminarRueda(ruedaId, tituloRueda, origen = 'registros') {
    Swal.fire({
        title: '¿Eliminar rueda permanentemente?',
        html: `
            <div class="text-left mt-2">
                <p class="text-sm text-gray-600 font-medium mb-2">
                    Estás a punto de eliminar la siguiente rueda de negocios:
                </p>
                <div class="my-3 p-3.5 bg-rose-50/80 rounded-2xl border border-rose-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20">
                        <i class="fas fa-calendar-times text-base"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-black text-rose-800 uppercase tracking-wider block">Rueda de Negocios</span>
                        <span class="text-sm font-extrabold text-gray-900 truncate block">
                            "${tituloRueda}"
                        </span>
                    </div>
                </div>
                <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200/60 text-xs text-amber-900 font-medium flex items-start gap-2.5">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-sm mt-0.5 shrink-0"></i>
                    <span>Esta acción eliminará todas las citas, ofertas, demandas e inscripciones asociadas. <strong>Esta acción no se puede deshacer.</strong></span>
                </div>
            </div>
        `,
        icon: 'warning',
        iconColor: '#f43f5e',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1.5"></i> Sí, eliminar definitivamente',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
        background: '#ffffff',
        customClass: {
            popup: 'rounded-[2rem] shadow-2xl border border-gray-100 p-6',
            confirmButton: 'rounded-full font-black px-6 py-3 text-xs uppercase tracking-wider shadow-lg shadow-rose-500/20 transition-all',
            cancelButton: 'rounded-full font-bold px-6 py-3 text-xs uppercase tracking-wider transition-all'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `index.php?controlador=admin&accion=eliminarRueda&id=${encodeURIComponent(ruedaId)}&origen=${encodeURIComponent(origen)}`;
        }
    });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
