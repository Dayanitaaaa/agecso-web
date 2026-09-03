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
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
</head>
<body class="bg-gray-50 flex min-h-screen flex-col md:flex-row">
    <?php 
    if(isset($_SESSION['usuario_id'])) {
        include __DIR__ . '/sidebar.php';
    }
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
                            <div class="hidden sm:flex items-center gap-3 px-4 py-1.5 bg-white/10 rounded-full border border-white/10 backdrop-blur-sm">
                                <div class="w-7 h-7 bg-white text-[#00a2ff] rounded-full flex items-center justify-center text-[10px] font-black">
                                    <?php echo strtoupper(substr($_SESSION['nombreUsuario'] ?? 'U', 0, 1)); ?>
                                </div>
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
