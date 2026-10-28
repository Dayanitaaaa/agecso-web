<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layout/header.php';
?>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Solicitar Pauta Publicitaria</h1>
                <p class="text-gray-500 text-sm mt-1">Promociona tu empresa en la página principal de AGECSO</p>
            </div>
            <a href="index.php?controlador=pauta&accion=misPautas" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-xs font-bold transition">
                Ver Mis Pautas
            </a>
        </div>

        <!-- Notificación de error -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
            <div class="bg-red-50 border-2 border-red-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-red-100 text-red-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-red-900">Error</h3>
                    <p class="text-xs text-red-700 mt-1"><?php echo htmlspecialchars($_GET['error_text'] ?? 'Ocurrió un error al procesar la solicitud.'); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="bg-white rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100 p-8">
            <form action="index.php?controlador=pauta&accion=guardar" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Información básica -->
                <div class="space-y-4">
                    <h3 class="text-lg font-black text-gray-900">Información de la Pauta</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Título de la Pauta *</label>
                        <input type="text" name="titulo" required maxlength="255"
                               class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800"
                               placeholder="Ej: Soluciones Tecnológicas para tu Negocio">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Descripción *</label>
                        <textarea name="descripcion" required rows="4"
                                  class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800"
                                  placeholder="Describe brevemente tu empresa o producto..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Enlace al Sitio Web (opcional)</label>
                        <input type="url" name="link_destino"
                               class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800"
                               placeholder="https://tu-sitio-web.com">
                    </div>
                </div>

                <!-- Imagen -->
                <div class="space-y-4">
                    <h3 class="text-lg font-black text-gray-900">Imagen de la Pauta</h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Imagen *</label>
                        <input type="file" name="imagen" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required
                               class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                        <p class="text-[10px] text-gray-400 mt-1">Formatos: JPG, PNG, GIF, WEBP. Máximo 5MB. Recomendado: 1200x400px</p>
                    </div>
                </div>

                <!-- Fechas y posición -->
                <div class="space-y-4">
                    <h3 class="text-lg font-black text-gray-900">Fechas y Posición</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" required
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Fecha de Fin *</label>
                            <input type="date" name="fecha_fin" required
                                   class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Posición de Display</label>
                        <select name="posicion"
                                class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-slate-100 focus:border-slate-800">
                            <option value="1">Posición 1 (Principal)</option>
                            <option value="2">Posición 2</option>
                            <option value="3">Posición 3</option>
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Las posiciones se muestran en orden. Si está ocupada, el administrador te contactará.</p>
                    </div>
                </div>

                <!-- Nota de pago -->
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-amber-100 text-amber-600 rounded-xl shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-amber-900">Nota sobre el Pago</h4>
                            <p class="text-xs text-amber-700 mt-1">Actualmente el pago se procesa manualmente. Una vez enviada la solicitud, el administrador revisará tu pauta y te contactará para procesar el pago. Al aprobarla, tu pauta se activará en las fechas seleccionadas.</p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] text-white px-8 py-3 rounded-full font-black text-sm uppercase tracking-widest shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        Enviar Solicitud
                    </button>
                    <a href="index.php?controlador=pauta&accion=misPautas" class="text-gray-500 text-sm font-medium hover:text-gray-700">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
