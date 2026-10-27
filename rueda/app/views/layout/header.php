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
                                <button id="btn-notificaciones" class="relative p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-all" onclick="toggleDropdownAlertas()">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span id="notif-contador" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center hidden">0</span>
                                </button>
                                
                                <!-- Dropdown de alertas -->
                                <div id="dropdown-alertas" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                    <div class="<?php echo $header_bg ?? 'bg-gradient-to-r from-[#00a2ff] to-[#4dbfff]'; ?> px-4 py-3">
                                        <h3 class="text-white font-black text-sm">Alertas</h3>
                                    </div>
                                    <div id="alertas-lista" class="max-h-64 overflow-y-auto p-2">
                                        <p class="text-gray-500 text-sm text-center py-4">Cargando alertas...</p>
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
    if (document.getElementById('notif-contador')) {
        fetch('index.php?controlador=usuario&accion=obtenerAlertas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertasData = data.alertas;
                    const contador = document.getElementById('notif-contador');
                    if (contador) {
                        contador.textContent = data.contador;
                        if (data.contador > 0) {
                            contador.classList.remove('hidden');
                        } else {
                            contador.classList.add('hidden');
                        }
                    }
                }
            })
            .catch(error => console.error('Error cargando contador de alertas:', error));
    }
});

// Toggle del dropdown de alertas
function toggleDropdownAlertas() {
    const dropdown = document.getElementById('dropdown-alertas');
    const lista = document.getElementById('alertas-lista');
    
    if (dropdown.classList.contains('hidden')) {
        // Abrir dropdown
        dropdown.classList.remove('hidden');
        
        // Cargar alertas si no están cargadas
        if (alertasData === null) {
            fetch('index.php?controlador=usuario&accion=obtenerAlertas')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alertasData = data.alertas;
                        renderizarAlertas();
                    }
                });
        } else {
            renderizarAlertas();
        }
    } else {
        // Cerrar dropdown
        dropdown.classList.add('hidden');
    }
}

// Renderizar las alertas en el dropdown
function renderizarAlertas() {
    const lista = document.getElementById('alertas-lista');
    
    if (!alertasData || alertasData.length === 0) {
        lista.innerHTML = `
            <div class="flex flex-col items-center justify-center py-8">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">No tienes alertas pendientes</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    alertasData.forEach(alerta => {
        const icono = alerta.tipo === 'reuniones' ? 'calendar-check' : (alerta.tipo === 'demandas' ? 'shopping-cart' : 'box');
        const color = alerta.tipo === 'reuniones' ? 'bg-blue-50 text-blue-500' : (alerta.tipo === 'demandas' ? 'bg-amber-50 text-amber-500' : 'bg-purple-50 text-purple-500');
        
        html += `
            <a href="${alerta.link}" class="block p-4 hover:bg-gray-50 rounded-xl transition-all duration-200 mb-2 last:mb-0 border border-gray-100 hover:border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl ${color} flex items-center justify-center shrink-0">
                        <i class="fas fa-${icono} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-gray-900 text-sm font-bold truncate">${alerta.mensaje}</p>
                        <p class="text-gray-400 text-xs mt-1">Click para ver detalles</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    </div>
                </div>
            </a>
        `;
    });
    
    lista.innerHTML = html;
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
