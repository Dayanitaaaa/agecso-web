<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// Obtener la ruta limpia
$request = $_SERVER['REQUEST_URI'];
$base_path = parse_url(APP_URL, PHP_URL_PATH) ?: '';
$request = str_replace($base_path, '', $request);
$request = parse_url($request, PHP_URL_PATH);
$request = trim($request, '/');

$parts = explode('/', $request);

// Mapeo básico de rutas para soporte de URLs limpias
if (!empty($parts[0])) {
    if ($parts[0] === 'admin') {
        $_GET['page'] = 'admin';
        if (!empty($parts[1])) $_GET['section'] = $parts[1];
        if (!empty($parts[2])) $_GET['action'] = $parts[2];
        if (!empty($parts[3])) $_GET['id'] = $parts[3];
    } elseif (in_array($parts[0], ['login', 'logout', 'forgot-password', 'reset-password', 'noticias', 'eventos', 'contacto', 'nosotros', 'somos-agecso', 'aliados', 'servicios', 'cursos-webinar', 'agenda'])) {
        $_GET['page'] = $parts[0];
    }
}

$page = $_GET['page'] ?? 'inicio';

// Rutas de administración
if ($page === 'login') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login();
} elseif ($page === 'forgot-password' || $page === 'forgotpassword') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->forgotPassword();
} elseif ($page === 'reset-password' || $page === 'resetpassword') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->resetPassword();
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
        case 'multimedia':
            $controller->multimedia();
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
