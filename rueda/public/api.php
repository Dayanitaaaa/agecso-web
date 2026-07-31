<?php
/**
 * Punto de entrada para la API REST de AGECSO
 * Acceso: public/api.php?resource=ruedas&action=list
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/Logger.php';

// Cabeceras CORS (Fundamental para la App)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Manejo de preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$resource = $_GET['resource'] ?? null;
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Routing básico (Evolucionará a algo más robusto)
switch ($resource) {
    case 'ruedas':
        require_once __DIR__ . '/../app/controllers/api/RuedaApiController.php';
        $controller = new RuedaApiController($pdo);
        if ($action == 'list') {
            $controller->listarActivas();
        } elseif ($action == 'detail' && $id) {
            $controller->detalle($id);
        }
        break;

    case 'demandas':
        require_once __DIR__ . '/../app/controllers/api/DemandaApiController.php';
        $controller = new DemandaApiController($pdo);
        if ($action == 'list') {
            $controller->listarPorRueda();
        } elseif ($action == 'create' && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $controller->crear();
        }
        break;

    case 'ofertas':
        require_once __DIR__ . '/../app/controllers/api/OfertaApiController.php';
        $controller = new OfertaApiController($pdo);
        if ($action == 'list') {
            $controller->listar();
        } elseif ($action == 'recommended') {
            $controller->recomendadas();
        }
        break;

    case 'reuniones':
        require_once __DIR__ . '/../app/controllers/api/ReunionApiController.php';
        $controller = new ReunionApiController($pdo);
        if ($action == 'list') {
            $controller->listar();
        } elseif ($action == 'detail' && $id) {
            $controller->detalle($id);
        }
        break;

    case 'auth':
        // Aquí irá el login vía JWT para la App
        break;

    default:
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Recurso no encontrado'
        ]);
        break;
}
