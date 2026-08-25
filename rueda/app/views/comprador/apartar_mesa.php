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
                        <?php if ($rueda['modalidad'] === 'virtual'): ?>
                            <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4 mb-4">
                                <p class="text-xs text-purple-800 font-black leading-relaxed">
                                    <i class="fas fa-video mr-1.5 text-purple-500"></i> 
                                    <strong>Reunión Virtual:</strong> Esta rueda se realizará en línea.
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
                        <?php endif; ?>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Fecha para apartar la mesa</label>
                            <input type="date" name="fecha_apartado" id="fecha_apartado" required
                                   class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-white font-bold"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   max="<?php echo $rueda['fechaFin']; ?>">
                            <p class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                La fecha debe estar entre <?php echo date('d/m/Y'); ?> y <?php echo date('d/m/Y', strtotime($rueda['fechaFin'])); ?>.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Seleccionar Mesa Disponible</label>
                            <div class="relative">
                                <select name="numero_mesa" id="numero_mesa_select" required
                                       class="block w-full border border-gray-200 rounded-2xl shadow-sm px-4 py-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 appearance-none bg-white font-bold">
                                    <option value="">-- Seleccionar Mesa --</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff]">
                                    <i class="fas fa-chair text-xs"></i>
                                </div>
                            </div>
                            <div id="mesa_info_text" class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                Cargando mesas disponibles...
                            </div>
                            <div id="mesas_ocupadas_info" class="mt-3 hidden">
                                <p class="text-xs text-red-600 font-bold flex items-center gap-1">
                                    <i class="fas fa-times-circle"></i>
                                    Mesas ocupadas: <span id="lista_ocupadas"></span>
                                </p>
                            </div>
                        </div>
                        
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
    cargarMesasDisponibles();
});

async function cargarMesasDisponibles() {
    const select = document.getElementById('numero_mesa_select');
    const infoText = document.getElementById('mesa_info_text');
    const ocupadasInfo = document.getElementById('mesas_ocupadas_info');
    const listaOcupadas = document.getElementById('lista_ocupadas');
    const ruedaId = "<?php echo $ruedaId; ?>";
    const compradorId = "<?php echo $miEmpresaId; ?>";

    if (!select) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&comprador_id=${compradorId}`);
        const result = await response.json();
        
        console.log('Resultado API:', result);

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

            // Mostrar mesas ocupadas si existen
            console.log('Debug info:', result.data.debug);
            if (result.data.debug && result.data.debug.mesas_ocupadas && result.data.debug.mesas_ocupadas.length > 0) {
                ocupadasInfo.classList.remove('hidden');
                listaOcupadas.textContent = result.data.debug.mesas_ocupadas.join(', ');
                console.log('Mostrando mesas ocupadas:', result.data.debug.mesas_ocupadas);
            } else {
                console.log('No hay mesas ocupadas para mostrar');
            }
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles</option>';
            infoText.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> No hay mesas disponibles en este momento.';
            
            if (result.data && result.data.debug && result.data.debug.mesas_ocupadas) {
                ocupadasInfo.classList.remove('hidden');
                listaOcupadas.textContent = result.data.debug.mesas_ocupadas.join(', ');
            }
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
        infoText.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> Error al cargar las mesas. Intenta nuevamente.';
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
