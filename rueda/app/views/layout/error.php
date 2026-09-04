<?php require_once __DIR__ . '/header.php'; ?>

<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden relative">
        <!-- Decoración de fondo -->
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-50 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-sky-50 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 p-8 sm:p-10 text-center">
            <!-- Icono Animado -->
            <div class="inline-flex items-center justify-center w-20 h-20 bg-rose-50 text-rose-500 rounded-[2rem] mb-8 animate-pulse">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            
            <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">¡Ups! Algo salió mal</h1>
            
            <div class="bg-gray-50 rounded-3xl p-5 mb-8 border border-gray-100">
                <p class="text-gray-600 text-sm font-bold leading-relaxed">
                    <?php echo isset($error_msg) ? htmlspecialchars($error_msg) : 'Ocurrió un error inesperado en el sistema.'; ?>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="javascript:history.back()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-black text-xs uppercase tracking-widest py-4 px-6 rounded-full transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Volver atrás
                </a>
                <a href="index.php" class="flex-1 bg-[#00a2ff] hover:bg-[#008ae0] text-white font-black text-xs uppercase tracking-widest py-4 px-6 rounded-full shadow-lg shadow-sky-500/20 hover:shadow-sky-500/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                    Ir al Inicio <i class="fas fa-home"></i>
                </a>
            </div>

            <?php if (ini_get('display_errors') && error_get_last()): ?>
                <div class="mt-8 pt-6 border-t border-gray-100 text-left">
                    <button onclick="document.getElementById('debug_info').classList.toggle('hidden')" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                        <i class="fas fa-code mr-1"></i> Detalles para Soporte
                    </button>
                    <div id="debug_info" class="hidden mt-4 p-4 bg-gray-900 rounded-2xl overflow-hidden">
                        <pre class="text-[10px] text-rose-300 overflow-x-auto font-mono"><?php print_r(error_get_last()); ?></pre>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
