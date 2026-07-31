<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50">
    <!-- Header del Dashboard Técnico -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center gap-3">
                        <i class="fas fa-server text-blue-400"></i>
                        Panel de Control Técnico
                    </h1>
                    <p class="mt-1 text-gray-400 text-sm">
                        SuperAdmin - Monitoreo de Sistema y Soporte TI
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium border border-green-500/30">
                        <i class="fas fa-circle text-[8px] mr-1"></i> Sistema Online
                    </span>
                    <span class="text-xs text-gray-500">
                        <?php echo date('Y-m-d H:i:s'); ?> UTC-5
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navegación Rápida -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="#usuarios" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Usuarios</p>
                        <p class="text-lg font-bold"><?php echo number_format($statsUsuarios['total_usuarios']); ?></p>
                    </div>
                </div>
            </a>
            <a href="#actividad" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 text-green-600 rounded-lg">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Reuniones</p>
                        <p class="text-lg font-bold"><?php echo number_format($statsActividad['total_reuniones']); ?></p>
                    </div>
                </div>
            </a>
            <a href="#database" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                        <i class="fas fa-database"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Base de Datos</p>
                        <p class="text-lg font-bold"><?php echo round($statsDatabase['total_size_mb'], 1); ?> MB</p>
                    </div>
                </div>
            </a>
            <a href="#seguridad" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Alertas 24h</p>
                        <p class="text-lg font-bold"><?php echo $statsSeguridad['errores_sistema_24h']; ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- COLUMNA IZQUIERDA: Métricas Principales -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- SECCIÓN: ESTADÍSTICAS DE USUARIOS -->
                <section id="usuarios" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-users text-blue-500"></i>
                            Estadísticas de Usuarios
                        </h2>
                        <span class="text-xs text-gray-500">Actualizado: ahora</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-3xl font-bold text-gray-900"><?php echo $statsUsuarios['total_usuarios']; ?></p>
                                <p class="text-xs text-gray-500 mt-1">Total Usuarios</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-3xl font-bold text-green-600"><?php echo $statsUsuarios['usuarios_activos']; ?></p>
                                <p class="text-xs text-gray-500 mt-1">Activos</p>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-3xl font-bold text-blue-600"><?php echo $statsUsuarios['usuarios_este_mes']; ?></p>
                                <p class="text-xs text-gray-500 mt-1">Nuevos (30d)</p>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <p class="text-3xl font-bold text-yellow-600"><?php echo $statsUsuarios['usuarios_hoy']; ?></p>
                                <p class="text-xs text-gray-500 mt-1">Registros Hoy</p>
                            </div>
                        </div>
                        
                        <!-- Distribución por Rol -->
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Distribución por Rol</h4>
                        <div class="space-y-2">
                            <?php foreach ($statsUsuarios['por_rol'] as $rol): ?>
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <span class="text-sm text-gray-600"><?php echo htmlspecialchars($rol['nombreRole']); ?></span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <?php 
                                        $percent = $statsUsuarios['total_usuarios'] > 0 
                                            ? ($rol['total'] / $statsUsuarios['total_usuarios']) * 100 
                                            : 0;
                                        ?>
                                        <div class="h-full bg-blue-500" style="width: <?php echo $percent; ?>%"></div>
                                    </div>
                                    <span class="text-sm font-medium w-8 text-right"><?php echo $rol['total']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- SECCIÓN: ACTIVIDAD DEL SISTEMA -->
                <section id="actividad" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-bar text-green-500"></i>
                            Actividad del Sistema
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Ruedas</span>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded"><?php echo $statsActividad['ruedas_activas']; ?> activas</span>
                                </div>
                                <p class="text-2xl font-bold"><?php echo $statsActividad['total_ruedas']; ?></p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Empresas</span>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded"><?php echo $statsActividad['empresas_aprobadas']; ?> aprobadas</span>
                                </div>
                                <p class="text-2xl font-bold"><?php echo $statsActividad['total_empresas']; ?></p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Reuniones</span>
                                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded"><?php echo $statsActividad['reuniones_esta_semana']; ?> esta sem</span>
                                </div>
                                <p class="text-2xl font-bold"><?php echo $statsActividad['total_reuniones']; ?></p>
                            </div>
                        </div>
                        
                        <!-- Estado de Reuniones -->
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Estado de Reuniones</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php foreach ($statsActividad['estadisticas_reuniones'] as $estado => $cantidad): ?>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <p class="text-lg font-bold"><?php echo $cantidad; ?></p>
                                <p class="text-xs text-gray-500 capitalize"><?php echo str_replace('_', ' ', $estado); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- SECCIÓN: BASE DE DATOS -->
                <section id="database" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-database text-purple-500"></i>
                            Base de Datos
                        </h2>
                        <span class="text-xs text-gray-500"><?php echo $statsDatabase['total_tablas']; ?> tablas</span>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500 border-b">
                                        <th class="pb-2">Tabla</th>
                                        <th class="pb-2 text-right">Registros</th>
                                        <th class="pb-2 text-right">Tamaño</th>
                                        <th class="pb-2 text-right">Índices</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php 
                                    $topTables = array_slice($statsDatabase['tablas'], 0, 8);
                                    foreach ($topTables as $tabla): 
                                    ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 font-medium"><?php echo $tabla['table_name']; ?></td>
                                        <td class="py-2 text-right"><?php echo number_format($tabla['table_rows']); ?></td>
                                        <td class="py-2 text-right"><?php echo $tabla['data_size_mb']; ?> MB</td>
                                        <td class="py-2 text-right"><?php echo $tabla['index_size_mb']; ?> MB</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg flex items-center justify-between text-sm">
                            <span class="text-gray-600">Tamaño total:</span>
                            <span class="font-bold"><?php echo round($statsDatabase['total_size_mb'], 2); ?> MB</span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- COLUMNA DERECHA: Seguridad, Logs, Servidor -->
            <div class="space-y-8">
                
                <!-- HEALTH CHECK -->
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-heartbeat text-red-500"></i>
                            Health Check
                        </h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <?php foreach ($healthCheck as $componente => $status): ?>
                        <div class="flex items-center justify-between p-3 rounded-lg <?php echo $status['status'] === 'ok' ? 'bg-green-50 border border-green-200' : ($status['status'] === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200'); ?>">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-<?php echo $status['status'] === 'ok' ? 'check-circle text-green-500' : ($status['status'] === 'warning' ? 'exclamation-triangle text-yellow-500' : 'times-circle text-red-500'); ?>"></i>
                                <span class="text-sm font-medium capitalize"><?php echo str_replace('_', ' ', $componente); ?></span>
                            </div>
                            <?php if (isset($status['percent_used'])): ?>
                            <span class="text-xs"><?php echo $status['percent_used']; ?>% usado</span>
                            <?php elseif (isset($status['writable'])): ?>
                            <span class="text-xs"><?php echo $status['writable'] ? 'OK' : 'Sin permisos'; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- SEGURIDAD -->
                <section id="seguridad" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-red-500"></i>
                            Seguridad (24h)
                        </h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg border border-red-200">
                            <span class="text-sm text-red-700">Errores Sistema</span>
                            <span class="font-bold text-red-700"><?php echo $statsSeguridad['errores_sistema_24h']; ?></span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                            <span class="text-sm text-orange-700">Logins Fallidos</span>
                            <span class="font-bold text-orange-700"><?php echo $statsSeguridad['intentos_login_fallidos_24h']; ?></span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <span class="text-sm text-yellow-700">Bloqueos Rate Limit</span>
                            <span class="font-bold text-yellow-700"><?php echo $statsSeguridad['usuarios_bloqueados_rate_limit']; ?></span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="text-sm text-gray-700">Accesos No Autorizados</span>
                            <span class="font-bold text-gray-700"><?php echo $statsSeguridad['accesos_no_autorizados']; ?></span>
                        </div>
                    </div>
                </section>

                <!-- INFO DEL SERVIDOR -->
                <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-server text-gray-500"></i>
                            Servidor
                        </h2>
                    </div>
                    <div class="p-4 space-y-2 text-sm">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">PHP</span>
                            <span class="font-mono"><?php echo $serverInfo['php_version']; ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">MySQL</span>
                            <span class="font-mono"><?php echo $serverInfo['db_version']; ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">Memory Limit</span>
                            <span class="font-mono"><?php echo $serverInfo['memory_limit']; ?></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span class="text-gray-500">Max Execution</span>
                            <span class="font-mono"><?php echo $serverInfo['max_execution_time']; ?></span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500">Display Errors</span>
                            <span class="font-mono <?php echo $serverInfo['display_errors'] ? 'text-red-600' : 'text-green-600'; ?>">
                                <?php echo $serverInfo['display_errors'] ? 'ON (DEV)' : 'OFF (PROD)'; ?>
                            </span>
                        </div>
                    </div>
                </section>

                <!-- ACCESO RÁPIDO -->
                <section class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-lg text-white p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i class="fas fa-tools"></i>
                        Herramientas de Soporte
                    </h3>
                    <div class="space-y-2">
                        <a href="?controlador=superadmin&accion=logs" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                            <i class="fas fa-file-alt mr-2"></i> Ver Logs Detallados
                        </a>
                        <a href="?controlador=superadmin&accion=mantenimiento" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                            <i class="fas fa-wrench mr-2"></i> Mantenimiento BD
                        </a>
                        <a href="?controlador=admin&accion=dashboard" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                            <i class="fas fa-briefcase mr-2"></i> Ir a Panel Admin (Negocio)
                        </a>
                    </div>
                </section>
            </div>
        </div>

        <!-- SECCIÓN: LOGS EN TIEMPO REAL -->
        <section class="mt-8 bg-gray-900 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-terminal text-green-400"></i>
                    Logs del Sistema
                </h2>
                <div class="flex gap-2">
                    <button onclick="refreshLogs()" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white text-sm rounded transition">
                        <i class="fas fa-sync-alt mr-1"></i> Actualizar
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-700">
                <?php foreach ($logsSistema as $key => $log): ?>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-300 flex items-center gap-2">
                            <i class="fas <?php echo $log['icon']; ?>"></i>
                            <?php echo $log['label']; ?>
                        </span>
                        <span class="text-xs text-gray-500"><?php echo $log['size']; ?></span>
                    </div>
                    <div class="h-40 overflow-y-auto text-xs font-mono space-y-1">
                        <?php if ($log['exists'] && !empty($log['lines'])): ?>
                            <?php foreach (array_slice($log['lines'], 0, 20) as $line): ?>
                            <div class="text-gray-400 hover:bg-gray-800 px-1 rounded truncate" title="<?php echo htmlspecialchars($line); ?>">
                                <?php echo htmlspecialchars(substr($line, 0, 60)) . (strlen($line) > 60 ? '...' : ''); ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-gray-600 italic">Sin entradas recientes</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>

<script>
function refreshLogs() {
    location.reload();
}

// Auto-refresh cada 60 segundos
setInterval(() => {
    // Solo recargar logs visibles, no toda la página
    console.log('Logs refresh disponible - clic en botón para actualizar');
}, 60000);
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
