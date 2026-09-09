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

        <?php if (!empty($_GET['msg']) && $_GET['msg'] === 'mesa_liberada'): ?>
            <div class="max-w-2xl mx-auto bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl flex items-center justify-between shadow-sm font-bold">
                <div class="flex items-center gap-3">
                    <i class="fas fa-info-circle text-amber-500 text-xl"></i>
                    <span>Tu mesa anterior ha sido liberada. Ahora puedes seleccionar una nueva mesa o fecha.</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-400 hover:text-amber-700"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($mesaExistente)): ?>
            <!-- TARJETA CUANDO EL COMPRADOR YA TIENE UNA MESA APARTADA -->
            <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,162,255,0.08)] border border-sky-100 overflow-hidden">
                <div class="bg-gradient-to-r from-[#002e53] to-[#00a2ff] px-8 py-7 text-white text-center relative overflow-hidden">
                    <div class="w-16 h-16 bg-white/10 rounded-3xl mx-auto flex items-center justify-center text-3xl mb-3 backdrop-blur-md border border-white/20">
                        <i class="fas fa-chair text-white"></i>
                    </div>
                    <span class="bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 text-[11px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                        Mesa Asignada y Activa
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black mt-2 tracking-tight">¡Ya Tienes Mesa Apartada!</h2>
                    <p class="text-white/80 text-xs font-bold mt-1">Tu espacio físico en esta Rueda de Negocios está asegurado</p>
                </div>

                <div class="p-8 sm:p-10 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-sky-50/50 border border-sky-100 rounded-2xl p-5 text-center">
                            <span class="text-[10px] font-black text-sky-600 uppercase tracking-wider block mb-1">Tu Número de Mesa</span>
                            <span class="text-3xl font-black text-[#002e53]">
                                Mesa <?php echo preg_replace('/[^0-9]/', '', (string)$mesaExistente['numero_mesa']); ?>
                            </span>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-center">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block mb-1">Fecha de tu Espacio</span>
                            <span class="text-base font-black text-slate-800 flex items-center justify-center gap-1.5 mt-1">
                                <i class="far fa-calendar-alt text-[#00a2ff]"></i>
                                <?php echo date('d/m/Y', strtotime($mesaExistente['fechaHora'])); ?>
                            </span>
                        </div>
                    </div>

                    <?php if (!empty($rueda['ubicacion'])): ?>
                        <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-orange-500 text-lg mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-black text-orange-900 uppercase tracking-wider">Lugar del Evento</h4>
                                <p class="text-xs font-bold text-orange-800 mt-0.5"><?php echo htmlspecialchars($rueda['ubicacion']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="bg-gray-50 rounded-2xl p-4 text-center">
                        <p class="text-xs text-gray-500 font-bold leading-relaxed">
                            Los proveedores interesados en tus requerimientos solicitarán citas directamente a tu mesa asignada.
                        </p>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <a href="index.php?controlador=comprador&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" 
                           class="flex-1 bg-[#00a2ff] hover:bg-[#008ae0] text-white py-3.5 px-6 rounded-full font-black text-xs uppercase tracking-wider text-center shadow-lg shadow-sky-500/20 transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-check"></i> Ver Mi Agenda de Citas
                        </a>
                        <a href="index.php?controlador=comprador&accion=liberarMesa&id=<?php echo $ruedaId; ?>" 
                           onclick="return confirm('¿Estás seguro de que deseas liberar tu mesa actual? Podrás seleccionar otra mesa disponible inmediatamente.');"
                           class="bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 py-3.5 px-6 rounded-full font-black text-xs uppercase tracking-wider text-center transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i> Cambiar / Liberar Mesa
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
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
                                <div class="relative">
                                    <input type="text" name="fecha_apartado" id="fecha_apartado" required
                                           class="block w-full border border-gray-200 rounded-2xl shadow-sm pl-12 pr-4 py-3.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-white font-black cursor-pointer text-gray-800"
                                           placeholder="Selecciona una fecha...">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff]">
                                        <i class="far fa-calendar-alt text-lg"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Rueda disponible del <?php echo date('d/m/Y', strtotime($rueda['fechaInicio'])); ?> al <?php echo date('d/m/Y', strtotime($rueda['fechaFin'])); ?>.
                                </p>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3 ml-1">
                                    <label class="block text-sm font-bold text-gray-700">Seleccionar Mesa Disponible</label>
                                    <div class="flex items-center gap-3 text-[11px] font-black uppercase tracking-wider">
                                        <span class="flex items-center gap-1.5 text-emerald-600">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Libre
                                        </span>
                                        <span class="flex items-center gap-1.5 text-slate-400">
                                            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span> Ocupada
                                        </span>
                                    </div>
                                </div>

                                <!-- Input oculto para enviar el valor seleccionado en el formulario -->
                                <input type="hidden" name="numero_mesa" id="numero_mesa_input" required>

                                <!-- Contenedor de la Cuadrícula Visual de Mesas -->
                                <div id="mesas_grid_container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5 p-4 bg-slate-50/80 rounded-3xl border border-slate-200/80 min-h-[140px] items-center justify-center">
                                    <div class="col-span-full text-center py-6 text-xs text-gray-400 font-bold flex items-center justify-center gap-2">
                                        <i class="fas fa-spinner fa-spin text-[#00a2ff]"></i> Cargando mapa de mesas...
                                    </div>
                                </div>

                                <div id="mesa_info_text" class="text-xs text-gray-400 mt-2.5 ml-1 flex items-center gap-1 font-bold">
                                    <i class="fas fa-info-circle text-[#00a2ff]"></i>
                                    Selecciona una fecha para ver las mesas disponibles.
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
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const minFecha = "<?php echo (strtotime($rueda['fechaInicio']) > time()) ? date('Y-m-d', strtotime($rueda['fechaInicio'])) : date('Y-m-d'); ?>";
    const maxFecha = "<?php echo date('Y-m-d', strtotime($rueda['fechaFin'])); ?>";
    const defaultFecha = minFecha;

    if (document.getElementById('fecha_apartado')) {
        flatpickr("#fecha_apartado", {
            locale: "es",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "D, j \\de F \\de Y",
            altInputClass: "block w-full border border-gray-200 rounded-2xl shadow-sm pl-12 pr-4 py-3.5 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition duration-200 bg-white font-black cursor-pointer text-gray-800",
            minDate: minFecha,
            maxDate: maxFecha,
            defaultDate: defaultFecha,
            disableMobile: "true",
            animate: true,
            onChange: function(selectedDates, dateStr) {
                cargarMesasDisponibles();
            }
        });
    }

    cargarMesasDisponibles();
});

async function cargarMesasDisponibles() {
    const grid = document.getElementById('mesas_grid_container');
    const inputOculto = document.getElementById('numero_mesa_input');
    const infoText = document.getElementById('mesa_info_text');
    const fechaInput = document.getElementById('fecha_apartado');
    const ruedaId = "<?php echo $ruedaId; ?>";
    const compradorId = "<?php echo $miEmpresaId; ?>";

    if (!grid || !fechaInput) return;
    if (!fechaInput.value) {
        grid.innerHTML = '<div class="col-span-full text-center py-6 text-xs text-gray-400 font-bold">Selecciona una fecha para ver el mapa de mesas.</div>';
        return;
    }

    const fechaHora = fechaInput.value + ' 05:00:00';
    grid.innerHTML = '<div class="col-span-full text-center py-6 text-xs text-gray-400 font-bold flex items-center justify-center gap-2"><i class="fas fa-spinner fa-spin text-[#00a2ff]"></i> Consultando disponibilidad...</div>';

    // Limpiar selección previa
    if (inputOculto) inputOculto.value = '';

    try {
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&comprador_id=${compradorId}&fecha_hora=${encodeURIComponent(fechaHora)}`);
        const result = await response.json();
        
        grid.innerHTML = '';

        if (result.status === 'success' && result.data) {
            const mesasLibres = (result.data.mesas || []).map(m => parseInt(m));
            const totalMesas = result.data.debug?.total_mesas_configuradas || (mesasLibres.length > 0 ? Math.max(...mesasLibres) : 10);

            if (totalMesas === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-6 text-xs text-rose-500 font-bold">No hay mesas configuradas para este evento.</div>';
                return;
            }

            let libresCount = 0;

            for (let i = 1; i <= totalMesas; i++) {
                const esLibre = mesasLibres.includes(i);
                if (esLibre) libresCount++;

                const card = document.createElement('div');
                card.dataset.mesa = i;

                if (esLibre) {
                    card.className = "mesa-card relative bg-white border-2 border-slate-200 hover:border-[#00a2ff] hover:bg-sky-50/40 rounded-2xl p-3.5 flex flex-col items-center justify-center gap-2 cursor-pointer transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 group";
                    card.innerHTML = `
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#00a2ff] group-hover:bg-[#00a2ff] group-hover:text-white flex items-center justify-center text-base transition-colors duration-200">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="text-center">
                            <span class="block text-xs font-black text-gray-800">Mesa ${i}</span>
                            <span class="inline-block mt-0.5 text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 uppercase tracking-wider">Libre</span>
                        </div>
                        <div class="check-badge absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#00a2ff] text-white rounded-full hidden items-center justify-center text-[10px] shadow-sm font-black">
                            <i class="fas fa-check"></i>
                        </div>
                    `;

                    card.addEventListener('click', function() {
                        seleccionarMesaVisual(i, card);
                    });
                } else {
                    card.className = "mesa-card relative bg-slate-100/70 border border-slate-200/60 rounded-2xl p-3.5 flex flex-col items-center justify-center gap-2 opacity-60 cursor-not-allowed select-none";
                    card.innerHTML = `
                        <div class="w-10 h-10 rounded-xl bg-slate-200/80 text-slate-400 flex items-center justify-center text-base">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="text-center">
                            <span class="block text-xs font-bold text-slate-500">Mesa ${i}</span>
                            <span class="inline-block mt-0.5 text-[9px] font-bold text-slate-400 bg-slate-200/70 px-2 py-0.5 rounded-full uppercase tracking-wider">Ocupada</span>
                        </div>
                    `;
                }

                grid.appendChild(card);
            }

            if (libresCount > 0) {
                infoText.innerHTML = `<i class="fas fa-check-circle text-emerald-500 text-sm"></i> <span class="text-emerald-700 font-bold">${libresCount} ${libresCount === 1 ? 'mesa disponible' : 'mesas disponibles'} para el día seleccionado. Haz clic en la mesa que prefieras.</span>`;
            } else {
                infoText.innerHTML = `<i class="fas fa-times-circle text-rose-500 text-sm"></i> <span class="text-rose-700 font-bold">Todas las mesas están ocupadas para esta fecha. Selecciona otro día.</span>`;
            }
        }
    } catch (error) {
        console.error("Error:", error);
        grid.innerHTML = '<div class="col-span-full text-center py-6 text-xs text-rose-500 font-bold">Error al cargar el mapa de mesas. Intenta nuevamente.</div>';
    }
}

function seleccionarMesaVisual(numeroMesa, cardElement) {
    const inputOculto = document.getElementById('numero_mesa_input');
    if (inputOculto) {
        inputOculto.value = numeroMesa;
    }

    // Desmarcar todas las tarjetas libres
    document.querySelectorAll('.mesa-card').forEach(c => {
        if (!c.classList.contains('cursor-not-allowed')) {
            c.classList.remove('border-[#00a2ff]', 'bg-sky-50', 'ring-4', 'ring-sky-100', 'shadow-md', '-translate-y-1');
            c.classList.add('border-slate-200', 'bg-white');
            const check = c.querySelector('.check-badge');
            if (check) {
                check.classList.remove('flex');
                check.classList.add('hidden');
            }
        }
    });

    // Marcar tarjeta seleccionada
    cardElement.classList.remove('border-slate-200', 'bg-white');
    cardElement.classList.add('border-[#00a2ff]', 'bg-sky-50', 'ring-4', 'ring-sky-100', 'shadow-md', '-translate-y-1');
    const check = cardElement.querySelector('.check-badge');
    if (check) {
        check.classList.remove('hidden');
        check.classList.add('flex');
    }

    const infoText = document.getElementById('mesa_info_text');
    if (infoText) {
        infoText.innerHTML = `<i class="fas fa-check-circle text-[#00a2ff] text-sm"></i> <span class="text-gray-900 font-extrabold">Has seleccionado la Mesa ${numeroMesa}. Completa el mensaje y haz clic en Apartar Mesa.</span>`;
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
