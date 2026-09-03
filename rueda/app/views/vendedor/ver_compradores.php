<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <!-- HEADER PREMIUM TEMA VERDE VENDEDOR -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(13,148,136,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Mercado de Negocios</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Compradores</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-shopping-basket mr-2 text-white/80"></i> <?php echo htmlspecialchars($rueda['tituloRueda']); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=vendedor&accion=verReuniones&rueda_id=<?php echo $ruedaId; ?>" class="bg-white text-[#0d9488] px-6 py-3 rounded-full font-black text-sm shadow-xl hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Citas
                </a>
            </div>
        </div>

        <!-- FILTROS DE BÚSQUEDA -->
        <div class="bg-white p-6 rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 mb-10">
            <form method="GET" action="" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="controlador" value="vendedor">
                <input type="hidden" name="accion" value="verCompradores">
                <input type="hidden" name="id" value="<?php echo $ruedaId; ?>">
                
                <div class="flex-1 relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#0d9488] transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" name="busqueda" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? ''); ?>" 
                           placeholder="Buscar compradores o demandas..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner">
                </div>
                <div class="md:w-64 relative group">
                    <select name="sector_id" class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                        <option value="">Todos los sectores</option>
                        <?php foreach ($todos_sectores as $sec): ?>
                            <option value="<?php echo $sec['id']; ?>" <?php echo ($_GET['sector_id'] ?? '') == $sec['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sec['nombreSector']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488] group-hover:scale-110 transition-transform">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <button type="submit" class="bg-[#0d9488] hover:bg-[#0f766e] text-white px-8 py-3.5 rounded-2xl font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20 flex items-center justify-center gap-2">
                    <i class="fas fa-filter text-xs"></i> Filtrar
                </button>
                <?php if (!empty($_GET['busqueda']) || !empty($_GET['sector_id'])): ?>
                    <a href="index.php?controlador=vendedor&accion=verCompradores&id=<?php echo $ruedaId; ?>" class="bg-gray-100 text-gray-500 px-6 py-3.5 rounded-2xl font-black text-sm hover:bg-gray-200 transition-all duration-300 text-center flex items-center justify-center">
                        <i class="fas fa-times mr-2 text-xs"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- GRID PRINCIPAL: COMPRADORES -->
        <div class="mb-14">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-chair text-[#0d9488]"></i> Compradores en Mesa (Esperando Solicitud)
                </h2>
                <div class="bg-teal-50 px-4 py-1.5 rounded-full border border-teal-100">
                    <span class="text-[10px] font-black text-[#0d9488] uppercase tracking-wider"><?php echo count($compradores); ?> resultados</span>
                </div>
            </div>
            
            <?php if (empty($compradores)): ?>
                <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <i class="fas fa-search text-gray-200 text-3xl"></i>
                    </div>
                    <p class="text-gray-900 font-black text-lg">No se encontraron compradores</p>
                    <p class="text-gray-400 text-sm font-bold mt-2">Prueba ajustando tus filtros de búsqueda o cambiando de sector.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($compradores as $c): ?>
                        <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-xl hover:border-teal-100 transition-all duration-500 flex flex-col group">
                            <div class="p-7 flex-1">
                                <div class="flex items-start justify-between mb-5">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="text-[9px] bg-teal-50 text-[#0d9488] px-3 py-1 rounded-full font-black uppercase tracking-wider border border-teal-100/50">
                                            <i class="fas fa-building mr-1 opacity-60"></i> <?php echo htmlspecialchars($c['razon_social'] ?? 'N/A'); ?>
                                        </span>
                                        <?php if (!empty($c['mesa_apartada'])): ?>
                                            <span class="text-[9px] bg-amber-50 text-amber-600 px-3 py-1 rounded-full font-black uppercase tracking-wider border border-amber-100 flex items-center gap-1.5 shadow-sm">
                                                <i class="fas fa-chair text-[10px] animate-pulse"></i> 
                                                Mesa <?php echo htmlspecialchars($c['mesa_apartada']); ?> Apartada
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($c['mesa_apartada'])): ?>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter border border-amber-200 shadow-sm">
                                                <i class="fas fa-chair mr-1"></i> Ubicado en Mesa: <?php echo htmlspecialchars($c['mesa_apartada']); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="font-black text-gray-900 text-lg mb-2 leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($c['razon_social'] ?? ''); ?></h3>
                                    <div class="mb-4">
                                        <p class="text-[10px] font-bold text-teal-600 uppercase tracking-tighter">CIIU: <?php echo htmlspecialchars($c['ciiu_personalizado'] ?: ($c['ciiu_clase'] ?? 'N/A')); ?></p>
                                        <?php if (!empty($c['ciiu_nombre_personalizado'])): ?>
                                            <p class="text-[9px] text-gray-400 font-medium line-clamp-1 italic"><?php echo htmlspecialchars($c['ciiu_nombre_personalizado']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                
                                <?php if (!empty($c['demandas'])): ?>
                                    <div class="space-y-3 pt-6 border-t border-gray-50">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Demandas:</p>
                                        <?php foreach ($c['demandas'] as $demanda): ?>
                                            <div class="bg-gray-50 p-3 rounded-xl">
                                                <p class="text-xs font-black text-gray-800 leading-tight"><?php echo htmlspecialchars($demanda['tituloDemanda']); ?></p>
                                                <p class="text-[10px] text-gray-500 mt-1 line-clamp-2"><?php echo htmlspecialchars($demanda['descripcionDemanda']); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="px-7 pb-7">
                                <?php if ($rueda['estadoRueda'] === 'activa'): ?>
                                    <?php if (!empty($c['mesa_apartada'])): ?>
                                        <button onclick="abrirModalSolicitud(<?php echo $c['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($c['razon_social'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($c['descripcion'] ?? 'Sin descripción disponible.')); ?>', '<?php echo $c['fecha_apartado']; ?>', '<?php echo $c['mesa_apartada']; ?>')" 
                                                class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition-all duration-300 shadow-lg shadow-amber-500/20 flex flex-col items-center justify-center gap-0.5 group-hover:scale-[1.02] transform">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-calendar-check text-[10px]"></i> Solicitar a esta Mesa
                                            </span>
                                            <span class="text-[8px] opacity-90 font-bold uppercase tracking-widest">Mesa <?php echo $c['mesa_apartada']; ?> está esperando</span>
                                        </button>
                                    <?php else: ?>
                                        <button onclick="abrirModalSolicitud(<?php echo $c['empresaId']; ?>, '<?php echo addslashes(htmlspecialchars($c['razon_social'] ?? 'N/A')); ?>')" 
                                                class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition-all duration-300 shadow-md shadow-teal-500/10 flex items-center justify-center gap-2">
                                            <i class="fas fa-calendar-plus text-[10px]"></i> Solicitar Reunión
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button disabled class="w-full bg-gray-50 text-gray-300 py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] cursor-not-allowed border border-gray-100 flex items-center justify-center gap-2">
                                        <i class="fas fa-lock text-[10px]"></i> Agendamiento Cerrado
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para solicitar reunión a comprador -->
<div id="modalSolicitud" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay oscuro premium -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('modalSolicitud').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Tarjeta de Modal Premium -->
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <!-- Botón Cerrar (X) arriba a la derecha -->
            <button type="button" onclick="document.getElementById('modalSolicitud').classList.add('hidden')" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="index.php?controlador=vendedor&accion=solicitarCita" method="POST">
                <input type="hidden" name="rueda_id" value="<?php echo $ruedaId; ?>">
                <input type="hidden" name="comprador_id" id="modal_comprador_id">
                <input type="hidden" name="vendedor_id" value="<?php echo $miEmpresa['id']; ?>">
                
                <div class="bg-white px-6 pt-7 pb-5 sm:p-8 sm:pb-6">
                    <!-- Título con Icono -->
                    <div class="flex items-center gap-2.5 mb-5 text-left">
                        <div class="p-2 bg-teal-500/10 text-[#0d9488] rounded-xl">
                            <i class="fas fa-handshake text-xl"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Proponer Hora de Reunión</h3>
                    </div>
                    
                    <!-- Tarjeta de Detalles del Comprador -->
                    <div class="bg-gradient-to-br from-teal-50/50 to-emerald-50/30 border border-teal-100 p-5 rounded-2xl mb-6 flex flex-col gap-4 text-left">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-white text-[#0d9488] rounded-xl shadow-sm shrink-0">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#0d9488] tracking-wider uppercase">Empresa Compradora</p>
                                <p id="modal_nombre_comprador" class="font-black text-gray-900 text-base mt-0.5"></p>
                                <p id="modal_descripcion_comprador" class="text-xs text-gray-500 mt-1 leading-relaxed line-clamp-3"></p>
                            </div>
                        </div>

                        <div id="info_mesa_apartada" class="flex items-center gap-3 pt-3 border-t border-teal-100/50">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-xl shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-amber-600 tracking-wider uppercase">Ubicación de la Cita</p>
                                <p class="font-bold text-gray-800 text-sm mt-0.5">
                                    Mesa <span id="modal_numero_mesa" class="text-amber-700 font-black"></span> 
                                    (Reservada por el comprador)
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos del Formulario -->
                    <div class="space-y-5 text-left">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">¿A qué hora te gustaría reunirte?</label>
                            <input type="text" name="fecha_hora" id="fecha_hora_input" required 
                                   class="block w-full border border-gray-200 rounded-full shadow-sm px-5 py-3 text-sm font-bold focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 bg-gray-50 cursor-pointer"
                                   placeholder="Seleccionar hora exacta...">
                            <p class="text-[10px] text-gray-400 mt-1.5 ml-1 flex items-center gap-1 font-bold">
                                <i class="fas fa-info-circle text-[#0d9488]"></i>
                                Sugiere una hora dentro del horario de la rueda.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 ml-1 mb-1.5 uppercase tracking-wider">Mensaje para el Comprador</label>
                            <textarea name="descripcion" rows="3" required 
                                      class="block w-full border border-gray-200 rounded-2xl shadow-sm px-5 py-4 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition duration-200 resize-none bg-gray-50" 
                                      placeholder="Ej: Hola, nos gustaría presentarte nuestros servicios de tecnología en tu mesa..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones del Footer -->
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalSolicitud').classList.add('hidden')" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-black text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#0d9488] text-sm font-black text-white hover:bg-[#0f766e] shadow-[0_4px_15px_rgba(13,148,136,0.2)] hover:shadow-[0_6px_20px_rgba(13,148,136,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Flatpickr en español para la cita
    if (document.getElementById('fecha_hora_input')) {
        flatpickr("#fecha_hora_input", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "F j, Y - h:i K",
            locale: "es",
            minDate: "<?php echo date('Y-m-d', strtotime($rueda['fechaInicio'])); ?>",
            maxDate: "<?php echo date('Y-m-d', strtotime($rueda['fechaFin'])); ?>",
            time_24hr: false,
            minuteIncrement: 30,
            disableMobile: "true",
            animate: true,
            onChange: function(selectedDates, dateStr) {
                // Aquí podrías disparar alguna validación extra si fuera necesario
            }
        });
    }
});

async function cargarMesasDisponibles(fechaHora) {
    const select = document.getElementById('numero_mesa_select');
    const infoText = document.getElementById('mesa_info_text');
    const ruedaId = "<?php echo $ruedaId; ?>";
    const vendedorId = "<?php echo $miEmpresaId; ?>";

    if (!select) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const encodedFecha = encodeURIComponent(fechaHora);
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&fecha_hora=${encodedFecha}&comprador_id=`);
        const result = await response.json();

        select.innerHTML = '<option value="">-- Seleccionar Mesa --</option>';
        
        if (result.status === 'success' && result.data && result.data.mesas && result.data.mesas.length > 0) {
            result.data.mesas.forEach(mesa => {
                const opt = document.createElement('option');
                opt.value = mesa;
                opt.textContent = mesa;
                select.appendChild(opt);
            });
            select.disabled = false;
            infoText.innerHTML = `<i class="fas fa-check-circle text-green-500"></i> ${result.data.mesas.length} mesas libres encontradas.`;
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles en este horario</option>';
            infoText.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> Todo ocupado. Intenta con otra hora.';
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
    }
}

function abrirModalSolicitud(compradorId, nombreComprador, descripcionComprador, fechaApartada = null, mesaApartada = null) {
    document.getElementById('modal_comprador_id').value = compradorId;
    document.getElementById('modal_nombre_comprador').innerText = nombreComprador;
    document.getElementById('modal_descripcion_comprador').innerText = descripcionComprador;
    
    if (mesaApartada) {
        document.getElementById('modal_numero_mesa').innerText = mesaApartada;
        document.getElementById('info_mesa_apartada').classList.remove('hidden');
    } else {
        document.getElementById('info_mesa_apartada').classList.add('hidden');
    }
    
    document.getElementById('modalSolicitud').classList.remove('hidden');
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
