<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        
        <!-- Mensajes -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'reunion_solicitada'): ?>
            <div class="mb-6 p-5 bg-gradient-to-r from-sky-50 to-blue-50 border-2 border-[#00a2ff]/30 rounded-3xl shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#00a2ff] text-white rounded-2xl flex items-center justify-center shadow-md shadow-sky-500/20 shrink-0 text-lg">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">¡Solicitud de Cita Enviada con Éxito!</h3>
                        <p class="text-xs text-gray-600 mt-0.5 font-medium">Tu propuesta de reunión ha sido enviada al proveedor. Puedes darle seguimiento desde tu sección de <b>"Mis Citas"</b>.</p>
                    </div>
                </div>
                <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-[#00a2ff] hover:bg-[#008ae0] text-white text-xs font-black px-4 py-2 rounded-xl transition-all shadow-sm shrink-0">
                    Ver Mis Citas
                </a>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'demanda_registrada'): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-300 rounded-2xl flex items-center">
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
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-widest text-white/60">
                        <li class="inline-flex items-center">
                            <a href="index.php?controlador=comprador&accion=dashboard" class="hover:text-white transition-colors">Panel</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right mx-2 text-[8px]"></i>
                                <span class="text-white">Buscar Vendedores</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">Catálogo de Proveedores</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-shopping-basket mr-2 text-white/80"></i> <?php echo htmlspecialchars($rueda['tituloRueda']); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=comprador&accion=dashboard" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 px-6 py-3 rounded-full font-bold text-sm transition-all duration-300 flex items-center gap-2 backdrop-blur-sm">
                    <i class="fas fa-arrow-left text-xs"></i> Volver al Panel
                </a>
                <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-white text-[#00a2ff] px-6 py-3 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Citas
                </a>
            </div>
        </div>

        <!-- GUÍA RÁPIDA: ¿CÓMO FUNCIONA LA RUEDA DE NEGOCIOS? -->
        <div class="bg-gradient-to-br from-white via-sky-50/40 to-blue-50/60 rounded-[2.5rem] p-6 sm:p-8 border border-sky-100 shadow-[0_4px_25px_rgba(0,162,255,0.06)] mb-8">
            <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-black shadow-md shadow-sky-500/20 text-base">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">¿Cómo concretar negocios en esta rueda?</h3>
                        <p class="text-xs text-slate-500 font-semibold">Sigue estos 3 pasos clave para conectar con proveedores y agendar citas.</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-100 text-sky-800 border border-sky-200">
                    <i class="fas fa-check-circle text-sky-600"></i> Flujo Recomendado
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Paso 1 -->
                <div class="bg-white p-5 rounded-2xl border border-sky-100/80 shadow-sm relative overflow-hidden group hover:border-sky-300 transition-all">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="w-7 h-7 rounded-full bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xs font-black">1</span>
                        <h4 class="font-black text-slate-900 text-sm">Explora "Buscar Proveedores"</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Revisa los productos, servicios y empresas vendedoras registradas. Filtra por sector CIIU o busca por palabras clave.
                    </p>
                </div>

                <!-- Paso 2 -->
                <div class="bg-white p-5 rounded-2xl border border-sky-100/80 shadow-sm relative overflow-hidden group hover:border-sky-300 transition-all">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="w-7 h-7 rounded-full bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xs font-black">2</span>
                        <h4 class="font-black text-slate-900 text-sm">Haz clic en "Solicitar Cita"</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Elige una empresa proveedora y envía una propuesta de fecha y hora disponible para acordar un espacio de negociación.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="bg-white p-5 rounded-2xl border border-sky-100/80 shadow-sm relative overflow-hidden group hover:border-sky-300 transition-all">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="w-7 h-7 rounded-full bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xs font-black">3</span>
                        <h4 class="font-black text-slate-900 text-sm">Gestiona en "Mis Citas"</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">
                        Revisa el estado de tus citas, responde contraofertas de los vendedores y conéctate al enlace virtual o asiste a la mesa física.
                    </p>
                </div>
            </div>
        </div>

        <!-- ======================================================
             SECCIÓN: MIS DEMANDAS / REQUERIMIENTOS (TARJETA REDISEÑADA)
             ====================================================== -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden mb-10">
            <!-- Header Tarjeta -->
            <div class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md mr-4 shadow-sm">
                            <i class="fas fa-bullhorn text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-white font-black text-xl tracking-tight">Mis Demandas / Requerimientos</h2>
                                <span class="bg-white/20 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full backdrop-blur-sm">
                                    <?php echo count($demandas_rueda ?? []); ?> activas
                                </span>
                            </div>
                            <p class="text-white/80 text-xs font-semibold mt-0.5">Comunica a los proveedores qué necesitas adquirir en esta rueda</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('modalNuevaDemanda').classList.remove('hidden')" 
                            class="bg-white text-[#00a2ff] px-6 py-3 rounded-full font-black text-xs uppercase tracking-wider hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 shadow-md shrink-0">
                        <i class="fas fa-plus text-[10px]"></i> Publicar Demanda
                    </button>
                </div>
            </div>
            
            <!-- Lista de Demandas Existentes -->
            <div class="p-6 sm:p-8">
                <?php if (empty($demandas_rueda)): ?>
                    <div class="text-center py-12 px-6 bg-gradient-to-b from-sky-50/50 to-white rounded-[2rem] border-2 border-dashed border-sky-200/80">
                        <div class="w-16 h-16 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="fas fa-clipboard-check text-2xl"></i>
                        </div>
                        <h4 class="text-slate-900 font-black text-base">Aún no has publicado demandas de compra</h4>
                        <p class="text-slate-500 text-xs font-medium mt-1.5 max-w-md mx-auto leading-relaxed">
                            Al registrar tus requerimientos, los vendedores podrán preparar propuestas personalizadas para tus reuniones de negocios.
                        </p>
                        <button onclick="document.getElementById('modalNuevaDemanda').classList.remove('hidden')" 
                                class="mt-5 inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-black uppercase tracking-wider px-6 py-3 rounded-full shadow-lg shadow-sky-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-plus"></i> Publicar Mi Primer Requerimiento
                        </button>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <?php foreach ($demandas_rueda as $demanda): ?>
                            <?php 
                                $tags = json_decode($demanda['tagsRequerimiento'] ?? '[]', true);
                                $tags_str = is_array($tags) ? implode(', ', $tags) : '';
                            ?>
                            <div class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 hover:border-sky-300 hover:bg-white hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <h3 class="font-black text-gray-900 text-sm leading-snug group-hover:text-[#00a2ff] transition-colors">
                                            <?php echo htmlspecialchars($demanda['tituloDemanda']); ?>
                                        </h3>
                                        <span class="text-[9px] font-black text-gray-400 bg-white px-2 py-0.5 rounded-md border border-gray-200 shrink-0 uppercase">
                                            <?php echo date('d M', strtotime($demanda['createdAt'])); ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-xs leading-relaxed mb-3 line-clamp-3 font-medium">
                                        <?php echo htmlspecialchars($demanda['descripcionDemanda']); ?>
                                    </p>
                                </div>
                                <?php if (!empty($tags)): ?>
                                    <div class="flex flex-wrap gap-1.5 pt-2 border-t border-gray-100">
                                        <?php foreach ($tags as $tag): ?>
                                            <span class="px-2.5 py-0.5 bg-white text-[#00a2ff] rounded-full text-[9px] font-bold border border-sky-100 shadow-2xs">
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
                        
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- FILTROS DE BÚSQUEDA REDISEÑADOS -->
        <div class="bg-white p-5 sm:p-7 rounded-[2.5rem] shadow-[0_10px_35px_rgba(0,0,0,0.03)] border border-gray-100/80 mb-12">
            <form method="GET" action="" class="flex flex-col md:flex-row gap-3.5">
                <input type="hidden" name="controlador" value="comprador">
                <input type="hidden" name="accion" value="verParticipantes">
                <input type="hidden" name="id" value="<?php echo $ruedaId; ?>">
                
                <div class="flex-1 relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#00a2ff] transition-colors">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>" 
                           placeholder="Buscar por producto, servicio, proveedor o palabra clave..." 
                           class="w-full bg-slate-50/70 border border-gray-200/80 rounded-2xl pl-11 pr-4 py-3.5 text-xs sm:text-sm font-bold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] focus:bg-white transition-all">
                </div>
                
                <?php if (!empty($todos_sectores)): ?>
                <div class="md:w-72 relative group">
                    <select name="sector_id" class="appearance-none w-full bg-slate-50/70 border border-gray-200/80 rounded-2xl pl-4 pr-10 py-3.5 text-xs sm:text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] focus:bg-white transition-all cursor-pointer">
                        <option value="">Todas las actividades</option>
                        <?php foreach ($todos_sectores as $sec): ?>
                            <option value="<?php echo htmlspecialchars($sec['valor']); ?>" <?php echo ($_GET['sector_id'] ?? '') === $sec['valor'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sec['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff] group-hover:scale-110 transition-transform">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-center gap-2">
                    <button type="submit" class="w-full md:w-auto bg-[#00a2ff] hover:bg-[#008ae0] text-white px-7 py-3.5 rounded-2xl font-black text-xs uppercase tracking-wider transition-all duration-300 shadow-md shadow-sky-500/20 hover:shadow-lg hover:shadow-sky-500/30 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fas fa-filter text-xs"></i> Filtrar
                    </button>
                    <?php if (!empty($_GET['busqueda']) || !empty($_GET['sector_id'])): ?>
                        <a href="index.php?controlador=comprador&accion=verParticipantes&id=<?php echo $ruedaId; ?>" class="bg-gray-100 text-gray-500 px-5 py-3.5 rounded-2xl font-black text-xs hover:bg-gray-200 transition-all duration-300 text-center flex items-center justify-center uppercase tracking-wider">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- GRID PRINCIPAL: OFERTAS REDISEÑADO -->
        <div class="mb-14">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 flex items-center justify-center text-[#00a2ff] border border-sky-100 shadow-sm">
                        <i class="fas fa-box-open text-base"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 leading-none">Ofertas Disponibles</h2>
                        <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-wider">Catálogo de proveedores listos para agendar</p>
                    </div>
                </div>
                <div class="bg-sky-50 px-4 py-1.5 rounded-full border border-sky-100">
                    <span class="text-[10px] font-black text-[#00a2ff] uppercase tracking-wider"><?php echo count($ofertas); ?> disponibles</span>
                </div>
            </div>
            
            <?php if (empty($ofertas)): ?>
                <div class="bg-white border-2 border-dashed border-gray-200 rounded-[2.5rem] p-16 text-center shadow-sm">
                    <div class="w-20 h-20 bg-sky-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-sky-100 text-[#00a2ff]">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <p class="text-gray-900 font-black text-lg">No se encontraron ofertas</p>
                    <p class="text-gray-400 text-sm font-bold mt-2">Prueba ajustando tus términos de búsqueda o limpiando los filtros.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                    <?php foreach ($ofertas as $oferta): 
                        // Determinar categoría/sector visual limpio (evitar Asalariados y textos crudos de DB)
                        $categoriaVisual = '';
                        $sectoresExcluidos = ['Asalariados', 'Sin actividad económica, solo para personas naturales', 'Personas naturales subsidiadas por terceros', 'Sin actividad económica'];
                        
                        if (!empty($oferta['ciiu_nombre_personalizado']) && !in_array($oferta['ciiu_nombre_personalizado'], $sectoresExcluidos)) {
                            $categoriaVisual = $oferta['ciiu_nombre_personalizado'];
                        } elseif (!empty($oferta['nombreSector']) && !in_array($oferta['nombreSector'], $sectoresExcluidos)) {
                            $categoriaVisual = $oferta['nombreSector'];
                        } elseif (!empty($oferta['tags'])) {
                            $tagsList = array_filter(array_map('trim', explode(',', $oferta['tags'])));
                            if (!empty($tagsList)) $categoriaVisual = reset($tagsList);
                        }
                        if (empty($categoriaVisual)) $categoriaVisual = 'Oferta Empresarial';
                    ?>
                        <div class="bg-white rounded-[2.2rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden hover:shadow-2xl hover:border-sky-200 transition-all duration-500 flex flex-col justify-between group">
                            <div class="p-7 flex-1">
                                <!-- Badge superior de sector / match -->
                                <div class="flex items-start justify-between gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] bg-sky-50 text-[#00a2ff] px-3.5 py-1.5 rounded-full font-black uppercase tracking-wider border border-sky-100/70 shadow-sm max-w-full truncate">
                                        <i class="fas fa-tag text-[9px] opacity-75 shrink-0"></i>
                                        <span class="truncate"><?php echo htmlspecialchars($categoriaVisual); ?></span>
                                    </span>
                                    
                                    <?php if (!empty($miCiiuMatch) && !empty($oferta['ciiu_personalizado']) && $oferta['ciiu_personalizado'] == $miCiiuMatch): ?>
                                        <span class="text-[9px] bg-amber-500 text-white px-2.5 py-1 rounded-full font-black uppercase tracking-wider shadow-sm shrink-0 flex items-center gap-1">
                                            <i class="fas fa-star text-[8px]"></i> Match
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Título y Descripción -->
                                <h3 class="font-black text-gray-900 text-lg mb-2.5 leading-tight group-hover:text-[#00a2ff] transition-colors line-clamp-2"><?php echo htmlspecialchars($oferta['tituloOferta'] ?? 'N/A'); ?></h3>
                                <p class="text-xs text-gray-500 leading-relaxed font-medium line-clamp-3 mb-6"><?php echo htmlspecialchars($oferta['descripcionOferta'] ?? 'N/A'); ?></p>
                                
                                <!-- Bloque de Datos del Proveedor -->
                                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-100/80 space-y-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-white shadow-sm flex items-center justify-center border border-gray-100 shrink-0 text-[#00a2ff]">
                                            <i class="fas fa-building text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Proveedor</p>
                                            <p class="font-black text-gray-900 text-xs leading-tight truncate"><?php echo htmlspecialchars($oferta['razon_social'] ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($oferta['ubicacionGeografica'])): ?>
                                    <div class="flex items-center text-[11px] text-gray-500 font-semibold pt-2 border-t border-gray-100/80">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-[10px] mr-1.5 shrink-0"></i>
                                        <span class="truncate"><?php echo htmlspecialchars($oferta['ubicacionGeografica']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Botón Agendar Cita con Proveedor o Estado si ya tiene cita -->
                            <div class="p-6 pt-0">
                                <?php 
                                $citaExistente = $citas_por_vendedor[$oferta['empresaId']] ?? null;
                                if ($citaExistente): 
                                    $est = $citaExistente['estadoCita'] ?? 'pendiente';
                                    $txtEstado = 'Cita Agendada';
                                    $colorBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    $colorIcon = 'bg-emerald-500';
                                    if ($est === 'pendiente') {
                                        $txtEstado = 'Cita Solicitada';
                                        $colorBadge = 'bg-sky-50 text-[#00a2ff] border-sky-200';
                                        $colorIcon = 'bg-[#00a2ff]';
                                    } elseif ($est === 'negociando') {
                                        $txtEstado = 'En Negociación';
                                        $colorBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $colorIcon = 'bg-amber-500';
                                    } elseif ($est === 'aceptada' || $est === 'agendada') {
                                        $txtEstado = 'Cita Confirmada';
                                        $colorBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $colorIcon = 'bg-emerald-500';
                                    }
                                ?>
                                    <div class="w-full <?php echo $colorBadge; ?> border-2 py-3 px-4 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-2.5 h-2.5 rounded-full <?php echo $colorIcon; ?> animate-pulse"></span>
                                            <span class="font-black text-gray-900"><?php echo $txtEstado; ?></span>
                                        </div>
                                        <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" 
                                           class="text-[#00a2ff] hover:text-[#008ae0] font-black text-[11px] flex items-center gap-1 hover:underline">
                                            <span>Gestionar</span>
                                            <i class="fas fa-arrow-right text-[9px]"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <button type="button" 
                                            onclick="abrirModalSolicitar(<?php echo (int)$oferta['empresaId']; ?>, '<?php echo htmlspecialchars(addslashes($oferta['razon_social'])); ?>', '<?php echo htmlspecialchars(addslashes($oferta['tituloOferta'])); ?>', '<?php echo htmlspecialchars(addslashes($categoriaVisual)); ?>')"
                                            class="w-full bg-[#00a2ff] hover:bg-[#008ae0] text-white py-3.5 px-4 rounded-2xl text-xs font-black transition-all duration-300 shadow-md shadow-sky-500/15 flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-sky-500/25 transform hover:-translate-y-0.5 uppercase tracking-wider">
                                        <i class="fas fa-calendar-plus text-sm"></i> Solicitar Cita
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
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($participantes as $p): 
                        $citaExistenteP = $citas_por_vendedor[$p['id']] ?? null;
                    ?>
                        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg hover:border-sky-100 transition-all text-center flex flex-col justify-between group">
                            <div>
                                <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-sky-100/50 group-hover:bg-[#00a2ff] group-hover:text-white transition-colors">
                                    <i class="fas fa-building text-[#00a2ff] group-hover:text-white text-lg transition-colors"></i>
                                </div>
                                <p class="text-xs font-black text-gray-900 line-clamp-2 uppercase tracking-tight"><?php echo htmlspecialchars($p['razon_social']); ?></p>
                                <p class="text-[10px] font-bold text-sky-500 mt-1 uppercase tracking-tighter">
                                    CIIU: <?php echo htmlspecialchars($p['ciiu_personalizado'] ?: ($p['ciiu_clase'] ?? 'N/A')); ?>
                                </p>
                                <?php if (!empty($p['ciiu_nombre_personalizado'])): ?>
                                    <p class="text-[9px] text-gray-400 font-medium line-clamp-1 italic mt-0.5"><?php echo htmlspecialchars($p['ciiu_nombre_personalizado']); ?></p>
                                <?php endif; ?>
                            </div>

                            <?php if ($citaExistenteP): ?>
                                <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" 
                                   class="mt-4 w-full bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 py-2.5 px-3 rounded-xl text-xs font-black transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="fas fa-check-circle text-emerald-500"></i> Cita Agendada
                                </a>
                            <?php else: ?>
                                <button type="button" 
                                        onclick="abrirModalSolicitar(<?php echo (int)$p['id']; ?>, '<?php echo htmlspecialchars(addslashes($p['razon_social'])); ?>', '', '<?php echo htmlspecialchars(addslashes($p['nombreSector'] ?? '')); ?>')"
                                        class="mt-4 w-full bg-sky-50 hover:bg-[#00a2ff] text-[#00a2ff] hover:text-white py-2.5 px-3 rounded-xl text-xs font-black transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="fas fa-calendar-plus text-xs"></i> Solicitar Cita
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
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

<!-- Modal Solicitar Cita con Proveedor -->
<div id="modalSolicitudCita" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="cerrarModalSolicitar()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative animate-modal">
            
            <!-- Botón Cerrar (X) -->
            <button type="button" onclick="cerrarModalSolicitar()" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none z-10">
                <i class="fas fa-times text-sm"></i>
            </button>

            <form action="index.php?controlador=comprador&accion=solicitarReunion" method="POST">
                <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                <input type="hidden" name="comprador_id" value="<?php echo $miEmpresaId; ?>">
                <input type="hidden" name="vendedor_id" id="modal_vendedor_id">
                <input type="hidden" name="redirect_to" value="verParticipantes">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-3 mb-5 text-left">
                        <div class="w-11 h-11 bg-sky-50 text-[#00a2ff] rounded-2xl flex items-center justify-center shadow-sm shrink-0">
                            <i class="fas fa-calendar-plus text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Agendar Cita de Negocios</h3>
                            <p class="text-xs text-gray-400 font-bold">Propón fecha y hora al proveedor</p>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de Detalles del Proveedor -->
                    <div class="bg-gradient-to-br from-sky-50/50 to-blue-50/30 border border-sky-100 p-5 rounded-2xl mb-6 text-left">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-white text-[#00a2ff] rounded-xl shadow-sm shrink-0">
                                <i class="fas fa-building text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[9px] font-black text-[#00a2ff] tracking-wider uppercase">Empresa Proveedora</p>
                                <p id="modal_vendedor_nombre" class="font-black text-gray-900 text-base mt-0.5 truncate"></p>
                                <p id="modal_vendedor_contexto" class="text-xs text-gray-500 mt-1 font-medium line-clamp-2"></p>
                            </div>
                        </div>

                        <!-- Información de Mesa (Si es presencial) -->
                        <?php if (($rueda['modalidad'] ?? 'virtual') !== 'virtual' && ($rueda['cantidadMesas'] ?? 0) > 0): ?>
                            <div class="mt-4 pt-3 border-t border-sky-100/60">
                                <?php if (!empty($miMesaApartada)): ?>
                                    <input type="hidden" name="numero_mesa" value="<?php echo $miMesaApartada; ?>">
                                    <div class="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 p-2.5 rounded-xl border border-amber-200 font-bold">
                                        <i class="fas fa-map-marker-alt text-amber-500"></i>
                                        <span>Tu mesa asignada: <strong class="text-amber-900 font-black">Mesa #<?php echo $miMesaApartada; ?></strong></span>
                                    </div>
                                <?php else: ?>
                                    <div>
                                        <label class="block text-xs font-black text-gray-700 mb-1.5 uppercase tracking-wider">
                                            <i class="fas fa-chair text-amber-500 mr-1"></i> Asigna tu mesa para esta rueda <span class="text-red-500">*</span>
                                        </label>
                                        <select name="numero_mesa" required class="block w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs font-bold bg-white focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff]">
                                            <option value="">Selecciona una mesa disponible...</option>
                                            <?php for ($m = 1; $m <= ($rueda['cantidadMesas'] ?? 0); $m++): ?>
                                                <?php if (!in_array($m, $mesas_ocupadas ?? [])): ?>
                                                    <option value="<?php echo $m; ?>">Mesa #<?php echo $m; ?> (Disponible)</option>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Campos del Formulario -->
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-xs font-black text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">
                                Fecha y Hora Propuesta <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="fecha_hora" id="fecha_hora_solicitud" required 
                                       class="block w-full border border-gray-200 rounded-2xl shadow-sm pl-11 pr-4 py-3 text-sm font-bold focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-gray-50 cursor-pointer"
                                       placeholder="Seleccionar fecha y hora...">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#00a2ff]">
                                    <i class="far fa-calendar-alt text-base"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                Bloques de <?php echo (int)($rueda['duracionCitaMinutos'] ?? 30); ?> minutos dentro del horario oficial de la rueda.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Mensaje o Interés Comercial (Opcional)</label>
                            <textarea name="mensaje" rows="3" 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-xs focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 resize-none bg-gray-50 font-medium" 
                                      placeholder="Ej: Hola, nos interesa conocer sus precios y cotizar para el próximo mes..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="cerrarModalSolicitar()" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-xs font-black text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-full border border-transparent px-7 py-2.5 bg-[#00a2ff] text-xs font-black text-white hover:bg-[#008ae0] shadow-[0_4px_15px_rgba(0,162,255,0.2)] hover:shadow-[0_6px_20px_rgba(0,162,255,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-wider">
                        <i class="fas fa-paper-plane text-xs"></i> Enviar Solicitud de Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let flatpickrInstanceSolicitud = null;

function abrirModalSolicitar(vendedorId, razonSocial, ofertaTitulo = '', sector = '') {
    document.getElementById('modal_vendedor_id').value = vendedorId;
    document.getElementById('modal_vendedor_nombre').innerText = razonSocial;
    
    let contexto = '';
    if (ofertaTitulo) {
        contexto = 'Oferta de interés: ' + ofertaTitulo;
    } else if (sector) {
        contexto = 'Sector: ' + sector;
    } else {
        contexto = 'Reunión de vinculación comercial';
    }
    document.getElementById('modal_vendedor_contexto').innerText = contexto;

    const modal = document.getElementById('modalSolicitudCita');
    if (modal) modal.classList.remove('hidden');

    // Inicializar o actualizar Flatpickr
    const inputFecha = document.getElementById('fecha_hora_solicitud');
    if (inputFecha && typeof flatpickr !== 'undefined') {
        if (!flatpickrInstanceSolicitud) {
            flatpickrInstanceSolicitud = flatpickr(inputFecha, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "j \\de F, Y - h:i K",
                time_24hr: false,
                minuteIncrement: <?php echo (int)($rueda['duracionCitaMinutos'] ?? 30); ?>,
                locale: "es",
                minDate: "<?php echo date('Y-m-d H:i', max(strtotime(SYSTEM_TIME), strtotime($rueda['fechaInicio'] . ' ' . ($rueda['horaInicio'] ?? '08:00:00')))); ?>",
                maxDate: "<?php echo date('Y-m-d 23:59', strtotime($rueda['fechaFin'] . ' ' . ($rueda['horaFin'] ?? '18:00:00'))); ?>",
                defaultHour: 9,
                defaultMinute: 0
            });
        }
    }
}

function cerrarModalSolicitar() {
    const modal = document.getElementById('modalSolicitudCita');
    if (modal) modal.classList.add('hidden');
}

function toggleParticipantes() {
    const seccion = document.getElementById('grid_empresas');
    const icono = document.querySelector('button[onclick*="grid_empresas"] i');
    if (seccion) {
        seccion.classList.toggle('hidden');
        if (icono) icono.classList.toggle('rotate-180');
    }
}

<?php if (!empty($_GET['msg'])): ?>
document.addEventListener("DOMContentLoaded", function() {
    const msg = "<?php echo htmlspecialchars($_GET['msg']); ?>";
    if (msg === 'reunion_solicitada') {
        Swal.fire({
            icon: 'success',
            title: '¡Solicitud de Cita Enviada!',
            text: 'Tu propuesta de reunión fue enviada exitosamente al proveedor. Podrás ver su respuesta en "Mis Citas".',
            confirmButtonColor: '#00a2ff',
            confirmButtonText: 'Excelente'
        });
    } else if (msg === 'demanda_registrada') {
        Swal.fire({
            icon: 'success',
            title: '¡Demanda Registrada!',
            text: 'Tu requerimiento ha sido publicado con éxito en esta rueda.',
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Listo'
        });
    }
    
    // Limpiar el parametro msg de la URL sin recargar
    const url = new URL(window.location);
    url.searchParams.delete('msg');
    window.history.replaceState({}, document.title, url.toString());
});
<?php endif; ?>
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
