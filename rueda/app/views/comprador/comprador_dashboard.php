<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        <?php
            $nombreSesion = $_SESSION['nombreUsuario'] ?? 'Usuario';
            $razon_social = $empresa['razon_social'] ?? 'Empresa';
        ?>
        
        <!-- BIENVENIDA Y ACCIONES RÁPIDAS -->
        <div class="bg-gradient-to-r from-[#00a2ff] via-[#4dbfff] to-[#008ae0] rounded-3xl p-6 sm:p-8 shadow-[0_10px_30px_rgba(0,162,255,0.15)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <!-- Círculos decorativos de fondo -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full backdrop-blur-sm">Panel de Control</span>
                <h1 class="text-3xl sm:text-4xl font-black mt-3 tracking-tight">Centro de Mando: Comprador</h1>
                <p class="text-white/90 mt-2 flex items-center text-sm sm:text-base font-medium">
                    <i class="fas fa-building mr-2 text-white/80"></i> <?php echo htmlspecialchars($razon_social); ?> 
                    <span class="mx-3 text-white/50">|</span>
                    <i class="fas fa-user mr-2 text-white/80"></i> <?php echo htmlspecialchars($nombreSesion); ?>
                </p>
            </div>
            <div class="relative z-10 flex flex-wrap gap-3.5">
                <a href="index.php?controlador=comprador&accion=verReuniones" class="bg-white text-[#00a2ff] hover:bg-sky-50 px-6 py-3 rounded-full font-bold text-sm shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.2)] hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-xs"></i> Mis Reuniones
                </a>
                <a href="index.php?controlador=comprador&accion=verEncuestas" class="bg-white/15 hover:bg-white/25 text-white border border-white/20 px-6 py-3 rounded-full font-bold text-sm shadow-sm hover:-translate-y-0.5 transform transition-all duration-300 flex items-center gap-2 backdrop-blur-sm">
                    <i class="fas fa-poll-h text-xs"></i> Encuestas
                </a>
            </div>
        </div>

        <!-- NOTIFICACIONES -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'perfil_comprador_activado'): ?>
            <div class="bg-blue-50 border-2 border-blue-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">¡Perfil de Comprador Restablecido!</h3>
                    <p class="text-xs text-gray-500 mt-1">Has regresado exitosamente al perfil de Comprador. Ahora puedes buscar ofertas de productos y participar en todas las ruedas de negocios de manera gratuita.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'inscribete_primero'): ?>
            <div class="bg-amber-50 border-2 border-amber-200 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-amber-900">¡Inscríbete a una rueda para acceder al Mercado de Ofertas!</h3>
                    <p class="text-xs text-amber-700 mt-1">Selecciona una de las ruedas activas en la sección de abajo y haz clic en "Inscribirme" para comenzar a interactuar con los vendedores.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'inscripcion_pendiente_revision'): ?>
            <div class="bg-sky-50 border-2 border-sky-200 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-sky-100 text-sky-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-sky-900">Tu inscripción está en revisión</h3>
                    <p class="text-xs text-sky-700 mt-1">El administrador revisará y aprobará tu participación en la rueda en breve. Una vez aprobada, tendrás acceso completo al catálogo de ofertas.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'inscripcion_enviada'): ?>
            <div class="bg-emerald-50 border-2 border-emerald-200 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-emerald-900">¡Inscripción enviada exitosamente!</h3>
                    <p class="text-xs text-emerald-700 mt-1">Tu solicitud de participación ha sido registrada. Te notificaremos cuando el administrador la apruebe.</p>
                </div>
            </div>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'ya_inscrito'): ?>
            <div class="bg-blue-50 border-2 border-blue-200 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-blue-900">Ya estás inscrito en esta rueda</h3>
                    <p class="text-xs text-blue-700 mt-1">Tu empresa ya cuenta con un registro para esta rueda de negocios.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN DE KPIs: RESUMEN DE ALTO NIVEL -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Citas Totales -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-sky-50 text-[#00a2ff] rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Citas Totales</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['total_citas']; ?></p>
                </div>
            </div>
            
            <!-- Realizadas -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-emerald-50 text-emerald-500 rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Realizadas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['citas_realizadas']; ?></p>
                </div>
            </div>

            <!-- Por Gestionar -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group relative">
                <?php if (($kpis['citas_por_gestionar'] ?? 0) > 0): ?>
                    <span class="absolute -top-2 -right-2 flex h-6 w-6">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-6 w-6 bg-rose-500 text-white text-[10px] font-black items-center justify-center"><?php echo $kpis['citas_por_gestionar']; ?></span>
                    </span>
                <?php endif; ?>
                <div class="p-4 <?php echo ($kpis['citas_por_gestionar'] ?? 0) > 0 ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500'; ?> rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bell-exclamation text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Por Gestionar</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['citas_por_gestionar'] ?? 0; ?></p>
                </div>
            </div>

            <!-- Mis Demandas -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-indigo-50 text-indigo-500 rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mis Demandas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['total_demandas']; ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- COLUMNA PRINCIPAL: RUEDAS ACTIVAS (RESUMEN) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- SECCIÓN DE CITAS POR GESTIONAR (NUEVA ALERT PARA REAL-TIME) -->
                <?php if (($kpis['citas_por_gestionar'] ?? 0) > 0): ?>
                    <div class="bg-gradient-to-br from-rose-50 to-white border-2 border-rose-100 p-6 rounded-3xl shadow-sm mb-8 animate-modal relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-500/5 rounded-full blur-2xl"></div>
                        <div class="flex items-center mb-4 relative z-10">
                            <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl mr-4 shrink-0 animate-bounce">
                                <i class="fas fa-bell text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Acciones Requeridas en Citas</h2>
                                <p class="text-rose-600 text-xs font-bold uppercase tracking-wider mt-1">Tienes propuestas o contraofertas por responder</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 relative z-10 font-medium">Tienes <strong><?php echo $kpis['citas_por_gestionar']; ?></strong> cita(s) con contraofertas de vendedores que esperan tu respuesta.</p>
                        
                        <a href="index.php?controlador=comprador&accion=verReuniones" class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:-translate-y-0.5 transition-all duration-300 relative z-10">
                            Gestionar Ahora <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- SECCIÓN DE TRAZABILIDAD PENDIENTE -->
                <?php if (!empty($trazabilidad_pendientes)): ?>
                    <div class="bg-purple-50/55 border border-purple-100 p-6 rounded-3xl shadow-sm mb-8">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-purple-100 text-purple-700 rounded-xl mr-3">
                                <i class="fas fa-history text-xl"></i>
                            </div>
                            <h2 class="text-xl font-extrabold text-gray-900">Seguimiento de Trazabilidad</h2>
                        </div>
                        <p class="text-purple-700 text-sm mb-5">Es momento de actualizar el estado de tus negocios. Ayúdanos a medir el impacto real de las ruedas de negocios.</p>
                        
                        <div class="space-y-3.5">
                            <?php foreach ($trazabilidad_pendientes as $tp): ?>
                                <div class="bg-white p-4 rounded-2xl border border-purple-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 uppercase">
                                                <?php echo $tp['tipo'] === '3_meses' ? '3 Meses' : '6 Meses'; ?>
                                            </span>
                                            <p class="font-extrabold text-gray-800 text-sm"><?php echo htmlspecialchars($tp['nombre_contraparte'] ?? 'Empresa'); ?></p>
                                        </div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mt-1.5"><?php echo htmlspecialchars($tp['tituloRueda']); ?> | Cita: <?php echo date('d/m/Y', strtotime($tp['fecha_reunion'])); ?></p>
                                    </div>
                                    <button onclick="abrirModalEncuesta(
                                        <?php echo $tp['reunionId']; ?>, 
                                        '<?php echo htmlspecialchars($tp['nombre_contraparte'] ?? 'Empresa'); ?>', 
                                        '<?php echo htmlspecialchars($tp['tituloRueda']); ?>',
                                        '<?php echo date('d/m/Y H:i', strtotime($tp['fecha_reunion'])); ?>',
                                        'trazabilidad_<?php echo $tp['tipo']; ?>',
                                        <?php echo $tp['id']; ?>
                                    )" class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-5 py-2.5 rounded-full font-bold transition-colors shadow-md shadow-purple-500/10">
                                        Actualizar
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- SECCIÓN DE ENCUESTAS PENDIENTES -->
                <?php if (!empty($encuestas_pendientes)): ?>
                    <div class="bg-sky-50/50 border border-sky-100 p-6 rounded-3xl shadow-sm mb-8">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-sky-100 text-sky-700 rounded-xl mr-3">
                                <i class="fas fa-poll-h text-xl"></i>
                            </div>
                            <h2 class="text-xl font-extrabold text-gray-900">Encuestas Pendientes</h2>
                        </div>
                        <p class="text-sky-700 text-sm mb-5">Tienes citas finalizadas que aún no has calificado. Tu opinión es fundamental para el ecosistema AGECSO.</p>

                        <div class="space-y-3.5">
                            <?php foreach ($encuestas_pendientes as $ep): ?>
                                <div class="bg-white p-4 rounded-2xl border border-sky-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div>
                                        <p class="font-extrabold text-gray-800 text-sm"><?php echo htmlspecialchars($ep['nombre_vendedor']); ?></p>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mt-1.5"><?php echo htmlspecialchars($ep['tituloRueda']); ?> | <?php echo date('d/m/Y H:i', strtotime($ep['fechaHora'])); ?></p>
                                    </div>
                                    <a href="index.php?controlador=comprador&accion=verEncuestas&reunion_id=<?php echo $ep['id']; ?>&nombre=<?php echo urlencode($ep['nombre_vendedor']); ?>&rueda=<?php echo urlencode($ep['tituloRueda']); ?>&fecha=<?php echo urlencode(date('d/m/Y H:i', strtotime($ep['fechaHora']))); ?>"
                                       class="bg-[#00a2ff] hover:bg-[#008ae0] text-white text-xs px-5 py-2.5 rounded-full font-bold transition-colors shadow-md shadow-sky-500/10 text-center">
                                        Diligenciar
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center tracking-tight">
                        <i class="fas fa-layer-group mr-2.5 text-[#00a2ff]"></i> Participación en Ruedas
                    </h2>
                </div>
                
                <?php if (empty($ruedas_activas)): ?>
                    <div class="bg-sky-50/40 border-2 border-dashed border-sky-200 rounded-3xl p-10 text-center">
                        <i class="fas fa-calendar-times text-sky-300 text-4xl mb-4"></i>
                        <p class="text-sky-800 font-extrabold text-lg">No estás participando en ruedas activas.</p>
                        <p class="text-sm text-sky-600 mt-2 max-w-sm mx-auto">Explora las ruedas disponibles en el panel de "Nuevas Oportunidades" e inscríbete para comenzar a agendar.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-6">
                        <?php foreach ($ruedas_activas as $r): ?>
                            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300">
                                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-50 bg-gradient-to-r from-gray-50/50 to-white">
                                    <div>
                                        <h3 class="font-extrabold text-gray-900 text-lg"><?php echo htmlspecialchars($r['tituloRueda']); ?></h3>
                                        <div class="flex items-center mt-2.5 flex-wrap gap-2">
                                            <span class="text-[10px] bg-sky-50 text-[#00a2ff] border border-sky-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider"><?php echo htmlspecialchars($r['estadoInscripcion']); ?></span>
                                            <?php if (($r['modalidad'] ?? 'virtual') === 'virtual'): ?>
                                                <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider" title="Virtual"><i class="fas fa-video mr-1"></i>Virtual</span>
                                            <?php else: ?>
                                                <span class="text-[10px] bg-orange-50 text-orange-700 border border-orange-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider" title="Presencial: <?php echo htmlspecialchars($r['ubicacion'] ?? ''); ?>"><i class="fas fa-map-marker-alt mr-1"></i>Presencial</span>
                                            <?php endif; ?>
                                            <span class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider" title="Rueda de Negocio"><i class="far fa-calendar-alt mr-1 text-[#00a2ff]"></i>Evento: <?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?> al <?php echo date('d/m/Y', strtotime($r['fechaFin'])); ?></span>
                                            <?php if (!empty($r['fechaInscripcionInicio']) && !empty($r['fechaInscripcionFin'])): ?>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider" title="Periodo de Registro"><i class="fas fa-edit mr-1 text-amber-500"></i>Reg: <?php echo date('d/m/Y', strtotime($r['fechaInscripcionInicio'])); ?> al <?php echo date('d/m/Y', strtotime($r['fechaInscripcionFin'])); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="index.php?controlador=comprador&accion=verParticipantes&id=<?php echo $r['id']; ?>" class="text-xs bg-sky-50 text-[#00a2ff] hover:bg-[#00a2ff] hover:text-white px-4 py-2.5 rounded-full font-extrabold transition-all duration-300 shadow-sm shadow-sky-500/5 flex items-center gap-1.5">
                                            <i class="fas fa-store text-[10px]"></i> Mercado
                                        </a>
                                        <a href="index.php?controlador=comprador&accion=apartarMesa&id=<?php echo $r['id']; ?>" class="text-xs bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white px-4 py-2.5 rounded-full font-extrabold transition-all duration-300 shadow-sm shadow-emerald-500/5 flex items-center gap-1.5">
                                            <i class="fas fa-chair text-[10px]"></i> Apartar Mesa
                                        </a>
                                        <a href="index.php?controlador=comprador&accion=verReuniones" class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white px-4 py-2.5 rounded-full font-extrabold transition-all duration-300 shadow-sm shadow-indigo-500/5 flex items-center gap-1.5">
                                            <i class="far fa-calendar-check text-[10px]"></i> Citas
                                        </a>
                                    </div>
                                </div>
                                <div class="p-5 bg-gray-50/30 grid grid-cols-2 text-center divide-x divide-gray-100">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-widest">Requerimientos</p>
                                        <p class="text-xl font-black text-gray-700 mt-1"><?php echo count($demandas_por_rueda[$r['id']] ?? []); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-widest">Solicitudes</p>
                                        <p class="text-xl font-black text-gray-700 mt-1"><?php echo count($citas_por_rueda[$r['id']] ?? []); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- COLUMNA LATERAL: ACCIONES RÁPIDAS Y RUEDAS DISPONIBLES -->
            <div class="space-y-10">
                <!-- Ruedas Disponibles -->
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center tracking-tight">
                        <i class="fas fa-plus-circle mr-2 text-emerald-500"></i> Nuevas Oportunidades
                    </h2>
                    <div class="space-y-5">
                        <?php foreach ($ruedas as $rd): ?>
                            <?php if (!isset($mis_inscripciones[$rd['id']])): ?>
                                <?php
                                    $hoy = date('Y-m-d', strtotime(SYSTEM_TIME));
                                    $inicioInsc = !empty($rd['fechaInscripcionInicio']) ? $rd['fechaInscripcionInicio'] : null;
                                    $finInsc = !empty($rd['fechaInscripcionFin']) ? $rd['fechaInscripcionFin'] : null;

                                    $inscripcionAbierta = true;
                                    $mensajeInscripcion = '';
                                    $badgeEstado = 'Inscripciones Abiertas';
                                    $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-100/50';

                                    if ($inicioInsc && $finInsc) {
                                        if ($hoy < $inicioInsc) {
                                            $inscripcionAbierta = false;
                                            $badgeEstado = 'Inscripciones Próximas';
                                            $badgeColor = 'bg-blue-50 text-blue-600 border-blue-100/50';
                                            $mensajeInscripcion = 'Inscripciones inician el ' . date('d/m/Y', strtotime($inicioInsc));
                                        } elseif ($hoy > $finInsc) {
                                            $inscripcionAbierta = false;
                                            $badgeEstado = 'Inscripciones Cerradas';
                                            $badgeColor = 'bg-rose-50 text-rose-600 border-rose-100/50';
                                            $mensajeInscripcion = 'Inscripciones cerradas';
                                        } else {
                                            $mensajeInscripcion = 'Inscripciones cierran el ' . date('d/m/Y', strtotime($finInsc));
                                        }
                                    } else {
                                        $mensajeInscripcion = 'Inscripciones abiertas';
                                    }
                                ?>
                                <div class="bg-gradient-to-br from-white to-gray-50/50 p-5 rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] transition-all duration-300">
                                    <h4 class="font-extrabold text-gray-800 text-sm tracking-tight"><?php echo htmlspecialchars($rd['tituloRueda']); ?></h4>
                                    <p class="text-[11px] text-gray-500 mt-1.5 leading-relaxed line-clamp-2"><?php echo htmlspecialchars($rd['descripcionRueda']); ?></p>
                                    
                                    <!-- Fechas de Inscripción Informativas -->
                                    <div class="mt-3.5 bg-gray-50 p-2.5 rounded-xl border border-gray-100 text-[11px] text-gray-500 font-semibold space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span>Rueda de Negocio:</span>
                                            <span class="font-bold text-gray-700"><?php echo date('d/m/Y', strtotime($rd['fechaInicio'])); ?></span>
                                        </div>
                                        <?php if ($inicioInsc && $finInsc): ?>
                                            <div class="flex items-center justify-between text-[10px]">
                                                <span>Inscripciones:</span>
                                                <span class="font-bold text-gray-600"><?php echo date('d/m/Y', strtotime($inicioInsc)); ?> al <?php echo date('d/m/Y', strtotime($finInsc)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-4 flex items-center gap-1.5 flex-wrap">
                                        <?php if (($rd['modalidad'] ?? 'virtual') === 'virtual'): ?>
                                            <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-100/50 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider" title="Virtual"><i class="fas fa-video mr-1 text-[9px]"></i>Virtual</span>
                                        <?php else: ?>
                                            <span class="text-[10px] bg-orange-50 text-orange-700 border border-orange-100/50 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider" title="Presencial: <?php echo htmlspecialchars($rd['ubicacion'] ?? ''); ?>"><i class="fas fa-map-marker-alt mr-1 text-[9px]"></i>Presencial</span>
                                        <?php endif; ?>
                                        <span class="text-[10px] font-bold border px-2 py-0.5 rounded-full uppercase tracking-wider <?php echo $badgeColor; ?>"><?php echo $badgeEstado; ?></span>
                                    </div>
                                    <div class="mt-4">
                                        <?php if ($inscripcionAbierta): ?>
                                            <a href="index.php?controlador=comprador&accion=inscribirseRueda&id=<?php echo $rd['id']; ?>" class="w-full block text-center text-xs bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-full font-extrabold shadow-md shadow-emerald-500/10 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300">
                                                <i class="fas fa-user-plus mr-1"></i> Inscribirse
                                            </a>
                                        <?php else: ?>
                                            <button disabled class="w-full block text-center text-xs bg-gray-200 text-gray-400 py-2.5 rounded-full font-extrabold cursor-not-allowed border border-gray-300/30" title="<?php echo htmlspecialchars($mensajeInscripcion); ?>">
                                                <i class="fas fa-lock mr-1"></i> No Disponible
                                            </button>
                                            <p class="text-[10px] text-center text-gray-400 mt-2 font-semibold italic"><?php echo htmlspecialchars($mensajeInscripcion); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Encuesta de Satisfacción -->
<div id="modalEncuesta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalEncuesta').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form action="index.php?controlador=comprador&accion=registrarEncuesta" method="POST">
                <input type="hidden" name="reunion_id" id="encuesta_reunion_id">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-7 sm:pb-5">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-4 flex items-center"><i class="fas fa-poll-h text-amber-500 mr-2"></i> Encuesta de Satisfacción</h3>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 bg-gray-50 px-3.5 py-2.5 rounded-2xl border border-gray-100">Califica al vendedor: <span id="encuesta_nombre_empresa" class="font-extrabold text-gray-800"></span></p>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Calificación General (1 a 5)</label>
                            <select name="calificacion" required class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#00a2ff] focus:border-[#00a2ff]">
                                <option value="5">5 - Excelente</option>
                                <option value="4">4 - Muy buena</option>
                                <option value="3">3 - Buena</option>
                                <option value="2">2 - Regular</option>
                                <option value="1">1 - Mala</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Expectativa de Negocio</label>
                            <select name="expectativa_cumplida" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#00a2ff] focus:border-[#00a2ff]">
                                <option value="inmediato">Inmediato</option>
                                <option value="corto_plazo">Corto Plazo</option>
                                <option value="mediano_plazo">Mediano Plazo</option>
                                <option value="ninguno">Ninguno</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">¿Fue una cita efectiva?</label>
                            <div class="mt-2 flex space-x-6">
                                <label class="flex items-center text-sm font-medium text-gray-700 cursor-pointer"><input type="radio" name="efectividad_cita" value="1" checked class="mr-2 text-[#00a2ff] focus:ring-[#00a2ff]"> Sí</label>
                                <label class="flex items-center text-sm font-medium text-gray-700 cursor-pointer"><input type="radio" name="efectividad_cita" value="0" class="mr-2 text-[#00a2ff] focus:ring-[#00a2ff]"> No</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Comentarios</label>
                            <textarea name="comentario" rows="3" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#00a2ff] focus:border-[#00a2ff]" placeholder="Escribe tus comentarios aquí..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse rounded-b-3xl gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-md px-5 py-2.5 bg-blue-600 text-sm font-extrabold text-white hover:bg-blue-700 sm:ml-3 sm:w-auto transition duration-200">Enviar Encuesta</button>
                    <button type="button" onclick="document.getElementById('modalEncuesta').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-extrabold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto transition duration-200">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/modal_encuesta.php'; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
