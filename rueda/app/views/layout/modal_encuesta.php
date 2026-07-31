<!-- CDN de FontAwesome por si no está en el layout principal -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Modal de Encuesta de Satisfacción Unificado (Soporta Satisfacción y Trazabilidad) -->
<div id="modalEncuesta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="cerrarModalEncuesta()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <form action="index.php?controlador=<?php echo ($_SESSION['slugRole'] ?? '') === 'comprador' ? 'comprador' : 'vendedor'; ?>&accion=registrarEncuesta" method="POST" id="formEncuesta">
                <input type="hidden" name="reunion_id" id="encuesta_reunion_id">
                <input type="hidden" name="tipo_encuesta" id="input_tipo_encuesta" value="satisfaccion">
                <input type="hidden" name="seguimiento_id" id="input_seguimiento_id" value="">
                <input type="hidden" name="calificacion" id="input_calificacion" value="0">
                <input type="hidden" name="expectativa_cumplida" id="input_expectativa" value="inmediato">
                
                <div class="bg-white px-8 pt-8 pb-6">
                    <!-- Cabecera con Icono -->
                    <div class="flex items-center mb-6">
                        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-2xl mr-4" id="modal_icono">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="modal_titulo">Califica tu experiencia</h3>
                            <p class="text-xs text-gray-500" id="modal_subtitulo">Tu opinión nos ayuda a mejorar la rueda de negocios</p>
                        </div>
                    </div>

                    <!-- Información de la Cita -->
                    <div class="bg-gray-50 rounded-2xl p-4 mb-8 border border-gray-100 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Rueda de Negocios</p>
                            <p id="info_rueda" class="text-xs font-bold text-gray-800 truncate">Cargando...</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Fecha y Hora</p>
                            <p id="info_fecha" class="text-xs font-bold text-gray-800">Cargando...</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Reunión con</p>
                            <p id="info_contraparte" class="text-sm font-bold text-blue-700">Cargando...</p>
                        </div>
                    </div>

                    <!-- Tag de tipo de encuesta -->
                    <div id="trazabilidad_tag" class="hidden mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                            <i class="fas fa-history mr-1"></i>
                            <span id="trazabilidad_texto">Seguimiento de trazabilidad</span>
                        </span>
                    </div>

                    <!-- 1. Estrellas -->
                    <div class="mb-8 text-center" id="seccion_estrellas">
                        <label class="block text-sm font-bold text-gray-700 mb-4">¿Cómo calificarías la reunión? <span class="text-red-500">*</span></label>
                        <div class="flex justify-center space-x-3 mb-2">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <button type="button" onclick="setRating(<?php echo $i; ?>)" onmouseover="hoverRating(<?php echo $i; ?>)" onmouseout="resetRating()" 
                                        class="star-btn text-5xl text-gray-200 transition-all transform hover:scale-110 focus:outline-none">
                                    <i class="fas fa-star"></i>
                                </button>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xs font-bold text-gray-400 mt-2" id="rating_text">Haz clic en una estrella</p>
                    </div>

                    <!-- 2. Expectativas (solo satisfacción) -->
                    <div class="mb-8" id="seccion_expectativas">
                        <label class="block text-sm font-bold text-gray-700 mb-4">¿Se cumplieron tus expectativas?</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" onclick="setExpectativa('inmediato')" id="exp_inmediato"
                                    class="exp-card border-2 border-gray-100 rounded-2xl p-3 flex flex-col items-center justify-center transition-all hover:bg-gray-50 focus:outline-none">
                                <i class="fas fa-check-circle text-green-500 mb-2 text-xl"></i>
                                <span class="text-[10px] font-bold text-gray-600 text-center leading-tight">Sí, inmediatamente</span>
                            </button>
                            <button type="button" onclick="setExpectativa('mediano_plazo')" id="exp_mediano_plazo"
                                    class="exp-card border-2 border-gray-100 rounded-2xl p-3 flex flex-col items-center justify-center transition-all hover:bg-gray-50 focus:outline-none">
                                <i class="fas fa-clock text-yellow-500 mb-2 text-xl"></i>
                                <span class="text-[10px] font-bold text-gray-600 text-center leading-tight">A mediano plazo</span>
                            </button>
                            <button type="button" onclick="setExpectativa('ninguno')" id="exp_ninguno"
                                    class="exp-card border-2 border-gray-100 rounded-2xl p-3 flex flex-col items-center justify-center transition-all hover:bg-gray-50 focus:outline-none">
                                <i class="fas fa-times-circle text-red-500 mb-2 text-xl"></i>
                                <span class="text-[10px] font-bold text-gray-600 text-center leading-tight">No se cumplieron</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Valor de Negocio Inicial (solo satisfacción) -->
                    <div class="mb-8" id="seccion_valor_inicial">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Valor estimado del negocio proyectado</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">$</span>
                            </div>
                            <input type="number" name="posibilidad_negocio" id="input_posibilidad_negocio" step="0.01" value="0.00"
                                   class="block w-full pl-8 pr-4 py-3 border-gray-100 bg-gray-50 rounded-2xl text-gray-900 font-bold focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- 3.5 Negocio Concretado (solo trazabilidad) -->
                    <div class="mb-8 hidden" id="seccion_negocio_concretado">
                        <label class="block text-sm font-bold text-gray-700 mb-3">¿El negocio se concretó?</label>
                        <div class="flex space-x-4">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="negocio_concretado" value="1" class="sr-only peer" onchange="toggleNegocioConcretado(true)">
                                <div class="border-2 border-gray-200 rounded-2xl p-4 text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                                    <p class="text-xs font-bold text-gray-700">Sí, se concretó</p>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="negocio_concretado" value="0" class="sr-only peer" checked onchange="toggleNegocioConcretado(false)">
                                <div class="border-2 border-gray-200 rounded-2xl p-4 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                    <i class="fas fa-times-circle text-red-500 text-2xl mb-2"></i>
                                    <p class="text-xs font-bold text-gray-700">No se concretó</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 3.6 Monto Final (solo trazabilidad si se concretó) -->
                    <div class="mb-8 hidden" id="seccion_monto_final">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Monto final del negocio concretado</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">$</span>
                            </div>
                            <input type="number" name="monto_final" id="input_monto_final" step="0.01" value="0.00"
                                   class="block w-full pl-8 pr-4 py-3 border-gray-100 bg-gray-50 rounded-2xl text-gray-900 font-bold focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <!-- 3.7 Fecha de Cierre (solo trazabilidad si se concretó) -->
                    <div class="mb-8 hidden" id="seccion_fecha_cierre">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de cierre del negocio</label>
                        <input type="date" name="fecha_cierre" id="input_fecha_cierre"
                               class="block w-full px-4 py-3 border-gray-100 bg-gray-50 rounded-2xl text-gray-900 font-bold focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- 4. Toggles de Asistencia (solo satisfacción) -->
                    <div class="flex items-center justify-between bg-blue-50 p-4 rounded-2xl border border-blue-100 mb-8" id="seccion_asistencia">
                        <div class="flex items-center">
                            <i class="fas fa-users text-blue-500 mr-3"></i>
                            <span class="text-xs font-bold text-gray-700">¿Asistieron ambas partes?</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="asistencia_completa" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- 5. Comentarios -->
                    <div>
                        <textarea name="comentario" id="input_comentario" rows="3" 
                                  class="w-full px-4 py-3 border-gray-100 bg-gray-50 rounded-2xl text-xs focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
                                  placeholder="Escribe tus comentarios adicionales aquí..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-6 flex space-x-4">
                    <button type="button" onclick="validarYEnviar()" class="flex-1 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        <i class="fas fa-save mr-2"></i> <span id="btn_guardar_texto">Guardar Calificación</span>
                    </button>
                    <button type="button" onclick="cerrarModalEncuesta()" class="px-6 py-4 text-sm font-bold rounded-2xl text-gray-500 bg-white border border-gray-200 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .star-btn {
        cursor: pointer;
        display: inline-block;
        line-height: 1;
    }
    .star-btn i {
        display: block;
    }
    .star-btn.active { color: #facc15 !important; }
    .star-btn.hover { color: #fbbf24 !important; opacity: 0.6; }
    .star-btn.text-gray-200 { color: #e5e7eb !important; }
    .exp-card.active { border-color: #3b82f6; background-color: #eff6ff; transform: translateY(-2px); }
</style>

<script>
let currentRating = 0;
let currentTipoEncuesta = 'satisfaccion';

function abrirModalEncuesta(id, nombre, rueda = 'Rueda de Negocios', fecha = 'N/A', tipo = 'satisfaccion', seguimientoId = '') {
    document.getElementById('encuesta_reunion_id').value = id;
    document.getElementById('info_rueda').innerText = rueda;
    document.getElementById('info_fecha').innerText = fecha;
    document.getElementById('info_contraparte').innerText = nombre;
    document.getElementById('input_tipo_encuesta').value = tipo;
    document.getElementById('input_seguimiento_id').value = seguimientoId;
    
    currentTipoEncuesta = tipo;
    
    // Configurar UI según tipo de encuesta
    configurarTipoEncuesta(tipo);
    
    setRating(0);
    setExpectativa('inmediato');
    
    document.getElementById('modalEncuesta').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function configurarTipoEncuesta(tipo) {
    const esTrazabilidad = tipo.includes('trazabilidad');
    
    if (esTrazabilidad) {
        // Mostrar tag de trazabilidad
        document.getElementById('trazabilidad_tag').classList.remove('hidden');
        document.getElementById('trazabilidad_texto').innerText = tipo === 'trazabilidad_3_meses' ? 'Seguimiento a 3 meses' : 'Seguimiento a 6 meses';
        
        // Actualizar cabecera
        document.getElementById('modal_icono').innerHTML = '<i class="fas fa-history text-xl"></i>';
        document.getElementById('modal_icono').className = 'bg-purple-100 text-purple-600 p-3 rounded-2xl mr-4';
        document.getElementById('modal_titulo').innerText = 'Seguimiento de Trazabilidad';
        document.getElementById('modal_subtitulo').innerText = 'Actualiza el estado del negocio proyectado';
        
        // Mostrar campos de trazabilidad
        document.getElementById('seccion_negocio_concretado').classList.remove('hidden');
        document.getElementById('seccion_monto_final').classList.add('hidden'); // Ocultar inicialmente
        document.getElementById('seccion_fecha_cierre').classList.add('hidden'); // Ocultar inicialmente
        
        // Ocultar campos de satisfacción
        document.getElementById('seccion_estrellas').classList.add('hidden');
        document.getElementById('seccion_expectativas').classList.add('hidden');
        document.getElementById('seccion_valor_inicial').classList.add('hidden');
        document.getElementById('seccion_asistencia').classList.add('hidden');
        
        // Actualizar texto del botón
        document.getElementById('btn_guardar_texto').innerText = 'Guardar Seguimiento';
        
        // Actualizar placeholder de comentarios
        document.getElementById('input_comentario').placeholder = 'Describe el estado actual del negocio, avances, o razones por las que no se concretó...';
        
    } else {
        // Satisfacción normal
        document.getElementById('trazabilidad_tag').classList.add('hidden');
        
        document.getElementById('modal_icono').innerHTML = '<i class="fas fa-star text-xl"></i>';
        document.getElementById('modal_icono').className = 'bg-yellow-100 text-yellow-600 p-3 rounded-2xl mr-4';
        document.getElementById('modal_titulo').innerText = 'Califica tu experiencia';
        document.getElementById('modal_subtitulo').innerText = 'Tu opinión nos ayuda a mejorar la rueda de negocios';
        
        // Ocultar campos de trazabilidad
        document.getElementById('seccion_negocio_concretado').classList.add('hidden');
        document.getElementById('seccion_monto_final').classList.add('hidden');
        document.getElementById('seccion_fecha_cierre').classList.add('hidden');
        
        // Mostrar campos de satisfacción
        document.getElementById('seccion_estrellas').classList.remove('hidden');
        document.getElementById('seccion_expectativas').classList.remove('hidden');
        document.getElementById('seccion_valor_inicial').classList.remove('hidden');
        document.getElementById('seccion_asistencia').classList.remove('hidden');
        
        document.getElementById('btn_guardar_texto').innerText = 'Guardar Calificación';
        document.getElementById('input_comentario').placeholder = 'Escribe tus comentarios adicionales aquí...';
    }
}

function toggleNegocioConcretado(concretado) {
    if (concretado) {
        document.getElementById('seccion_monto_final').classList.remove('hidden');
        document.getElementById('seccion_fecha_cierre').classList.remove('hidden');
    } else {
        document.getElementById('seccion_monto_final').classList.add('hidden');
        document.getElementById('seccion_fecha_cierre').classList.add('hidden');
    }
}

function cerrarModalEncuesta() {
    document.getElementById('modalEncuesta').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function setRating(val) {
    currentRating = val;
    document.getElementById('input_calificacion').value = val;
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, index) => {
        if (index < val) {
            star.classList.add('active');
            star.classList.remove('text-gray-200');
        } else {
            star.classList.remove('active');
            star.classList.add('text-gray-200');
        }
    });
    
    const text = document.getElementById('rating_text');
    if (val === 0) {
        text.innerText = 'Haz clic en una estrella';
        text.className = 'text-xs font-bold text-gray-400 mt-2';
    } else {
        text.className = 'text-xs font-bold text-yellow-600 mt-2';
        if (val === 1) text.innerText = 'Muy insatisfecho';
        else if (val === 2) text.innerText = 'Insatisfecho';
        else if (val === 3) text.innerText = 'Regular';
        else if (val === 4) text.innerText = 'Satisfecho';
        else if (val === 5) text.innerText = '¡Excelente!';
    }
}

function hoverRating(val) {
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, index) => {
        if (index < val) star.classList.add('hover');
    });
}

function resetRating() {
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach(star => star.classList.remove('hover'));
}

function setExpectativa(val) {
    document.getElementById('input_expectativa').value = val;
    document.querySelectorAll('.exp-card').forEach(card => card.classList.remove('active'));
    document.getElementById('exp_' + val).classList.add('active');
}

function validarYEnviar() {
    if (parseInt(document.getElementById('input_calificacion').value) < 1) {
        alert("Por favor, selecciona al menos una estrella para calificar.");
        return;
    }
    document.getElementById('formEncuesta').submit();
}

// Soporte para apertura automática vía URL
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const reunionId = urlParams.get('reunion_id');
    const nombre = urlParams.get('nombre');
    const rueda = urlParams.get('rueda');
    const fecha = urlParams.get('fecha');
    
    if (reunionId && nombre) {
        abrirModalEncuesta(reunionId, nombre, rueda || 'Rueda de Negocios', fecha || 'N/A');
    }
});
</script>
