<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        
        <!-- Mensajes -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'demanda_registrada'): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-300 rounded-lg flex items-center">
                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-bold text-green-800">¡Demanda registrada exitosamente!</p>
                    <p class="text-green-700 text-sm">Tu requerimiento ha sido publicado y los vendedores podrán verlo.</p>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- HEADER PREMIUM TEMA AZUL COMPRADOR -->
        <div class="bg-gradient-to-r from-[#00a2ff] via-[#4dbfff] to-[#008ae0] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(0,162,255,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Mercado de Negocios</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Catálogo de Ofertas</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-shopping-basket mr-2 text-white/80"></i> <?php echo htmlspecialchars($rueda['tituloRueda']); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-white text-[#00a2ff] px-6 py-3 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Citas
                </a>
            </div>
        </div>

        <!-- ======================================================
             SECCIÓN: MIS DEMANDAS / REQUERIMIENTOS (TARJETA REDISEÑADA)
             ====================================================== -->
        <div class="max-w-4xl mx-auto bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <!-- Header Tarjeta -->
            <div class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] px-8 py-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md mr-4 shadow-sm">
                            <i class="fas fa-bullhorn text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-black text-xl tracking-tight">Mis Demandas / Requerimientos</h2>
                            <p class="text-white/80 text-xs font-bold uppercase tracking-wider mt-0.5">Comunica qué necesitas comprar en esta rueda</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('modalNuevaDemanda').classList.remove('hidden')" 
                            class="bg-white text-[#00a2ff] px-6 py-3 rounded-full font-black text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center shadow-md">
                        <i class="fas fa-plus mr-2 text-xs"></i> Nueva Demanda
                    </button>
                </div>
            </div>
            
            <!-- Lista de Demandas Existentes -->
            <div class="p-8">
                <?php if (empty($demandas_rueda)): ?>
                    <div class="text-center py-12 bg-sky-50/30 rounded-[2rem] border-2 border-dashed border-sky-100 group hover:border-sky-200 transition-colors">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-sky-50">
                            <i class="fas fa-clipboard-list text-sky-300 text-3xl group-hover:scale-110 transition-transform"></i>
                        </div>
                        <p class="text-sky-900 font-black text-lg">No has registrado demandas todavía</p>
                        <p class="text-sky-600/70 text-sm font-bold mt-2">Haz clic en "Nueva Demanda" para comunicar tus necesidades y ser visible para los vendedores.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($demandas_rueda as $demanda): ?>
                            <?php 
                                $tags = json_decode($demanda['tagsRequerimiento'] ?? '[]', true);
                                $tags_str = is_array($tags) ? implode(', ', $tags) : '';
                            ?>
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:border-sky-100 hover:shadow-md transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="font-black text-gray-900 text-base leading-tight group-hover:text-[#00a2ff] transition-colors"><?php echo htmlspecialchars($demanda['tituloDemanda']); ?></h3>
                                    <div class="bg-gray-50 px-2 py-1 rounded-lg">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">
                                            <?php echo date('d M', strtotime($demanda['createdAt'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs leading-relaxed mb-4 line-clamp-3 font-medium"><?php echo htmlspecialchars($demanda['descripcionDemanda']); ?></p>
                                <?php if ($tags_str): ?>
                                    <div class="flex flex-wrap gap-1.5 mt-auto">
                                        <?php foreach ($tags as $tag): ?>
                                            <span class="px-3 py-1 bg-sky-50 text-[#00a2ff] rounded-full text-[9px] font-black uppercase tracking-wider border border-sky-100/50">
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

        <!-- SECCIÓN: OFERTAS DE TU MISMO SECTOR (REDISEÑADA) -->
        <?php if (!empty($ofertas_mismo_sector)): ?>
        <div class="mb-14">
            <h2 class="text-sm font-black text-[#00a2ff] mb-6 flex items-center gap-2 uppercase tracking-[0.2em]">
                <i class="fas fa-star text-amber-400"></i> Recomendados para tu sector
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                <?php foreach ($ofertas_mismo_sector as $oferta): ?>
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-sky-100 transition-all duration-300 flex flex-col group relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-sky-50 rounded-full blur-xl group-hover:bg-sky-100 transition-colors"></div>
                        <div class="flex justify-between items-start relative z-10 mb-3">
                            <span class="text-[9px] bg-[#00a2ff] text-white px-3 py-1 rounded-full font-black uppercase tracking-widest shadow-sm">Match</span>
                        </div>
                        <h3 class="font-black text-gray-900 mt-2 text-sm line-clamp-2 leading-tight group-hover:text-[#00a2ff] transition-colors relative z-10"><?php echo htmlspecialchars($oferta['tituloOferta'] ?? 'N/A'); ?></h3>
                        <p class="text-[10px] text-[#00a2ff] font-black mt-2 uppercase tracking-wide relative z-10 flex items-center gap-1">
                            <i class="fas fa-building text-[8px] opacity-60"></i> <?php echo htmlspecialchars($oferta['razon_social'] ?? 'N/A'); ?>
                        </p>
                        
                        <div class="mt-auto pt-4 relative z-10">
                            <?php if ($rueda['estadoRueda'] === 'activa'): ?>
                                <button onclick="abrirModalCitaOferta(<?php echo $oferta['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($oferta['razon_social'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($oferta['tituloOferta'] ?? 'N/A')); ?>')" 
                                        class="w-full bg-[#00a2ff] hover:bg-[#008ae0] text-white py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-md shadow-sky-500/10">
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
                <input type="hidden" name="controlador" value="comprador">
                <input type="hidden" name="accion" value="verParticipantes">
                <input type="hidden" name="id" value="<?php echo $ruedaId; ?>">
                
                <div class="flex-1 relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#00a2ff] transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>" 
                           placeholder="Buscar ofertas, empresas o descripciones..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition-all shadow-inner">
                </div>
                <div class="md:w-64 relative group">
                    <select name="sector_id" class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition-all cursor-pointer shadow-inner">
                        <option value="">Todos los sectores</option>
                        <?php foreach ($todos_sectores as $sec): ?>
                            <option value="<?php echo $sec['id']; ?>" <?php echo ($_GET['sector_id'] ?? '') == $sec['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sec['nombreSector']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff] group-hover:scale-110 transition-transform">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <button type="submit" class="bg-[#00a2ff] hover:bg-[#008ae0] text-white px-8 py-3.5 rounded-2xl font-black text-sm transition-all duration-300 shadow-lg shadow-sky-500/20 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-xs"></i> Filtrar
                </button>
                <?php if (!empty($_GET['busqueda']) || !empty($_GET['sector_id'])): ?>
                    <a href="index.php?controlador=comprador&accion=verParticipantes&id=<?php echo $ruedaId; ?>" class="bg-gray-100 text-gray-500 px-6 py-3.5 rounded-2xl font-black text-sm hover:bg-gray-200 transition-all duration-300 text-center flex items-center justify-center">
                        <i class="fas fa-times mr-2 text-xs"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- GRID PRINCIPAL: OFERTAS REDISEÑADO -->
        <div class="mb-14">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-box-open text-[#00a2ff]"></i> Ofertas Disponibles
                </h2>
                <div class="bg-sky-50 px-4 py-1.5 rounded-full border border-sky-100">
                    <span class="text-[10px] font-black text-[#00a2ff] uppercase tracking-wider"><?php echo count($ofertas); ?> resultados</span>
                </div>
            </div>
            
            <?php if (empty($ofertas)): ?>
                <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <i class="fas fa-search text-gray-200 text-3xl"></i>
                    </div>
                    <p class="text-gray-900 font-black text-lg">No se encontraron ofertas</p>
                    <p class="text-gray-400 text-sm font-bold mt-2">Prueba ajustando tus filtros de búsqueda o cambiando de sector.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($ofertas as $oferta): ?>
                        <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-xl hover:border-sky-100 transition-all duration-500 flex flex-col group">
                            <div class="p-7 flex-1">
                                <div class="flex items-start justify-between mb-5">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-[9px] bg-sky-50 text-[#00a2ff] px-3 py-1 rounded-full font-black uppercase tracking-wider border border-sky-100/50">
                                            <i class="fas fa-tag mr-1 opacity-60"></i> <?php echo htmlspecialchars($oferta['nombreSector'] ?? 'N/A'); ?>
                                        </span>
                                        <?php if ($oferta['sectorId'] == $miSectorId): ?>
                                            <span class="text-[9px] bg-emerald-500 text-white px-3 py-1 rounded-full font-black uppercase tracking-wider shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i> Match Sector
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <h3 class="font-black text-gray-900 text-lg mb-3 leading-tight group-hover:text-[#00a2ff] transition-colors"><?php echo htmlspecialchars($oferta['tituloOferta'] ?? 'N/A'); ?></h3>
                                <p class="text-xs text-gray-500 leading-relaxed font-medium line-clamp-4 mb-6"><?php echo htmlspecialchars($oferta['descripcionOferta'] ?? 'N/A'); ?></p>
                                
                                <div class="space-y-3 pt-6 border-t border-gray-50">
                                    <div class="flex items-center text-xs">
                                        <div class="w-8 h-8 rounded-full bg-sky-50 flex items-center justify-center mr-3 border border-sky-100/50">
                                            <i class="fas fa-building text-[#00a2ff] text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Proveedor</p>
                                            <p class="font-black text-gray-800 leading-none"><?php echo htmlspecialchars($oferta['razon_social'] ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($oferta['ubicacionGeografica'])): ?>
                                    <div class="flex items-center text-xs text-gray-400">
                                        <i class="fas fa-map-marker-alt w-8 text-center text-[10px] mr-0.5"></i>
                                        <span class="font-bold tracking-tight italic"><?php echo htmlspecialchars($oferta['ubicacionGeografica']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="px-7 pb-7">
                                <?php if ($rueda['estadoRueda'] === 'activa'): ?>
                                    <button onclick="abrirModalCitaOferta(<?php echo $oferta['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($oferta['razon_social'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($oferta['tituloOferta'] ?? 'N/A')); ?>')" 
                                            class="w-full bg-[#00a2ff] hover:bg-[#008ae0] text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition-all duration-300 shadow-md shadow-sky-500/10 flex items-center justify-center gap-2">
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
                    class="w-full flex items-center justify-between p-8 bg-gray-50/50 border border-gray-100 rounded-[2rem] group hover:bg-white hover:shadow-xl hover:border-sky-100 transition-all duration-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 group-hover:bg-sky-50 transition-colors">
                        <i class="fas fa-users text-sky-400 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div class="text-left">
                        <h2 class="text-lg font-black text-gray-900 leading-tight">Empresas Participantes</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1"><?php echo count($participantes); ?> empresas inscritas en esta rueda</p>
                    </div>
                </div>
                <i class="fas fa-chevron-down text-gray-300 transition-transform duration-500"></i>
            </button>
            
            <div id="grid_empresas" class="hidden mt-10">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    <?php foreach ($participantes as $p): ?>
                        <div class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-sky-100 transition-all text-center group">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-gray-50 group-hover:bg-sky-50 transition-colors">
                                <i class="fas fa-building text-gray-300 text-sm group-hover:text-sky-400 transition-colors"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-900 line-clamp-2 uppercase tracking-tight"><?php echo htmlspecialchars($p['razon_social']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para solicitar reunión desde una Oferta -->
<div id="modalCitaOferta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalCitaOferta').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalCitaOferta').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=comprador&accion=solicitarReunion" method="POST">
                <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                <input type="hidden" name="vendedor_id" id="modal_vendedor_id">
                <input type="hidden" name="comprador_id" value="<?php echo $miEmpresaId; ?>">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-5 text-left">
                        <div class="p-2 bg-sky-500/10 text-[#00a2ff] rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Solicitar Reunión de Negocio</h3>
                    </div>
                    
                    <!-- Tarjeta de Detalles del Negocio -->
                    <div class="bg-gradient-to-br from-sky-50/50 to-blue-50/30 border border-sky-100 p-4 rounded-2xl mb-6 flex flex-col gap-3 text-left">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-sky-500/10 text-[#00a2ff] rounded-xl text-md flex items-center justify-center shrink-0">
                                <i class="fas fa-box-open text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#00a2ff] tracking-wider uppercase">Interés en oferta</p>
                                <p id="modal_titulo_oferta" class="font-extrabold text-gray-800 text-sm mt-0.5"></p>
                            </div>
                        </div>
                        <div class="h-px bg-sky-100/50"></div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-500/10 text-gray-600 rounded-xl text-md flex items-center justify-center shrink-0">
                                <i class="fas fa-building text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">Proveedor</p>
                                <p id="modal_nombre_vendedor" class="font-bold text-gray-800 text-sm mt-0.5"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos del Formulario -->
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Fecha y Hora Propuesta</label>
                            <input type="text" name="fecha_hora" id="fecha_hora_input" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-white cursor-pointer"
                                   placeholder="Seleccionar fecha y hora...">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                La cita debe agendarse antes del <?php echo date('d/m/Y H:i', strtotime($rueda['fechaFin'])); ?>
                            </p>
                        </div>

                        <?php if ($rueda['modalidad'] === 'virtual'): ?>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Link de Reunión (Opcional)</label>
                                <input type="url" name="link_reunion" placeholder="https://meet.google.com/..." 
                                       class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200">
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Puedes agregarlo ahora o después desde tus Citas Programadas
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
                                           class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 appearance-none bg-white font-bold">
                                        <option value="">Selecciona una fecha primero...</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff]">
                                        <i class="fas fa-chair text-[10px]"></i>
                                    </div>
                                </div>
                                <p id="mesa_info_text" class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Solo se muestran las mesas libres para el horario elegido.
                                </p>
                            </div>
                        <?php endif; ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Mensaje / Objetivo</label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 resize-none" 
                                      placeholder="Describe tu interés en esta oferta..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalCitaOferta').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-black text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#00a2ff] text-sm font-black text-white hover:bg-[#008ae0] shadow-[0_4px_15px_rgba(0,162,255,0.2)] hover:shadow-[0_6px_20px_rgba(0,162,255,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nueva Demanda -->
<div id="modalNuevaDemanda" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalNuevaDemanda').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalNuevaDemanda').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=comprador&accion=registrarRequerimiento" method="POST">
                <input type="hidden" name="empresa_id" value="<?php echo $miEmpresaId; ?>">
                <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                <input type="hidden" name="redirect_to" value="verParticipantes">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-5 text-left">
                        <div class="p-2 bg-sky-500/10 text-[#00a2ff] rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Publicar Nueva Demanda</h3>
                    </div>
                    
                    <!-- Información de la Rueda -->
                    <div class="bg-gradient-to-br from-sky-50/50 to-blue-50/30 border border-sky-100 p-4 rounded-2xl mb-6 text-left">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-sky-500/10 text-[#00a2ff] rounded-xl text-md flex items-center justify-center shrink-0">
                                <i class="fas fa-info-circle text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#00a2ff] tracking-wider uppercase">Rueda de Negocio Actual</p>
                                <p class="font-extrabold text-gray-800 text-sm mt-0.5"><?php echo htmlspecialchars($rueda['tituloRueda']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos del Formulario -->
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Título de la Demanda <span class="text-red-500">*</span></label>
                            <input type="text" name="tituloDemanda" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 font-bold"
                                   placeholder="Ej: Compra de 10 laptops corporativas">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Descripción Detallada <span class="text-red-500">*</span></label>
                            <textarea name="descripcionDemanda" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 resize-none font-medium" 
                                      placeholder="Describe tus requerimientos técnicos, cantidades, etc..."></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Tags / Palabras Clave</label>
                            <input type="text" name="tags" 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-2.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 font-bold"
                                   placeholder="Ej: tecnología, laptops, hardware">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-tags text-[#00a2ff] text-[9px]"></i>
                                Sepáralos por comas para que los vendedores te encuentren fácilmente.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalNuevaDemanda').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#00a2ff] text-sm font-black text-white hover:bg-[#008ae0] shadow-[0_4px_15px_rgba(0,162,255,0.2)] hover:shadow-[0_6px_20px_rgba(0,162,255,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        <i class="fas fa-save mr-2 text-[10px]"></i> Guardar Demanda
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
    const compradorId = "<?php echo $miEmpresaId; ?>";

    if (!select) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const encodedFecha = encodeURIComponent(fechaHora);
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&fecha_hora=${encodedFecha}&comprador_id=${compradorId}`);
        const result = await response.json();

        select.innerHTML = '<option value="">-- Seleccionar Mesa --</option>';
        
        if (result.status === 'success' && result.data && result.data.mesas && result.data.mesas.length > 0) {
            let mesaYaAsignada = result.data.mesa_sugerida;
            
            result.data.mesas.forEach(mesa => {
                const opt = document.createElement('option');
                opt.value = mesa;
                opt.textContent = mesa + (mesa === mesaYaAsignada ? ' (Tu mesa actual)' : '');
                if (mesa === mesaYaAsignada) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
            select.disabled = false;
            
            if (mesaYaAsignada) {
                infoText.innerHTML = `<i class="fas fa-check-circle text-[#00a2ff]"></i> Se ha pre-seleccionado tu mesa asignada.`;
            } else {
                infoText.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> ${result.data.mesas.length} mesas libres encontradas.`;
            }
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles en este horario</option>';
            
            // Mostrar información de debug si está disponible
            if (result.data && result.data.debug) {
                console.log("DEBUG INFO:", result.data.debug);
                const debugInfo = result.data.debug;
                let mensaje = `<i class="fas fa-times-circle text-red-500"></i> `;
                
                if (debugInfo.total_mesas_configuradas === 0) {
                    mensaje += `ERROR: La rueda no tiene mesas configuradas. Contacta al administrador.`;
                } else if (debugInfo.mesas_ocupadas && debugInfo.mesas_ocupadas.length > 0) {
                    mensaje += `Todas las mesas ocupadas. <br><small style="color: #666;">Mesas: ${debugInfo.total_mesas_configuradas}, Ocupadas: ${debugInfo.mesas_ocupadas.length}</small>`;
                } else {
                    mensaje += `No hay mesas disponibles. <br><small style="color: #666;">Mesas configuradas: ${debugInfo.total_mesas_configuradas}</small>`;
                }
                
                infoText.innerHTML = mensaje;
            } else {
                infoText.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> Todo ocupado. Intenta con otra hora.';
            }
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
    }
}

function abrirModalCitaOferta(vendedorId, nombreVendedor, tituloOferta) {
    document.getElementById('modal_vendedor_id').value = vendedorId;
    document.getElementById('modal_nombre_vendedor').innerText = nombreVendedor;
    document.getElementById('modal_titulo_oferta').innerText = tituloOferta;
    document.getElementById('modalCitaOferta').classList.remove('hidden');
}

function toggleParticipantes() {
    const seccion = document.getElementById('seccion-participantes');
    const icono = document.getElementById('icon-participantes');
    if (seccion.classList.contains('hidden')) {
        seccion.classList.remove('hidden');
        icono.classList.add('rotate-180');
    } else {
        seccion.classList.add('hidden');
        icono.classList.remove('rotate-180');
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
