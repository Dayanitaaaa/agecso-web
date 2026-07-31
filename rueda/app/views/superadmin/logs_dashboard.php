<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Consola de Trazabilidad del Sistema (SuperAdmin)</h1>

        <!-- Tarjetas de Métricas Técnicas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800 p-5 rounded-xl shadow-lg border-b-4 border-indigo-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-indigo-500 text-white mr-4">
                        <i class="fas fa-users-cog text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Usuarios Activos</p>
                        <p class="text-2xl font-bold text-white"><?php echo $stats['usuarios_activos']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 p-5 rounded-xl shadow-lg border-b-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-500 text-white mr-4">
                        <i class="fas fa-sign-in-alt text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Sesiones Hoy</p>
                        <p class="text-2xl font-bold text-white"><?php echo $stats['sesiones_hoy']; ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 p-5 rounded-xl shadow-lg border-b-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-red-500 text-white mr-4">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Alertas Detectadas</p>
                        <p class="text-2xl font-bold text-white"><?php echo $stats['errores_24h']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Panel: Eventos de Autenticación -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-blue-600 text-white flex justify-between items-center">
                    <h3 class="font-bold">Eventos de Acceso (Auth)</h3>
                    <span class="text-xs bg-blue-500 px-2 py-1 rounded">auth_events.log</span>
                </div>
                <div class="p-4 bg-gray-900 text-green-400 font-mono text-xs h-64 overflow-y-auto">
                    <?php foreach ($logs_content['auth_events'] as $line): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panel: Errores Críticos del Sistema -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-red-600 text-white flex justify-between items-center">
                    <h3 class="font-bold">Errores Críticos (SQL/System)</h3>
                    <span class="text-xs bg-red-500 px-2 py-1 rounded">system_errors.log</span>
                </div>
                <div class="p-4 bg-gray-900 text-red-400 font-mono text-xs h-64 overflow-y-auto">
                    <?php foreach ($logs_content['system_errors'] as $line): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panel: Actividad de Negocio -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-green-600 text-white flex justify-between items-center">
                    <h3 class="font-bold">Operaciones de Negocio</h3>
                    <span class="text-xs bg-green-500 px-2 py-1 rounded">business_ops.log</span>
                </div>
                <div class="p-4 bg-gray-900 text-green-300 font-mono text-xs h-64 overflow-y-auto">
                    <?php foreach ($logs_content['business_ops'] as $line): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panel: Depuración de Login -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-gray-700 text-white flex justify-between items-center">
                    <h3 class="font-bold">Depuración de Hashes</h3>
                    <span class="text-xs bg-gray-600 px-2 py-1 rounded">debug_login.txt</span>
                </div>
                <div class="p-4 bg-gray-900 text-yellow-500 font-mono text-[10px] h-64 overflow-y-auto">
                    <?php foreach ($logs_content['debug_login'] as $line): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panel: Errores de Intrusión (Guest) -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-orange-600 text-white flex justify-between items-center">
                    <h3 class="font-bold">Intentos Fallidos / Intrusión</h3>
                    <span class="text-xs bg-orange-500 px-2 py-1 rounded">guest_errors.log</span>
                </div>
                <div class="p-4 bg-gray-900 text-orange-400 font-mono text-[10px] h-64 overflow-y-auto">
                    <?php foreach ($logs_content['guest_errors'] as $line): ?>
                        <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Panel: Registro de Administradores -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-indigo-600 text-white flex justify-between items-center">
                    <h3 class="font-bold">Actividad Administrativa</h3>
                    <span class="text-xs bg-indigo-500 px-2 py-1 rounded">admin_errors.log</span>
                </div>
                <div class="p-4 bg-gray-900 text-indigo-300 font-mono text-[10px] h-64 overflow-y-auto">
                    <?php if (isset($logs_content['admin_errors'])): ?>
                        <?php foreach ($logs_content['admin_errors'] as $line): ?>
                            <p class="mb-1"><?php echo htmlspecialchars($line); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
