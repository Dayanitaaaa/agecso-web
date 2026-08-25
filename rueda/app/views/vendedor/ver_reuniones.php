<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">

        <!-- HEADER PREMIUM TEMA VERDE VENDEDOR -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(13,148,136,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Gestión de Citas</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Mis Reuniones: <span class="text-white/90">Vendedor</span></h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-handshake mr-2 text-white/80"></i> Gestiona propuestas, links y resultados
                </p>
            </div>
        </div>

        <!-- SELECTOR DE RUEDA -->
        <?php if (!empty($ruedas)): ?>
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 max-w-xl">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-filter text-[#0d9488]"></i>
                        <span class="text-sm font-extrabold text-gray-900">Filtrar por Rueda de Negocios</span>
                    </div>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="controlador" value="vendedor">
                        <input type="hidden" name="accion" value="verReuniones">
                        <div class="relative group">
                            <select name="rueda_id" onchange="this.form.submit()" 
                                    class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                                <?php foreach ($ruedas as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" 
                                            <?php echo ($rueda_id_filtro == $r['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($r['tituloRueda'] ?? 'N/A'); ?> 
                                        (<?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?> - <?php echo date('d/m/Y', strtotime($r['fechaFin'])); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488] group-hover:scale-110 transition-transform">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if ($rueda_actual): ?>
                <div class="flex-1 lg:max-w-2xl bg-teal-50/40 rounded-2xl p-5 border border-teal-100/50">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="p-3 bg-white text-[#0d9488] rounded-2xl shadow-sm">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-extrabold text-[#0d9488] uppercase tracking-wider">Mostrando citas de:</p>
                            <p class="text-base font-black text-gray-900"><?php echo htmlspecialchars($rueda_actual['tituloRueda'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[10px] bg-white border border-teal-100 text-[#0d9488] px-3 py-1 rounded-full font-extrabold uppercase tracking-wider shadow-sm">
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
        <div class="bg-teal-50 border-2 border-teal-100 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-white text-[#0d9488] rounded-2xl shadow-sm">
                <i class="fas fa-info-circle text-xl"></i>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-gray-900">No estás inscrito en ruedas activas</h3>
                <p class="text-xs text-gray-500 mt-1">Explora las ruedas disponibles en tu panel de vendedor.</p>
            </div>
            <a href="index.php?controlador=vendedor&accion=dashboard" class="ml-auto text-xs bg-[#0d9488] hover:bg-[#0f766e] text-white px-5 py-2.5 rounded-full font-extrabold transition-colors shadow-md shadow-teal-500/10">
                Ver Ruedas
            </a>
        </div>
        <?php endif; ?>

        <!-- SECCIÓN SUPERIOR: Tres columnas (Esperando Respuesta / Contraofertas / Aceptadas) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Columna 1: Esperando respuesta del comprador -->
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-[#0d9488] to-[#14b8a6] px-6 py-5 flex items-center justify-between">
                    <h2 class="text-white font-black text-base flex items-center gap-2"><i class="fas fa-clock text-white/80"></i> Esperando Respuesta</h2>
                    <span class="bg-white text-[#0d9488] w-8 h-8 flex items-center justify-center rounded-full text-xs font-black shadow-sm"><?php echo count($citas_pendientes_comprador); ?></span>
                </div>
                <div class="p-5 space-y-5 max-h-[600px] overflow-y-auto custom-scrollbar flex-1">
                    <?php if (empty($citas_pendientes_comprador)): ?>
                        <div class="bg-teal-50/50 border-2 border-dashed border-teal-100 rounded-3xl p-10 text-center">
                            <i class="fas fa-hourglass-half text-teal-200 text-4xl mb-4"></i>
                            <p class="text-teal-800 font-extrabold text-sm">No tienes propuestas esperando</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas_pendientes_comprador as $cita): ?>
                            <div class="bg-white p-5 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-teal-200 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-extrabold text-gray-900"><?php echo htmlspecialchars($cita['nombre_comprador'] ?? 'N/A'); ?></p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (($rueda_actual['modalidad'] ?? 'virtual') !== 'virtual'): ?>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                <span class="text-[9px] bg-orange-50 text-orange-600 border border-orange-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> Presencial
                                                </span>
                                                <?php if (!empty($cita['numero_mesa'])): ?>
                                                    <span class="text-[9px] bg-teal-50 text-[#0d9488] border border-teal-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                        <i class="fas fa-chair mr-1"></i> Mesa <?php echo htmlspecialchars($cita['numero_mesa']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[10px] bg-teal-50 text-[#0d9488] border border-teal-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider">Pendiente</span>
                                </div>
                                <div class="text-xs text-gray-600 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center mb-4">
                                    <i class="far fa-calendar-alt mr-2 text-[#0d9488]"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                </div>
                                <div class="bg-teal-50/50 text-[#0d9488] text-xs p-3 rounded-xl border border-teal-100 text-center font-bold">
                                    <i class="fas fa-clock mr-1"></i> Esperando que el comprador responda
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Columna 2: Contraofertas recibidas (requieren acción) -->
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-[#0f766e] to-[#0d9488] px-6 py-5 flex items-center justify-between">
                    <h2 class="text-white font-black text-base flex items-center gap-2"><i class="fas fa-exchange-alt text-white/80"></i> Solicitudes y Contraofertas Recibidas</h2>
                    <span class="bg-white text-[#0f766e] w-8 h-8 flex items-center justify-center rounded-full text-xs font-black shadow-sm"><?php echo count($citas_por_aceptar); ?></span>
                </div>
                <div class="p-5 space-y-5 max-h-[600px] overflow-y-auto custom-scrollbar flex-1">
                    <?php if (empty($citas_por_aceptar)): ?>
                        <div class="bg-teal-50/50 border-2 border-dashed border-teal-100 rounded-3xl p-10 text-center">
                            <i class="fas fa-check-double text-teal-200 text-4xl mb-4"></i>
                            <p class="text-teal-800 font-extrabold text-sm">No tienes contraofertas</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas_por_aceptar as $cita): 
                            $numero_propuesta = $cita['contadorContrapropuestas'] ?? 1;
                            $limite_alcanzado = $numero_propuesta >= 4;
                        ?>
                            <div class="bg-white p-5 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-teal-200 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-extrabold text-gray-900"><?php echo htmlspecialchars($cita['nombre_comprador'] ?? 'N/A'); ?></p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (($rueda_actual['modalidad'] ?? 'virtual') !== 'virtual'): ?>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                <span class="text-[9px] bg-orange-50 text-orange-600 border border-orange-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> Presencial
                                                </span>
                                                <?php if (!empty($cita['numero_mesa'])): ?>
                                                    <span class="text-[9px] bg-teal-50 text-[#0d9488] border border-teal-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                        <i class="fas fa-chair mr-1"></i> Mesa <?php echo htmlspecialchars($cita['numero_mesa']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] bg-amber-50 text-amber-600 border border-amber-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider block mb-1">
                                            <?php echo ($cita['estadoCita'] == 'pendiente') ? 'Nueva Solicitud' : 'Contraoferta #' . ($cita['contadorContrapropuestas'] ?? 1); ?>
                                        </span>
                                        <?php if ($limite_alcanzado): ?>
                                            <span class="text-[9px] text-rose-500 font-bold">Última permitida</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center mb-4">
                                    <i class="far fa-calendar-alt mr-2 text-[#0d9488]"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                </div>
                                <?php if ($cita['linkReunion']): ?>
                                    <div class="text-xs text-[#0d9488] mb-3 truncate font-bold">
                                        <i class="fas fa-link mr-1"></i> <?php echo htmlspecialchars($cita['linkReunion'] ?? ''); ?>
                                    </div>
                                <?php endif; ?>
                                <button onclick="abrirModalGestionar(<?php echo $cita['id']; ?>, '<?php echo addslashes(htmlspecialchars($cita['nombre_comprador'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($cita['fechaHora'])); ?>', <?php echo $numero_propuesta; ?>, '<?php echo addslashes(htmlspecialchars($cita['linkReunion'] ?? '')); ?>')" 
                                        class="w-full bg-[#0d9488] hover:bg-[#0f766e] text-white py-3 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-teal-500/10 uppercase tracking-wider">
                                    <i class="fas fa-tasks mr-2"></i> Responder Propuesta
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Derecha: Aceptadas / Programadas -->
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-6 py-5 flex items-center justify-between">
                    <h2 class="text-white font-black text-base flex items-center gap-2"><i class="fas fa-calendar-check text-white/80"></i> Citas Aceptadas</h2>
                    <span class="bg-white text-emerald-600 w-8 h-8 flex items-center justify-center rounded-full text-xs font-black shadow-sm"><?php echo count($citas_programadas); ?></span>
                </div>
                <div class="p-5 space-y-5 max-h-[600px] overflow-y-auto custom-scrollbar flex-1">
                    <?php if (empty($citas_programadas)): ?>
                        <div class="bg-emerald-50/50 border-2 border-dashed border-emerald-100 rounded-3xl p-10 text-center">
                            <i class="fas fa-handshake text-emerald-200 text-4xl mb-4"></i>
                            <p class="text-emerald-800 font-extrabold text-sm">No tienes citas aceptadas</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($citas_programadas as $cita): ?>
                            <div class="bg-white p-5 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-gray-100 hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-extrabold text-gray-900"><?php echo htmlspecialchars($cita['nombre_comprador'] ?? 'N/A'); ?></p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (($rueda_actual['modalidad'] ?? 'virtual') !== 'virtual'): ?>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                <span class="text-[9px] bg-orange-50 text-orange-600 border border-orange-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> Presencial
                                                </span>
                                                <?php if (!empty($cita['numero_mesa'])): ?>
                                                    <span class="text-[9px] bg-teal-50 text-[#0d9488] border border-teal-100 px-2 py-0.5 rounded-full font-black uppercase tracking-wider">
                                                        <i class="fas fa-chair mr-1"></i> Mesa <?php echo htmlspecialchars($cita['numero_mesa']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[10px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-full font-black uppercase tracking-wider"><i class="fas fa-check-circle mr-1"></i> Agendada</span>
                                </div>
                                <div class="text-xs text-gray-600 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center mb-4">
                                    <i class="far fa-calendar-check mr-2 text-emerald-500"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <?php 
                                    $linkReunion = trim($cita['linkReunion'] ?? '');
                                    $esUrlValida = !empty($linkReunion) && filter_var($linkReunion, FILTER_VALIDATE_URL);
                                    $citaAceptada = in_array($cita['estadoCita'], ['aceptada', 'agendada']);
                                    $esPropositor = ($cita['propositor'] ?? '') === 'vendedor';
                                    $puedeAgregarLink = $citaAceptada && $esPropositor && empty($linkReunion);
                                    $esVirtual = ($rueda_actual['modalidad'] ?? 'virtual') === 'virtual';

                                    // Verificar si ya es hora de la reunión (permitir 5 minutos antes)
                                    $fechaReunion = strtotime($cita['fechaHora']);
                                    $ahora = time();
                                    $esHoraDeReunion = $ahora >= ($fechaReunion - 300); // 5 minutos de gracia

                                    // Datos para el calendario
                                    $tituloEvento = urlencode("Reunión AGESCO: " . ($cita['nombre_comprador'] ?? 'Socio'));
                                    $fechaInicio = date('Ymd\THis', strtotime($cita['fechaHora']));
                                    $fechaFin = date('Ymd\THis', strtotime($cita['fechaHora'] . ' +30 minutes'));
                                    $detallesEvento = urlencode("Cita de negocios agendada en la Rueda de Negocios AGESCO.\n\nSocio: " . ($cita['nombre_comprador'] ?? 'N/A') . "\nLink: " . ($linkReunion ?: 'Pendiente'));
                                    $ubicacionEvento = urlencode($esUrlValida ? $linkReunion : ($rueda_actual['ubicacion'] ?? 'Virtual'));
                                    
                                    $googleUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$tituloEvento}&dates={$fechaInicio}/{$fechaFin}&details={$detallesEvento}&location={$ubicacionEvento}";
                                    ?>
                                    
                                    <?php if ($esVirtual): ?>
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
                                                    class="block w-full text-center bg-[#0d9488] hover:bg-[#0f766e] text-white py-3 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-teal-500/10 uppercase tracking-wider">
                                                <i class="fas fa-link mr-2"></i> Vincular Link
                                            </button>
                                        <?php else: ?>
                                            <div class="bg-gray-50 text-gray-400 py-3 rounded-xl text-xs text-center italic w-full font-bold border border-gray-100">
                                                <?php if (!$citaAceptada): ?>
                                                    <i class="fas fa-clock mr-1"></i> Pendiente de aceptar fecha
                                                <?php elseif (!$esPropositor): ?>
                                                    <i class="fas fa-hourglass-half mr-1"></i> Esperando link del comprador
                                                <?php else: ?>
                                                    <i class="fas fa-hourglass-half mr-1"></i> Esperando link de reunión
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Diseño para Presencial -->
                                        <div class="w-full bg-orange-50 border border-orange-100 rounded-xl p-3">
                                            <div class="flex items-center gap-2 text-orange-700 mb-1">
                                                <i class="fas fa-map-marker-alt text-xs"></i>
                                                <span class="text-[10px] font-black uppercase tracking-wider">Reunión Presencial</span>
                                            </div>
                                            <p class="text-xs font-bold text-gray-700 truncate" title="<?php echo htmlspecialchars($rueda_actual['ubicacion'] ?? 'Ubicación no definida'); ?>">
                                                <?php echo htmlspecialchars($rueda_actual['ubicacion'] ?? 'Ubicación no definida'); ?>
                                            </p>
                                            <?php if (!empty($cita['numero_mesa'])): ?>
                                                <div class="mt-2 pt-2 border-t border-orange-100 flex items-center justify-between">
                                                    <span class="text-[9px] font-bold text-orange-600 uppercase">Mesa / Stand:</span>
                                                    <span class="text-sm font-black text-orange-700"><?php echo htmlspecialchars($cita['numero_mesa']); ?></span>
                                                </div>
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
                                                <a href="<?php echo $googleUrl; ?>" target="_blank" class="flex items-center gap-3 px-4 py-2.5 hover:bg-teal-50 rounded-lg text-[10px] font-bold text-gray-700 transition-colors">
                                                    <i class="fab fa-google text-red-500 text-sm"></i> Google Calendar
                                                </a>
                                                <button onclick="descargarICS('<?php echo addslashes($cita['nombre_comprador'] ?? 'Socio'); ?>', '<?php echo $cita['fechaHora']; ?>', '<?php echo addslashes($linkReunion ?: 'Pendiente'); ?>', '<?php echo addslashes($rueda_actual['ubicacion'] ?? 'Virtual'); ?>')" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-teal-50 rounded-lg text-[10px] font-bold text-gray-700 transition-colors">
                                                    <i class="fab fa-apple text-gray-800 text-sm"></i> Apple / Outlook (ICS)
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (strtotime($cita['fechaHora']) < time()): ?>
                                    <button onclick="abrirModalResultado(<?php echo $cita['id']; ?>, '<?php echo addslashes(htmlspecialchars($cita['nombre_comprador'] ?? 'N/A')); ?>')" 
                                            class="w-full bg-[#0f766e] hover:bg-[#0d9488] text-white py-2.5 rounded-xl text-xs font-black transition-all duration-300 shadow-md shadow-teal-500/10 uppercase tracking-wider">
                                        <i class="fas fa-chart-line mr-2"></i> Registrar Resultado
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- HISTORIAL DE CITAS -->
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-[#0f766e] to-[#0d9488] px-6 py-5">
                <h2 class="text-white font-black text-sm text-center uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                    <i class="fas fa-history text-white/80"></i> Historial de Citas <span class="text-white/70 font-bold">(Finalizadas o Descartadas)</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-50">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Comprador</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Rueda</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Fecha/Hora</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Monto Venta</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Estado</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        <?php if (empty($citas_historial)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <i class="fas fa-folder-open text-gray-200 text-4xl mb-4"></i>
                                    <p class="text-gray-400 font-extrabold text-sm">No hay registros en el historial</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($citas_historial as $cita): ?>
                                <tr class="hover:bg-teal-50/30 transition duration-200">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-gray-900"><?php echo htmlspecialchars($cita['nombre_comprador'] ?? 'N/A'); ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"><?php echo htmlspecialchars($cita['tituloRueda'] ?? 'N/A'); ?></p>
                                        <?php if (($rueda_actual['modalidad'] ?? 'virtual') !== 'virtual'): ?>
                                            <p class="text-[9px] text-orange-600 font-black mt-1 uppercase">
                                                <i class="fas fa-map-marker-alt mr-1"></i> Presencial 
                                                <?php if(!empty($cita['numero_mesa'])) echo " - Mesa " . htmlspecialchars($cita['numero_mesa']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-black text-gray-600 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-flex items-center shadow-sm">
                                            <i class="far fa-calendar-alt mr-2 text-[#0d9488]"></i> <?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <p class="text-sm font-black text-gray-900">$<?php echo number_format($cita['monto_negocio'] ?? 0, 2); ?></p>
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
                                            <button onclick="abrirModalEncuesta(<?php echo $cita['id']; ?>, '<?php echo addslashes(htmlspecialchars($cita['nombre_comprador'] ?? 'N/A')); ?>', '<?php echo addslashes(htmlspecialchars($cita['tituloRueda'])); ?>', '<?php echo date('d/m/Y H:i', strtotime($cita['fechaHora'])); ?>')" 
                                                    class="text-[10px] font-black text-[#0d9488] hover:text-white bg-teal-50 hover:bg-[#0d9488] border border-teal-100 px-4 py-2 rounded-full transition-all duration-300 uppercase tracking-wider">
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

<!-- Modal para Gestionar Cita Recibida (Vendedor) -->
<div id="modalGestionarCita" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalGestionar()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=vendedor&accion=gestionarCitaRecibida" method="POST" id="formGestionarCita">
                <input type="hidden" name="cita_id" id="gestionar_cita_id">
                <input type="hidden" name="accion_cita" id="accion_cita">
                <div class="bg-white px-6 pt-6 pb-5 sm:p-7 sm:pb-6">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-5 flex items-center">
                        <i class="fas fa-tasks text-[#0d9488] mr-2"></i> Responder Contraoferta
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Comprador</p>
                            <p class="text-sm font-black text-gray-900"><span id="gestionar_nombre_comprador"></span></p>
                        </div>
                        
                        <div class="bg-teal-50/50 p-3 rounded-xl border border-teal-100">
                            <p class="text-[10px] font-bold text-[#0d9488] uppercase tracking-wider mb-1">Fecha y Hora Propuesta</p>
                            <p class="text-sm font-black text-gray-900" id="gestionar_fecha_actual"></p>
                        </div>

                        <div id="contador_contrapropuestas">
                            <p class="text-xs text-[#0d9488] font-bold bg-teal-50 px-3 py-2 rounded-xl border border-teal-100 inline-flex items-center">
                                <i class="fas fa-exchange-alt mr-2"></i>
                                Contrapropuesta <span id="numero_actual_propuesta"></span> de 4 máximo
                            </p>
                        </div>

                        <div id="limite_alcanzado_msg" class="hidden">
                            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                                <p class="text-sm text-rose-700 font-bold">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <strong>Has alcanzado el límite de contrapropuestas.</strong><br>
                                    Solo puedes aceptar esta propuesta. No es posible hacer más contraofertas.
                                </p>
                            </div>
                        </div>

                        <div id="link_propuesto_box" class="hidden">
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Link Propuesto</p>
                                <p class="text-sm text-[#0d9488] font-black truncate" id="gestionar_link_actual"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-3">¿Qué deseas hacer?</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" onclick="seleccionarAccion('aceptada')" id="btn_aceptar" class="accion-btn bg-emerald-50 text-emerald-700 py-3 rounded-xl text-sm font-black hover:bg-emerald-100 transition border-2 border-transparent">
                                    <i class="fas fa-check mr-1"></i> Aceptar Propuesta
                                </button>
                                <button type="button" onclick="seleccionarAccion('contraoferta')" id="btn_contraoferta" class="accion-btn bg-teal-50 text-[#0d9488] py-3 rounded-xl text-sm font-black hover:bg-teal-100 transition border-2 border-transparent">
                                    <i class="fas fa-exchange-alt mr-1"></i> Hacer Contraoferta
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 italic font-bold">
                                <i class="fas fa-info-circle mr-1"></i>
                                Como vendedor, no puedes rechazar. Solo aceptar o contraofertar.
                            </p>
                        </div>

                        <div id="campos_contraoferta" class="hidden space-y-3">
                            <div>
                                <label class="block text-sm font-black text-gray-700">
                                    Nueva Fecha y Hora <span class="text-rose-500">*</span>
                                </label>
                                <input type="datetime-local" name="nueva_fecha" id="gestionar_fecha" 
                                       onchange="if(typeof cargarMesasGestionar === 'function') cargarMesasGestionar(this.value)"
                                       class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all">
                            </div>

                            <?php if (($rueda_actual['modalidad'] ?? 'virtual') !== 'virtual'): ?>
                            <div id="box_mesa_gestionar">
                                <label class="block text-sm font-black text-gray-700">Mesa / Stand Disponible</label>
                                <div class="relative group mt-2">
                                    <select name="numero_mesa" id="gestionar_mesa_select" 
                                           class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all cursor-pointer shadow-inner">
                                        <option value="">Selecciona una fecha primero...</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#0d9488] group-hover:scale-110 transition-transform">
                                        <i class="fas fa-chair text-xs"></i>
                                    </div>
                                </div>
                                <p id="gestionar_mesa_info" class="text-[10px] text-gray-400 mt-2 font-bold italic">
                                    <i class="fas fa-info-circle mr-1"></i> Solo mesas libres en el horario elegido.
                                </p>
                            </div>
                            <?php endif; ?>

                            <div>
                                <label class="block text-sm font-black text-gray-700">Mensaje (opcional)</label>
                                <textarea name="mensaje" rows="2" placeholder="Explica por qué propones esta nueva fecha..." 
                                          class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all"></textarea>
                            </div>
                        </div>

                        <div id="campos_aceptar" class="hidden">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                                <p class="text-sm text-emerald-700 font-bold">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Al aceptar, la cita quedará confirmada. El link de reunión se agregará después desde "Citas Aceptadas".
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

function abrirModalGestionar(id, nombre, fecha, contador, link) {
    document.getElementById('gestionar_cita_id').value = id;
    document.getElementById('gestionar_nombre_comprador').innerText = nombre;
    document.getElementById('gestionar_fecha_actual').innerText = new Date(fecha).toLocaleString('es-CO', {
        day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    document.getElementById('gestionar_fecha').value = fecha.replace(' ', 'T');
    
    const linkBox = document.getElementById('link_propuesto_box');
    const linkActual = document.getElementById('gestionar_link_actual');
    if (link && link !== 'No proporcionado') {
        linkBox.classList.remove('hidden');
        linkActual.innerText = link;
    } else {
        linkBox.classList.add('hidden');
    }
    
    contadorPropuestas = contador;
    limiteAlcanzado = contador >= 4;
    
    document.getElementById('numero_actual_propuesta').innerText = contador;
    
    const limiteMsg = document.getElementById('limite_alcanzado_msg');
    if (limiteAlcanzado) {
        limiteMsg.classList.remove('hidden');
        document.getElementById('btn_contraoferta').disabled = true;
        document.getElementById('btn_contraoferta').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        limiteMsg.classList.add('hidden');
        document.getElementById('btn_contraoferta').disabled = false;
        document.getElementById('btn_contraoferta').classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    seleccionarAccion(null);
    document.getElementById('formGestionarCita').reset();
    document.getElementById('gestionar_cita_id').value = id;
    document.getElementById('gestionar_fecha').value = fecha.replace(' ', 'T');
    
    document.getElementById('modalGestionarCita').classList.remove('hidden');
}

function cerrarModalGestionar() {
    document.getElementById('modalGestionarCita').classList.add('hidden');
}

function seleccionarAccion(accion) {
    document.querySelectorAll('.accion-btn').forEach(btn => {
        btn.classList.remove('border-[#0d9488]', 'ring-2', 'ring-teal-200');
        btn.classList.add('border-transparent');
    });
    
    document.getElementById('campos_aceptar').classList.add('hidden');
    document.getElementById('campos_contraoferta').classList.add('hidden');
    
    const btnConfirmar = document.getElementById('btn_confirmar');
    
    if (!accion) {
        btnConfirmar.disabled = true;
        btnConfirmar.classList.add('bg-gray-400', 'cursor-not-allowed');
        btnConfirmar.classList.remove('bg-emerald-600', 'hover:bg-emerald-700', 'bg-[#0d9488]', 'hover:bg-[#0f766e]');
        btnConfirmar.innerText = 'Confirmar Acción';
        return;
    }
    
    const btnId = accion === 'aceptada' ? 'btn_aceptar' : 'btn_contraoferta';
    const btnSeleccionado = document.getElementById(btnId);
    btnSeleccionado.classList.remove('border-transparent');
    btnSeleccionado.classList.add('border-[#0d9488]', 'ring-2', 'ring-teal-200');
    
    document.getElementById('accion_cita').value = accion;
    
    if (accion === 'aceptada') {
        document.getElementById('campos_aceptar').classList.remove('hidden');
        btnConfirmar.classList.remove('bg-gray-400', 'cursor-not-allowed', 'bg-[#0d9488]', 'hover:bg-[#0f766e]');
        btnConfirmar.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        btnConfirmar.innerText = 'Confirmar Aceptación';
        btnConfirmar.disabled = false;
    } else if (accion === 'contraoferta') {
        document.getElementById('campos_contraoferta').classList.remove('hidden');
        document.getElementById('gestionar_fecha').required = true;
        btnConfirmar.classList.remove('bg-gray-400', 'cursor-not-allowed', 'bg-emerald-600', 'hover:bg-emerald-700');
        btnConfirmar.classList.add('bg-[#0d9488]', 'hover:bg-[#0f766e]');
        btnConfirmar.innerText = 'Enviar Contraoferta';
        btnConfirmar.disabled = false;
        
        // Si es presencial, cargar mesas al abrir los campos
        if (document.getElementById('gestionar_mesa_select')) {
            cargarMesasGestionar(document.getElementById('gestionar_fecha').value);
        }
    }
}

async function cargarMesasGestionar(fechaHora) {
    const select = document.getElementById('gestionar_mesa_select');
    const infoText = document.getElementById('gestionar_mesa_info');
    const ruedaId = "<?php echo $rueda_actual['id'] ?? ''; ?>";

    if (!select || !ruedaId || !fechaHora) return;

    select.innerHTML = '<option value="">Cargando mesas disponibles...</option>';
    select.disabled = true;

    try {
        const encodedFecha = encodeURIComponent(fechaHora.replace('T', ' '));
        const response = await fetch(`index.php?controlador=api/reunion&accion=getMesasDisponibles&rueda_id=${ruedaId}&fecha_hora=${encodedFecha}`);
        const result = await response.json();

        select.innerHTML = '<option value="">-- Seleccionar Mesa --</option>';
        
        if (result.status === 'success' && result.data && result.data.mesas && result.data.mesas.length > 0) {
            result.data.mesas.forEach(mesa => {
                const opt = document.createElement('option');
                opt.value = mesa;
                opt.textContent = 'Mesa ' + mesa;
                select.appendChild(opt);
            });
            select.disabled = false;
            infoText.innerHTML = `<i class="fas fa-check-circle text-emerald-500 mr-1"></i> ${result.data.mesas.length} mesas libres encontradas.`;
        } else {
            select.innerHTML = '<option value="">No hay mesas disponibles en este horario</option>';
            infoText.innerHTML = '<i class="fas fa-times-circle text-rose-500 mr-1"></i> Todo ocupado. Intenta con otra hora.';
            select.disabled = true;
        }
    } catch (error) {
        console.error("Error al cargar mesas:", error);
        select.innerHTML = '<option value="">Error al cargar mesas</option>';
    }
}
</script>

<!-- Reutilizamos el modal de encuesta si es necesario -->
<script>
function abrirModalEncuesta(reunionId, razon_social) {
    if (document.getElementById('encuesta_reunion_id')) {
        document.getElementById('encuesta_reunion_id').value = reunionId;
    }
    if (document.getElementById('encuesta_nombre_empresa')) {
        document.getElementById('encuesta_nombre_empresa').innerText = razon_social;
    }
    if (document.getElementById('modalEncuesta')) {
        document.getElementById('modalEncuesta').classList.remove('hidden');
    }
}

function abrirModalResultado(citaId, nombreComprador) {
    if (document.getElementById('resultado_cita_id')) {
        document.getElementById('resultado_cita_id').value = citaId;
    }
    if (document.getElementById('nombre_comprador_resultado')) {
        document.getElementById('nombre_comprador_resultado').innerText = nombreComprador;
    }
    if (document.getElementById('modalResultado')) {
        document.getElementById('modalResultado').classList.remove('hidden');
    }
}
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

</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<!-- Modal de Encuesta de Satisfacción -->
<div id="modalEncuesta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalEncuesta()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=vendedor&accion=agregarLinkReunion" method="POST" id="formEncuesta">
                <input type="hidden" name="reunion_id" id="encuesta_reunion_id">
                <input type="hidden" name="calificacion" id="input_calificacion" value="0">
                <input type="hidden" name="expectativa_cumplida" id="input_expectativa" value="inmediato">
                
                <div class="bg-white px-8 pt-8 pb-6">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-poll-h text-[#0d9488] mr-2"></i> Encuesta de Satisfacción
                    </h3>
                    <!-- 1. Bloque de Información de la Reunión -->
                    <div class="bg-teal-50/50 rounded-2xl p-5 mb-8 border border-teal-100">
                        <h4 class="text-[#0d9488] font-black text-sm mb-3 flex items-center">
                            <i class="fas fa-handshake mr-2"></i> Información de la Reunión
                        </h4>
                        <div class="grid grid-cols-2 gap-y-3 text-xs">
                            <div>
                                <p class="text-gray-400 mb-0.5 font-black uppercase tracking-wider text-[10px]">Rueda:</p>
                                <p id="info_rueda" class="font-black text-gray-800">Cargando...</p>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5 font-black uppercase tracking-wider text-[10px]">Fecha:</p>
                                <p id="info_fecha" class="font-black text-gray-800">Cargando...</p>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5 font-black uppercase tracking-wider text-[10px]">Tu rol:</p>
                                <p class="font-black text-[#0d9488]">Vendedor</p>
                            </div>
                            <div>
                                <p class="text-gray-400 mb-0.5 font-black uppercase tracking-wider text-[10px]">Contraparte:</p>
                                <p id="info_contraparte" class="font-black text-gray-800">Cargando...</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Calificación por estrellas -->
                    <div class="mb-8 text-center">
                        <label class="block text-sm font-black text-gray-700 mb-4">1. ¿Cómo calificas la reunión? <span class="text-rose-500">*</span></label>
                        <div class="flex justify-center space-x-2 mb-2">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <button type="button" onclick="setRating(<?php echo $i; ?>)" onmouseover="hoverRating(<?php echo $i; ?>)" onmouseout="resetRating()" 
                                        class="star-btn text-4xl text-gray-200 transition-all transform hover:scale-110 focus:outline-none">
                                    <i class="fas fa-star"></i>
                                </button>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xs font-black text-gray-400 mt-2" id="rating_text">Selecciona de 1 a 5 estrellas</p>
                    </div>

                    <!-- 3. Expectativas (Tarjetas) -->
                    <div class="mb-8">
                        <label class="block text-sm font-black text-gray-700 mb-4">2. ¿Se cumplieron tus expectativas?</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" onclick="setExpectativa('inmediato')" id="exp_inmediato"
                                    class="exp-card border-2 border-gray-100 rounded-xl p-3 flex flex-col items-center justify-center transition-all hover:bg-teal-50">
                                <i class="fas fa-check-circle text-emerald-500 mb-2 text-lg"></i>
                                <span class="text-[10px] font-black text-gray-600 text-center leading-tight">Sí, inmediatamente</span>
                            </button>
                            <button type="button" onclick="setExpectativa('mediano_plazo')" id="exp_mediano_plazo"
                                    class="exp-card border-2 border-gray-100 rounded-xl p-3 flex flex-col items-center justify-center transition-all hover:bg-teal-50">
                                <i class="fas fa-clock text-[#0d9488] mb-2 text-lg"></i>
                                <span class="text-[10px] font-black text-gray-600 text-center leading-tight">A mediano plazo</span>
                            </button>
                            <button type="button" onclick="setExpectativa('ninguno')" id="exp_ninguno"
                                    class="exp-card border-2 border-gray-100 rounded-xl p-3 flex flex-col items-center justify-center transition-all hover:bg-rose-50">
                                <i class="fas fa-times-circle text-rose-500 mb-2 text-lg"></i>
                                <span class="text-[10px] font-black text-gray-600 text-center leading-tight">No se cumplieron</span>
                            </button>
                        </div>
                    </div>

                    <!-- 4. Valor Estimado -->
                    <div class="mb-8">
                        <label class="block text-sm font-black text-gray-700 mb-2">3. ¿Cuál es el valor estimado del negocio proyectado?</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm font-black">$</span>
                            </div>
                            <input type="number" name="posibilidad_negocio" step="0.01" value="0.00"
                                   class="block w-full pl-8 pr-12 py-3 border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] bg-gray-50 font-bold">
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 font-black italic">Deja en 0 si no aplica</p>
                    </div>

                    <!-- 5. Toggles -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <div class="flex items-center">
                                <div class="bg-teal-100 text-[#0d9488] p-2 rounded-lg mr-3">
                                    <i class="fas fa-handshake text-xs"></i>
                                </div>
                                <span class="text-xs font-black text-gray-700">¿Consideras que fue una cita efectiva?</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="efectividad_cita" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0d9488]"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <div class="flex items-center">
                                <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg mr-3">
                                    <i class="fas fa-users text-xs"></i>
                                </div>
                                <span class="text-xs font-black text-gray-700">¿Asistieron ambas partes a la reunión?</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="asistencia_completa" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <!-- 6. Comentarios -->
                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-3">6. Comentarios adicionales</label>
                        <textarea name="comentario" rows="3" 
                                  class="w-full px-4 py-3 border-gray-200 rounded-2xl text-xs focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] placeholder-gray-400 bg-gray-50"
                                  placeholder="Escribe tus comentarios aquí..."></textarea>
                    </div>
                </div>

                <!-- Footer de Botones -->
                <div class="bg-gray-50 px-8 py-6 flex space-x-4 rounded-b-[2.5rem]">
                    <button type="button" onclick="validarYEnviar()" class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-black rounded-full text-white bg-[#0d9488] hover:bg-[#0f766e] transition shadow-md shadow-teal-500/10 focus:outline-none">
                        <i class="fas fa-save mr-2"></i> Guardar Encuesta
                    </button>
                    <button type="button" onclick="cerrarModalEncuesta()" class="px-6 py-3 border border-gray-200 text-sm font-black rounded-full text-gray-600 bg-white hover:bg-gray-50 transition focus:outline-none flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .star-btn.active { color: #facc15; }
    .star-btn.hover { color: #fbbf24; opacity: 0.7; }
    .exp-card.active { border-color: #0d9488; background-color: #f0fdfa; transform: translateY(-2px); }
</style>

<script>
let currentRating = 0;

function abrirModalEncuesta(id, nombre, rueda = 'Rueda ejemplo', fecha = 'N/A') {
    document.getElementById('encuesta_reunion_id').value = id;
    document.getElementById('info_rueda').innerText = rueda;
    document.getElementById('info_fecha').innerText = fecha;
    document.getElementById('info_contraparte').innerText = nombre;
    
    // Resetear form a 0 para obligar a marcar
    setRating(0);
    setExpectativa('inmediato');
    
    document.getElementById('modalEncuesta').classList.remove('hidden');
}

function cerrarModalEncuesta() {
    document.getElementById('modalEncuesta').classList.add('hidden');
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
        text.innerText = 'Selecciona de 1 a 5 estrellas';
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
    const calif = document.getElementById('input_calificacion').value;
    if (parseInt(calif) < 1) {
        alert("Por favor, selecciona una calificación (estrellas) antes de enviar.");
        return;
    }
    document.getElementById('formEncuesta').submit();
}

function abrirModalLink(citaId) {
    document.getElementById('link_cita_id').value = citaId;
    document.getElementById('link_input').value = '';
    document.getElementById('modalAgregarLink').classList.remove('hidden');
}

function cerrarModalLink() {
    document.getElementById('modalAgregarLink').classList.add('hidden');
}
</script>

<!-- Modal para Agregar Link de Reunión -->
<div id="modalAgregarLink" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="cerrarModalLink()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=vendedor&accion=agregarLinkReunion" method="POST">
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

<!-- Modal para registrar resultado de la reunión -->
<div id="modalResultado" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modalResultado').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 animate-modal">
            <form action="index.php?controlador=vendedor&accion=registrarResultado" method="POST">
                <input type="hidden" name="cita_id" id="resultado_cita_id">
                <div class="bg-white px-6 pt-6 pb-5 sm:p-7 sm:pb-6">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-5 flex items-center">
                        <i class="fas fa-chart-line text-[#0d9488] mr-2"></i> Resultado de Reunión
                    </h3>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 mb-5">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Comprador</p>
                        <p class="text-sm font-black text-gray-900"><span id="nombre_comprador_resultado"></span></p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-black text-gray-700">Monto aproximado del Negocio ($)</label>
                            <input type="number" name="monto_negocio" required class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all font-bold" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700">Notas y Acuerdos</label>
                            <textarea name="notas_resultado" rows="4" required class="mt-2 block w-full bg-gray-50 border border-gray-200 rounded-xl shadow-sm p-3 text-sm focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-[#0d9488] transition-all" placeholder="Resumen de la reunión y próximos pasos..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse rounded-b-[2.5rem] gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-md px-5 py-2.5 bg-[#0d9488] hover:bg-[#0f766e] text-sm font-black text-white transition-all duration-300 sm:ml-3 sm:w-auto">Finalizar Reunión</button>
                    <button type="button" onclick="document.getElementById('modalResultado').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-black text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto transition-all duration-200">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
