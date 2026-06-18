<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

$page = $_GET['page'] ?? 'inicio';

// Rutas de administración
if ($page === 'login') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login();
} elseif ($page === 'logout') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->logout();
} elseif ($page === 'admin') {
    require_once __DIR__ . '/../app/controllers/AdminController.php';
    $controller = new AdminController($pdo);
    
    $section = $_GET['section'] ?? 'dashboard';
    switch ($section) {
        case 'noticias':
            $controller->noticias();
            break;
        case 'eventos':
            $controller->eventos();
            break;
        case 'cursos':
            $controller->cursos();
            break;
        case 'aliados':
            $controller->aliados();
            break;
        case 'servicios':
            $controller->servicios();
            break;
        case 'mensajes':
            $controller->mensajes();
            break;
        default:
            $controller->index();
            break;
    }
} else {
    // Rutas públicas
    require_once __DIR__ . '/../app/controllers/PageController.php';
    $controller = new PageController($pdo);
    $controller->show($page);
}
