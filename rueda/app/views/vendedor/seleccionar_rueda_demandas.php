<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <?php if (empty($ruedas_disponibles)): ?>
            <!-- Mensaje si no hay ruedas disponibles -->
            <div class="bg-amber-50/40 border-2 border-dashed border-amber-200 rounded-[2.5rem] p-16 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-amber-50">
                    <i class="fas fa-exclamation-triangle text-amber-300 text-3xl"></i>
                </div>
                <p class="text-amber-900 font-black text-lg">No tienes ruedas disponibles</p>
                <p class="text-amber-600 text-sm font-bold mt-2 max-w-md mx-auto">Para explorar demandas de compradores, primero debes estar inscrito y aceptado en una rueda de negocios activa.</p>
                <a href="index.php?controlador=vendedor&accion=dashboard" class="inline-flex items-center mt-6 bg-[#0d9488] hover:bg-[#0f766e] text-white px-8 py-3 rounded-full font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20">
                    <i class="fas fa-search mr-2"></i> Buscar ruedas disponibles
                </a>
            </div>
        <?php else: ?>
            <!-- Grid de ruedas disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($ruedas_disponibles as $rueda): 
                    $fecha_inicio = new DateTime($rueda['fechaInicio']);
                    $fecha_fin = new DateTime($rueda['fechaFin']);
                    $hoy = new DateTime();
                    $esta_activa = $hoy >= $fecha_inicio && $hoy <= $fecha_fin;
                    $demandas_count = $rueda['total_demandas'] ?? 0;
                ?>
                    <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-xl hover:border-teal-100 transition-all duration-500 group">
                        <!-- Header con estado -->
                        <div class="relative">
                            <div class="h-40 bg-gradient-to-br from-[#0d9488] to-[#14b8a6] flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-white/10"></div>
                                <i class="fas fa-handshake text-white text-5xl relative z-10 group-hover:scale-110 transition-transform duration-300"></i>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-black backdrop-blur-sm
                                    <?php echo $rueda['estadoRueda'] === 'activa' ? 'bg-emerald-500/90 text-white' : 
                                        ($rueda['estadoRueda'] === 'inscripciones' ? 'bg-sky-500/90 text-white' : 'bg-gray-500/90 text-white'); ?>">
                                    <?php echo ucfirst($rueda['estadoRueda'] ?? ''); ?>
                                </span>
                            </div>
                            <?php if ($demandas_count > 0): ?>
                                <div class="absolute -bottom-4 left-6">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black bg-amber-500 text-white border-4 border-white shadow-lg">
                                        <i class="fas fa-clipboard-list mr-1.5"></i>
                                        <?php echo $demandas_count; ?> demanda<?php echo $demandas_count > 1 ? 's' : ''; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contenido -->
                        <div class="p-8 pt-10">
                            <h3 class="text-xl font-black text-gray-900 mb-3 leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($rueda['tituloRueda'] ?? ''); ?></h3>
                            <p class="text-sm text-gray-500 mb-6 line-clamp-2 font-medium leading-relaxed"><?php echo htmlspecialchars($rueda['descripcionRueda'] ?? ''); ?></p>

                            <!-- Fechas -->
                            <div class="flex items-center text-sm text-gray-400 mb-6">
                                <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center mr-3 border border-teal-100/50">
                                    <i class="fas fa-calendar-alt text-[#0d9488] text-xs"></i>
                                </div>
                                <span class="font-bold tracking-tight">
                                    <?php echo $fecha_inicio->format('d M'); ?> - <?php echo $fecha_fin->format('d M Y'); ?>
                                </span>
                            </div>

                            <!-- Indicadores -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center text-sm">
                                    <span class="w-2.5 h-2.5 rounded-full <?php echo $esta_activa ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300'; ?> mr-2.5"></span>
                                    <span class="<?php echo $esta_activa ? 'text-emerald-600 font-black' : 'text-gray-400 font-bold'; ?>">
                                        <?php echo $esta_activa ? 'En curso' : 'Próximamente'; ?>
                                    </span>
                                </div>
                                <?php if ($demandas_count == 0): ?>
                                    <span class="text-xs text-gray-400 font-bold">Sin demandas aún</span>
                                <?php endif; ?>
                            </div>

                            <!-- Botón de acción -->
                            <a href="index.php?controlador=vendedor&accion=explorarDemandas&ruedaId=<?php echo $rueda['id']; ?>" 
                               class="w-full inline-flex items-center justify-center px-6 py-4 bg-[#0d9488] hover:bg-[#0f766e] text-white font-black rounded-2xl transition-all duration-300 shadow-md shadow-teal-500/10 hover:shadow-lg hover:-translate-y-0.5
                               <?php echo $demandas_count == 0 ? 'opacity-60 cursor-not-allowed' : ''; ?>">
                                <i class="fas fa-search mr-2"></i>
                                <?php echo $demandas_count > 0 ? 'Ver Demandas' : 'No hay demandas'; ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
