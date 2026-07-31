<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        <!-- HEADER PREMIUM TEMA VERDE VENDEDOR -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(13,148,136,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Trazabilidad de Citas</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Mis Encuestas de Satisfacción</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-star-half-alt mr-2 text-white/80"></i> Revisa tus calificaciones y encuestas pendientes
                </p>
            </div>
        </div>

        <!-- SECCIÓN DE ENCUESTAS PENDIENTES -->
        <?php if (!empty($encuestas_pendientes)): ?>
            <div class="mb-14">
                <h2 class="text-sm font-black text-[#0d9488] mb-6 flex items-center gap-3 uppercase tracking-[0.2em]">
                    <i class="fas fa-clock text-teal-400"></i> Pendientes por calificar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($encuestas_pendientes as $ep): ?>
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 border-l-4 border-l-[#0d9488] hover:shadow-xl transition-all duration-300 group">
                            <div class="mb-4">
                                <p class="text-[10px] font-black text-[#0d9488] uppercase tracking-widest mb-1"><?php echo htmlspecialchars($ep['tituloRueda']); ?></p>
                                <p class="text-base font-black text-gray-900 group-hover:text-[#0d9488] transition-colors leading-tight"><?php echo htmlspecialchars($ep['contraparte']); ?></p>
                                <div class="flex items-center text-gray-400 text-[10px] mt-2 font-bold uppercase">
                                    <i class="far fa-calendar-alt mr-1.5"></i> <?php echo date('d/m/Y H:i', strtotime($ep['fechaHora'])); ?>
                                </div>
                            </div>
                            <button onclick="abrirModalEncuesta(<?php echo $ep['id']; ?>, '<?php echo addslashes(htmlspecialchars($ep['contraparte'])); ?>', '<?php echo addslashes(htmlspecialchars($ep['tituloRueda'])); ?>', '<?php echo date('d/m/Y H:i', strtotime($ep['fechaHora'])); ?>')"
                                    class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white text-xs py-3 rounded-2xl font-black uppercase tracking-widest transition-all duration-300 shadow-md shadow-teal-500/10 flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i> Calificar Ahora
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- HISTORIAL DE CALIFICACIONES -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#0f766e] to-[#0d9488] px-8 py-6">
                <h2 class="text-white font-black text-lg flex items-center gap-3">
                    <i class="fas fa-history text-white/80"></i> Historial de Calificaciones
                </h2>
            </div>

            <div class="p-4 sm:p-8">
                <?php if (empty($mis_encuestas)): ?>
                    <div class="text-center py-20 bg-gray-50/50 rounded-[2rem] border-2 border-dashed border-gray-100">
                        <i class="fas fa-folder-open text-gray-200 text-5xl mb-4"></i>
                        <p class="text-gray-400 font-black text-lg uppercase tracking-wider">No has respondido encuestas todavía</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($mis_encuestas as $enc): ?>
                            <div class="bg-white p-6 sm:p-8 rounded-[2rem] border border-gray-100 hover:border-teal-100 hover:shadow-xl transition-all duration-500 relative group">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            <span class="text-[10px] font-black text-[#0d9488] uppercase tracking-widest bg-teal-50 px-3 py-1 rounded-full border border-teal-100/50">
                                                <?php echo htmlspecialchars($enc['tituloRueda']); ?>
                                            </span>
                                            <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full uppercase tracking-wider border border-gray-100">
                                                <i class="far fa-clock mr-1"></i> <?php echo date('d/m/Y H:i', strtotime($enc['createdAt'])); ?>
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2 leading-tight">
                                            Reunión con: <span class="text-[#0d9488]"><?php echo htmlspecialchars($enc['contraparte']); ?></span>
                                        </h3>
                                        
                                        <div class="flex flex-wrap items-center gap-4">
                                            <!-- Calificación con Estrellas -->
                                            <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-3 flex items-center gap-3">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Calificación</span>
                                                <div class="flex gap-1 text-amber-400">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <i class="<?php echo $i <= $enc['calificacionGeneral'] ? 'fas' : 'far'; ?> fa-star text-xs shadow-sm"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>

                                            <!-- Expectativa -->
                                            <div class="bg-teal-50/30 border border-teal-100 rounded-2xl p-3 flex items-center gap-3">
                                                <span class="text-[9px] font-black text-teal-600/60 uppercase tracking-widest">Expectativa</span>
                                                <span class="text-xs font-black text-[#0d9488] uppercase">
                                                    <?php echo ucfirst(str_replace('_', ' ', $enc['expectativaNegocio'])); ?>
                                                </span>
                                            </div>

                                            <!-- Valor de Negocio -->
                                            <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-3 flex items-center gap-3">
                                                <span class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest">Negocio</span>
                                                <span class="text-xs font-black text-emerald-700">
                                                    $<?php echo number_format($enc['valorNegocioProyectado'], 0); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <?php if (!empty($enc['comentarios'])): ?>
                                            <div class="mt-6 p-5 bg-gray-50/80 rounded-2xl border-l-4 border-[#0d9488] relative">
                                                <i class="fas fa-quote-left absolute top-4 left-4 text-gray-200 text-xl opacity-50"></i>
                                                <p class="text-sm text-gray-600 italic font-medium leading-relaxed pl-4">
                                                    <?php echo htmlspecialchars($enc['comentarios']); ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/modal_encuesta.php'; ?>
<?php include __DIR__ . '/../layout/footer.php'; ?>
