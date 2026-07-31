<?php
// Configuración de errores para producción
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('expose_php', 0);

// Fix permisos de sesiones en XAMPP
$session_dir = __DIR__ . '/../sessions';
if (!is_dir($session_dir)) {
    mkdir($session_dir, 0777, true);
}
ini_set('session.save_path', $session_dir);

// --- HARDENING DE SESIONES ---
// Duración: 8 horas (28800 segundos) para usuarios de negocio
ini_set('session.gc_maxlifetime', 28800);
ini_set('session.cookie_lifetime', 28800);

// Cookies HTTPOnly (no accesibles por JavaScript - previene XSS)
ini_set('session.cookie_httponly', 1);

// Cookies SameSite=Lax (protección CSRF cross-site)
ini_set('session.cookie_samesite', 'Lax');

// En producción con HTTPS, activar secure:
// ini_set('session.cookie_secure', 1);

// Usar solo cookies para sesión (no URL)
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);

// Regenerar ID de sesión periódicamente para prevenir fixation
ini_set('session.sid_bits_per_character', 6);
ini_set('session.sid_length', 48);
// ----------------------------

// Configurar handler de errores personalizado para capturar solo errores graves
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Solo convertir en excepción errores fatales y warnings graves, no notices
    if ($errno === E_ERROR || $errno === E_PARSE || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR || $errno === E_USER_ERROR) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    // Para warnings y notices, solo loggear y continuar
    error_log("[WARNING] $errstr in $errfile on line $errline");
    return true;
});

// Cargar la conexión
require_once '../config/db.php';
require_once '../app/models/MatchmakingModel.php';

// Auto-finalizar citas cuya fecha + 1 hora ya pasó (mover al historial)
try {
    MatchmakingModel::autoFinalizarCitasPasadas($pdo);
} catch (Exception $e) {
    error_log('[CITAS_AUTO_FINALIZAR] ' . $e->getMessage());
}

// Actualizar automáticamente estados de ruedas de negocios según fechas (ejecución única por rueda)
try {
    $hoy = date('Y-m-d', strtotime(SYSTEM_TIME));

    // 1. Iniciar automáticamente si está en planeación y llegó la fecha (lógica simplificada)
    $stmtInicInsc = $pdo->prepare(
        "UPDATE ruedas_negocios 
         SET estadoRueda = 'activa'
         WHERE estadoRueda = 'planeacion'
           AND fechaInicio <= ?
           AND fechaFin >= ?"
    );
    $stmtInicInsc->execute([$hoy, $hoy]);

    // 3. Activar: solo si autoActivada = 0 y la fecha de inicio ya llegó
    $stmtActivar = $pdo->prepare(
        "UPDATE ruedas_negocios 
         SET estadoRueda = 'activa', autoActivada = 1
         WHERE autoActivada = 0
           AND fechaInicio <= ?
           AND fechaFin >= ?"
    );
    $stmtActivar->execute([$hoy, $hoy]);

    // 4. Finalizar: solo si autoFinalizada = 0 y la fecha de fin ya pasó
    $stmtFinalizar = $pdo->prepare(
        "UPDATE ruedas_negocios 
         SET estadoRueda = 'finalizada', autoFinalizada = 1
         WHERE autoFinalizada = 0
           AND fechaFin < ?"
    );
    $stmtFinalizar->execute([$hoy]);
} catch (Exception $e) {
    error_log('[RUEDAS_AUTO_ESTADO] ' . $e->getMessage());
}

// Obtener controlador y acción de la URL (Ejemplo: index.php?controlador=usuario&accion=login)
$controlador_nombre = isset($_GET['controlador']) ? $_GET['controlador'] : 'home';
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'index';

// Detectar si es un controlador de API (prefijo 'api/' o sufijo 'Api')
$esApi = false;
$apiPath = '';

if (strpos($controlador_nombre, 'api/') === 0 || strpos($controlador_nombre, 'Api') !== false) {
    $esApi = true;
    // Si viene como api/auth, convertir a AuthApi
    if (strpos($controlador_nombre, 'api/') === 0) {
        $apiName = substr($controlador_nombre, 4); // quitar 'api/'
        $controlador_nombre = ucfirst($apiName) . 'Api';
    }
    $apiPath = '../app/controllers/api/';
} else {
    $apiPath = '../app/controllers/';
}

// Construir la ruta al controlador
$archivo_controlador = $apiPath . ucfirst($controlador_nombre) . 'Controller.php';

if (file_exists($archivo_controlador)) {
    require_once $archivo_controlador;
    $nombre_clase = ucfirst($controlador_nombre) . 'Controller';
    $controlador = new $nombre_clase($pdo);

    if (method_exists($controlador, $accion)) {
        try {
            $controlador->$accion();
        } catch (Exception $e) {
            // Para APIs, devolver JSON de error
            if ($esApi || $controlador_nombre === 'AuthApi') {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error interno del servidor',
                    'timestamp' => date('c')
                ]);
                error_log('[API_ERROR] ' . $e->getMessage());
            } else {
                // Muestra el error real para diagnosticar
                die("ERROR DETECTADO: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            }
        }
    } else {
        if ($esApi) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => "La acción '$accion' no existe",
                'timestamp' => date('c')
            ]);
        } else {
            echo "La acción '$accion' no existe en el controlador '$controlador_nombre'.";
        }
    }
} else {
    if ($esApi) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => "El controlador '$controlador_nombre' no existe",
            'timestamp' => date('c')
        ]);
    } else {
        echo "El controlador '$controlador_nombre' no existe.";
    }
}
?>
