<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="space-y-10">
        <?php
            $nombreSesion = $_SESSION['nombreUsuario'] ?? 'Usuario';
            $razon_social = $empresa['razon_social'] ?? 'Empresa';
        ?>
        
        <!-- BIENVENIDA Y ACCIONES RÁPIDAS -->
        <div class="bg-gradient-to-r from-[#0d9488] via-[#14b8a6] to-[#0f766e] rounded-[2.5rem] p-8 sm:p-10 shadow-[0_15px_40px_rgba(13,148,136,0.2)] text-white relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <!-- Círculos decorativos de fondo -->
            <div class="absolute -right-10 -top-10 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    Panel Vendedor Activo
                </div>
                <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight">
                    ¡Hola, <?php echo explode(' ', htmlspecialchars($nombreSesion))[0]; ?>! 
                </h1>
                <p class="text-white/80 mt-4 flex items-center text-base sm:text-lg font-bold">
                    <i class="fas fa-building mr-2.5 opacity-70"></i> <?php echo htmlspecialchars($razon_social); ?>
                </p>
            </div>
            
            <div class="relative z-10 grid grid-cols-2 gap-4">
                <a href="index.php?controlador=vendedor&accion=verReuniones" class="bg-white/10 hover:bg-white/20 backdrop-blur-lg border border-white/30 p-4 rounded-3xl transition-all duration-300 group">
                    <i class="fas fa-calendar-alt text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                    <p class="text-xs font-black uppercase tracking-wider">Mis Citas</p>
                </a>
                <button onclick="abrirModalMembresia()" class="bg-white/10 hover:bg-white/20 backdrop-blur-lg border border-white/30 p-4 rounded-3xl transition-all duration-300 group">
                    <i class="fas fa-crown text-2xl mb-2 group-hover:scale-110 transition-transform <?php echo ($empresa['membresia_estado'] ?? 'inactivo') === 'activo' ? 'text-amber-300' : ''; ?>"></i>
                    <p class="text-xs font-black uppercase tracking-wider">Membresía</p>
                </button>
            </div>
        </div>

        <!-- SECCIÓN: ¿QUÉ DESEAS HACER HOY? (INTERACTIVA VENDEDOR) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="index.php?controlador=vendedor&accion=seleccionarRuedaCompradores" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(13,148,136,0.1)] transition-all duration-500 flex flex-col items-center text-center transform hover:-translate-y-2">
                <h3 class="text-xl font-black text-gray-900 mb-2">Buscar Clientes</h3>
                <p class="text-gray-500 text-sm font-medium leading-relaxed">Encuentra compradores interesados y solicita reuniones para presentar tus ofertas.</p>
                <div class="mt-6 flex items-center text-[#0d9488] font-black text-xs uppercase tracking-widest">
                    Ver Compradores <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <a href="index.php?controlador=vendedor&accion=seleccionarRuedaMisOfertas" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(20,184,166,0.1)] transition-all duration-500 flex flex-col items-center text-center transform hover:-translate-y-2">
                <h3 class="text-xl font-black text-gray-900 mb-2">Mis Productos</h3>
                <p class="text-gray-500 text-sm font-medium leading-relaxed">Publica y gestiona lo que ofreces para que los compradores puedan encontrarte.</p>
                <div class="mt-6 flex items-center text-emerald-500 font-black text-xs uppercase tracking-widest">
                    Gestionar Ofertas <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <a href="index.php?controlador=vendedor&accion=seleccionarRuedaDemandas" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(99,102,241,0.1)] transition-all duration-500 flex flex-col items-center text-center transform hover:-translate-y-2">
                <h3 class="text-xl font-black text-gray-900 mb-2">Ver Requerimientos</h3>
                <p class="text-gray-500 text-sm font-medium leading-relaxed">Explora qué necesitan comprar las empresas hoy y ofrece tus soluciones directamente.</p>
                <div class="mt-6 flex items-center text-indigo-500 font-black text-xs uppercase tracking-widest">
                    Explorar Demandas <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>
        </div>

        <!-- GUÍA RÁPIDA (PARA USUARIOS QUE SE SIENTEN PERDIDOS) -->
        <div class="bg-indigo-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="relative z-10">
                <h2 class="text-2xl font-black mb-6 flex items-center gap-3">
                    <i class="fas fa-magic text-indigo-300"></i> ¿Cómo vender más en la rueda?
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 font-black text-indigo-200 border border-white/20">1</div>
                        <div>
                            <p class="font-black text-sm uppercase tracking-wider text-indigo-200 mb-1">Paso 1: Ofrece</p>
                            <p class="text-xs text-indigo-100/70 leading-relaxed font-medium">Ve a <b>"Mis Productos"</b> y publica lo que vendes. Si no publicas, los compradores no podrán encontrarte.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 font-black text-indigo-200 border border-white/20">2</div>
                        <div>
                            <p class="font-black text-sm uppercase tracking-wider text-indigo-200 mb-1">Paso 2: Busca</p>
                            <p class="text-xs text-indigo-100/70 leading-relaxed font-medium">Haz clic en <b>"Buscar Clientes"</b> para ver a los compradores y solicitarles una cita.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 font-black text-indigo-200 border border-white/20">3</div>
                        <div>
                            <p class="font-black text-sm uppercase tracking-wider text-indigo-200 mb-1">Paso 3: Prepárate</p>
                            <p class="text-xs text-indigo-100/70 leading-relaxed font-medium">Revisa <b>"Mis Citas"</b> constantemente. Cuando acepten tu solicitud, tendrás una hora fija para negociar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERTA DE MEMBRESÍA INACTIVA -->
        <?php if (($empresa['membresia_estado'] ?? 'inactivo') !== 'activo'): ?>
            <div class="bg-rose-50 border-2 border-rose-100 p-5 rounded-3xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center">
                    <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl mr-4 shrink-0">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-900">Membresía Inactiva / Pendiente de Pago</h3>
                        <p class="text-xs text-gray-500 mt-1">Tu cuenta de vendedor tiene restricciones comerciales. No podrás inscribirte en ruedas de negocios ni solicitar citas hasta que actives tu membresía.</p>
                    </div>
                </div>
                <button onclick="abrirModalMembresia()" class="bg-rose-500 hover:bg-rose-600 text-white text-xs px-6 py-3 rounded-full font-bold transition-colors shadow-md shadow-rose-500/10 shrink-0">
                    <i class="fas fa-crown mr-1.5"></i> Activar Membresía
                </button>
            </div>
        <?php endif; ?>

        <!-- NOTIFICACIONES DE MEMBRESÍA -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'membresia_activada'): ?>
            <div class="bg-emerald-50 border-2 border-emerald-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">¡Suscripción Activada Exitosamente!</h3>
                    <p class="text-xs text-gray-500 mt-1">Tu membresía de vendedor ha sido activada correctamente. Ahora tienes acceso ilimitado a todas las ruedas de negocios y agendamiento de citas.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'pago_fallido'): ?>
            <div class="bg-rose-50 border-2 border-rose-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">Pago Cancelado o Rechazado</h3>
                    <p class="text-xs text-gray-500 mt-1">La transacción a través de Mercado Pago no se completó. Intenta nuevamente o usa otro medio de pago.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'pago_pendiente'): ?>
            <div class="bg-amber-50 border-2 border-amber-100 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-amber-100 text-amber-600 rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">Pago en Verificación</h3>
                    <p class="text-xs text-gray-500 mt-1">Tu pago está pendiente de aprobación por Mercado Pago. Se activará automáticamente apenas se confirme la transacción.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'perfil_vendedor_activado'): ?>
            <div class="bg-[#0d9488]/10 border-2 border-[#0d9488]/20 p-5 rounded-3xl shadow-sm flex items-center">
                <div class="p-3 bg-[#0d9488]/20 text-[#0d9488] rounded-2xl mr-4 shrink-0 font-bold">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900">¡Perfil de Vendedor Activado!</h3>
                    <p class="text-xs text-gray-500 mt-1">Te has convertido exitosamente en Vendedor/Proveedor de AGESCO. Para poder comenzar a ofrecer tus productos y agendar reuniones, adquiere una membresía activa abajo.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN DE KPIs: RESUMEN DE ALTO NIVEL -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Citas Totales -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-teal-50 text-[#0d9488] rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Citas Totales</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['total_citas']; ?></p>
                </div>
            </div>
            
            <!-- Ventas Proyectadas -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-emerald-50 text-emerald-500 rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ventas Proyectadas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1">$<?php echo number_format($kpis['volumen_negocio'] ?? 0, 0); ?></p>
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

            <!-- Mis Ofertas -->
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                <div class="p-4 bg-teal-50 text-teal-600 rounded-2xl mr-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mis Ofertas</p>
                    <p class="text-3xl font-black text-gray-800 mt-1"><?php echo $kpis['total_ofertas']; ?></p>
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
                                <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Nuevas Solicitudes de Cita</h2>
                                <p class="text-rose-600 text-xs font-bold uppercase tracking-wider mt-1">Requieren tu acción inmediata</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 relative z-10 font-medium">Tienes <strong><?php echo $kpis['citas_por_gestionar']; ?></strong> cita(s) propuestas por compradores o contraofertas pendientes de tu respuesta.</p>
                        
                        <a href="index.php?controlador=vendedor&accion=verReuniones" class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:-translate-y-0.5 transition-all duration-300 relative z-10">
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
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mt-1.5"><?php echo htmlspecialchars($tp['tituloRueda']); ?> | Reunión: <?php echo date('d/m/Y', strtotime($tp['fecha_reunion'])); ?></p>
                                    </div>
                                    <button onclick="abrirModalEncuesta(
                                        <?php echo $tp['reunionId']; ?>, 
                                        '<?php echo htmlspecialchars($tp['nombre_contraparte'] ?? 'Empresa'); ?>', 
                                        '<?php echo htmlspecialchars($tp['tituloRueda']); ?>',
                                        '<?php echo date('d/m/Y H:i', strtotime($tp['fecha_reunion'])); ?>',
                                        'trazabilidad_<?php echo $tp['tipo']; ?>',
                                        <?php echo $tp['id']; ?>
                                    )" class="bg-purple-500 hover:bg-purple-600 text-white text-xs px-5 py-2.5 rounded-full font-bold transition-colors shadow-md shadow-purple-500/10">
                                        Actualizar
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- SECCIÓN DE ENCUESTAS PENDIENTES -->
                <?php if (!empty($encuestas_pendientes)): ?>
                    <div class="bg-teal-50/50 border border-teal-100 p-6 rounded-3xl shadow-sm mb-8">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-teal-100 text-teal-700 rounded-xl mr-3">
                                <i class="fas fa-poll-h text-xl"></i>
                            </div>
                            <h2 class="text-xl font-extrabold text-gray-900">Encuestas Pendientes</h2>
                        </div>
                        <p class="text-teal-700 text-sm mb-5">Tienes citas finalizadas que aún no has calificado. Tu opinión es fundamental para el ecosistema AGECSO.</p>

                        <div class="space-y-3.5">
                            <?php foreach ($encuestas_pendientes as $ep): ?>
                                <div class="bg-white p-4 rounded-2xl border border-teal-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div>
                                        <p class="font-extrabold text-gray-800 text-sm"><?php echo htmlspecialchars($ep['nombre_comprador']); ?></p>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mt-1.5"><?php echo htmlspecialchars($ep['tituloRueda']); ?> | <?php echo date('d/m/Y H:i', strtotime($ep['fechaHora'])); ?></p>
                                    </div>
                                    <a href="index.php?controlador=vendedor&accion=verEncuestas&reunion_id=<?php echo $ep['id']; ?>&nombre=<?php echo urlencode($ep['nombre_comprador']); ?>&rueda=<?php echo urlencode($ep['tituloRueda']); ?>&fecha=<?php echo urlencode(date('d/m/Y H:i', strtotime($ep['fechaHora']))); ?>"
                                       class="bg-[#0d9488] hover:bg-[#0f766e] text-white text-xs px-5 py-2.5 rounded-full font-bold transition-colors shadow-md shadow-teal-500/10 text-center">
                                        Diligenciar
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center tracking-tight">
                        <i class="fas fa-layer-group mr-2.5 text-[#0d9488]"></i> Participación en Ruedas
                    </h2>
                </div>
                
                <?php if (empty($ruedas_activas)): ?>
                    <div class="bg-teal-50/40 border-2 border-dashed border-teal-200 rounded-3xl p-10 text-center">
                        <i class="fas fa-calendar-times text-teal-300 text-4xl mb-4"></i>
                        <p class="text-teal-800 font-extrabold text-lg">No estás participando en ruedas activas.</p>
                        <p class="text-sm text-teal-600 mt-2 max-w-sm mx-auto">Explora las ruedas disponibles en el panel de "Nuevas Oportunidades" e inscríbete para comenzar a agendar.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-6">
                        <?php foreach ($ruedas_activas as $r): ?>
                            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] transition-all duration-300">
                                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-50 bg-gradient-to-r from-gray-50/50 to-white">
                                    <div>
                                        <h3 class="font-extrabold text-gray-900 text-lg"><?php echo htmlspecialchars($r['tituloRueda']); ?></h3>
                                        <div class="flex items-center mt-2.5 flex-wrap gap-2">
                                            <span class="text-[10px] bg-teal-50 text-[#0d9488] border border-teal-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider"><?php echo htmlspecialchars($r['estadoInscripcion']); ?></span>
                                            <?php if (($r['modalidad'] ?? 'virtual') === 'virtual'): ?>
                                                <span class="text-[10px] bg-purple-50 text-purple-700 border border-purple-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider" title="Virtual"><i class="fas fa-video mr-1"></i>Virtual</span>
                                            <?php else: ?>
                                                <span class="text-[10px] bg-orange-50 text-orange-700 border border-orange-100 px-2.5 py-0.5 rounded-full font-extrabold uppercase tracking-wider" title="Presencial: <?php echo htmlspecialchars($r['ubicacion'] ?? ''); ?>"><i class="fas fa-map-marker-alt mr-1"></i>Presencial</span>
                                            <?php endif; ?>
                                            <span class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider" title="Rueda de Negocio"><i class="far fa-calendar-alt mr-1 text-[#0d9488]"></i>Evento: <?php echo date('d/m/Y', strtotime($r['fechaInicio'])); ?> al <?php echo date('d/m/Y', strtotime($r['fechaFin'])); ?></span>
                                            <?php if (!empty($r['fechaInscripcionInicio']) && !empty($r['fechaInscripcionFin'])): ?>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider" title="Periodo de Registro"><i class="fas fa-edit mr-1 text-amber-500"></i>Reg: <?php echo date('d/m/Y', strtotime($r['fechaInscripcionInicio'])); ?> al <?php echo date('d/m/Y', strtotime($r['fechaInscripcionFin'])); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="index.php?controlador=vendedor&accion=verMisOfertas&id=<?php echo $r['id']; ?>" class="text-xs bg-teal-600 text-white hover:bg-teal-700 px-6 py-3 rounded-full font-black transition-all duration-300 shadow-lg shadow-teal-500/20 flex items-center gap-2 group">
                                            <i class="fas fa-box-open text-[10px]"></i> Mis Ofertas
                                        </a>
                                        <a href="index.php?controlador=vendedor&accion=verCompradores&id=<?php echo $r['id']; ?>" class="text-xs bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white px-4 py-3 rounded-full font-extrabold transition-all duration-300 shadow-sm flex items-center gap-1.5">
                                            <i class="fas fa-users text-[10px]"></i> Buscar Clientes
                                        </a>
                                        <a href="index.php?controlador=vendedor&accion=verReuniones" class="text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-3 rounded-full font-extrabold transition-all duration-300 shadow-sm flex items-center gap-1.5">
                                            <i class="far fa-calendar-check text-[10px]"></i> Agenda
                                        </a>
                                        <button onclick="abrirModalOferta(<?php echo $r['id']; ?>)" class="text-xs bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white px-4 py-3 rounded-full font-extrabold transition-all duration-300 shadow-sm flex items-center gap-1.5">
                                            <i class="fas fa-plus text-[10px]"></i> Nueva Oferta
                                        </button>
                                    </div>
                                </div>
                                <div class="p-5 bg-gray-50/30 grid grid-cols-2 text-center divide-x divide-gray-100">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-widest">Ofertas</p>
                                        <p class="text-xl font-black text-gray-700 mt-1"><?php echo count($ofertas_por_rueda[$r['id']] ?? []); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-widest">Solicitudes</p>
                                        <p class="text-xl font-black text-gray-700 mt-1"><?php echo count($reuniones_por_rueda[$r['id']] ?? []); ?></p>
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
                            <?php 
                                $ya_inscrito = false;
                                foreach($mis_ruedas as $mr) { if($mr['id'] == $rd['id']) $ya_inscrito = true; }
                            ?>
                            <?php if (!$ya_inscrito): ?>
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
                                            <a href="index.php?controlador=vendedor&accion=inscribirseRueda&id=<?php echo $rd['id']; ?>" class="w-full block text-center text-xs bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-full font-extrabold shadow-md shadow-emerald-500/10 hover:shadow-lg hover:shadow-emerald-500/20 transition-all duration-300">
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

                <!-- Historial Rápido -->
                <?php if(!empty($ruedas_pasadas)): ?>
                <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                    <h3 class="font-extrabold mb-4 flex items-center tracking-tight text-sm uppercase tracking-wider text-indigo-200">
                        <i class="fas fa-history mr-2 text-indigo-400"></i> Historial Rápido
                    </h3>
                    <div class="space-y-4">
                        <?php foreach (array_slice($ruedas_pasadas, 0, 2) as $rp): ?>
                            <div class="border-b border-indigo-800/60 pb-3.5 last:border-0 last:pb-0">
                                <p class="text-xs font-extrabold truncate text-white"><?php echo htmlspecialchars($rp['tituloRueda']); ?></p>
                                <p class="text-[10px] text-indigo-300 mt-1 uppercase font-bold tracking-wider">Finalizó el <?php echo date('d/m/y', strtotime($rp['fechaFin'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para registrar oferta -->
<div id="modalOferta" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalOferta').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form action="index.php?controlador=vendedor&accion=registrarOferta" method="POST">
                <input type="hidden" name="empresa_id" value="<?php echo $empresa['id']; ?>">
                <input type="hidden" name="rueda_id" id="cita_rueda_id_oferta">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-7 sm:pb-5">
                    <h3 class="text-xl leading-6 font-black text-gray-900 mb-4 flex items-center"><i class="fas fa-plus-circle text-teal-600 mr-2"></i> Nueva Oferta Comercial</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Categoría del Producto</label>
                            <select name="sector_id" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#0d9488] focus:border-[#0d9488]">
                                <?php foreach ($todos_sectores as $sec): ?>
                                    <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['nombreSector']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nombre del Producto o Servicio</label>
                            <input type="text" name="producto_servicio" required class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#0d9488] focus:border-[#0d9488]" placeholder="Ej: Consultoría en Marketing">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Descripción Comercial</label>
                            <textarea name="descripcion" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#0d9488] focus:border-[#0d9488]" placeholder="Detalla los beneficios de tu oferta..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Palabras Clave (Tags)</label>
                            <input type="text" name="tags" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm p-2.5 text-sm focus:ring-[#0d9488] focus:border-[#0d9488]" placeholder="ej: software, cloud, soporte">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-7 sm:flex sm:flex-row-reverse rounded-b-3xl gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-md px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-sm font-extrabold text-white transition duration-200">Publicar Oferta</button>
                    <button type="button" onclick="document.getElementById('modalOferta').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-extrabold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto transition duration-200">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function abrirModalOferta(ruedaId) {
    document.getElementById('cita_rueda_id_oferta').value = ruedaId;
    document.getElementById('modalOferta').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function cerrarModalOferta() {
    document.getElementById('modalOferta').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>

<?php require_once __DIR__ . '/../layout/modal_encuesta.php'; ?>

<?php include __DIR__ . '/modal_membresia.php'; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
