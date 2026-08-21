<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        
        <!-- BIENVENIDA Y ACCIONES RÁPIDAS (Tema Amarillo Sutil/Dorado para Admin) -->
        <div class="bg-gradient-to-r from-[#fede32] via-[#ffe082] to-[#ffd54f] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(254,222,50,0.12)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <!-- Círculos decorativos de fondo -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <span class="bg-black/10 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-white/10 drop-shadow-sm">Supervisión General</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight text-white drop-shadow-[0_2px_4px_rgba(15,23,42,0.18)]">Panel de Administración</h1>
                <p class="text-white mt-2 flex items-center text-sm sm:text-base font-bold drop-shadow-[0_1px_2px_rgba(15,23,42,0.15)]">
                    <i class="fas fa-user-shield mr-2 text-white/90"></i> Administrador AGECSO
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3">
                <button onclick="document.getElementById('modalCrearRueda').classList.remove('hidden')" 
                        class="bg-gray-900 hover:bg-black text-amber-300 hover:text-amber-200 px-6 py-3.5 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2.5">
                    <i class="fas fa-plus-circle text-amber-400 text-base"></i> Crear Rueda de Negocio
                </button>
            </div>
        </div>

        <!-- Enlaces de Desplazamiento Rápido -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                <i class="fas fa-rocket mr-2 text-amber-500"></i> Navegación Rápida
            </p>
            <div class="flex flex-wrap gap-2.5">
                <a href="#ruedas" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5">
                    <i class="fas fa-calendar-alt text-[10px]"></i> Ruedas de Negocios
                </a>
                <a href="#solicitudes" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5">
                    <i class="fas fa-user-plus text-[10px]"></i> Inscripciones
                </a>
                <a href="#empresas-pendientes" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5 relative">
                    <i class="fas fa-building text-[10px]"></i> Empresas Pendientes
                    <?php if (!empty($empresas_pendientes)): ?>
                        <span class="ml-1.5 bg-amber-500 text-white text-[9px] px-1.5 py-0.5 rounded-full font-black"><?php echo count($empresas_pendientes); ?></span>
                    <?php endif; ?>
                </a>
                <a href="#reuniones-pendientes" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5 relative">
                    <i class="fas fa-handshake text-[10px]"></i> Reuniones Pendientes
                    <?php if (!empty($solicitudes_reuniones)): ?>
                        <span class="ml-1.5 bg-amber-500 text-white text-[9px] px-1.5 py-0.5 rounded-full font-black"><?php echo count($solicitudes_reuniones); ?></span>
                    <?php endif; ?>
                </a>
                <a href="#seguimiento" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5">
                    <i class="fas fa-chart-line text-[10px]"></i> Seguimiento Citas
                </a>
                <a href="#encuestas" class="bg-amber-50/30 hover:bg-amber-500 hover:text-white text-amber-900 border border-amber-100/50 hover:border-amber-500 px-4 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-300 flex items-center gap-1.5">
                    <i class="fas fa-poll-h text-[10px]"></i> Encuestas Recientes
                </a>
            </div>
        </div>

        <!-- Nueva sección: Empresas Pendientes de Aprobación -->
        <div id="empresas-pendientes" class="bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl overflow-hidden border border-gray-100 scroll-mt-6 hover:shadow-[0_8px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
            <div class="px-6 py-5 border-b border-gray-100 bg-amber-50/10 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-gray-800 tracking-tight flex items-center gap-2"><i class="fas fa-building text-amber-500"></i> Empresas Pendientes de Aprobación</h3>
                <span class="bg-amber-500 text-white text-xs px-2.5 py-1 rounded-full font-black shadow-sm"><?php echo count($empresas_pendientes); ?></span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">NIT</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($empresas_pendientes)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic text-sm">No hay empresas pendientes de aprobación.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($empresas_pendientes as $ep): ?>
                                <tr class="hover:bg-amber-50/5 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($ep['razon_social']); ?></div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5"><?php echo htmlspecialchars($ep['tipo_persona']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-extrabold">
                                        <div class="flex flex-col gap-1.5 items-start">
                                            <span><?php echo htmlspecialchars($ep['nit']); ?></span>
                                            <a href="https://www.rues.org.co/" target="_blank" class="inline-flex items-center text-[9px] font-black text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-1.5 py-0.5 rounded-md transition" title="Consultar NIT en el RUES Oficial de Colombia">
                                                <i class="fas fa-search-dollar mr-1"></i> Verificar RUES
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-semibold">
                                        <?php echo htmlspecialchars($ep['email']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold">
                                        <div class="flex items-center justify-center gap-3.5">
                                            <a href="index.php?controlador=admin&accion=verPerfilEmpresa&id=<?php echo $ep['id']; ?>" class="text-sky-600 hover:text-sky-800 font-extrabold flex items-center gap-1 text-xs" title="Ver Perfil Completo"><i class="fas fa-eye text-[10px]"></i> Perfil</a>
                                            <a href="index.php?controlador=admin&accion=gestionarEmpresa&id=<?php echo $ep['id']; ?>&estado=aprobada" class="text-emerald-600 hover:text-emerald-800 font-extrabold text-xs">Aprobar</a>
                                            <a href="index.php?controlador=admin&accion=gestionarEmpresa&id=<?php echo $ep['id']; ?>&estado=rechazada" class="text-red-500 hover:text-red-700 font-extrabold text-xs">Rechazar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nueva sección: Solicitudes de Reunión Pendientes (Aprobación Admin) -->
        <div id="reuniones-pendientes" class="bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl overflow-hidden border border-gray-100 scroll-mt-6 hover:shadow-[0_8px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
            <div class="px-6 py-5 border-b border-gray-100 bg-amber-50/10 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-gray-800 tracking-tight flex items-center gap-2"><i class="fas fa-handshake text-amber-500"></i> Solicitudes de Reunión (Pendientes de Admin)</h3>
                <span class="bg-amber-500 text-white text-xs px-2.5 py-1 rounded-full font-black shadow-sm"><?php echo count($solicitudes_reuniones); ?></span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Fecha/Hora</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Participantes</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Rueda</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($solicitudes_reuniones)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic text-sm">No hay solicitudes de reunión pendientes de aprobación.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitudes_reuniones as $sr): ?>
                                <tr class="hover:bg-amber-50/5 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900 font-extrabold">
                                        <?php echo date('d/m/Y H:i', strtotime($sr['fechaHora'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-[10px] text-sky-600 font-black uppercase tracking-wider">Comprador:</div>
                                        <div class="text-xs font-extrabold text-gray-900 mb-1.5"><?php echo htmlspecialchars($sr['comprador']); ?></div>
                                        <div class="text-[10px] text-teal-600 font-black uppercase tracking-wider">Vendedor:</div>
                                        <div class="text-xs font-extrabold text-gray-900"><?php echo htmlspecialchars($sr['vendedor']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-bold">
                                        <?php echo htmlspecialchars($sr['rueda'] ?? ($sr['tituloRueda'] ?? 'N/A')); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold">
                                        <div class="flex items-center justify-center gap-3.5">
                                            <a href="index.php?controlador=admin&accion=gestionarReunion&id=<?php echo $sr['id']; ?>&estado=aprobada_admin" class="text-emerald-600 hover:text-emerald-800 font-extrabold text-xs">Aprobar</a>
                                            <a href="index.php?controlador=admin&accion=gestionarReunion&id=<?php echo $sr['id']; ?>&estado=rechazada_admin" class="text-red-500 hover:text-red-700 font-extrabold text-xs">Rechazar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nueva sección: Solicitudes de Inscripción Pendientes -->
        <div id="solicitudes" class="bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl overflow-hidden border border-gray-100 scroll-mt-6 hover:shadow-[0_8px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
            <div class="px-6 py-5 border-b border-gray-100 bg-amber-50/10 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-800 tracking-tight flex items-center gap-2"><i class="fas fa-user-plus text-amber-500"></i> Solicitudes de Inscripción Pendientes</h3>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mt-1.5">Empresas que desean participar en ruedas de negocios</p>
                </div>
                <span class="bg-amber-500 text-white text-xs px-2.5 py-1 rounded-full font-black shadow-sm"><?php echo count($inscripciones_pendientes); ?></span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Fecha Solicitud</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-400 uppercase tracking-wider">Rueda de Negocios</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($inscripciones_pendientes)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic text-sm">No hay solicitudes de inscripción pendientes.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inscripciones_pendientes as $ins): ?>
                                <tr class="hover:bg-amber-50/5 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900 font-extrabold"><?php echo date('d/m/Y H:i', strtotime($ins['createdAt'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($ins['razon_social'] ?? 'Empresa no encontrada'); ?></div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">Clase: <?php echo htmlspecialchars($ins['sectorId'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-bold"><?php echo htmlspecialchars($ins['tituloRueda'] ?? ($ins['nombreRueda'] ?? 'Rueda no encontrada')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold">
                                        <div class="flex items-center justify-center gap-3.5">
                                            <a href="index.php?controlador=admin&accion=gestionarInscripcion&id=<?php echo $ins['id']; ?>&estado=aceptada" class="text-emerald-600 hover:text-emerald-800 font-extrabold text-xs">Aceptar</a>
                                            <a href="index.php?controlador=admin&accion=gestionarInscripcion&id=<?php echo $ins['id']; ?>&estado=rechazada" class="text-red-500 hover:text-red-700 font-extrabold text-xs">Rechazar</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal para Crear Admin -->
<div id="modalCrearAdmin" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalCrearAdmin').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalCrearAdmin').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=admin&accion=crearAdmin" method="POST">
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-6 text-left">
                        <div class="p-2 bg-amber-500/10 text-amber-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Nuevo Administrador</h3>
                    </div>
                    
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Nombre Completo <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200"
                                   placeholder="Ej: Ana María García">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="correo" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200"
                                   placeholder="admin2@agecso.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Contraseña Temporal <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200"
                                   placeholder="********">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1">
                                <i class="fas fa-lock text-amber-400"></i>
                                El nuevo administrador podrá cambiarla después.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalCrearAdmin').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-6 py-2.5 bg-amber-500 text-sm font-extrabold text-white hover:bg-amber-600 shadow-[0_4px_15px_rgba(245,158,11,0.2)] hover:shadow-[0_6px_20px_rgba(245,158,11,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none">
                        Crear Administrador
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Crear Rueda -->
<div id="modalCrearRueda" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalCrearRueda').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalCrearRueda').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=admin&accion=crearRueda" method="POST">
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-6 text-left">
                        <div class="p-2 bg-amber-500/10 text-amber-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Nueva Rueda de Negocios</h3>
                    </div>
                    
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Título de la Rueda <span class="text-red-500">*</span></label>
                            <input type="text" name="titulo" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200"
                                   placeholder="Ej: Rueda Regional de Agro 2026">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Descripción del Evento <span class="text-red-500">*</span></label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 resize-none" 
                                      placeholder="Detalles sobre el alcance, sectores invitados y objetivos..."></textarea>
                        </div>

                        <!-- Fechas de Inscripción -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-amber-600">Inscripciones Inicio</label>
                                <input type="text" name="fecha_inscripcion_inicio" id="fecha_inscripcion_inicio" required 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 bg-white cursor-pointer"
                                    placeholder="Seleccionar fecha...">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-amber-600">Inscripciones Fin</label>
                                <input type="text" name="fecha_inscripcion_fin" id="fecha_inscripcion_fin" required 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 bg-white cursor-pointer"
                                    placeholder="Seleccionar fecha...">
                            </div>
                        </div>

                        <!-- Fechas del Evento -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Inicio de la Rueda</label>
                                <input type="text" name="fecha_inicio" id="fecha_inicio" required 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-sky-400 transition duration-200 bg-white cursor-pointer"
                                    placeholder="Seleccionar fecha...">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Fin de la Rueda</label>
                                <input type="text" name="fecha_fin" id="fecha_fin" required 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-sky-400 transition duration-200 bg-white cursor-pointer"
                                    placeholder="Seleccionar fecha...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Estado Inicial</label>
                                <div class="relative">
                                    <select name="estado" class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 appearance-none bg-white">
                                        <option value="planeacion">Planeación</option>
                                        <option value="inscripciones">Inscripciones</option>
                                        <option value="activa">Activa</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Modalidad</label>
                                <div class="relative">
                                    <select name="modalidad" id="modalidad_select" onchange="toggleUbicacion()" 
                                            class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 appearance-none bg-white">
                                        <option value="virtual">Virtual</option>
                                        <option value="presencial">Presencial</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="ubicacion_container" class="hidden space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Lugar / Dirección del Evento <span class="text-red-500">*</span></label>
                                <input type="text" name="ubicacion" id="ubicacion_input" 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200" 
                                    placeholder="Ej: Calle 123 # 45-67, Centro de Convenciones">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Cantidad de Mesas Disponibles</label>
                                <input type="number" name="cantidad_mesas" id="cantidad_mesas_input" min="1" value="1"
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">
                                    <i class="fas fa-info-circle text-sky-400"></i>
                                    Define el número total de mesas físicas asignadas para este evento.
                                </p>
                            </div>
                        </div>

                        <div id="virtual_info" class="bg-sky-50/50 border border-sky-100 rounded-2xl p-4">
                            <p class="text-[11px] text-sky-800 font-medium leading-relaxed">
                                <i class="fas fa-video mr-1.5 text-sky-500"></i> <strong>Modalidad Virtual:</strong> Las reuniones se realizarán por video llamada. Los participantes agregarán sus propios links de conexión.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalCrearRueda').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-6 py-2.5 bg-amber-500 text-sm font-extrabold text-white hover:bg-amber-600 shadow-[0_4px_15px_rgba(245,158,11,0.2)] hover:shadow-[0_6px_20px_rgba(245,158,11,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none">
                        Crear Evento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleUbicacion() {
        const modalidad = document.getElementById('modalidad_select').value;
        const container = document.getElementById('ubicacion_container');
        const input = document.getElementById('ubicacion_input');
        const info = document.getElementById('virtual_info');
        
        if (modalidad === 'presencial') {
            container.classList.remove('hidden');
            if (input) input.required = true;
            info.classList.add('hidden');
        } else {
            container.classList.add('hidden');
            if (input) {
                input.required = false;
                input.value = 'Virtual';
            }
            info.classList.remove('hidden');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleUbicacion();

        // Configuración base para Flatpickr en el Admin
        const configDate = {
            locale: "es",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            disableMobile: "true",
            animate: true,
            minDate: "today"
        };

        const fpInscInicio = flatpickr("#fecha_inscripcion_inicio", {
            ...configDate,
            onChange: function(selectedDates, dateStr) {
                fpInscFin.set("minDate", dateStr);
            }
        });

        const fpInscFin = flatpickr("#fecha_inscripcion_fin", {
            ...configDate,
            onChange: function(selectedDates, dateStr) {
                fpInicio.set("minDate", dateStr);
            }
        });

        const fpInicio = flatpickr("#fecha_inicio", {
            ...configDate,
            onChange: function(selectedDates, dateStr) {
                fpFin.set("minDate", dateStr);
            }
        });

        const fpFin = flatpickr("#fecha_fin", {
            ...configDate
        });
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
