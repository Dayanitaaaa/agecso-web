<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#fede32] via-[#ffe082] to-[#ffd54f] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(254,222,50,0.12)] text-white relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-black/10 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full border border-white/10">Configuración</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Crear Nueva Rueda de Negocios</h1>
                <p class="text-white mt-2 flex items-center text-sm sm:text-base font-bold">
                    <i class="fas fa-calendar-plus mr-2"></i> Configura un nuevo evento de rueda de negocios
                </p>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white p-8 rounded-[2rem] shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-gray-100">
            <form action="index.php?controlador=admin&accion=crearRueda" method="POST">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Título de la Rueda <span class="text-red-500">*</span></label>
                        <input type="text" name="titulo" required 
                               class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200"
                               placeholder="Ej: Rueda Regional de Agro 2026">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Descripción del Evento <span class="text-red-500">*</span></label>
                        <textarea name="descripcion" rows="4" required 
                                  class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200 resize-none" 
                                  placeholder="Detalles sobre el alcance, sectores invitados y objetivos..."></textarea>
                    </div>

                    <!-- Fechas de Inscripción -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-amber-600">Inscripciones Inicio <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha_inscripcion_inicio" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-amber-600">Inscripciones Fin <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha_inscripcion_fin" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                        </div>
                    </div>

                    <!-- Fechas del Evento -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Inicio de la Rueda <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha_inicio" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-sky-400 transition duration-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Fin de la Rueda <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha_fin" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-sky-400 transition duration-200">
                        </div>
                    </div>

                    <!-- Franja Horaria de Reuniones -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-emerald-600">
                                <i class="fas fa-clock mr-1"></i> Hora Inicio de Citas <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="hora_inicio" value="08:00" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-50 focus:border-emerald-400 transition duration-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-emerald-600">
                                <i class="fas fa-clock mr-1"></i> Hora Fin de Citas <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="hora_fin" value="18:00" required 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-50 focus:border-emerald-400 transition duration-200">
                        </div>
                    </div>

                    <!-- Duración de Citas -->
                    <div class="bg-amber-50/60 border border-amber-200/60 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-black text-sm">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-800">Duración por Cita de Negocio</p>
                                <p class="text-xs text-gray-500">Bloques estándar de 30 minutos por reunión</p>
                            </div>
                        </div>
                        <span class="bg-amber-500 text-white text-xs font-black px-4 py-1.5 rounded-full shadow-sm">
                            30 Minutos
                        </span>
                        <input type="hidden" name="duracion_cita" value="30">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Estado Inicial</label>
                            <select name="estado" class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                                <option value="planeacion">Planeación</option>
                                <option value="inscripciones">Inscripciones</option>
                                <option value="activa">Activa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Modalidad</label>
                            <select name="modalidad" id="modalidad_select" onchange="toggleUbicacion()" 
                                    class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                                <option value="virtual">Virtual</option>
                                <option value="presencial">Presencial</option>
                            </select>
                        </div>
                    </div>

                    <div id="ubicacion_container" class="hidden space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Lugar / Dirección del Evento <span class="text-red-500">*</span></label>
                            <input type="text" name="ubicacion" id="ubicacion_input" 
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200" 
                                placeholder="Ej: Calle 123 # 45-67, Centro de Convenciones">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider text-sky-600">Cantidad de Mesas Disponibles</label>
                            <input type="number" name="cantidad_mesas" id="cantidad_mesas_input" min="1" value="1"
                                class="block w-full border border-gray-200 rounded-full shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-amber-50 focus:border-amber-400 transition duration-200">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1">
                                <i class="fas fa-info-circle text-sky-400"></i>
                                Define el número total de mesas físicas asignadas para este evento.
                            </p>
                        </div>
                    </div>

                    <div id="virtual_info" class="bg-sky-50/50 border border-sky-100 rounded-2xl p-4">
                        <p class="text-[11px] text-sky-800 font-medium leading-relaxed">
                            <i class="fas fa-video mr-1.5 text-sky-500"></i> <strong>Modalidad Virtual:</strong> Las reuniones se realizarán por video llamada. Los participantes agregarán sus propios links de conexión.
                        </p>
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <a href="index.php?controlador=admin&accion=dashboard" class="inline-flex justify-center rounded-full border border-gray-200 px-6 py-3 bg-white text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center rounded-full border border-transparent px-8 py-3 bg-amber-500 text-sm font-extrabold text-white hover:bg-amber-600 shadow-[0_4px_15px_rgba(245,158,11,0.2)] hover:shadow-[0_6px_20px_rgba(245,158,11,0.35)] hover:-translate-y-0.5 transition duration-200 transform">
                            Crear Rueda
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleUbicacion() {
    const modalidad = document.getElementById('modalidad_select').value;
    const container = document.getElementById('ubicacion_container');
    const input = document.getElementById('ubicacion_input');
    const info = document.getElementById('virtual_info');
    
    if (modalidad === 'presencial') {
        container.classList.remove('hidden');
        if (input) input.required = true;
        info.classList.add('hidden');
    } else {
        container.classList.add('hidden');
        if (input) {
            input.required = false;
            input.value = '';
        }
        info.classList.remove('hidden');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleUbicacion();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
