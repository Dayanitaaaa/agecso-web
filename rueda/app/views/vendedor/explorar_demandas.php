<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="space-y-10 py-8">
        
        <!-- FILTROS DE BÚSQUEDA REDISEÑADOS -->
        <div class="bg-white p-6 rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex-1 relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#0d9488] transition-colors">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                    <input type="text" id="filtro_texto" placeholder="Buscar demandas, empresas o descripciones..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all shadow-inner">
                </div>
                <div class="md:w-64 relative group">
                    <select id="filtro_sector" class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                        <option value="">Todos los sectores</option>
                        <?php foreach ($sectores as $sector): ?>
                            <option value="<?php echo htmlspecialchars($sector['id']); ?>">
                                <?php echo htmlspecialchars($sector['ciiu_clase'] ?? ''); ?> - <?php echo htmlspecialchars($sector['nombreSector'] ?? ''); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488] group-hover:scale-110 transition-transform">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- RESUMEN GENERAL -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-teal-50 text-[#0d9488] rounded-2xl">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Demandas</p>
                        <p class="text-3xl font-black text-gray-800 mt-1" id="total_demandas"><?php echo count($demandas); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-500 rounded-2xl">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Compradores</p>
                        <p class="text-3xl font-black text-gray-800 mt-1"><?php echo count(array_unique(array_column($demandas, 'razon_social'))); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-amber-50 text-amber-500 rounded-2xl">
                        <i class="fas fa-handshake text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mis Reuniones</p>
                        <p class="text-3xl font-black text-gray-800 mt-1"><?php echo count($reuniones_existentes); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRID PRINCIPAL: DEMANDAS REDISEÑADO -->
        <div class="mb-14">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fas fa-clipboard-list text-[#0d9488]"></i> Demandas Disponibles
                </h2>
                <div class="bg-teal-50 px-4 py-1.5 rounded-full border border-teal-100">
                    <span class="text-[10px] font-black text-[#0d9488] uppercase tracking-wider" id="total_demandas_badge"><?php echo count($demandas); ?> resultados</span>
                </div>
            </div>
            
            <?php if (empty($demandas)): ?>
                <div class="bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <i class="fas fa-search text-gray-200 text-3xl"></i>
                    </div>
                    <p class="text-gray-900 font-black text-lg">No se encontraron demandas</p>
                    <p class="text-gray-400 text-sm font-bold mt-2">Los compradores aún no han publicado demandas para esta rueda de negocios.</p>
                    <?php if (!empty($otras_ruedas)): ?>
                        <a href="index.php?controlador=vendedor&accion=seleccionarRuedaDemandas" class="inline-flex items-center mt-4 bg-[#0d9488] hover:bg-[#0f766e] text-white px-6 py-2 rounded-full font-black text-sm transition-all duration-300 shadow-lg shadow-teal-500/20">
                            <i class="fas fa-exchange-alt mr-2"></i> Ver otras ruedas
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div id="contenedor_demandas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($demandas as $demanda): 
                        $tiene_reunion = isset($reuniones_index[$demanda['empresaId']]);
                        $estado_reunion = $tiene_reunion ? $reuniones_index[$demanda['empresaId']] : null;
                    ?>
                        <div class="demanda-card bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-xl hover:border-teal-100 transition-all duration-500 flex flex-col group"
                             data-texto="<?php echo strtolower(htmlspecialchars($demanda['tituloDemanda'] . ' ' . $demanda['descripcionDemanda'])); ?>"
                             data-sector="<?php echo htmlspecialchars($demanda['sectorId']); ?>">
                            <div class="p-7 flex-1">
                                <div class="flex items-start justify-between mb-5">
                                    <div class="flex flex-wrap gap-2">
                                        <div class="flex flex-col">
                                            <span class="text-[9px] bg-teal-50 text-[#0d9488] px-3 py-1 rounded-full font-black uppercase tracking-wider border border-teal-100/50 w-fit">
                                                <i class="fas fa-tag mr-1 opacity-60"></i> <?php echo htmlspecialchars($demanda['ciiu_personalizado'] ?: ($demanda['ciiu_clase'] ?? 'N/A')); ?>
                                            </span>
                                            <?php if (!empty($demanda['ciiu_nombre_personalizado'])): ?>
                                                <span class="text-[8px] text-gray-400 font-bold mt-1 line-clamp-1 italic"><?php echo htmlspecialchars($demanda['ciiu_nombre_personalizado']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($tiene_reunion): ?>
                                            <span class="text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-wider shadow-sm
                                                <?php echo $estado_reunion === 'aceptada' ? 'bg-emerald-500 text-white' : 
                                                    ($estado_reunion === 'realizada' ? 'bg-blue-500 text-white' : 
                                                    'bg-amber-500 text-white'); ?>">
                                                <i class="fas fa-handshake mr-1"></i>
                                                <?php echo ucfirst($estado_reunion ?? ''); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <h3 class="font-black text-gray-900 text-lg mb-3 leading-tight group-hover:text-[#0d9488] transition-colors"><?php echo htmlspecialchars($demanda['razon_social'] ?? ''); ?></h3>
                                
                                <div class="space-y-4 mb-6">
                                    <!-- Ofertas de la empresa (Lo que ofrecen) -->
                                    <?php if (!empty($demanda['ofertas'])): ?>
                                        <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-100/50">
                                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-bullhorn text-[10px]"></i> Lo que ofrece:
                                            </p>
                                            <?php foreach (array_slice($demanda['ofertas'], 0, 2) as $oferta): ?>
                                                <div class="mb-2 last:mb-0">
                                                    <p class="text-[11px] font-black text-gray-800 leading-tight"><?php echo htmlspecialchars($oferta['tituloOferta']); ?></p>
                                                    <p class="text-[10px] text-gray-500 line-clamp-2 mt-0.5"><?php echo htmlspecialchars($oferta['descripcionOferta']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Demandas de la empresa (Lo que buscan) -->
                                    <?php if (!empty($demanda['demandas'])): ?>
                                        <div class="bg-teal-50/50 p-3 rounded-xl border border-teal-100/50">
                                            <p class="text-[9px] font-black text-[#0d9488] uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-shopping-basket text-[10px]"></i> Lo que busca:
                                            </p>
                                            <?php foreach (array_slice($demanda['demandas'], 0, 2) as $dem): ?>
                                                <div class="mb-2 last:mb-0">
                                                    <p class="text-[11px] font-black text-gray-800 leading-tight"><?php echo htmlspecialchars($dem['tituloDemanda']); ?></p>
                                                    <p class="text-[10px] text-gray-500 line-clamp-2 mt-0.5"><?php echo htmlspecialchars($dem['descripcionDemanda']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (empty($demanda['ofertas']) && empty($demanda['demandas'])): ?>
                                        <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                                            <p class="text-[10px] text-gray-400 font-bold italic">Esta empresa aún no ha publicado ofertas ni demandas.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="space-y-3 pt-6 border-t border-gray-50">
                                    <div class="flex items-center text-xs">
                                        <div class="w-8 h-8 rounded-full bg-teal-50 flex items-center justify-center mr-3 border border-teal-100/50">
                                            <i class="fas fa-building text-[#0d9488] text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                                                <?php echo ($demanda['rol_slug'] === 'comprador') ? 'Comprador' : 'Vendedor'; ?>
                                            </p>
                                            <p class="font-black text-gray-800 leading-none"><?php echo htmlspecialchars($demanda['razon_social'] ?? ''); ?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($demanda['ubicacionGeografica'])): ?>
                                    <div class="flex items-center text-xs text-gray-400">
                                        <i class="fas fa-map-marker-alt w-8 text-center text-[10px] mr-0.5"></i>
                                        <span class="font-bold tracking-tight italic"><?php echo htmlspecialchars($demanda['ubicacionGeografica']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Tags -->
                                <?php if (!empty($demanda['tagsRequerimiento'])): 
                                    $tags = json_decode($demanda['tagsRequerimiento'], true);
                                    if (is_array($tags) && !empty($tags)): ?>
                                        <div class="flex flex-wrap gap-1.5 mt-4">
                                            <?php foreach ($tags as $tag): ?>
                                                <span class="px-3 py-1 bg-teal-50 text-[#0d9488] rounded-full text-[9px] font-black uppercase tracking-wider border border-teal-100/50">
                                                    #<?php echo htmlspecialchars($tag); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="px-7 pb-7">
                                <?php if ($tiene_reunion): ?>
                                    <span class="inline-flex items-center justify-center w-full px-4 py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] bg-gray-100 text-gray-400 border border-gray-100">
                                        <i class="fas fa-check mr-2"></i> Ya tienes reunión
                                    </span>
                                <?php else: ?>
                                <button onclick="solicitarReunion(<?php echo $demanda['empresaId']; ?>, <?php echo $rueda_actual['id']; ?>, '<?php echo htmlspecialchars(addslashes($demanda['razon_social'] ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($demanda['descripcion'] ?? 'Sin descripción.')); ?>', '<?php echo $demanda['mesa_apartada'] ?? ''; ?>')" 
                                            class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white py-3.5 rounded-2xl text-xs font-black uppercase tracking-[0.1em] transition-all duration-300 shadow-md shadow-teal-500/10 flex items-center justify-center gap-2">
                                        <i class="fas fa-calendar-plus text-[10px]"></i> Solicitar Reunión
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

<!-- Modal para solicitar reunión -->
<div id="modalSolicitarReunion" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="cerrarModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            
            <button type="button" onclick="cerrarModal()" 
                    class="absolute right-5 top-5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition duration-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form id="formSolicitarReunion" action="index.php?controlador=vendedor&accion=solicitarCita" method="POST">
                <input type="hidden" id="comprador_id" name="comprador_id">
                <input type="hidden" id="rueda_id" name="rueda_id" value="<?php echo $rueda_actual['id'] ?? ''; ?>">
                
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
                                <p id="nombre_comprador" class="font-black text-gray-900 text-base mt-0.5"></p>
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
                                      placeholder="Ej: Hola, nos gustaría presentarte nuestros servicios..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50/50 border-t border-gray-100 px-6 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="cerrarModal()" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-gray-200 px-5 py-2.5 bg-white text-sm font-black text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-200 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex justify-center rounded-full border border-transparent px-8 py-2.5 bg-[#0d9488] text-sm font-black text-white hover:bg-[#0f766e] shadow-[0_4px_15px_rgba(13,148,136,0.2)] hover:shadow-[0_6px_20px_rgba(13,148,136,0.35)] hover:-translate-y-0.5 transition duration-200 transform focus:outline-none uppercase tracking-widest">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funciones de filtrado
const filtroTexto = document.getElementById('filtro_texto');
const filtroSector = document.getElementById('filtro_sector');
const contenedor = document.getElementById('contenedor_demandas');
const totalDemandas = document.getElementById('total_demandas');
const totalDemandasBadge = document.getElementById('total_demandas_badge');

function filtrarDemandas() {
    const texto = filtroTexto.value.toLowerCase();
    const sector = filtroSector.value;
    
    const cards = document.querySelectorAll('.demanda-card');
    let visibles = 0;
    
    cards.forEach(card => {
        const cardTexto = card.getAttribute('data-texto');
        const cardSector = card.getAttribute('data-sector');
        
        const coincideTexto = texto === '' || cardTexto.includes(texto);
        const coincideSector = sector === '' || cardSector === sector;
        
        if (coincideTexto && coincideSector) {
            card.style.display = '';
            visibles++;
        } else {
            card.style.display = 'none';
        }
    });
    
    if (totalDemandas) {
        totalDemandas.textContent = visibles;
    }
    if (totalDemandasBadge) {
        totalDemandasBadge.textContent = visibles + ' resultados';
    }
}

if (filtroTexto) filtroTexto.addEventListener('input', filtrarDemandas);
if (filtroSector) filtroSector.addEventListener('change', filtrarDemandas);

// Modal de solicitud de reunión
function solicitarReunion(compradorId, ruedaId, nombreComprador, descripcionComprador, mesaApartada = '') {
    document.getElementById('comprador_id').value = compradorId;
    document.getElementById('nombre_comprador').textContent = nombreComprador;
    document.getElementById('modal_descripcion_comprador').textContent = descripcionComprador;
    
    if (mesaApartada) {
        document.getElementById('modal_numero_mesa').innerText = mesaApartada;
        document.getElementById('info_mesa_apartada').classList.remove('hidden');
    } else {
        document.getElementById('info_mesa_apartada').classList.add('hidden');
    }

    // Inicializar Flatpickr con 30 min
    flatpickr("#fecha_hora_input", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y - h:i K",
        locale: "es",
        minDate: "<?php echo date('Y-m-d', strtotime($rueda_actual['fechaInicio'])); ?>",
        maxDate: "<?php echo date('Y-m-d', strtotime($rueda_actual['fechaFin'])); ?>",
        time_24hr: false,
        minuteIncrement: 30,
        disableMobile: "true",
        animate: true
    });
    
    document.getElementById('modalSolicitarReunion').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalSolicitarReunion').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalSolicitarReunion').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
