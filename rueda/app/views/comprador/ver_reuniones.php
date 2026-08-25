<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">

        <!-- SELECTOR DE RUEDA REDISEÑADO -->
        <?php if (!empty($ruedas)): ?>
        <div class="bg-white p-6 rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 max-w-xl">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-filter text-[#00a2ff]"></i>
                        <span class="text-sm font-extrabold text-gray-900">Filtrar por Rueda de Negocios</span>
                    </div>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="controlador" value="comprador">
                        <input type="hidden" name="accion" value="verReuniones">
                        <div class="relative group">
                            <select name="rueda_id" onchange="this.form.submit()" 
                                    class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition-all cursor-pointer shadow-inner">
                                <?php foreach ($ruedas as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" 
                                            <?php echo ($rueda_id_filtro == $r['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r['tituloRueda'] ?? 'N/A'); ?> 
                                        (<?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?> - <?php echo date('d/m/Y', strtotime($r['fechaFin'])); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#00a2ff] group-hover:scale-110 transition-transform">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if ($rueda_actual): ?>
                <div class="flex-1 lg:max-w-2xl bg-gradient-to-br from-sky-50/50 to-blue-50/30 rounded-2xl p-5 border border-sky-100/50">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="p-3 bg-white text-[#00a2ff] rounded-2xl shadow-sm">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-extrabold text-[#00a2ff] uppercase tracking-wider">Mostrando citas de:</p>
                            <p class="text-base font-black text-gray-900"><?php echo htmlspecialchars($rueda_actual['tituloRueda'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[10px] bg-white border border-sky-100 text-[#00a2ff] px-3 py-1 rounded-full font-extrabold uppercase tracking-wider shadow-sm">
                                <?php echo ($rueda_actual['estadoRueda'] == 'activa') ? 'Rueda Activa' : 'Rueda ' . ucfirst($rueda_actual['estadoRueda']); ?>
                            </span>
                            <?php if (strtolower($rueda_actual['modalidad'] ?? 'virtual') === 'virtual'): ?>
                                <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-100 px-3 py-1 rounded-full font-extrabold uppercase tracking-wider"><i class="fas fa-video mr-1"></i>Virtual</span>
                            <?php else: ?>
                                <span class="text-[10px] bg-orange-50 text-orange-700 border border-orange-100 px-3 py-1 rounded-full font-extrabold uppercase tracking-wider" title="Presencial: <?php echo htmlspecialchars($rueda_actual['ubicacion'] ?? ''); ?>"><i class="fas fa-map-marker-alt mr-1"></i>Presencial</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-sky-50 border-2 border-sky-100 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-white text-[#00a2ff] rounded-2xl shadow-sm">
                <i class="fas fa-info-circle text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-gray-900">No estás inscrito en ruedas activas</h3>
                <p class="text-xs text-gray-500 mt-1">Explora las ruedas disponibles en tu panel de comprador.</p>
            </div>
            <a href="index.php?controlador=comprador&accion=dashboard" class="ml-auto text-xs bg-[#00a2ff] hover:bg-[#008ae0] text-white px-5 py-2.5 rounded-full font-extrabold transition-colors shadow-md shadow-sky-500/10">
                Ver Ruedas
            </a>
        </div>
        <?php endif; ?>

        <!-- SECCIÓN SUPERIOR: KANBAN PENDIENTES / PROGRAMADAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Izquierda: Pendientes por Aceptar -->
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-[#00a2ff] to-[#4dbfff] px-6 py-5 flex items-center justify-between">
                    <h2 class="text-white font-black text-lg flex items-center gap-2"><i class="fas fa-exchange-alt text-white/80"></i> Solicitudes y Contraofertas Recibidas</h2>
                    <span class="bg-white text-[#00a2ff] w-8 h-8 flex items-center justify-center rounded-full text-xs font-black shadow-sm"><?php echo count($citas_por_aceptar); ?></span>
                </div>
                <div class="p-6 space-y-5 max-h-[600px] overflow-y-auto custom-scrollbar flex-1">
                    <?php if (empty($citas_por_aceptar)): ?>
                        <div class="bg-sky-50/50 border-2 border-dashed border-sky-100 rounded-3xl p-12 text-center">
                            <i class="fas fa-check-double text-sky-200 text-4xl mb-4"></i>
                            <p class="text-sky-800 font-extrabold text-sm">No tienes citas pendientes</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas_por_aceptar as $cita): 
                            $es_contraoferta = ($cita['estadoCita'] == 'negociando');
                            $numero_propuesta = $cita['contadorContrapropuestas'] ?? 1;
                        ?>
                            <div class="bg-white p-5 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-sky-200 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-extrabold text-gray-900"><?php echo htmlspecialchars($cita['nombre_vendedor'] ?? 'N/A'); ?></p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (!empty($cita['numero_mesa'])): ?>
                                            <p class="text-[10px] text-[#00a2ff] font-bold mt-1 uppercase">
                                                <i class="fas fa-chair mr-1"></i> <?php echo htmlspecialchars($cita['numero_mesa']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] bg-amber-50 text-amber-600 border border-amber-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider block mb-1">
                                            <?php echo $es_contraoferta ? 'Contraoferta #' . $numero_propuesta : 'Nueva Solicitud'; ?>
                                        </span>
                                        <?php if ($numero_propuesta >= 4): ?>
                                            <span class="text-[9px] text-rose-500 font-bold">Última propuesta</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center mb-4">
                                    <i class="far fa-calendar-alt mr-2 text-[#00a2ff]"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                </div>
                                <button onclick="abrirModalGestionar(<?php echo $cita['id']; ?>, '<?php echo addslashes(htmlspecialchars($cita['nombre_vendedor'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($cita['fechaHora'])); ?>', <?php echo $cita['contadorContrapropuestas']; ?>, <?php echo $es_contraoferta ? 'true' : 'false'; ?>)" 
                                        class="w-full bg-[#00a2ff] hover:bg-[#008ae0] text-white py-3 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-sky-500/10 uppercase tracking-wider">
                                    <i class="fas fa-tasks mr-2"></i> Gestionar Propuesta
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Derecha: Aceptadas / Programadas -->
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-6 py-5 flex items-center justify-between">
                    <h2 class="text-white font-black text-lg flex items-center gap-2"><i class="fas fa-calendar-check text-white/80"></i> Citas Programadas</h2>
                    <span class="bg-white text-emerald-600 w-8 h-8 flex items-center justify-center rounded-full text-xs font-black shadow-sm"><?php echo count($citas_programadas); ?></span>
                </div>
                <div class="p-6 space-y-5 max-h-[600px] overflow-y-auto custom-scrollbar flex-1">
                    <?php if (empty($citas_programadas)): ?>
                        <div class="bg-emerald-50/50 border-2 border-dashed border-emerald-100 rounded-3xl p-12 text-center">
                            <i class="fas fa-handshake text-emerald-200 text-4xl mb-4"></i>
                            <p class="text-emerald-800 font-extrabold text-sm">No tienes citas programadas</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas_programadas as $cita): ?>
                            <?php $esMesaApartada = ($cita['estadoCita'] == 'mesa_apartada'); ?>
                            <div class="bg-white p-5 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <?php if ($esMesaApartada): ?>
                                            <p class="font-extrabold text-gray-900">Esperando vendedor...</p>
                                        <?php else: ?>
                                            <p class="font-extrabold text-gray-900"><?php echo htmlspecialchars($cita['nombre_vendedor'] ?? 'N/A'); ?></p>
                                        <?php endif; ?>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (!empty($cita['numero_mesa'])): ?>
                                            <p class="text-[10px] text-[#00a2ff] font-bold mt-1 uppercase">
                                                <i class="fas fa-chair mr-1"></i> <?php echo htmlspecialchars($cita['numero_mesa']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($esMesaApartada): ?>
                                        <span class="text-[10px] bg-amber-50 text-amber-600 border border-amber-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider"><i class="fas fa-chair mr-1"></i> Mesa Apartada</span>
                                    <?php else: ?>
                                        <span class="text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider"><i class="fas fa-check-circle mr-1"></i> Agendada</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-600 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center mb-4">
                                    <i class="far fa-calendar-check mr-2 text-emerald-500"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <?php 
                                    $linkReunion = trim($cita['linkReunion'] ?? '');
                                    $esUrlValida = !empty($linkReunion) && filter_var($linkReunion, FILTER_VALIDATE_URL);
                                    $citaAceptada = in_array($cita['estadoCita'], ['aceptada', 'agendada']);
                                    $esPropositor = ($cita['propositor'] ?? '') === 'comprador';
                                    $puedeAgregarLink = $citaAceptada && $esPropositor && empty($linkReunion);
                                    
                                    // Verificar si ya es hora de la reunión (permitir 5 minutos antes)
                                    $fechaReunion = strtotime($cita['fechaHora']);
                                    $ahora = time();
                                    $esHoraDeReunion = $ahora >= ($fechaReunion - 300); // 5 minutos de gracia
                                    
                                    // Datos para el calendario
                                    $tituloEvento = urlencode("Reunión AGESCO: " . ($cita['nombre_vendedor'] ?? 'Socio'));
                                    $fechaInicio = date('Ymd\THis', strtotime($cita['fechaHora']));
                                    $fechaFin = date('Ymd\THis', strtotime($cita['fechaHora'] . ' +30 minutes'));
                                    $detallesEvento = urlencode("Cita de negocios agendada en la Rueda de Negocios AGESCO.\n\nSocio: " . ($cita['nombre_vendedor'] ?? 'N/A') . "\nLink: " . ($linkReunion ?: 'Pendiente'));
                                    $ubicacionEvento = urlencode($esUrlValida ? $linkReunion : ($rueda_actual['ubicacion'] ?? 'Virtual'));
                                    
                                    $googleUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$tituloEvento}&dates={$fechaInicio}/{$fechaFin}&details={$detallesEvento}&location={$ubicacionEvento}";
                                    ?>

                                    <?php if ($esUrlValida): ?>
                                        <?php if ($esHoraDeReunion): ?>
                                            <a href="<?php echo htmlspecialchars($linkReunion); ?>" target="_blank" rel="noopener noreferrer" class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white py-3 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-emerald-500/10 uppercase tracking-wider">
                                                <i class="fas fa-video mr-2"></i> Unirse a Reunión
                                            </a>
                                        <?php else: ?>
                                            <button disabled class="block w-full text-center bg-gray-100 text-gray-400 py-3 rounded-xl text-xs font-black cursor-not-allowed border border-gray-200 uppercase tracking-wider">
                                                <i class="fas fa-lock mr-2"></i> Unirse (Disponible a las <?php echo date('H:i', $fechaReunion); ?>)
                                            </button>
                                        <?php endif; ?>
                                    <?php elseif ($puedeAgregarLink): ?>
                                        <button onclick="abrirModalLink(<?php echo $cita['id']; ?>)" 
                                                class="block w-full text-center bg-[#00a2ff] hover:bg-[#008ae0] text-white py-3 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-sky-500/10 uppercase tracking-wider">
                                            <i class="fas fa-link mr-2"></i> Vincular Link
                                        </button>
                                    <?php else: ?>
                                        <div class="bg-gray-50 text-gray-400 py-3 rounded-xl text-xs text-center italic w-full font-bold border border-gray-100">
                                            <?php if (!$citaAceptada): ?>
                                                <i class="fas fa-clock mr-1"></i> Pendiente de aceptar fecha
                                            <?php elseif (!$esPropositor): ?>
                                                <i class="fas fa-hourglass-half mr-1"></i> Esperando link del vendedor
                                            <?php else: ?>
                                                <i class="fas fa-hourglass-half mr-1"></i> Esperando link de reunión
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Botón Sincronizar Calendario -->
                                    <div class="relative calendar-container">
                                        <button type="button" onclick="toggleCalendarDropdown(this)" class="w-full bg-white border-2 border-emerald-100 text-emerald-600 hover:bg-emerald-50 py-2.5 rounded-xl text-[10px] font-black transition-all duration-300 uppercase tracking-wider flex items-center justify-center gap-2">
                                            <i class="fas fa-calendar-plus text-xs"></i> Sincronizar Calendario
                                        </button>
                                        <div class="calendar-dropdown">
                                            <div class="p-2 space-y-1">
                                                <a href="<?php echo $googleUrl; ?>" target="_blank" class="flex items-center gap-3 px-4 py-2.5 hover:bg-sky-50 rounded-lg text-[10px] font-bold text-gray-700 transition-colors">
                                                    <i class="fab fa-google text-red-500 text-sm"></i> Google Calendar
                                                </a>
                                                <button onclick="descargarICS('<?php echo addslashes($cita['nombre_vendedor'] ?? 'Socio'); ?>', '<?php echo $cita['fechaHora']; ?>', '<?php echo addslashes($linkReunion ?: 'Pendiente'); ?>', '<?php echo addslashes($rueda_actual['ubicacion'] ?? 'Virtual'); ?>')" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-sky-50 rounded-lg text-[10px] font-bold text-gray-700 transition-colors">
                                                    <i class="fab fa-apple text-gray-800 text-sm"></i> Apple / Outlook (ICS)
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE CITAS -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#008ae0] to-[#00a2ff] px-6 py-5">
                <h2 class="text-white font-black text-sm text-center uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                    <i class="fas fa-history text-white/80"></i> Historial de Citas <span class="text-white/70 font-bold">(Finalizadas o Descartadas)</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-50">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Vendedor</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Rueda</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Fecha/Hora</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Estado</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        <?php if (empty($citas_historial)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <i class="fas fa-folder-open text-gray-200 text-4xl mb-4"></i>
                                    <p class="text-gray-400 font-extrabold text-sm">No hay registros en el historial</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($citas_historial as $cita): ?>
                                <tr class="hover:bg-sky-50/30 transition duration-200">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-gray-900"><?php echo htmlspecialchars($cita['nombre_vendedor'] ?? 'N/A'); ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-black text-gray-600 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center shadow-sm">
                                            <i class="far fa-calendar-alt mr-2 text-[#00a2ff]"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php
                                            $badge_class = 'bg-gray-100 text-gray-600 border-gray-200';
                                            if ($cita['estadoCita'] == 'realizada') $badge_class = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                            if ($cita['estadoCita'] == 'cancelada' || $cita['estadoCita'] == 'rechazada') $badge_class = 'bg-rose-50 text-rose-600 border-rose-100';
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black border uppercase tracking-wider <?php echo $badge_class; ?>">
                                            <?php echo htmlspecialchars($cita['estadoCita'] ?: 'Pendiente'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php 
                                            $ya_paso = (date('Y-m-d', strtotime($cita['fechaHora'])) < date('Y-m-d', strtotime(SYSTEM_TIME)));
                                            if (($ya_paso || $cita['estadoCita'] == 'realizada') && $cita['estadoCita'] != 'cancelada'): 
                                        ?>
                                            <button onclick="abrirModalEncuesta(<?php echo $cita['id']; ?>, '<?php echo addslashes(htmlspecialchars($cita['nombre_vendedor'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($cita['tituloRueda'])); ?>', '<?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>')" 
                                                    class="text-[10px] font-black text-[#00a2ff] hover:text-white bg-sky-50 hover:bg-[#00a2ff] border border-sky-100 px-4 py-2 rounded-full transition-all duration-300 uppercase tracking-wider">
                                                <i class="fas fa-star mr-1"></i> Calificar
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-300 text-xs font-black">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODALES ESTILO PANEL VENDEDOR -->
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 20px; }
    .animate-modal { animation: modalSlideUp 0.3s ease-out forwards; }
    @keyframes modalSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    
    .calendar-dropdown {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 50;
        margin-bottom: 0.5rem;
    }
    
    .calendar-dropdown.active {
        display: block;
        animation: modalSlideUp 0.2s ease-out;
    }
</style>

<script>
function toggleCalendarDropdown(btn) {
    const dropdown = btn.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.calendar-dropdown');
    
    allDropdowns.forEach(d => {
        if (d !== dropdown) d.classList.remove('active');
    });
    
    dropdown.classList.toggle('active');
    
    // Cerrar al hacer clic afuera
    const closeDropdown = (e) => {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
            document.removeEventListener('click', closeDropdown);
        }
    };
    
    if (dropdown.classList.contains('active')) {
        setTimeout(() => {
            document.addEventListener('click', closeDropdown);
        }, 10);
    }
}

function descargarICS(nombreSocio, fechaHora, link, ubicacion) {
    const start = new Date(fechaHora);
    const end = new Date(start.getTime() + 30 * 60000); // 30 mins
    
    const formatDate = (date) => {
        return date.toISOString().replace(/-|:|\.\d+/g, "");
    };

    const icsContent = [
        "BEGIN:VCALENDAR",
        "VERSION:2.0",
        "BEGIN:VEVENT",
        "DTSTART:" + formatDate(start),
        "DTEND:" + formatDate(end),
        "SUMMARY:Reunión AGESCO: " + nombreSocio,
        "DESCRIPTION:Cita de negocios agendada en la Rueda de Negocios AGESCO.\\n\\nSocio: " + nombreSocio + "\\nLink: " + link,
        "LOCATION:" + (link !== "Pendiente" ? link : ubicacion),
        "END:VEVENT",
        "END:VCALENDAR"
    ].join("\n");

    const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `Cita_AGESCO_${nombreSocio.replace(/\s+/g, '_')}.ics`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function abrirModalEncuesta(reunionId, nombre, rueda, fecha) {
    if (document.getElementById('encuesta_reunion_id')) {
        document.getElementById('encuesta_reunion_id').value = reunionId;
    }
    if (document.getElementById('encuesta_nombre_empresa')) {
        document.getElementById('encuesta_nombre_empresa').innerText = nombre;
    }
    if (document.getElementById('modalEncuesta')) {
        document.getElementById('modalEncuesta').classList.remove('hidden');
    }
}
</script>

<!-- Modal para Gestionar Cita (Comprador) -->
<div id="modalGestionarCita" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalGestionar()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=comprador&accion=gestionarCita" method="POST" id="formGestionarCita">
                <input type="hidden" name="cita_id" id="gestionar_cita_id">
                <input type="hidden" name="accion" id="gestionar_accion">
                <div class="bg-white px-6 pt-6 pb-5 sm:p-7 sm:pb-6">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-5 flex items-center">
                        <i class="fas fa-tasks text-[#00a2ff] mr-2"></i> Gestionar Propuesta
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Vendedor</p>
                            <p class="text-sm font-black text-gray-900"><span id="gestionar_nombre_vendedor"></span></p>
                        </div>
                        
                        <div class="bg-sky-50/50 p-3 rounded-xl border border-sky-100">
                            <p class="text-[10px] font-bold text-[#00a2ff] uppercase tracking-wider mb-1">Fecha y Hora Propuesta</p>
                            <p class="text-sm font-black text-gray-900" id="gestionar_fecha_actual"></p>
                        </div>

                        <div id="contador_contrapropuestas" class="hidden">
                            <p class="text-xs text-[#00a2ff] font-bold bg-sky-50 px-3 py-2 rounded-xl border border-sky-100 inline-flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Contrapropuesta <span id="numero_actual_propuesta"></span> de 4
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-3">¿Qué deseas hacer?</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" onclick="seleccionarAccion('aceptada')" id="btn_aceptar" class="accion-btn bg-emerald-50 text-emerald-700 py-3 rounded-xl text-xs font-black hover:bg-emerald-100 transition border-2 border-transparent">
                                    <i class="fas fa-check mr-1"></i> Aceptar
                                </button>
                                <button type="button" onclick="seleccionarAccion('contraoferta')" id="btn_contraoferta" class="accion-btn bg-sky-50 text-[#00a2ff] py-3 rounded-xl text-xs font-black hover:bg-sky-100 transition border-2 border-transparent">
                                    <i class="fas fa-exchange-alt mr-1"></i> Contraofertar
                                </button>
                                <button type="button" onclick="seleccionarAccion('rechazada')" id="btn_rechazar" class="accion-btn bg-rose-50 text-rose-700 py-3 rounded-xl text-xs font-black hover:bg-rose-100 transition border-2 border-transparent">
                                    <i class="fas fa-times mr-1"></i> Rechazar
                                </button>
                            </div>
                        </div>

                        <div id="campos_aceptar" class="hidden">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                                <p class="text-sm text-emerald-700 font-bold">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Al aceptar, la cita quedará confirmada. El link de reunión se agregará después desde "Citas Programadas".
                                </p>
                            </div>
                        </div>

                        <div id="campos_contraoferta" class="hidden space-y-3">
                            <div>
                                <label class="block text-sm font-black text-gray-700">
                                    Nueva Fecha y Hora <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="nueva_fecha" id="nueva_fecha" 
                                       class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-black text-gray-700">Mensaje (opcional)</label>
                                <textarea name="mensaje" rows="2" placeholder="Explica por qué propones esta nueva fecha..." 
                                          class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-sky-50 focus:border-[#00a2ff] transition-all"></textarea>
                            </div>
                        </div>

                        <div id="campos_rechazar" class="hidden">
                            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                                <p class="text-sm text-rose-700 font-bold">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    ¿Estás seguro? Esta acción cancelará definitivamente la propuesta de reunión.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse rounded-b-[2.5rem] gap-2">
                    <button type="submit" id="btn_confirmar" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-md px-5 py-2.5 bg-gray-400 text-sm font-black text-white sm:ml-3 sm:w-auto cursor-not-allowed" disabled>
                        Confirmar Acción
                    </button>
                    <button type="button" onclick="cerrarModalGestionar()" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-black text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto transition-all duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let contadorPropuestas = 0;
let limiteAlcanzado = false;

function abrirModalGestionar(id, nombre, fecha, contador, esContraoferta) {
    document.getElementById('gestionar_cita_id').value = id;
    document.getElementById('gestionar_nombre_vendedor').innerText = nombre;
    document.getElementById('gestionar_fecha_actual').innerText = new Date(fecha).toLocaleString('es-CO', {
        day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    
    contadorPropuestas = contador;
    limiteAlcanzado = contador >= 4;
    
    const contadorDiv = document.getElementById('contador_contrapropuestas');
    if (esContraoferta) {
        contadorDiv.classList.remove('hidden');
        document.getElementById('numero_actual_propuesta').innerText = contador;
    } else {
        contadorDiv.classList.add('hidden');
    }
    
    if (limiteAlcanzado) {
        document.getElementById('btn_contraoferta').disabled = true;
        document.getElementById('btn_contraoferta').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        document.getElementById('btn_contraoferta').disabled = false;
        document.getElementById('btn_contraoferta').classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    seleccionarAccion(null);
    document.getElementById('formGestionarCita').reset();
    document.getElementById('gestionar_cita_id').value = id;
    
    document.getElementById('modalGestionarCita').classList.remove('hidden');
}

function cerrarModalGestionar() {
    document.getElementById('modalGestionarCita').classList.add('hidden');
}

function seleccionarAccion(accion) {
    document.querySelectorAll('.accion-btn').forEach(btn => {
        btn.classList.remove('border-[#00a2ff]', 'ring-2', 'ring-sky-200');
        btn.classList.add('border-transparent');
    });
    
    document.getElementById('campos_aceptar').classList.add('hidden');
    document.getElementById('campos_contraoferta').classList.add('hidden');
    document.getElementById('campos_rechazar').classList.add('hidden');
    
    const btnConfirmar = document.getElementById('btn_confirmar');
    
    if (!accion) {
        btnConfirmar.disabled = true;
        btnConfirmar.classList.add('bg-gray-400', 'cursor-not-allowed');
        btnConfirmar.classList.remove('bg-[#00a2ff]', 'hover:bg-[#008ae0]', 'bg-emerald-600', 'hover:bg-emerald-700', 'bg-rose-600', 'hover:bg-rose-700');
        btnConfirmar.innerText = 'Confirmar Acción';
        return;
    }
    
    const btnSeleccionado = document.getElementById('btn_' + (accion === 'contraoferta' ? 'contraoferta' : accion === 'aceptada' ? 'aceptar' : 'rechazar'));
    btnSeleccionado.classList.remove('border-transparent');
    btnSeleccionado.classList.add('border-[#00a2ff]', 'ring-2', 'ring-sky-200');
    
    document.getElementById('gestionar_accion').value = accion;
    
    if (accion === 'aceptada') {
        document.getElementById('campos_aceptar').classList.remove('hidden');
        btnConfirmar.classList.remove('bg-gray-400', 'cursor-not-allowed', 'bg-[#00a2ff]', 'hover:bg-[#008ae0]', 'bg-rose-600', 'hover:bg-rose-700');
        btnConfirmar.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        btnConfirmar.innerText = 'Confirmar Aceptación';
        btnConfirmar.disabled = false;
    } else if (accion === 'contraoferta') {
        document.getElementById('campos_contraoferta').classList.remove('hidden');
        document.getElementById('nueva_fecha').required = true;
        btnConfirmar.classList.remove('bg-gray-400', 'cursor-not-allowed', 'bg-emerald-600', 'hover:bg-emerald-700', 'bg-rose-600', 'hover:bg-rose-700');
        btnConfirmar.classList.add('bg-[#00a2ff]', 'hover:bg-[#008ae0]');
        btnConfirmar.innerText = 'Enviar Contraoferta';
        btnConfirmar.disabled = false;
    } else if (accion === 'rechazada') {
        document.getElementById('campos_rechazar').classList.remove('hidden');
        btnConfirmar.classList.remove('bg-gray-400', 'cursor-not-allowed', 'bg-[#00a2ff]', 'hover:bg-[#008ae0]', 'bg-emerald-600', 'hover:bg-emerald-700');
        btnConfirmar.classList.add('bg-rose-600', 'hover:bg-rose-700');
        btnConfirmar.innerText = 'Confirmar Rechazo';
        btnConfirmar.disabled = false;
    }
}
</script>

<?php require_once __DIR__ . '/../layout/modal_encuesta.php'; ?>

<script>
function abrirModalLink(reunionId) {
    const input = document.getElementById('link_cita_id');
    if (input) input.value = reunionId;
    const modal = document.getElementById('modalAgregarLink');
    if (modal) modal.classList.remove('hidden');
}

function cerrarModalLink() {
    const modal = document.getElementById('modalAgregarLink');
    if (modal) modal.classList.add('hidden');
}
</script>

<!-- Modal para Agregar Link de Reunión -->
<div id="modalAgregarLink" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalLink()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=comprador&accion=agregarLinkReunion" method="POST">
                <input type="hidden" name="cita_id" id="link_cita_id">
                <div class="bg-white px-6 pt-6 pb-5 sm:p-7 sm:pb-6">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-5 flex items-center">
                        <i class="fas fa-link text-[#0d9488] mr-2"></i> Vincular Link de Reunión
                    </h3>
                    <div class="bg-teal-50/50 border border-teal-100 p-3 rounded-xl mb-5">
                        <p class="text-sm text-[#0d9488] font-bold">
                            <i class="fas fa-info-circle mr-1"></i>
                            Como propositor de esta cita, debes proporcionar el link de la reunión. Este enlace solo se puede agregar una vez.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-black text-gray-700">Link de Reunión <span class="text-rose-500">*</span></label>
                        <input type="url" name="link_reunion" id="link_input" placeholder="https://meet.google.com/xxx-xxxx-xxx" required
                               class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all">
                        <p class="text-xs text-gray-400 font-bold mt-2">Google Meet, Zoom, Teams, etc.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse rounded-b-[2.5rem] gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-md px-5 py-2.5 bg-[#0d9488] hover:bg-[#0f766e] text-sm font-black text-white transition-all duration-300 sm:ml-3 sm:w-auto">
                        <i class="fas fa-check mr-2"></i> Guardar Link
                    </button>
                    <button type="button" onclick="cerrarModalLink()" 
                            class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-black text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto transition-all duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
