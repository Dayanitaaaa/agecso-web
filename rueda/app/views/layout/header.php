<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGECSO - Software Rueda de Negocios</title>
    <link rel="icon" type="image/jpeg" href="img/AGECSO.jpg">
    <link rel="apple-touch-icon" href="img/AGECSO.jpg">
    <!-- Tailwind CSS (local) -->
    <script src="assets/js/tailwind.js"></script>
    <!-- FontAwesome (CDN) -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">
    <!-- SweetAlert2 (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flatpickr (Premium Datepicker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="assets/css/flatpickr-custom.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
</head>
<body class="bg-gray-50 flex min-h-screen flex-col md:flex-row">
    <?php 
    if(isset($_SESSION['usuario_id'])) {
        include __DIR__ . '/sidebar.php';
    }
    
    // Cargar logoPath del usuario si está logueado
    $logoPath = $_SESSION['logoPath'] ?? null;
    ?>
    
    <div class="flex-grow flex flex-col <?php echo isset($_SESSION['usuario_id']) ? 'md:ml-64' : ''; ?> transition-all duration-300">
        <!-- Top Nav Original Restaurada -->
        <nav class="<?php echo $header_bg ?? 'bg-gradient-to-r from-[#00a2ff] via-[#4dbfff] to-[#008ae0]'; ?> text-white shadow-md border-b border-sky-400/10 h-16 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <!-- Botón para abrir sidebar en móvil -->
                        <?php if(isset($_SESSION['usuario_id'])): ?>
                            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-all">
                                <i class="fas fa-bars"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-4">
                        <?php if(isset($_SESSION['usuario_id'])): ?>
                            <!-- Campanita de notificaciones con dropdown -->
                            <div class="relative">
                                <button id="btn-notificaciones" type="button" class="relative p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-all focus:outline-none" onclick="toggleDropdownAlertas(event)">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span id="notif-contador" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center hidden shadow-sm animate-pulse">0</span>
                                </button>
                                
                                <!-- Dropdown de alertas -->
                                <div id="dropdown-alertas" class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden text-left">
                                    <div class="<?php echo $header_bg ?? 'bg-gradient-to-r from-[#00a2ff] to-[#4dbfff]'; ?> px-4 py-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-bell text-white text-xs"></i>
                                            <h3 class="text-white font-black text-sm">Notificaciones</h3>
                                        </div>
                                        <button type="button" id="btn-marcar-todas-dropdown" onclick="marcarTodasLeidasDropdown(event)" class="text-[11px] font-bold text-white/90 hover:text-white hover:underline transition-all hidden">
                                            <i class="fas fa-check-double mr-1 text-[10px]"></i>Marcar leídas
                                        </button>
                                    </div>
                                    <div id="alertas-lista" class="max-h-80 overflow-y-auto divide-y divide-gray-100/70 p-1">
                                        <div class="text-center py-6 text-gray-400 text-xs font-medium">Cargando notificaciones...</div>
                                    </div>
                                    <div class="p-2.5 bg-gray-50 border-t border-gray-100 text-center">
                                        <a href="index.php?controlador=notificacion&accion=panel" class="text-xs font-black text-gray-700 hover:text-[#00a2ff] transition-colors block py-1">
                                            Ver todas las notificaciones &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="hidden sm:flex items-center gap-3 px-4 py-1.5 bg-white/10 rounded-full border border-white/10 backdrop-blur-sm">
                                <?php if (!empty($logoPath)): ?>
                                    <img src="uploads/logos/<?php echo htmlspecialchars($logoPath); ?>" alt="Logo" class="w-7 h-7 rounded-full object-contain bg-white">
                                <?php else: ?>
                                    <div class="w-7 h-7 bg-white text-[#00a2ff] rounded-full flex items-center justify-center text-[10px] font-black">
                                        <?php echo strtoupper(substr($_SESSION['nombreUsuario'] ?? 'U', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <span class="text-xs font-black text-white"><?php echo htmlspecialchars($_SESSION['nombreUsuario'] ?? 'Usuario'); ?></span>
                            </div>
                        <?php else: ?>
                            <a href="https://agecso.org/" class="flex items-center gap-2 hover:opacity-90 transition-opacity">
                                <img src="img/AGECSO.jpg" alt="AGECSO Logo" class="h-8 w-8 rounded-lg shadow-sm border border-white/30">
                                <span class="text-white font-black text-lg tracking-tight">AGECSO</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
        
        <main class="flex-grow <?php echo (isset($login_layout) && $login_layout) ? '' : 'p-4 md:p-8'; ?>">

<script>
let alertasData = null;

// Cargar contador de notificaciones al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContadorNotificaciones();
});

function actualizarContadorNotificaciones() {
    const contador = document.getElementById('notif-contador');
    if (!contador) return;
    fetch('index.php?controlador=notificacion&accion=contador')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const total = parseInt(data.contador) || 0;
                contador.textContent = total;
                if (total > 0) {
                    contador.classList.remove('hidden');
                } else {
                    contador.classList.add('hidden');
                }
            }
        })
        .catch(err => console.error('Error contador:', err));
}

// Toggle del dropdown de alertas (sin borrar ni marcar nada automáticamente)
function toggleDropdownAlertas(e) {
    if (e) e.stopPropagation();
    const dropdown = document.getElementById('dropdown-alertas');
    if (!dropdown) return;
    
    const isHidden = dropdown.classList.contains('hidden');
    if (isHidden) {
        dropdown.classList.remove('hidden');
        cargarNotificacionesDropdown();
    } else {
        dropdown.classList.add('hidden');
    }
}

// Cargar notificaciones recientes para el dropdown
function cargarNotificacionesDropdown() {
    const lista = document.getElementById('alertas-lista');
    if (!lista) return;

    fetch('index.php?controlador=notificacion&accion=obtenerRecientes')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertasData = data.notificaciones || [];
                const unreadCount = parseInt(data.contador) || 0;
                
                // Actualizar badge
                const contador = document.getElementById('notif-contador');
                if (contador) {
                    contador.textContent = unreadCount;
                    if (unreadCount > 0) {
                        contador.classList.remove('hidden');
                    } else {
                        contador.classList.add('hidden');
                    }
                }

                // Botón marcar leídas visible solo si hay pendientes
                const btnMarcar = document.getElementById('btn-marcar-todas-dropdown');
                if (btnMarcar) {
                    if (unreadCount > 0) {
                        btnMarcar.classList.remove('hidden');
                    } else {
                        btnMarcar.classList.add('hidden');
                    }
                }

                renderizarAlertas();
            } else {
                lista.innerHTML = '<p class="text-gray-400 text-xs text-center py-6">No se pudieron cargar las notificaciones.</p>';
            }
        })
        .catch(err => {
            console.error('Error cargando notificaciones:', err);
            lista.innerHTML = '<p class="text-gray-400 text-xs text-center py-6">Error al conectar con el servidor.</p>';
        });
}

// Renderizar las alertas en el dropdown
function renderizarAlertas() {
    const lista = document.getElementById('alertas-lista');
    if (!lista) return;
    
    if (!alertasData || alertasData.length === 0) {
        lista.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                    <i class="fas fa-bell-slash text-lg"></i>
                </div>
                <p class="text-gray-800 text-xs font-bold">No tienes notificaciones aún</p>
                <p class="text-gray-400 text-[11px] mt-0.5">Te avisaremos cuando haya actividad importante</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    alertasData.forEach(alerta => {
        let icono = 'fa-info-circle';
        let color = 'bg-blue-50 text-blue-600';
        
        const tipo = (alerta.tipo || '').toLowerCase();
        if (tipo.includes('pauta')) {
            icono = 'fa-bullhorn';
            color = 'bg-orange-50 text-orange-600';
        } else if (tipo.includes('cita_rechazada') || tipo.includes('cancelada')) {
            icono = 'fa-calendar-times';
            color = 'bg-rose-50 text-rose-600';
        } else if (tipo.includes('cita_aceptada')) {
            icono = 'fa-calendar-check';
            color = 'bg-emerald-50 text-emerald-600';
        } else if (tipo.includes('cita')) {
            icono = 'fa-calendar-plus';
            color = 'bg-sky-50 text-[#00a2ff]';
        } else if (tipo.includes('demanda')) {
            icono = 'fa-shopping-cart';
            color = 'bg-amber-50 text-amber-600';
        } else if (tipo.includes('oferta')) {
            icono = 'fa-tags';
            color = 'bg-purple-50 text-purple-600';
        } else if (tipo.includes('inscripcion')) {
            icono = 'fa-user-check';
            color = 'bg-teal-50 text-teal-600';
        }

        const esNoLeida = (alerta.leida === 0 || alerta.leida === '0');
        const bgClase = esNoLeida ? 'bg-sky-50/50 hover:bg-sky-100/60' : 'bg-white hover:bg-gray-50';
        
        html += `
            <a href="${alerta.link}" onclick="marcarNotificacionClick(event, ${alerta.id})" class="block p-3.5 ${bgClase} transition-all duration-150 relative">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl ${color} flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <i class="fas ${icono} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-gray-900 text-xs ${esNoLeida ? 'font-black' : 'font-bold'} truncate">${alerta.titulo}</p>
                            ${esNoLeida ? '<span class="w-2 h-2 rounded-full bg-[#00a2ff] shrink-0" title="Nueva"></span>' : ''}
                        </div>
                        <p class="text-gray-600 text-[11px] leading-snug mt-0.5 line-clamp-2">${alerta.mensaje}</p>
                        <p class="text-[10px] text-gray-400 font-semibold mt-1 flex items-center gap-1">
                            <i class="far fa-clock text-[9px]"></i>
                            <span>${alerta.tiempo || ''}</span>
                        </p>
                    </div>
                </div>
            </a>
        `;
    });
    
    lista.innerHTML = html;
}

// Al hacer clic en una notificación individual, se marca como leída
function marcarNotificacionClick(e, id) {
    if (id) {
        if (navigator.sendBeacon) {
            const formData = new FormData();
            formData.append('id', id);
            navigator.sendBeacon('index.php?controlador=notificacion&accion=marcarLeida', formData);
        } else {
            fetch('index.php?controlador=notificacion&accion=marcarLeida', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + encodeURIComponent(id)
            });
        }
    }
}

// Marcar todas como leídas manualmente SIN borrarlas de la lista
function marcarTodasLeidasDropdown(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    fetch('index.php?controlador=notificacion&accion=marcarTodasLeidas', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ocultar contador
            const contador = document.getElementById('notif-contador');
            if (contador) contador.classList.add('hidden');
            
            // Ocultar botón marcar todas
            const btnMarcar = document.getElementById('btn-marcar-todas-dropdown');
            if (btnMarcar) btnMarcar.classList.add('hidden');

            // Actualizar visualmente a leídas SIN borrarlas
            if (alertasData && alertasData.length > 0) {
                alertasData.forEach(a => a.leida = 1);
                renderizarAlertas();
            }
        }
    })
    .catch(err => console.error('Error al marcar leídas:', err));
}

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown-alertas');
    const button = document.getElementById('btn-notificaciones');
    
    if (dropdown && !dropdown.classList.contains('hidden')) {
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    }
});
</script>
