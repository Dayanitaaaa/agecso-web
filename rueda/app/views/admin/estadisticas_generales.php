<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(15,23,42,0.25)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/10 text-slate-300 text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-white/10">Panel de Control</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Estadísticas y Métricas del Sistema</h1>
                <p class="text-slate-300 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-chart-pie mr-2 text-slate-400"></i> Rendimiento general de ruedas, citas comerciales, impacto y satisfacción
                </p>
            </div>
            <div class="relative z-10 flex items-center gap-3">
                <a href="index.php?controlador=admin&accion=verRegistrosPaneles" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2.5 rounded-full text-xs font-bold transition border border-white/10">
                    <i class="fas fa-list-alt"></i> Ver Registros
                </a>
            </div>
        </div>

        <!-- TARJETAS DE MÉTRICAS PRINCIPALES (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            
            <!-- 1. Total Empresas -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Empresas</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center text-xs">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900 leading-none"><?php echo $total_empresas; ?></p>
                    <div class="flex items-center gap-2 mt-2 text-[10px] font-bold text-gray-500">
                        <span class="text-blue-600"><?php echo $total_compradores; ?> comp.</span> | 
                        <span class="text-teal-600"><?php echo $total_vendedores; ?> vend.</span>
                    </div>
                </div>
            </div>

            <!-- 2. Ruedas de Negocios -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Ruedas Eventos</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900 leading-none"><?php echo $total_ruedas; ?></p>
                    <p class="text-[10px] font-bold text-indigo-600 mt-2">
                        <i class="fas fa-bolt text-[9px]"></i> <?php echo $total_ruedas_activas; ?> Activa(s)
                    </p>
                </div>
            </div>

            <!-- 3. Citas / Reuniones -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Citas Totales</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900 leading-none"><?php echo $total_reuniones; ?></p>
                    <div class="flex items-center gap-1.5 mt-2 text-[10px] font-bold text-emerald-600">
                        <i class="fas fa-check-circle text-[9px]"></i> <?php echo $citas_realizadas; ?> Realizadas
                    </div>
                </div>
            </div>

            <!-- 4. Volumen Negocio Proyectado -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Acuerdos COP</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xl font-black text-emerald-600 leading-none truncate" title="$<?php echo number_format($volumen_negocio_total, 0, ',', '.'); ?>">
                        $<?php echo number_format($volumen_negocio_total, 0, ',', '.'); ?>
                    </p>
                    <p class="text-[10px] font-bold text-gray-400 mt-2">Negocio estimado</p>
                </div>
            </div>

            <!-- 5. Satisfacción Promedio -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Satisfacción</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xs">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-1">
                        <p class="text-2xl font-black text-slate-900 leading-none"><?php echo number_format($satisfaccion_promedio, 1); ?></p>
                        <span class="text-xs font-black text-gray-400">/ 5.0</span>
                    </div>
                    <p class="text-[10px] font-bold text-amber-600 mt-2">
                        <?php echo $total_encuestas; ?> encuesta(s)
                    </p>
                </div>
            </div>

            <!-- 6. Recaudado Membresías -->
            <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Membresías</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div>
                    <p class="text-xl font-black text-purple-700 leading-none truncate">
                        $<?php echo number_format($recaudado_membresias, 0, ',', '.'); ?>
                    </p>
                    <p class="text-[10px] font-bold text-gray-400 mt-2">Recaudación</p>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DE GRÁFICAS INTERACTIVAS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Gráfica 1: Distribución y Estado de las Citas -->
            <div class="bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl p-6 sm:p-7 border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <i class="fas fa-chart-pie text-blue-600"></i> Estado de las Citas
                        </h3>
                        <p class="text-xs text-gray-400 font-bold mt-0.5">Distribución de reuniones comerciales</p>
                    </div>
                    <span class="text-xs font-black bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100">
                        <?php echo $total_reuniones; ?> Total
                    </span>
                </div>
                
                <div class="relative flex-1 min-h-[220px] flex items-center justify-center">
                    <?php if ($total_reuniones == 0): ?>
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-calendar-check text-4xl mb-2 text-gray-200"></i>
                            <p class="text-xs font-bold">Aún no hay reuniones agendadas</p>
                        </div>
                    <?php else: ?>
                        <canvas id="graficaEstadoCitas"></canvas>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-4 mt-2 border-t border-gray-50 text-xs font-bold">
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Realizadas: <strong><?php echo $citas_realizadas; ?></strong>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span> Agendadas: <strong><?php echo $citas_agendadas; ?></strong>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span> Pendientes: <strong><?php echo $citas_pendientes; ?></strong>
                    </div>
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-rose-400"></span> Canceladas: <strong><?php echo $citas_canceladas; ?></strong>
                    </div>
                </div>
            </div>

            <!-- Gráfica 2: Ventas e Ingresos de Membresías -->
            <div class="lg:col-span-2 bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl p-6 sm:p-7 border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <i class="fas fa-chart-line text-slate-800"></i> Recaudación de Membresías
                        </h3>
                        <p class="text-xs text-gray-400 font-bold mt-0.5">Ingresos generados por planes de proveedores</p>
                    </div>
                    <span class="text-xs font-black bg-purple-50 text-purple-700 px-3 py-1 rounded-full border border-purple-100">
                        $<?php echo number_format($recaudado_membresias, 0, ',', '.'); ?> COP
                    </span>
                </div>
                
                <div class="relative flex-1 min-h-[220px] w-full">
                    <canvas id="graficaMembresias"></canvas>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: REPORTES DE IMPACTO POR RUEDA DE NEGOCIOS -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-7 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center">
                        <i class="fas fa-file-invoice text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Reportes de Impacto por Evento</h3>
                        <p class="text-xs text-gray-400 font-bold">Selecciona una rueda para descargar su reporte PDF oficial o consultar su trazabilidad detallada</p>
                    </div>
                </div>
                <span class="text-xs font-black text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm w-fit">
                    <?php echo count($ruedas_list ?? []); ?> Evento(s)
                </span>
            </div>

            <div class="p-6 sm:p-7">
                <?php if (empty($ruedas_list)): ?>
                    <div class="text-center py-12 bg-slate-50 rounded-2xl">
                        <i class="fas fa-calendar-times text-slate-300 text-4xl mb-3"></i>
                        <p class="text-sm font-bold text-gray-500">No hay ruedas de negocios creadas todavía.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <?php foreach ($ruedas_list as $rueda): 
                            $nombre = $rueda['nombreRueda'] ?? $rueda['tituloRueda'] ?? 'Rueda';
                            $fechaInicio = date('d/m/Y', strtotime($rueda['fechaInicio']));
                            $fechaFin = date('d/m/Y', strtotime($rueda['fechaFin']));
                            $isVirtual = ($rueda['modalidad'] ?? '') === 'virtual';
                        ?>
                            <div class="p-5 rounded-2xl border border-gray-200 hover:border-slate-800 hover:shadow-lg transition-all duration-300 flex flex-col justify-between bg-white group">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <h4 class="text-base font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                            <?php echo htmlspecialchars($nombre); ?>
                                        </h4>
                                        <span class="shrink-0 text-[9px] font-black uppercase px-2.5 py-0.5 rounded-full <?php echo $isVirtual ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100'; ?>">
                                            <?php echo $isVirtual ? 'Virtual' : 'Presencial'; ?>
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-500 font-bold mb-4 flex items-center gap-1.5">
                                        <i class="far fa-calendar text-slate-400"></i> <?php echo $fechaInicio; ?> - <?php echo $fechaFin; ?>
                                    </p>

                                    <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50 rounded-xl mb-4 text-xs font-bold text-gray-600">
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Empresas:</span>
                                            <span class="text-slate-900 font-black"><?php echo (int)$rueda['total_empresas_inscritas']; ?></span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Citas:</span>
                                            <span class="text-blue-600 font-black"><?php echo (int)$rueda['total_citas_rueda']; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <a href="index.php?controlador=admin&accion=verEstadisticas&id=<?php echo $rueda['id']; ?>" 
                                   class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-black text-white text-xs font-extrabold py-2.5 px-4 rounded-xl transition shadow-sm">
                                    <i class="fas fa-chart-bar text-xs text-slate-300"></i> Ver Reporte / Descargar PDF
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Gráfica Circular de Citas
    const canvasCitas = document.getElementById('graficaEstadoCitas');
    if (canvasCitas) {
        const ctxCitas = canvasCitas.getContext('2d');
        const realizadas = <?php echo (int)$citas_realizadas; ?>;
        const agendadas = <?php echo (int)$citas_agendadas; ?>;
        const pendientes = <?php echo (int)$citas_pendientes; ?>;
        const canceladas = <?php echo (int)$citas_canceladas; ?>;

        new Chart(ctxCitas, {
            type: 'doughnut',
            data: {
                labels: ['Realizadas', 'Agendadas', 'Pendientes', 'Canceladas'],
                datasets: [{
                    data: [realizadas, agendadas, pendientes, canceladas],
                    backgroundColor: ['#10b981', '#3b82f6', '#fbbf24', '#f43f5e'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 14
                    }
                },
                cutout: '70%'
            }
        });
    }

    // 2. Gráfica de Membresías
    const canvasMembresias = document.getElementById('graficaMembresias');
    if (canvasMembresias) {
        const ctxMembresias = canvasMembresias.getContext('2d');
        const labels = <?php echo json_encode($labels_grafica); ?>;
        const datos = <?php echo json_encode($valores_grafica); ?>;
        
        const displayLabels = labels.length > 0 ? labels : ['Sin recaudación'];
        const displayDatos = datos.length > 0 ? datos : [0];
        
        new Chart(ctxMembresias, {
            type: 'line',
            data: {
                labels: displayLabels,
                datasets: [{
                    label: 'Ingresos ($ COP)',
                    data: displayDatos,
                    backgroundColor: 'rgba(15, 23, 42, 0.06)',
                    borderColor: '#0f172a',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0f172a',
                    pointBorderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return ' Recaudado: $' + value.toLocaleString('es-CO', { minimumFractionDigits: 0 });
                            }
                        },
                        backgroundColor: '#0f172a',
                        titleFont: { size: 11, weight: 'bold' },
                        bodyFont: { size: 12, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 14
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 50000,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10, weight: 'bold' },
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-CO');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
