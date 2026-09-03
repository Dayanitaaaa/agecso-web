<?php
$userRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
$current_page = $_GET['accion'] ?? '';
$controlador = $_GET['controlador'] ?? '';

// Definir los ítems del menú y colores según el rol
$menu_items = [];
$sidebar_bg = 'bg-gradient-to-b from-[#00a2ff] via-[#4dbfff] to-[#008ae0]'; // Default azul

if ($userRole === 'admin') {
    $menu_items = [
        ['label' => 'Dashboard', 'icon' => 'fas fa-chart-pie', 'accion' => 'dashboard', 'controlador' => 'admin'],
        ['label' => 'Auditoría Total', 'icon' => 'fas fa-clipboard-list', 'accion' => 'verRegistrosPaneles', 'controlador' => 'admin'],
        ['label' => 'Configurar Ruedas', 'icon' => 'fas fa-cog', 'accion' => 'crearRueda', 'controlador' => 'admin'],
        ['label' => 'Estadísticas', 'icon' => 'fas fa-chart-line', 'accion' => 'verEstadisticas', 'controlador' => 'admin'],
    ];
    $sidebar_bg = 'bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900';
} elseif ($userRole === 'comprador') {
    $menu_items = [
        ['label' => 'Panel Principal', 'icon' => 'fas fa-th-large', 'accion' => 'dashboard', 'controlador' => 'comprador'],
        ['label' => 'Gestión de Citas', 'icon' => 'fas fa-handshake', 'accion' => 'verReuniones', 'controlador' => 'comprador'],
        ['label' => 'Buscar Vendedores', 'icon' => 'fas fa-search-dollar', 'accion' => 'verParticipantes', 'controlador' => 'comprador'],
        ['label' => 'Mis Encuestas', 'icon' => 'fas fa-poll-h', 'accion' => 'verEncuestas', 'controlador' => 'comprador'],
    ];
    $sidebar_bg = 'bg-gradient-to-b from-[#00a2ff] via-[#4dbfff] to-[#008ae0]';
} elseif ($userRole === 'vendedor' || $userRole === 'proveedor') {
    $menu_items = [
        ['label' => 'Panel Principal', 'icon' => 'fas fa-th-large', 'accion' => 'dashboard', 'controlador' => 'vendedor'],
        ['label' => 'Gestión de Citas', 'icon' => 'fas fa-handshake', 'accion' => 'verReuniones', 'controlador' => 'vendedor'],
        ['label' => 'Mis Productos/Servicios', 'icon' => 'fas fa-box-open', 'accion' => 'verTodasMisOfertas', 'controlador' => 'vendedor'],
        ['label' => 'Buscar Clientes', 'icon' => 'fas fa-users', 'accion' => 'explorarDemandas', 'controlador' => 'vendedor'],
        ['label' => 'Mis Encuestas', 'icon' => 'fas fa-poll-h', 'accion' => 'verEncuestas', 'controlador' => 'vendedor'],
    ];
    $sidebar_bg = 'bg-gradient-to-b from-[#0d9488] via-[#14b8a6] to-[#0f766e]';
}
?>

<!-- Sidebar Lateral -->
<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full md:translate-x-0 border-r border-black/5 shadow-2xl <?php echo $sidebar_bg; ?>">
    <div class="h-full px-4 py-6 overflow-y-auto flex flex-col">
        <!-- Logo -->
        <a href="https://agecso.org/" class="flex items-center gap-3 px-2 mb-10 hover:opacity-90 transition-opacity">
            <img src="img/AGECSO.jpg" alt="Logo" class="h-9 w-auto rounded-xl shadow-sm">
            <span class="text-xl font-black tracking-tight text-white">AGECSO</span>
        </a>

        <!-- Menú de Navegación Dinámico -->
        <nav class="space-y-2 flex-grow">
            <?php foreach ($menu_items as $item): ?>
                <?php 
                    $url = "index.php?controlador={$item['controlador']}&accion={$item['accion']}";
                    $isActive = ($current_page == $item['accion'] && $controlador == $item['controlador']);
                    // Caso especial para el dashboard inicial
                    if ($current_page == '' && $item['accion'] == 'dashboard') $isActive = true;
                ?>
                <a href="<?php echo $url; ?>" class="flex items-center p-3 text-sm font-bold rounded-2xl transition-all duration-200 <?php echo $isActive ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white'; ?>">
                    <i class="<?php echo $item['icon']; ?> w-5 text-lg"></i>
                    <span class="ml-3"><?php echo $item['label']; ?></span>
                    <?php if ($item['accion'] == 'verReuniones' && isset($kpis['citas_por_gestionar']) && $kpis['citas_por_gestionar'] > 0): ?>
                        <span class="inline-flex items-center justify-center w-5 h-5 ml-auto text-[10px] font-black text-white bg-rose-500 rounded-full"><?php echo $kpis['citas_por_gestionar']; ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>

            <!-- Módulo de Mensajes (Solo Roles de Socio) -->
            <?php if ($userRole !== 'admin'): ?>
                <a href="#" class="flex items-center p-3 text-sm font-bold rounded-2xl text-white/40 cursor-not-allowed group">
                    <i class="fas fa-comments w-5 text-lg"></i>
                    <span class="ml-3">Mensajes / Chat</span>
                    <span class="ml-auto text-[9px] bg-white/20 px-2 py-0.5 rounded-full">Próximamente</span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- Footer del Sidebar: Usuario -->
        <div class="pt-6 border-t border-white/10 space-y-2">
            <a href="index.php?controlador=usuario&accion=perfil" class="flex items-center p-3 text-sm font-bold rounded-2xl text-white/80 hover:bg-white/10 hover:text-white transition-all">
                <i class="fas fa-user-circle w-5 text-lg"></i>
                <span class="ml-3">Mi Perfil</span>
            </a>
            <a href="index.php?controlador=usuario&accion=logout" class="flex items-center p-3 text-sm font-bold rounded-2xl text-white hover:bg-white/10 transition-all">
                <i class="fas fa-sign-out-alt w-5 text-lg"></i>
                <span class="ml-3">Cerrar Sesión</span>
            </a>
        </div>
    </div>
</aside>

<!-- Overlay para móviles -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-gray-900/50 hidden md:hidden" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>
