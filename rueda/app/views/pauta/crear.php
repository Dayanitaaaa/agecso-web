<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../layout/header.php';
?>

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Definir variables CSS según el rol -->
    <style>
        :root {
            --form-primary: <?php 
                $role = strtolower($_SESSION['slugRole'] ?? '');
                switch($role) {
                    case 'admin': echo '#1e293b'; break;
                    case 'vendedor':
                    case 'proveedor': echo '#0d9488'; break;
                    case 'comprador': echo '#00a2ff'; break;
                    default: echo '#00a2ff';
                }
            ?>;
            --form-secondary: <?php 
                switch($role) {
                    case 'admin': echo '#334155'; break;
                    case 'vendedor':
                    case 'proveedor': echo '#14b8a6'; break;
                    case 'comprador': echo '#4dbfff'; break;
                    default: echo '#4dbfff';
                }
            ?>;
            --form-accent: <?php 
                switch($role) {
                    case 'admin': echo '#3b82f6'; break;
                    case 'vendedor':
                    case 'proveedor': echo '#0f766e'; break;
                    case 'comprador': echo '#008ae0'; break;
                    default: echo '#008ae0';
                }
            ?>;
        }
        
        .form-input-focus:focus {
            border-color: var(--form-primary) !important;
            ring-color: var(--form-secondary) !important;
        }
        
        .form-section-icon {
            background: linear-gradient(135deg, var(--form-primary), var(--form-secondary)) !important;
        }
        
        .form-button {
            background: linear-gradient(135deg, var(--form-primary), var(--form-secondary)) !important;
        }
    </style>

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
                        <div class="w-10 h-10 rounded-xl form-section-icon flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">Información de la Pauta</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Título de la Pauta *</label>
                            <input type="text" name="titulo" required maxlength="255"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 form-input-focus"
                                   placeholder="Ej: Soluciones Tecnológicas para tu Negocio">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Descripción *</label>
                            <textarea name="descripcion" required rows="5"
                                      class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 resize-none form-input-focus"
                                      placeholder="Describe brevemente tu empresa o producto..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Enlace al Sitio Web (opcional)</label>
                            <input type="url" name="link_destino"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 form-input-focus"
                                   placeholder="https://tu-sitio-web.com">
                        </div>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-xl form-section-icon flex items-center justify-center text-white shadow-lg">
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
                        <div class="w-10 h-10 rounded-xl form-section-icon flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900">Fechas de Publicación</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fecha de Inicio *</label>
                            <input type="text" name="fecha_inicio" id="fecha_inicio" required placeholder="Selecciona fecha"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 cursor-pointer form-input-focus">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fecha de Fin *</label>
                            <input type="text" name="fecha_fin" id="fecha_fin" required placeholder="Selecciona fecha"
                                   class="w-full border-2 border-gray-200 rounded-2xl px-5 py-4 text-sm font-medium text-gray-800 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 cursor-pointer form-input-focus">
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
                    <button type="submit" class="flex-1 form-button text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
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
// Personalizar calendario con Flatpickr según el panel
document.addEventListener('DOMContentLoaded', function() {
    // Usar las variables CSS ya definidas
    const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--form-primary').trim() || '#0d9488';
    const secondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--form-secondary').trim() || '#14b8a6';
    const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--form-accent').trim() || '#0f766e';
    
    // Inicializar Flatpickr
    if (typeof flatpickr !== 'undefined') {
        const config = {
            dateFormat: 'd/m/Y',
            locale: 'es',
            minDate: 'today',
            disableMobile: true,
            onOpen: function(selectedDates, dateStr, instance) {
                const calendar = instance.calendarContainer;
                calendar.style.setProperty('--flatpickr-primary', primaryColor);
                calendar.style.setProperty('--flatpickr-secondary', secondaryColor);
                calendar.style.setProperty('--flatpickr-accent', accentColor);
            }
        };
        
        flatpickr('#fecha_inicio', config);
        flatpickr('#fecha_fin', config);
    }
    
    // Agregar estilos CSS para Flatpickr con ALTA LEGIBILIDAD y contraste
    const style = document.createElement('style');
    style.textContent = `
        .flatpickr-calendar {
            z-index: 99999 !important;
            background: #ffffff !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            padding: 14px !important;
            width: 320px !important;
            opacity: 1 !important;
        }

        .flatpickr-months {
            background: linear-gradient(135deg, var(--flatpickr-accent, #0f766e) 0%, var(--flatpickr-primary, #0d9488) 50%, var(--flatpickr-secondary, #14b8a6) 100%) !important;
            border-radius: 1rem !important;
            padding: 8px 6px !important;
            margin-bottom: 12px !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12) !important;
            align-items: center !important;
        }

        .flatpickr-months .flatpickr-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            height: 38px !important;
        }

        .flatpickr-current-month,
        .flatpickr-current-month .cur-month,
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            color: #ffffff !important;
            fill: #ffffff !important;
            font-weight: 800 !important;
            font-size: 15px !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: transparent !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            color: #0f172a !important;
            background: #ffffff !important;
            font-weight: 600 !important;
        }

        .flatpickr-current-month input.cur-year {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: #ffffff !important;
            fill: #ffffff !important;
            border-radius: 9999px !important;
            padding: 6px 10px !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            fill: #ffffff !important;
            stroke: #ffffff !important;
            width: 14px !important;
            height: 14px !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background: rgba(255, 255, 255, 0.25) !important;
        }

        span.flatpickr-weekday,
        .flatpickr-weekday {
            color: #334155 !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .flatpickr-day {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            border-radius: 0.75rem !important;
            border: 1px solid transparent !important;
            height: 38px !important;
            line-height: 36px !important;
            margin: 2px 0 !important;
            transition: all 0.15s ease !important;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #94a3b8 !important;
            opacity: 0.45 !important;
        }

        .flatpickr-day:hover:not(.selected):not(.startRange):not(.endRange) {
            background: #f0fdfa !important;
            color: var(--flatpickr-primary, #0d9488) !important;
            border-color: rgba(13, 148, 136, 0.2) !important;
        }

        .flatpickr-day.today {
            border: 2px solid var(--flatpickr-primary, #0d9488) !important;
            color: var(--flatpickr-primary, #0d9488) !important;
            background: transparent !important;
            font-weight: 900 !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: linear-gradient(135deg, var(--flatpickr-primary, #0d9488), var(--flatpickr-secondary, #14b8a6)) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.35) !important;
        }

        .flatpickr-day.inRange {
            background: #ccfbf1 !important;
            color: #0f766e !important;
            border-color: transparent !important;
            border-radius: 0 !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
