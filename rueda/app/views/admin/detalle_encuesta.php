<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
        <!-- Encabezado del Detalle -->
        <div class="bg-indigo-700 px-8 py-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-extrabold mb-2">Detalle de la Encuesta</h1>
                    <p class="text-indigo-100 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> 
                        Realizada el <?php echo date('d/m/Y H:i', strtotime($encuesta['createdAt'])); ?>
                    </p>
                </div>
                <div class="text-right">
                    <span class="bg-indigo-600 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-indigo-400">
                        <?php echo htmlspecialchars($encuesta['rolCalificador']); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Información del Evento y Empresas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Contexto del Evento</h3>
                    <p class="text-lg font-bold text-gray-800 mb-1"><?php echo htmlspecialchars($encuesta['tituloRueda'] ?? 'N/A'); ?></p>
                    <p class="text-sm text-gray-500 italic">Fecha Cita: <?php echo date('d/m/Y H:i', strtotime($encuesta['fechaHora'])); ?></p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Participantes</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Comprador:</span>
                            <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($encuesta['comprador'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Vendedor:</span>
                            <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($encuesta['vendedor'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados de la Encuesta -->
            <div class="border-t border-gray-100 pt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-poll-h mr-3 text-indigo-600"></i> Respuestas del Formulario
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Calificación -->
                    <div class="text-center p-6 bg-yellow-50 rounded-2xl border border-yellow-100">
                        <p class="text-xs font-bold text-yellow-700 uppercase mb-3">Satisfacción</p>
                        <div class="flex justify-center text-yellow-500 text-2xl mb-2">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="<?php echo $i <= $encuesta['calificacionGeneral'] ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-3xl font-black text-yellow-800"><?php echo $encuesta['calificacionGeneral']; ?>.0</p>
                    </div>

                    <!-- Expectativas -->
                    <div class="text-center p-6 <?php echo $encuesta['expectativaNegocio'] !== 'ninguno' ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100'; ?> rounded-2xl border">
                        <p class="text-xs font-bold <?php echo $encuesta['expectativaNegocio'] !== 'ninguno' ? 'text-green-700' : 'text-red-700'; ?> uppercase mb-3">Expectativas</p>
                        <i class="fas <?php echo $encuesta['expectativaNegocio'] !== 'ninguno' ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500'; ?> text-4xl mb-2"></i>
                        <p class="text-sm font-bold <?php echo $encuesta['expectativaNegocio'] !== 'ninguno' ? 'text-green-800' : 'text-red-800'; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $encuesta['expectativaNegocio'])); ?>
                        </p>
                    </div>

                    <!-- Valor Proyectado -->
                    <div class="text-center p-6 bg-blue-50 rounded-2xl border border-blue-100">
                        <p class="text-xs font-bold text-blue-700 uppercase mb-3">Valor de Negocio</p>
                        <i class="fas fa-dollar-sign text-blue-500 text-3xl mb-2"></i>
                        <p class="text-2xl font-black text-blue-800">$<?php echo number_format($encuesta['valorNegocioProyectado'], 0); ?></p>
                        <p class="text-[10px] text-blue-600 uppercase font-bold mt-1">Proyectado</p>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="mt-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Comentarios del Calificador</h4>
                    <div class="relative">
                        <i class="fas fa-quote-left absolute -top-2 -left-2 text-gray-200 text-3xl"></i>
                        <p class="text-gray-700 italic text-lg leading-relaxed relative z-10 px-6">
                            <?php echo !empty($encuesta['comentarios']) ? htmlspecialchars($encuesta['comentarios']) : 'No se proporcionaron comentarios adicionales.'; ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
