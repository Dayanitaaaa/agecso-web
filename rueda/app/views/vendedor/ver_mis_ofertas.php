<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <!-- HEADER PREMIUM TEMA VERDE VENDEDOR -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(13,148,136,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Mercado de Negocios</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Mis Ofertas</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-bullhorn mr-2 text-white/80"></i> <?php echo htmlspecialchars($rueda['tituloRueda']); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=vendedor&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-white text-[#0d9488] px-6 py-3 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Citas
                </a>
            </div>
        </div>

        <!-- ======================================================
             SECCIÓN: MIS OFERTAS (TARJETA REDISEÑADA)
             ====================================================== -->
        <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <!-- Header Tarjeta -->
            <div class="bg-gradient-to-r from-[#0d9488] to-[#14b8a6] px-8 py-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md mr-4 shadow-sm">
                            <i class="fas fa-box-open text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Mis Ofertas</h2>
                            <p class="text-white/80 text-xs font-bold uppercase tracking-wider mt-0.5">Promociona tus productos/servicios en esta rueda</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('cita_rueda_id_oferta').value = '<?php echo $ruedaId; ?>'; document.getElementById('modalOferta').classList.remove('hidden')" 
                            class="bg-white text-[#0d9488] px-6 py-3 rounded-full font-black text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-plus mr-2 text-xs"></i> Nueva Oferta
                    </button>
                </div>
            </div>
            
            <!-- Lista de Ofertas Existentes -->
            <div class="p-8">
                <?php if (empty($ofertas_rueda)): ?>
                    <div class="text-center py-12 bg-teal-50/30 rounded-[2rem] border-2 border-dashed border-teal-100 group hover:border-teal-200 transition-colors">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-teal-50">
                            <i class="fas fa-box-open text-teal-300 text-3xl group-hover:scale-110 transition-transform"></i>
                        </div>
                        <p class="text-teal-900 font-black text-lg">No has registrado ofertas todavía</p>
                        <p class="text-teal-600/70 text-sm font-bold mt-2">Haz clic en "Nueva Oferta" para promocionar tus productos y ser visible para los compradores.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($ofertas_rueda as $oferta): ?>
                            <?php 
                                $tags = json_decode($oferta['tagsBusqueda'] ?? '[]', true);
                                $tags_str = is_array($tags) ? implode(', ', $tags) : '';
                            ?>
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-teal-100 hover:shadow-md transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-black text-gray-900 text-base leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($oferta['tituloOferta']); ?></h3>
                                    <div class="bg-gray-50 px-2 py-1 rounded-lg">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">
                                            <?php echo date('d M', strtotime($oferta['createdAt'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs leading-relaxed mb-4 line-clamp-3 font-medium"><?php echo htmlspecialchars($oferta['descripcionOferta']); ?></p>
                                <?php if ($tags_str): ?>
                                    <div class="flex flex-wrap gap-1.5 mt-auto">
                                        <?php foreach ($tags as $tag): ?>
                                            <span class="px-3 py-1 bg-teal-50 text-[#0d9488] rounded-full text-[9px] font-black uppercase tracking-wider border border-teal-100/50">
                                                #<?php echo htmlspecialchars($tag); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- SECCIÓN: DEMANDAS DE TU MISMO SECTOR (REDISEÑADA) -->
        <?php if (!empty($demandas_mismo_sector)): ?>
        <div class="mb-14">
            <h2 class="text-sm font-black text-[#0d9488] mb-6 flex items-center gap-2 uppercase tracking-[0.2em]">
                <i class="fas fa-star text-amber-400"></i> Recomendados para tu sector
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                <?php foreach ($demandas_mismo_sector as $demanda): ?>
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-teal-100 transition-all duration-300 flex flex-col group relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-teal-50 rounded-full blur-xl group-hover:bg-teal-100 transition-colors"></div>
                        <div class="flex justify-between items-start relative z-10 mb-3">
                            <span class="text-[9px] bg-teal-500 text-white px-3 py-1 rounded-full font-black uppercase tracking-widest shadow-sm">Match</span>
                        </div>
                        <h3 class="font-black text-gray-900 mt-2 text-sm line-clamp-2 leading-tight group-hover:text-[#0d9488] transition-colors relative z-10"><?php echo htmlspecialchars($demanda['tituloDemanda'] ?? 'N/A'); ?></h3>
                        <p class="text-[10px] text-[#0d9488] font-black mt-2 uppercase tracking-wide relative z-10 flex items-center gap-1">
                            <i class="fas fa-building text-[8px] opacity-60"></i> <?php echo htmlspecialchars($demanda['razon_social'] ?? 'N/A'); ?>
                        </p>
                        
                        <div class="mt-auto pt-4 relative z-10">
                            <?php if ($rueda['estadoRueda'] === 'activa'): ?>
                                <button onclick="abrirModalCitaDemanda(<?php echo $demanda['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($demanda['razon_social'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($demanda['tituloDemanda'] ?? 'N/A')); ?>')" 
                                        class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-md shadow-teal-500/10">
                                    Solicitar Cita
                                </button>
                            <?php else: ?>
                                <button disabled class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed border border-gray-100" title="Rueda no activa">
                                    <i class="fas fa-lock mr-1"></i> Bloqueado
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- FILTROS DE BÚSQUEDA REDISEÑADOS -->
        <div class="bg-white p-6 rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 mb-10">
            <form method="GET" action="" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="controlador" value="vendedor">
                <input type="hidden" name="accion" value="verMisOfertas">
                <input type="hidden" name="id" value="<?php echo $ruedaId; ?>">
                
                <div class="flex-1 relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#0d9488] transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>" 
                           placeholder="Buscar demandas, empresas o descripciones..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner">
                </div>
                <div class="md:w-64 relative group">
                    <select name="sector_id" class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                        <option value="">Todos los sectores</option>
                        <?php foreach ($todos_sectores as $sec): ?>
                            <option value="<?php echo $sec['id']; ?>" <?php echo ($_GET['sector_id'] ?? '') == $sec['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sec['nombreSector']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488] group-hover:scale-110 transition-transform">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <button type="submit" class="bg-[#0d9488] hover:bg-[#0f766e] text-white px-8 py-3.5 rounded-2xl font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-xs"></i> Filtrar
                </button>
                <?php if (!empty($_GET['busqueda']) || !empty($_GET['sector_id'])): ?>
                    <a href="index.php?controlador=vendedor&accion=verMisOfertas&id=<?php echo $ruedaId; ?>" class="bg-gray-100 text-gray-500 px-6 py-3.5 rounded-2xl font-black text-sm hover:bg-gray-200 transition-all duration-300 text-center flex items-center justify-center">
                        <i class="fas fa-times mr-2 text-xs"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- GRID PRINCIPAL: DEMANDAS REDISEÑADO -->
        <div class="mb-14">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-clipboard-list text-[#0d9488]"></i> Demandas Disponibles
                </h2>
                <div class="bg-teal-50 px-4 py-1.5 rounded-full border border-teal-100">
                    <span class="text-[10px] font-black text-[#0d9488] uppercase tracking-wider"><?php echo count($demandas); ?> resultados</span>
                </div>
            </div>
            
            <?php if (empty($demandas)): ?>
                <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <i class="fas fa-search text-gray-200 text-3xl"></i>
                    </div>
                    <p class="text-gray-900 font-black text-lg">No se encontraron demandas</p>
                    <p class="text-gray-400 text-sm font-bold mt-2">Prueba ajustando tus filtros de búsqueda o cambiando de sector.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($demandas as $demanda): ?>
                        <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-xl hover:border-teal-100 transition-all duration-500 flex flex-col group">
                            <div class="p-7 flex-1">
                                <div class="flex items-start justify-between mb-5">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-[9px] bg-teal-50 text-[#0d9488] px-3 py-1 rounded-full font-black uppercase tracking-wider border border-teal-100/50">
                                            <i class="fas fa-tag mr-1 opacity-60"></i> <?php echo htmlspecialchars($demanda['nombreSector'] ?? 'N/A'); ?>
                                        </span>
                                        <?php if (isset($demanda['sectorId']) && $demanda['sectorId'] == $miSectorId): ?>
                                            <span class="text-[9px] bg-emerald-500 text-white px-3 py-1 rounded-full font-black uppercase tracking-wider shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i> Match Sector
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <h3 class="font-black text-gray-900 text-lg mb-3 leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($demanda['tituloDemanda'] ?? 'N/A'); ?></h3>
                                <p class="text-xs text-gray-500 leading-relaxed font-medium line-clamp-4 mb-6"><?php echo htmlspecialchars($demanda['descripcionDemanda'] ?? 'N/A'); ?></p>
                                
                                <div class="space-y-3 pt-6 border-t border-gray-50">
                                    <div class="flex items-center text-xs">
                                        <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center mr-3 border border-teal-100/50">
                                            <i class="fas fa-building text-[#0d9488] text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Comprador</p>
                                            <p class="font-black text-gray-800 leading-none"><?php echo htmlspecialchars($demanda['razon_social'] ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($demanda['ubicacionGeografica'])): ?>
                                    <div class="flex items-center text-xs text-gray-400">
                                        <i class="fas fa-map-marker-alt w-8 text-center text-[10px] mr-0.5"></i>
                                        <span class="font-bold tracking-tight italic"><?php echo htmlspecialchars($demanda['ubicacionGeografica']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="px-7 pb-7">
                                <?php if ($rueda['estadoRueda'] === 'activa'): ?>
                                    <button onclick="abrirModalCitaDemanda(<?php echo $demanda['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($demanda['razon_social'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($demanda['tituloDemanda'] ?? 'N/A')); ?>')" 
                                            class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition-all duration-300 shadow-md shadow-teal-500/10 flex items-center justify-center gap-2">
                                        <i class="fas fa-calendar-plus text-[10px]"></i> Solicitar Reunión
                                    </button>
                                <?php else: ?>
                                    <button disabled class="w-full bg-gray-50 text-gray-300 py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] cursor-not-allowed border border-gray-100 flex items-center justify-center gap-2">
                                        <i class="fas fa-lock text-[10px]"></i> Agendamiento Cerrado
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECCIÓN: EMPRESAS PARTICIPANTES REDISEÑADA -->
        <div class="mt-16 border-t border-gray-100 pt-16 mb-20">
            <button onclick="document.getElementById('grid_empresas').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180')" 
                    class="w-full flex items-center justify-between p-8 bg-gray-50/50 border border-gray-100 rounded-[2rem] group hover:bg-white hover:shadow-xl hover:border-teal-100 transition-all duration-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:bg-teal-50 transition-colors">
                        <i class="fas fa-users text-teal-400 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="text-left">
                        <h2 class="text-lg font-black text-gray-900 leading-tight">Empresas Participantes</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1"><?php echo count($participantes); ?> compradores inscritos en esta rueda</p>
                    </div>
                </div>
                <i class="fas fa-chevron-down text-gray-300 transition-transform duration-500"></i>
            </button>
            
            <div id="grid_empresas" class="hidden mt-10">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    <?php foreach ($participantes as $p): ?>
                        <div class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-100 transition-all text-center group">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-50 group-hover:bg-teal-50 transition-colors">
                                <i class="fas fa-building text-gray-300 text-sm group-hover:text-teal-400 transition-colors"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-900 line-clamp-2 uppercase tracking-tight"><?php echo htmlspecialchars($p['razon_social']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para solicitar reunión desde una Demanda -->
<div id="modalCitaDemanda" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalCitaDemanda').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalCitaDemanda').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=vendedor&accion=solicitarCita" method="POST">
                <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                <input type="hidden" name="comprador_id" id="modal_comprador_id">
                <input type="hidden" name="vendedor_id" value="<?php echo $miEmpresaId; ?>">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-5 text-left">
                        <div class="p-2 bg-teal-500/10 text-[#0d9488] rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Solicitar Reunión de Negocio</h3>
                    </div>
                    
                    <!-- Tarjeta de Detalles del Negocio -->
                    <div class="bg-gradient-to-br from-teal-50/50 to-emerald-50/30 border border-teal-100 p-4 rounded-2xl mb-6 flex flex-col gap-3 text-left">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-500/10 text-[#0d9488] rounded-xl text-md flex items-center justify-center shrink-0">
                                <i class="fas fa-bullhorn text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#0d9488] tracking-wider uppercase">Interés en demanda</p>
                                <p id="modal_titulo_demanda" class="font-extrabold text-gray-800 text-sm mt-0.5"></p>
                            </div>
                        </div>
                        <div class="h-px bg-teal-100/50"></div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-500/10 text-gray-600 rounded-xl text-md flex items-center justify-center shrink-0">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">Comprador</p>
                                <p id="modal_nombre_comprador" class="font-bold text-gray-800 text-sm mt-0.5"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos del Formulario -->
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Fecha y Hora Propuesta</label>
                            <input type="text" name="fecha_hora" id="fecha_hora_input" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 bg-white cursor-pointer"
                                   placeholder="Seleccionar fecha y hora...">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#0d9488]"></i>
                                La cita debe agendarse antes del <?php echo date('d/m/Y H:i', strtotime($rueda['fechaFin'])); ?>
                            </p>
                        </div>

                        <?php if ($rueda['modalidad'] === 'virtual'): ?>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Link de Reunión (Opcional)</label>
                                <input type="url" name="link_reunion" placeholder="https://meet.google.com/..." 
                                       class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200">
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#0d9488]"></i>
                                    Puedes agregarlo ahora o después desde tus Citas Aceptadas
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 mb-4 text-left">
                                <p class="text-[11px] text-orange-800 font-black leading-relaxed text-left">
                                    <i class="fas fa-map-marker-alt mr-1.5 text-orange-500"></i> 
                                    <strong>Reunión Presencial:</strong> Esta rueda se realiza físicamente en: <br>
                                    <span class="font-bold text-orange-900 ml-5"><?php echo htmlspecialchars($rueda['ubicacion']); ?></span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Número de Mesa / Stand (Opcional)</label>
                                <div class="relative">
                                    <select name="numero_mesa" id="numero_mesa_select" 
                                           class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 appearance-none bg-white font-bold">
                                        <option value="">Selecciona una fecha primero...</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488]">
                                        <i class="fas fa-chair text-[10px]"></i>
                                    </div>
                                </div>
                                <p id="mesa_info_text" class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#0d9488]"></i>
                                    Solo se muestran las mesas libres para el horario elegido.
                                </p>
                            </div>
                        <?php endif; ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Mensaje / Objetivo</label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 resize-none" 
                                      placeholder="Describe tu interés en esta demanda..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalCitaDemanda').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-black text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#0d9488] text-sm font-black text-white hover:bg-[#0f766e] shadow-[0_4px_15px_rgba(13,148,136,0.2)] hover:shadow-[0_6px_20px_rgba(13,148,136,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para registrar oferta -->
<div id="modalOferta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalOferta').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalOferta').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=vendedor&accion=registrarOferta" method="POST">
                <input type="hidden" name="empresa_id" value="<?php echo $miEmpresaId; ?>">
                <input type="hidden" name="rueda_id" id="cita_rueda_id_oferta">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-6 text-left">
                        <div class="p-2 bg-teal-500/10 text-[#0d9488] rounded-xl">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Publicar Nueva Oferta</h3>
                    </div>
                    
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Categoría del Sector</label>
                            <div class="relative">
                                <select name="sector_id" class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 appearance-none bg-white font-bold text-gray-700">
                                    <?php foreach ($todos_sectores as $sec): ?>
                                        <option value="<?php echo $sec['id']; ?>" <?php echo $sec['id'] == $miSectorId ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sec['nombreSector']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488]">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Nombre del Producto o Servicio <span class="text-red-500">*</span></label>
                            <input type="text" name="producto_servicio" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 font-bold" 
                                   placeholder="Ej: Consultoría en Estrategia Digital">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Descripción Comercial <span class="text-red-500">*</span></label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 resize-none font-medium" 
                                      placeholder="Detalla los beneficios y características de tu oferta..."></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Palabras Clave (Tags)</label>
                            <input type="text" name="tags" 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 font-bold" 
                                   placeholder="ej: software, cloud, asesoría">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-tags text-[#0d9488] text-[9px]"></i>
                                Sepáralos por comas para mejorar tu visibilidad.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalOferta').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#0d9488] text-sm font-black text-white hover:bg-[#0f766e] shadow-[0_4px_15px_rgba(13,148,136,0.2)] hover:shadow-[0_6px_20px_rgba(13,148,136,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        Publicar Oferta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Flatpickr en español para la cita
    if (document.getElementById('fecha_hora_input')) {
        flatpickr("#fecha_hora_input", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "F j, Y - h:i K",
            locale: "es",
            minDate: "today",
            maxDate: "<?php echo date('Y-m-d H:i', strtotime($rueda['fechaFin'])); ?>",
            time_24hr: false,
            disableMobile: "true",
            animate: true,
            onChange: function(selectedDates, dateStr) {
                if (document.getElementById('numero_mesa_select')) {
                    cargarMesasDisponibles(dateStr);
                }
            }
        });
    }
});

async function cargarMesasDisponibles(fechaHora) {
    const select = document.getElementById('numero_mesa_select');
    const infoText = document.getElementById('mesa_info_text');
    const ruedaId = "<?php echo $ruedaId; ?>";

    if (!select) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const encodedFecha = encodeURIComponent(fechaHora);
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&fecha_hora=${encodedFecha}`);
        const result = await response.json();

        select.innerHTML = '<option value="">-- Seleccionar Mesa --</option>';
        
        if (result.status === 'success' && result.data && result.data.mesas && result.data.mesas.length > 0) {
            result.data.mesas.forEach(mesa => {
                const opt = document.createElement('option');
                opt.value = mesa;
                opt.textContent = 'Mesa ' + mesa;
                select.appendChild(opt);
            });
            select.disabled = false;
            infoText.innerHTML = `<i class="fas fa-check-circle text-emerald-500 mr-1"></i> ${result.data.mesas.length} mesas libres encontradas.`;
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles en este horario</option>';
            infoText.innerHTML = '<i class="fas fa-times-circle text-rose-500 mr-1"></i> Todo ocupado. Intenta con otra hora.';
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
    }
}

function abrirModalCitaDemanda(compradorId, nombreComprador, tituloDemanda) {
    document.getElementById('modal_comprador_id').value = compradorId;
    document.getElementById('modal_nombre_comprador').innerText = nombreComprador;
    document.getElementById('modal_titulo_demanda').innerText = tituloDemanda;
    document.getElementById('modalCitaDemanda').classList.remove('hidden');
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
