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
            <form action="index.php?controlador=pauta&accion=guardar" method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Información básica -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00a2ff] to-[#4dbfff] flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">Información de la Pauta</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Título de la Pauta *</label>
                            <input type="text" name="titulo" required maxlength="255"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300"
                                   placeholder="Ej: Soluciones Tecnológicas para tu Negocio">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Descripción *</label>
                            <textarea name="descripcion" required rows="5"
                                      class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none"
                                      placeholder="Describe brevemente tu empresa o producto..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Enlace al Sitio Web (opcional)</label>
                            <input type="url" name="link_destino"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300"
                                   placeholder="https://tu-sitio-web.com">
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00a2ff] to-[#4dbfff] flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">Imagen de la Pauta</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Imagen *</label>
                            <div class="relative">
                                <input type="file" name="imagen" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required
                                       class="w-full border-2 border-dashed border-gray-300 rounded-2xl px-5 py-8 text-sm font-medium text-gray-600 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Formatos: JPG, PNG, GIF, WEBP. Máximo 5MB. Recomendado: 1200x400px</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00a2ff] to-[#4dbfff] flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">Fechas de Publicación</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" required
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fecha de Fin *</label>
                            <input type="date" name="fecha_fin" required
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300">
                        </div>
                    </div>
                </div>

                <!-- Nota de pago -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 rounded-xl shrink-0 shadow-md">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-amber-900">Nota sobre el Pago</h4>
                            <p class="text-xs text-amber-700 mt-1 leading-relaxed">Actualmente el pago se procesa manualmente. Una vez enviada la solicitud, el administrador revisará tu pauta y te contactará para procesar el pago. Al aprobarla, tu pauta se activará en las fechas seleccionadas.</p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#00a2ff] via-[#4dbfff] to-[#008ae0] text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar Solicitud
                    </button>
                    <a href="index.php?controlador=pauta&accion=misPautas" class="text-gray-500 text-sm font-medium hover:text-gray-700 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Personalizar calendario según el panel
document.addEventListener('DOMContentLoaded', function() {
    const role = "<?php echo strtolower($_SESSION['slugRole'] ?? ''); ?>";
    
    let primaryColor, secondaryColor, accentColor;
    
    switch(role) {
        case 'admin':
            primaryColor = '#1e293b'; // slate-900
            secondaryColor = '#334155'; // slate-700
            accentColor = '#3b82f6'; // blue-500
            break;
        case 'vendedor':
        case 'proveedor':
            primaryColor = '#0d9488'; // teal-700
            secondaryColor = '#14b8a6'; // teal-500
            accentColor = '#0f766e'; // teal-800
            break;
        case 'comprador':
            primaryColor = '#00a2ff'; // blue-500
            secondaryColor = '#4dbfff'; // blue-400
            accentColor = '#008ae0'; // blue-600
            break;
        default:
            primaryColor = '#00a2ff';
            secondaryColor = '#4dbfff';
            accentColor = '#008ae0';
    }
    
    // Agregar estilos CSS dinámicos para el calendario nativo
    const style = document.createElement('style');
    style.textContent = `
        /* Estilos para el calendario nativo */
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            padding: 0.5rem;
            background: linear-gradient(135deg, ${primaryColor}, ${secondaryColor});
            border-radius: 8px;
            color: white;
            margin-right: 0.5rem;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
        
        /* Firefox */
        input[type="date"]::-moz-calendar-picker-indicator {
            cursor: pointer;
            background: linear-gradient(135deg, ${primaryColor}, ${secondaryColor});
            border-radius: 8px;
            color: white;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
