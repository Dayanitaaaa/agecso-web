<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <!-- HEADER PREMIUM TEMA VERDE VENDEDOR -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(13,148,136,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Catálogo General</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Mis Ofertas</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-boxes mr-2 text-white/80"></i> Todas tus ofertas agrupadas por rueda de negocios
                </p>
            </div>
        </div>

        <!-- RESUMEN GENERAL -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-teal-50 text-[#0d9488] rounded-2xl">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Ofertas</p>
                        <p class="text-3xl font-black text-gray-800 mt-1"><?php echo count($todas_ofertas ?? []); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-500 rounded-2xl">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ruedas Activas</p>
                        <p class="text-3xl font-black text-gray-800 mt-1"><?php echo count($ruedas_inscrito ?? []); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl">
                        <i class="fas fa-layer-group text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ruedas con Ofertas</p>
                        <p class="text-3xl font-black text-gray-800 mt-1"><?php echo count($ofertas_por_rueda ?? []); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTA DE OFERTAS POR RUEDA -->
        <?php if (empty($ofertas_por_rueda)): ?>
            <div class="bg-teal-50/40 border-2 border-dashed border-teal-200 rounded-[2.5rem] p-16 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-teal-50">
                    <i class="fas fa-box-open text-teal-300 text-3xl"></i>
                </div>
                <p class="text-teal-900 font-black text-lg">No has registrado ofertas todavía</p>
                <p class="text-teal-600 text-sm font-bold mt-2 max-w-md mx-auto">Inscríbete en una rueda de negocios y comienza a promocionar tus productos para ser visible para los compradores.</p>
                <a href="index.php?controlador=vendedor&accion=dashboard" class="inline-flex items-center mt-6 bg-[#0d9488] hover:bg-[#0f766e] text-white px-8 py-3 rounded-full font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20">
                    <i class="fas fa-plus mr-2"></i> Explorar Ruedas
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach ($ofertas_por_rueda as $rueda): ?>
                    <div class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden hover:shadow-[0_10px_40px_rgba(0,0,0,0.06)] transition-all duration-500">
                        <!-- Header de la Rueda -->
                        <div class="bg-gradient-to-r from-[#0d9488] to-[#14b8a6] px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-black text-xl tracking-tight"><?php echo htmlspecialchars($rueda['tituloRueda']); ?></h3>
                                    <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                        <span class="text-white/80 text-xs font-bold">
                                            <i class="far fa-calendar mr-1"></i> <?php echo date('d/m/Y', strtotime($rueda['fechaInicio'])); ?> - <?php echo date('d/m/Y', strtotime($rueda['fechaFin'])); ?>
                                        </span>
                                        <?php if ($rueda['modalidad'] === 'virtual'): ?>
                                            <span class="text-white/80 text-xs font-bold"><i class="fas fa-video mr-1"></i> Virtual</span>
                                        <?php else: ?>
                                            <span class="text-white/80 text-xs font-bold"><i class="fas fa-map-marker-alt mr-1"></i> Presencial</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="bg-white/20 text-white text-xs font-black px-3 py-1.5 rounded-full backdrop-blur-sm">
                                    <?php echo count($rueda['ofertas']); ?> oferta(s)
                                </span>
                                <a href="index.php?controlador=vendedor&accion=verMisOfertas&id=<?php echo $rueda['rueda_id']; ?>" class="bg-white text-[#0d9488] px-6 py-2.5 rounded-full font-black text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                    <i class="fas fa-external-link-alt text-xs"></i> Ver Mercado
                                </a>
                            </div>
                        </div>

                        <!-- Lista de Ofertas de esta Rueda -->
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($rueda['ofertas'] as $oferta): ?>
                                    <?php 
                                        $tags = json_decode($oferta['tagsBusqueda'] ?? '[]', true);
                                        $tags_str = is_array($tags) ? implode(', ', $tags) : '';
                                    ?>
                                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100 hover:border-teal-100 hover:shadow-md transition-all duration-300 group">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="font-black text-gray-900 text-base leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($oferta['tituloOferta']); ?></h4>
                                            <div class="bg-white px-2 py-1 rounded-lg shadow-sm">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">
                                                    <?php echo date('d M', strtotime($oferta['createdAt'])); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-xs leading-relaxed mb-4 line-clamp-3 font-medium"><?php echo htmlspecialchars($oferta['descripcionOferta']); ?></p>
                                        <?php if ($tags_str): ?>
                                            <div class="flex flex-wrap gap-1.5 mt-auto">
                                                <?php foreach ($tags as $tag): ?>
                                                    <span class="px-3 py-1 bg-white text-[#0d9488] rounded-full text-[9px] font-black uppercase tracking-wider border border-teal-100/50">
                                                        #<?php echo htmlspecialchars($tag); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN: AGREGAR NUEVA OFERTA -->
        <?php if (!empty($ruedas_inscrito)): ?>
            <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-[2.5rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-8">
                <h2 class="text-xl font-extrabold text-gray-900 mb-6 flex items-center tracking-tight">
                    <i class="fas fa-plus-circle text-[#0d9488] mr-2.5"></i> Agregar Nueva Oferta
                </h2>
                
                <form action="index.php?controlador=vendedor&accion=registrarOferta" method="POST">
                    <input type="hidden" name="empresa_id" value="<?php echo $empresa['id']; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Seleccionar Rueda de Negocios</label>
                            <select name="rueda_id" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                                <option value="">-- Selecciona una rueda --</option>
                                <?php foreach ($ruedas_inscrito as $r): ?>
                                    <option value="<?php echo $r['id']; ?>">
                                        <?php echo htmlspecialchars($r['tituloRueda']); ?> (<?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Categoría del Producto</label>
                            <select name="sector_id" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                                <option value="">-- Selecciona una categoría --</option>
                                <?php foreach ($todos_sectores as $sec): ?>
                                    <option value="<?php echo $sec['id']; ?>" <?php echo $sec['id'] == $miSectorId ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($sec['nombreSector']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Título de la Oferta</label>
                        <input type="text" name="titulo" required placeholder="Ej: Café Premium de Origen 100% Arábica" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner">
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Descripción del Producto/Servicio</label>
                        <textarea name="descripcion" rows="4" required placeholder="Describe las características, beneficios y detalles de tu oferta..."
                                  class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner resize-none"></textarea>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Etiquetas de Búsqueda (separadas por coma)</label>
                        <input type="text" name="tags" placeholder="Ej: café, grano, premium, orgánico" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner">
                        <p class="text-[10px] text-gray-400 mt-1.5 font-bold">Las etiquetas ayudan a los compradores a encontrar tus productos más fácilmente.</p>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="bg-[#0d9488] hover:bg-[#0f766e] text-white px-8 py-3.5 rounded-2xl font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20 flex items-center gap-2">
                            <i class="fas fa-plus mr-2"></i> Publicar Oferta
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-amber-50 border-2 border-amber-100 p-6 rounded-3xl text-center">
                <p class="text-amber-800 font-black text-sm">No estás inscrito en ninguna rueda de negocios activa.</p>
                <p class="text-amber-600 text-xs font-bold mt-2">Inscríbete en una rueda para comenzar a publicar tus ofertas.</p>
                <a href="index.php?controlador=vendedor&accion=dashboard" class="inline-flex items-center mt-4 bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-full font-black text-xs transition-all duration-300">
                    Explorar Ruedas
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
