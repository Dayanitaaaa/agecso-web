<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#fede32] via-[#ffe082] to-[#ffd54f] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(254,222,50,0.12)] text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-black/10 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-white/10">Métricas</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Estadísticas Generales del Sistema</h1>
                <p class="text-white mt-2 flex items-center text-sm sm:text-base font-bold">
                    <i class="fas fa-chart-line mr-2"></i> Visión general del rendimiento de la plataforma
                </p>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 flex items-center border-l-4 border-[#fede32]">
                <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl mr-4">
                    <i class="fas fa-building text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Total Empresas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $total_empresas; ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 flex items-center border-l-4 border-amber-400">
                <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl mr-4">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Reuniones Programadas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $total_reuniones; ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-gray-100 flex items-center border-l-4 border-emerald-500">
                <div class="p-4 bg-emerald-50 text-emerald-500 rounded-2xl mr-4">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Recaudado Membresías</p>
                    <p class="text-3xl font-black text-gray-800 mt-1">$<?php echo number_format($recaudado_membresias, 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <!-- Sección de Gráfica de Membresías y Historial de Pagos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Gráfica de Ventas (Chart.js) -->
            <div class="lg:col-span-2 bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl p-6 border border-gray-100">
                <h3 class="text-lg font-extrabold text-gray-800 tracking-tight flex items-center gap-2 mb-4">
                    <i class="fas fa-chart-bar text-amber-500"></i> Ventas Mensuales de Membresías
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="graficaMembresias"></canvas>
                </div>
            </div>

            <!-- Historial de Pagos Recientes -->
            <div class="bg-white shadow-[0_4px_25px_rgba(0,0,0,0.01)] rounded-3xl p-6 border border-gray-100">
                <h3 class="text-base font-extrabold text-gray-800 tracking-tight flex items-center gap-2 mb-4 border-b border-gray-50 pb-3">
                    <i class="fas fa-history text-emerald-500"></i> Últimos Pagos
                </h3>
                <div class="space-y-4 max-h-64 overflow-y-auto pr-1">
                    <?php if (empty($pagos_recientes)): ?>
                        <div class="text-center py-8 text-gray-400 italic text-xs">No se han registrado pagos aún.</div>
                    <?php else: ?>
                        <?php foreach ($pagos_recientes as $pago): ?>
                            <div class="flex justify-between items-center p-3 rounded-2xl bg-gray-50 border border-gray-100">
                                <div class="min-w-0 flex-1 mr-2">
                                    <p class="text-xs font-bold text-gray-900 truncate"><?php echo htmlspecialchars($pago['razon_social']); ?></p>
                                    <span class="inline-flex items-center text-[9px] font-black uppercase tracking-wider text-emerald-600 mt-1">
                                        <i class="fas fa-check-circle mr-1"></i> Plan <?php echo htmlspecialchars($pago['plan']); ?>
                                    </span>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs font-black text-gray-800">$<?php echo number_format($pago['monto'], 0, ',', '.'); ?></p>
                                    <span class="text-[9px] text-gray-400 font-bold"><?php echo date('d/M H:i', strtotime($pago['fecha_pago'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('graficaMembresias').getContext('2d');
    
    const labels = <?php echo json_encode($labels_grafica); ?>;
    const datos = <?php echo json_encode($valores_grafica); ?>;
    
    // Si no hay datos, mostrar valores de ejemplo vacíos o un placeholder elegante
    const displayLabels = labels.length > 0 ? labels : ['Sin pagos'];
    const displayDatos = datos.length > 0 ? datos : [0];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: displayLabels,
            datasets: [{
                label: 'Ingresos ($ COP)',
                data: displayDatos,
                backgroundColor: 'rgba(251, 191, 36, 0.1)',
                borderColor: '#f59e0b',
                borderWidth: 3.5,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#f59e0b',
                pointBorderWidth: 2.5,
                pointRadius: 6,
                pointHoverRadius: 8,
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
                    cornerRadius: 16
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
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
