<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
    @media print {
        .no-print { display: none; }
        body { background: white; }
        .max-w-7xl { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .shadow-md, .shadow-sm { shadow: none !important; border: 1px solid #eee !important; }
        .grid { display: block !important; }
        .grid > div { margin-bottom: 20px; page-break-inside: avoid; }
        table { font-size: 10px !important; }
        h1 { font-size: 20px !important; }
    }
</style>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4">
        <div class="flex justify-between items-start mb-4 no-print">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="index.php?controlador=admin&accion=dashboard" class="text-blue-600 hover:text-blue-800 font-medium">Dashboard</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-600">Estadísticas de Impacto</li>
                </ol>
            </nav>
            <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white px-6 py-2 rounded-lg font-medium transition duration-200 shadow-sm">
                <i class="fas fa-print mr-2"></i> Descargar Reporte PDF
            </button>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($stats['tituloRueda']); ?></h1>
        <p class="text-gray-600 mb-8">Análisis de resultados y métricas de negocio.</p>

        <!-- Tarjetas de KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Empresas</p>
                <p class="text-2xl font-extrabold text-gray-800"><?php echo $total_participantes; ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Citas Totales</p>
                <p class="text-2xl font-extrabold text-blue-600"><?php echo $stats['citas_totales']; ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Exitosas</p>
                <p class="text-2xl font-extrabold text-green-600"><?php echo $stats['citas_exitosas']; ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Volumen</p>
                <p class="text-2xl font-extrabold text-indigo-600">$<?php echo number_format($stats['volumen_negocio_proyectado'], 0); ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Satisfacción</p>
                <div class="flex items-center">
                    <p class="text-2xl font-extrabold text-yellow-500"><?php echo number_format((float)($stats['satisfaccion_promedio'] ?? 0), 1); ?></p>
                    <span class="text-yellow-400 ml-1 text-sm"><i class="fas fa-star"></i></span>
                </div>
            </div>
        </div>

        </div>

        <!-- Historial de Reuniones por Empresa -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 mb-8">
            <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
                <h3 class="text-lg font-bold text-blue-900 flex items-center">
                    <i class="fas fa-history mr-2"></i> Historial de Participación por Empresa
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Empresa</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Citas</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Realizadas</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Empresas con las que se reunió</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($historial_empresas)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No se registran reuniones para las empresas en esta rueda.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historial_empresas as $emp): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($emp['razon_social'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                        <?php echo $emp['total_reuniones']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $emp['reuniones_realizadas'] > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                            <?php echo $emp['reuniones_realizadas']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <div class="max-w-md">
                                            <?php 
                                            if (!empty($emp['contrapartes'])) {
                                                $empresas_reunion = explode(', ', $emp['contrapartes']);
                                                // Eliminar duplicados si una empresa se reunió varias veces con la misma (aunque el requerimiento dice "puede que sea repetidas", mostraremos la lista completa o única según se prefiera, aquí mostramos todas para ver el historial real)
                                                foreach ($empresas_reunion as $c_emp) {
                                                    echo '<span class="inline-block bg-blue-100 text-blue-700 rounded px-2 py-0.5 mr-1 mb-1">' . htmlspecialchars($c_emp) . '</span>';
                                                }
                                            } else {
                                                echo '<span class="text-gray-400 italic">Sin reuniones concretadas</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Participación por Sectores -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 h-fit">
                <div class="px-6 py-4 bg-purple-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-purple-900">Participación por Sectores</h3>
                </div>
                <div class="p-6">
                    <?php if (empty($participacion_sectores)): ?>
                        <p class="text-center text-gray-500 italic">Sin datos de participación.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($participacion_sectores as $sector): ?>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($sector['nombreSector']); ?></span>
                                        <span class="text-gray-900 font-bold"><?php echo $sector['total']; ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <?php 
                                            $porcentaje = ($total_participantes > 0) ? ($sector['total'] / $total_participantes) * 100 : 0;
                                        ?>
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo $porcentaje; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Listado de Acuerdos Cerrados -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 h-fit lg:col-span-1">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Acuerdos de Negocio Registrados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Comprador</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Vendedor</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Monto Estimado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($negocios_detallados)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                    Aún no se han registrado cierres económicos en esta rueda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($negocios_detallados as $neg): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($neg['comprador'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($neg['vendedor'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 text-right">
                                        $<?php echo number_format($neg['montoEstimado'], 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Feedback de la Rueda de Negocios -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 mb-8">
            <div class="px-6 py-4 bg-indigo-50 border-b border-gray-200">
                <h3 class="text-lg font-bold text-indigo-900 flex items-center">
                    <i class="fas fa-poll-h mr-2"></i> Feedback y Encuestas de esta Rueda
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Empresa Calificadora</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Calificación</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Expectativas</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Comentario</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase no-print">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($encuestas_rueda)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No se han registrado encuestas para esta rueda aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($encuestas_rueda as $enc): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($enc['razon_social'] ?? 'N/A'); ?></div>
                                        <div class="text-[10px] uppercase font-bold text-gray-400"><?php echo $enc['rolCalificador']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center text-yellow-500">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?php echo $i <= $enc['calificacionGeneral'] ? 'fas' : 'far'; ?> fa-star text-xs"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        <?php if ($enc['expectativaNegocio'] !== 'ninguno'): ?>
                                            <span class="text-green-600 font-bold"><i class="fas fa-check-circle"></i> <?php echo ucfirst(str_replace('_', ' ', $enc['expectativaNegocio'])); ?></span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-bold"><i class="fas fa-times-circle"></i> Sin expectativas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate" title="<?php echo htmlspecialchars($enc['comentarios']); ?>">
                                        <?php echo htmlspecialchars($enc['comentarios'] ?: 'Sin comentarios'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm no-print">
                                        <a href="index.php?controlador=admin&accion=verDetalleEncuesta&id=<?php echo $enc['id']; ?>" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            <i class="fas fa-eye"></i>
                                        </a>
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

<?php include __DIR__ . '/../layout/footer.php'; ?>
