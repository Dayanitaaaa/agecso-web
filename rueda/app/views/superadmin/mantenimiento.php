<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-wrench text-blue-500"></i>
                    Mantenimiento del Sistema
                </h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Alertas de resultado -->
        <?php if (isset($resultado)): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $resultado['success'] ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'; ?> border">
            <div class="flex items-center">
                <i class="fas <?php echo $resultado['success'] ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                <span><?php echo $resultado['message'] ?? ($resultado['success'] ? 'Operación completada' : 'Error en la operación'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Limpieza de Logs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <i class="fas fa-broom text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Limpiar Logs Antiguos</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Elimina archivos de log con más de 30 días de antigüedad.
                            Esto libera espacio en disco.
                        </p>
                        <form method="POST" class="mt-4" onsubmit="return confirm('¿Eliminar logs antiguos?');">
                            <input type="hidden" name="accion" value="limpiar_logs">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                <i class="fas fa-trash-alt mr-1"></i> Limpiar Logs
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Optimización de Tablas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <i class="fas fa-database text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Optimizar Tablas</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Ejecuta OPTIMIZE TABLE en las tablas principales para 
                            mejorar el rendimiento de consultas.
                        </p>
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="accion" value="optimizar_tablas">
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                <i class="fas fa-compress-arrows-alt mr-1"></i> Optimizar Tablas
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Verificar Integridad -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                        <i class="fas fa-stethoscope text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Verificar Integridad</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Ejecuta CHECK TABLE en todas las tablas para detectar
                            posibles corrupciones o errores.
                        </p>
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="accion" value="verificar_integridad">
                            <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm transition">
                                <i class="fas fa-search mr-1"></i> Verificar Integridad
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info del Sistema -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Información del Sistema</h3>
                        <div class="mt-3 space-y-2 text-sm">
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500">Espacio en Disco</span>
                                <span class="font-mono"><?php echo round(disk_free_space(__DIR__) / 1024 / 1024 / 1024, 2); ?> GB libre</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500">PHP Version</span>
                                <span class="font-mono"><?php echo PHP_VERSION; ?></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-100">
                                <span class="text-gray-500">Memory Usage</span>
                                <span class="font-mono"><?php echo round(memory_get_usage(true) / 1024 / 1024, 2); ?> MB</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500">Peak Memory</span>
                                <span class="font-mono"><?php echo round(memory_get_peak_usage(true) / 1024 / 1024, 2); ?> MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Información adicional -->
        <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                <i class="fas fa-lightbulb"></i>
                Consejos de Mantenimiento
            </h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Ejecuta la optimización de tablas mensualmente o cuando observes lentitud en las consultas.</li>
                <li>• Limpia los logs antiguos trimestralmente para liberar espacio.</li>
                <li>• La verificación de integridad se recomienda después de interrupciones inesperadas del servidor.</li>
                <li>• Siempre haz un backup antes de realizar operaciones de mantenimiento.</li>
            </ul>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
