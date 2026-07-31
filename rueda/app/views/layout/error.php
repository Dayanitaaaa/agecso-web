<?php require_once __DIR__ . '/header.php'; ?>

<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg border-t-4 border-red-600">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">¡Ups! Algo salió mal</h1>
        <p class="text-gray-600 mb-6"><?php echo isset($error_msg) ? htmlspecialchars($error_msg) : 'Ocurrió un error inesperado en el sistema.'; ?></p>
        
        <div class="space-x-4">
            <a href="javascript:history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-md transition duration-200">
                Volver atrás
            </a>
            <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md transition duration-200">
                Ir al Inicio
            </a>
        </div>
    </div>
    
    <?php if (ini_get('display_errors')): ?>
    <div class="mt-8 p-4 bg-gray-50 rounded border border-gray-200">
        <p class="text-xs text-gray-500 font-mono">Detalles técnicos para soporte:</p>
        <pre class="text-xs text-red-500 mt-2 overflow-x-auto"><?php print_r(error_get_last()); ?></pre>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
