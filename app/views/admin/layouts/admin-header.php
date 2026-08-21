<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$section = $_GET['section'] ?? 'dashboard';
$sections = [
    'dashboard' => 'Dashboard',
    'agenda' => 'Agenda',
    'noticias' => 'Noticias',
    'eventos' => 'Eventos Realizados',
    'cursos' => 'Cursos Realizados',
    'aliados' => 'Aliados',
    'servicios' => 'Servicios',
    'multimedia' => 'Multimedia',
    'mensajes' => 'Mensajes'
];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Panel') ?> | Admin AGECSO</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="<?= APP_URL ?>/assets/img/AGECSO.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>
<body>
    <div class="admin-sidebar" id="adminSidebar">
        <div class="brand d-flex align-items-center gap-3">
            <img src="<?= APP_URL ?>/assets/img/AGECSO.jpg" alt="AGECSO">
            <div>
                <div class="fw-bold text-white">AGECSO</div>
                <small class="text-white-50">Admin Panel</small>
            </div>
        </div>
        <nav class="nav flex-column py-3">
            <?php foreach ($sections as $slug => $label): ?>
                <a class="nav-link <?= $section === $slug ? 'active' : '' ?>" href="<?= APP_URL ?>/admin/<?= $slug ?>">
                    <i class="bi bi-<?= $slug === 'dashboard' ? 'speedometer2' : ($slug === 'agenda' ? 'calendar2-check' : ($slug === 'noticias' ? 'newspaper' : ($slug === 'eventos' ? 'calendar-event' : ($slug === 'cursos' ? 'mortarboard' : ($slug === 'aliados' ? 'people' : ($slug === 'servicios' ? 'briefcase' : ($slug === 'multimedia' ? 'images' : 'envelope'))))))) ?>"></i>
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="mt-auto p-3 border-top border-secondary">
            <div class="text-white-50 small mb-2"><?= htmlspecialchars($_SESSION['admin_nombre'] ?? '') ?></div>
            <a href="<?= APP_URL ?>/logout" class="btn btn-sm btn-outline-light w-100">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </div>

    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <h4 class="mb-0"><?= htmlspecialchars($title ?? 'Panel') ?></h4>
                <small class="text-muted">AGECSO - Panel de Administración</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= APP_URL ?>/inicio" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Ver sitio
                </a>
                <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
