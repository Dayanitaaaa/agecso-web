<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <!-- HEADER PREMIUM TEMA AZUL COMPRADOR -->
        <div class="bg-gradient-to-r from-[#00a2ff] via-[#4dbfff] to-[#008ae0] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(0,162,255,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Rueda de Negocios</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Apartar Mesa</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-chair mr-2 text-white/80"></i> <?php echo htmlspecialchars($rueda['tituloRueda']); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-white text-[#00a2ff] px-6 py-3 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Reuniones
                </a>
            </div>
        </div>

        <!-- FORMULARIO PARA APARTAR MESA -->
        <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                        <i class="fas fa-chair text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-black text-xl tracking-tight">Seleccionar Mesa</h2>
                        <p class="text-white/80 text-xs font-bold uppercase tracking-wider mt-0.5">Elige tu mesa para la rueda de negocios</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form action="index.php?controlador=comprador&accion=procesarApartarMesa" method="POST">
                    <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                    <input type="hidden" name="comprador_id" value="<?php echo $miEmpresaId; ?>">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Fecha y Hora de la Reunión</label>
                            <input type="text" name="fecha_hora" id="fecha_hora_input" required 
                                   class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-white cursor-pointer"
                                   placeholder="Seleccionar fecha y hora...">
                            <p class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                La cita debe agendarse antes del <?php echo date('d/m/Y H:i', strtotime($rueda['fechaFin'])); ?>
                            </p>
                        </div>

                        <?php if ($rueda['modalidad'] === 'virtual'): ?>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Link de Reunión (Opcional)</label>
                                <input type="url" name="link_reunion" placeholder="https://meet.google.com/..." 
                                       class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200">
                                <p class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Puedes agregarlo ahora o después desde tus Citas Programadas
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 mb-4">
                                <p class="text-xs text-orange-800 font-black leading-relaxed">
                                    <i class="fas fa-map-marker-alt mr-1.5 text-orange-500"></i> 
                                    <strong>Reunión Presencial:</strong> Esta rueda se realiza físicamente en: <br>
                                    <span class="font-bold text-orange-900 ml-5"><?php echo htmlspecialchars($rueda['ubicacion']); ?></span>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Número de Mesa / Stand</label>
                                <div class="relative">
                                    <select name="numero_mesa" id="numero_mesa_select" 
                                           class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 appearance-none bg-white font-bold">
                                        <option value="">Selecciona una fecha primero...</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff]">
                                        <i class="fas fa-chair text-xs"></i>
                                    </div>
                                </div>
                                <p id="mesa_info_text" class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Solo se muestran las mesas libres para el horario elegido.
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Mensaje / Objetivo</label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 resize-none" 
                                      placeholder="Describe qué tipo de reuniones deseas tener..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <button type="submit" class="w-full bg-[#00a2ff] hover:bg-[#008ae0] text-white py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all duration-300 shadow-lg shadow-sky-500/20 flex items-center justify-center gap-2">
                            <i class="fas fa-chair text-xs"></i> Apartar Mesa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Flatpickr en español
    if (document.getElementById('fecha_hora_input')) {
        flatpickr("#fecha_hora_input", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "F j, Y - h:i K",
            locale: "es",
            minDate: "today",
            maxDate: "<?php echo date('Y-m-d H:i', strtotime($rueda['fechaFin'])); ?>",
            time_24hr: false,
            disableMobile: "true",
            animate: true,
            onChange: function(selectedDates, dateStr) {
                if (document.getElementById('numero_mesa_select')) {
                    cargarMesasDisponibles(dateStr);
                }
            }
        });
    }
});

async function cargarMesasDisponibles(fechaHora) {
    const select = document.getElementById('numero_mesa_select');
    const infoText = document.getElementById('mesa_info_text');
    const ruedaId = "<?php echo $ruedaId; ?>";
    const compradorId = "<?php echo $miEmpresaId; ?>";

    if (!select) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const encodedFecha = encodeURIComponent(fechaHora);
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&fecha_hora=${encodedFecha}&comprador_id=${compradorId}`);
        const result = await response.json();

        select.innerHTML = '<option value="">-- Seleccionar Mesa --</option>';
        
        if (result.status === 'success' && result.data && result.data.mesas && result.data.mesas.length > 0) {
            let mesaYaAsignada = result.data.mesa_sugerida;
            
            result.data.mesas.forEach(mesa => {
                const opt = document.createElement('option');
                opt.value = mesa;
                opt.textContent = mesa + (mesa === mesaYaAsignada ? ' (Tu mesa actual)' : '');
                if (mesa === mesaYaAsignada) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
            select.disabled = false;
            
            if (mesaYaAsignada) {
                infoText.innerHTML = `<i class="fas fa-check-circle text-[#00a2ff]"></i> Se ha pre-seleccionado tu mesa asignada.`;
            } else {
                infoText.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> ${result.data.mesas.length} mesas libres encontradas.`;
            }
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles en este horario</option>';
            infoText.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> Todo ocupado. Intenta con otra hora.';
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
