<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-terminal mr-3 text-indigo-600"></i> Consola de Trazabilidad (SuperAdmin)
            </h1>
            <a href="index.php?controlador=admin&accion=dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Selector de Archivos -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 font-bold text-gray-700 text-sm">
                        Archivos de Log
                    </div>
                    <nav class="divide-y divide-gray-100">
                        <?php foreach ($archivos as $archivo): ?>
                            <a href="index.php?controlador=superadmin&accion=verLogs&file=<?php echo urlencode($archivo); ?>" 
                               class="block px-4 py-3 text-sm <?php echo $archivo_seleccionado == $archivo ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50'; ?>">
                                <div class="flex items-center justify-between">
                                    <span><i class="fas fa-file-alt mr-2 <?php echo strpos($archivo, 'error') !== false ? 'text-red-400' : 'text-gray-400'; ?>"></i> <?php echo $archivo; ?></span>
                                    <span class="text-[10px] text-gray-400"><?php echo number_format(filesize("../logs/$archivo") / 1024, 1); ?> KB</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h4 class="text-xs font-bold text-blue-800 uppercase mb-2">Ayuda Técnica</h4>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Como SuperAdmin, tienes acceso a la trazabilidad completa del sistema. Aquí puedes monitorear intentos de login, errores de base de datos y operaciones de negocio.
                    </p>
                </div>
            </div>

            <!-- Visor de Contenido -->
            <div class="lg:col-span-3">
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-4 py-3 bg-gray-800 text-white flex justify-between items-center">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-code mr-2 text-green-400"></i>
                            Contenido: <span class="font-mono ml-2 text-gray-300"><?php echo $archivo_seleccionado; ?></span>
                        </div>
                        <div class="space-x-2">
                            <button onclick="location.reload()" class="text-xs bg-gray-700 hover:bg-gray-600 px-2 py-1 rounded">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                    </div>
                    <div class="p-0 bg-gray-900 overflow-x-auto">
                        <pre class="p-4 text-xs font-mono text-green-400 leading-relaxed" style="max-height: 600px; min-height: 400px;"><?php 
                            echo htmlspecialchars($contenido ?: 'El archivo está vacío o no se pudo leer.'); 
                        ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/header.php'; ?>
