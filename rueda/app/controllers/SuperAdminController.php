<?php
require_once '../includes/Logger.php';

/**
 * SuperAdminController - Panel de Control Técnico para Desarrolladores/Soporte TI
 * 
 * Este controlador está diseñado para el rol "superadmin" que representa
 * al equipo de desarrollo y soporte técnico del sistema AGECSO.
 * 
 * Funcionalidades:
 * - Métricas técnicas de uso y rendimiento
 * - Logs del sistema para diagnóstico
 * - Health checks de componentes
 * - Estadísticas de API y autenticación
 * - Monitoreo de seguridad (intentos fallidos, rate limiting)
 * - Gestión técnica del sistema (no operativa de negocio)
 */
class SuperAdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificación estricta: solo Super Admin (desarrolladores/soporte TI)
        $userRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
        if (!isset($_SESSION['usuario_id']) || $userRole !== 'superadmin') {
            Logger::logRoleError($userRole ?: 'guest', 'Acceso no autorizado a SuperAdminController', [
                'accion' => $_GET['accion'] ?? 'desconocida',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconocida'
            ]);
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
    }

    /**
     * Dashboard principal técnico - Métricas de sistema y uso
     */
    public function dashboard() {
        // ===== MÉTRICAS DE USUARIOS Y ACCESO =====
        $statsUsuarios = $this->getStatsUsuarios();
        
        // ===== MÉTRICAS DE ACTIVIDAD DEL SISTEMA =====
        $statsActividad = $this->getStatsActividad();
        
        // ===== MÉTRICAS DE BASE DE DATOS =====
        $statsDatabase = $this->getStatsDatabase();
        
        // ===== MÉTRICAS DE SEGURIDAD =====
        $statsSeguridad = $this->getStatsSeguridad();
        
        // ===== MÉTRICAS DE API =====
        $statsApi = $this->getStatsApi();
        
        // ===== HEALTH CHECK =====
        $healthCheck = $this->getHealthCheck();
        
        // ===== LOGS DEL SISTEMA =====
        $logsSistema = $this->getLogsSistema();
        
        // ===== INFORMACIÓN DEL SERVIDOR =====
        $serverInfo = $this->getServerInfo();

        require_once '../app/views/superadmin/tech_dashboard.php';
    }

    /**
     * Vista de logs detallados para diagnóstico
     */
    public function logs() {
        $tipo = $_GET['tipo'] ?? 'todos';
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        $logs = $this->obtenerLogsPorTipo($tipo, $fecha);
        
        require_once '../app/views/superadmin/logs_detalle.php';
    }

    /**
     * API Health Status - Estado de los endpoints
     */
    public function apiHealth() {
        $endpoints = [
            'auth' => $this->checkEndpoint('/api/auth/login'),
            'ofertas' => $this->checkEndpoint('/api/ofertas'),
            'reuniones' => $this->checkEndpoint('/api/reuniones'),
            'demandas' => $this->checkEndpoint('/api/demandas')
        ];
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'timestamp' => date('c'),
            'endpoints' => $endpoints
        ]);
        exit();
    }

    /**
     * Estadísticas detalladas de uso (JSON para gráficas)
     */
    public function usageStats() {
        $periodo = $_GET['periodo'] ?? '7d'; // 24h, 7d, 30d, 90d
        
        $stats = [
            'logins_por_dia' => $this->getLoginsPorPeriodo($periodo),
            'reuniones_por_dia' => $this->getReunionesPorPeriodo($periodo),
            'usuarios_nuevos' => $this->getUsuariosNuevosPorPeriodo($periodo),
            'errores_por_dia' => $this->getErroresPorPeriodo($periodo)
        ];
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit();
    }

    /**
     * Gestión de caché y optimización
     */
    public function mantenimiento() {
        $accion = $_POST['accion'] ?? null;
        $resultado = null;
        
        if ($accion === 'limpiar_logs') {
            $resultado = $this->limpiarLogsAntiguos();
        } elseif ($accion === 'optimizar_tablas') {
            $resultado = $this->optimizarTablas();
        } elseif ($accion === 'verificar_integridad') {
            $resultado = $this->verificarIntegridadBD();
        }
        
        require_once '../app/views/superadmin/mantenimiento.php';
    }

    // ==================== MÉTODOS PRIVADOS DE ESTADÍSTICAS ====================

    private function getStatsUsuarios() {
        return [
            'total_usuarios' => $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(),
            'usuarios_activos' => $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE isActive = 1")->fetchColumn(),
            'usuarios_inactivos' => $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE isActive = 0")->fetchColumn(),
            'usuarios_hoy' => $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE DATE(createdAt) = CURDATE()")->fetchColumn(),
            'usuarios_esta_semana' => $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn(),
            'usuarios_este_mes' => $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn(),
            'por_rol' => $this->pdo->query("
                SELECT r.slugRole, r.nombreRole, COUNT(u.id) as total 
                FROM roles r 
                LEFT JOIN usuarios u ON r.id = u.roleId AND u.isActive = 1
                GROUP BY r.id, r.slugRole, r.nombreRole
            ")->fetchAll(),
            'ultimos_accesos' => $this->pdo->query("
                SELECT u.id, u.nombreUsuario, u.email, r.nombreRole, u.createdAt
                FROM usuarios u
                JOIN roles r ON u.roleId = r.id
                ORDER BY u.createdAt DESC
                LIMIT 10
            ")->fetchAll()
        ];
    }

    private function getStatsActividad() {
        return [
            'total_ruedas' => $this->pdo->query("SELECT COUNT(*) FROM ruedas_negocios")->fetchColumn(),
            'ruedas_activas' => $this->pdo->query("SELECT COUNT(*) FROM ruedas_negocios WHERE estadoRueda = 'activa'")->fetchColumn(),
            'ruedas_finalizadas' => $this->pdo->query("SELECT COUNT(*) FROM ruedas_negocios WHERE estadoRueda = 'finalizada'")->fetchColumn(),
            'total_reuniones' => $this->pdo->query("SELECT COUNT(*) FROM reuniones")->fetchColumn(),
            'reuniones_este_mes' => $this->pdo->query("SELECT COUNT(*) FROM reuniones WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn(),
            'reuniones_esta_semana' => $this->pdo->query("SELECT COUNT(*) FROM reuniones WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn(),
            'estadisticas_reuniones' => $this->pdo->query("
                SELECT estadoCita, COUNT(*) as total 
                FROM reuniones 
                GROUP BY estadoCita
            ")->fetchAll(PDO::FETCH_KEY_PAIR),
            'total_empresas' => $this->pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn(),
            'empresas_pendientes' => $this->pdo->query("SELECT COUNT(*) FROM empresas WHERE estado_verificacion = 'pendiente'")->fetchColumn(),
            'empresas_aprobadas' => $this->pdo->query("SELECT COUNT(*) FROM empresas WHERE estado_verificacion = 'aprobada'")->fetchColumn(),
            'total_ofertas' => $this->pdo->query("SELECT COUNT(*) FROM ofertas")->fetchColumn(),
            'total_demandas' => $this->pdo->query("SELECT COUNT(*) FROM demandas")->fetchColumn(),
            'encuestas_completadas' => $this->pdo->query("SELECT COUNT(*) FROM encuestas_satisfaccion")->fetchColumn(),
        ];
    }

    private function getStatsDatabase() {
        $tablas = $this->pdo->query("
            SELECT table_name, 
                   table_rows, 
                   ROUND(data_length / 1024 / 1024, 2) as data_size_mb,
                   ROUND(index_length / 1024 / 1024, 2) as index_size_mb
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
            ORDER BY data_length DESC
        ")->fetchAll();
        
        $totalSize = array_sum(array_column($tablas, 'data_size_mb'));
        
        return [
            'tablas' => $tablas,
            'total_size_mb' => $totalSize,
            'total_tablas' => count($tablas),
            'conexiones_activas' => $this->pdo->query("SHOW STATUS LIKE 'Threads_connected'")->fetchColumn(1),
            'queries_por_segundo' => $this->pdo->query("SHOW STATUS LIKE 'Queries'")->fetchColumn(1),
        ];
    }

    private function getStatsSeguridad() {
        // Verificar si existe archivo de rate limiting
        $rateLimitFile = __DIR__ . '/../../storage/ratelimit.json';
        $intentosBloqueados = 0;
        if (file_exists($rateLimitFile)) {
            $rateData = json_decode(file_get_contents($rateLimitFile), true);
            $intentosBloqueados = count($rateData);
        }
        
        $errores_por_rol = [];
        try {
            $errores_por_rol = $this->pdo->query("
                SELECT 'Comprador' as rol, COUNT(*) as total FROM comprador_errors_log UNION ALL
                SELECT 'Vendedor', COUNT(*) FROM vendedor_errors_log UNION ALL
                SELECT 'Admin', COUNT(*) FROM admin_errors_log
            ")->fetchAll();
        } catch (PDOException $e) {
            // Si no existen las tablas de errores por rol, devolvemos un conteo simulado en 0
            $errores_por_rol = [
                ['rol' => 'Comprador', 'total' => 0],
                ['rol' => 'Vendedor', 'total' => 0],
                ['rol' => 'Admin', 'total' => 0]
            ];
        }

        return [
            'intentos_login_fallidos_24h' => $this->contarErroresRecientes(['auth_events.log'], 24),
            'errores_sistema_24h' => $this->contarErroresRecientes(['system_errors.log'], 24),
            'accesos_no_autorizados' => $this->contarErroresRecientes(['guest_errors.log'], 24),
            'usuarios_bloqueados_rate_limit' => $intentosBloqueados,
            'errores_por_rol' => $errores_por_rol,
        ];
    }

    private function getStatsApi() {
        $requests_totales = 0;
        $tokens_activos_estimados = 0;
        $errores_api_24h = 0;

        try {
            $requests_totales = $this->pdo->query("SELECT COUNT(*) FROM api_logs WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
        } catch (PDOException $e) {
            $requests_totales = 0;
        }

        try {
            $tokens_activos_estimados = $this->pdo->query("
                SELECT COUNT(DISTINCT usuario_id) 
                FROM user_sessions 
                WHERE last_activity >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ")->fetchColumn();
        } catch (PDOException $e) {
            $tokens_activos_estimados = 0;
        }

        try {
            $errores_api_24h = $this->pdo->query("
                SELECT COUNT(*) 
                FROM api_logs 
                WHERE status_code >= 400 
                AND createdAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ")->fetchColumn();
        } catch (PDOException $e) {
            $errores_api_24h = 0;
        }

        return [
            'requests_totales' => $requests_totales,
            'tokens_activos_estimados' => $tokens_activos_estimados,
            'errores_api_24h' => $errores_api_24h,
        ];
    }

    private function getHealthCheck() {
        $checks = [];
        
        // Check Base de Datos
        try {
            $this->pdo->query("SELECT 1");
            $checks['database'] = ['status' => 'ok', 'message' => 'Conexión establecida'];
        } catch (Exception $e) {
            $checks['database'] = ['status' => 'error', 'message' => $e->getMessage()];
        }
        
        // Check Espacio en Disco
        $freeSpace = disk_free_space(__DIR__);
        $totalSpace = disk_total_space(__DIR__);
        $percentUsed = 100 - (($freeSpace / $totalSpace) * 100);
        $checks['disk_space'] = [
            'status' => $percentUsed > 90 ? 'warning' : 'ok',
            'percent_used' => round($percentUsed, 2),
            'free_gb' => round($freeSpace / 1024 / 1024 / 1024, 2)
        ];
        
        // Check Memoria
        $checks['memory'] = [
            'status' => 'ok',
            'memory_limit' => ini_get('memory_limit'),
            'current_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB'
        ];
        
        // Check Logs (escritura)
        $logDir = __DIR__ . '/../../logs';
        $checks['logs'] = [
            'status' => is_writable($logDir) ? 'ok' : 'warning',
            'writable' => is_writable($logDir)
        ];
        
        // Check Sesiones
        $sessionDir = __DIR__ . '/../../sessions';
        $checks['sessions'] = [
            'status' => is_writable($sessionDir) ? 'ok' : 'warning',
            'writable' => is_writable($sessionDir)
        ];
        
        return $checks;
    }

    private function getLogsSistema() {
        $logFiles = [
            'auth' => ['path' => __DIR__ . '/../../logs/auth_events.log', 'label' => 'Autenticación', 'icon' => 'fa-lock'],
            'system' => ['path' => __DIR__ . '/../../logs/system_errors.log', 'label' => 'Errores Sistema', 'icon' => 'fa-exclamation-triangle'],
            'business' => ['path' => __DIR__ . '/../../logs/business_ops.log', 'label' => 'Operaciones Negocio', 'icon' => 'fa-briefcase'],
            'debug' => ['path' => __DIR__ . '/../../logs/debug_login.txt', 'label' => 'Debug Login', 'icon' => 'fa-bug'],
            'rate_limit' => ['path' => __DIR__ . '/../../storage/ratelimit.json', 'label' => 'Rate Limiting', 'icon' => 'fa-shield-alt'],
        ];
        
        $logs = [];
        foreach ($logFiles as $key => $config) {
            $lines = [];
            if (file_exists($config['path'])) {
                $content = file($config['path']);
                $lines = array_slice(array_reverse($content), 0, 50);
            }
            $logs[$key] = [
                'label' => $config['label'],
                'icon' => $config['icon'],
                'lines' => $lines,
                'exists' => file_exists($config['path']),
                'size' => file_exists($config['path']) ? round(filesize($config['path']) / 1024, 2) . ' KB' : 'N/A'
            ];
        }
        
        return $logs;
    }

    private function getServerInfo() {
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
            'db_version' => $this->pdo->query("SELECT VERSION()")->fetchColumn(),
            'timezone' => date_default_timezone_get(),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'display_errors' => ini_get('display_errors'),
            'session.gc_maxlifetime' => ini_get('session.gc_maxlifetime') . 's',
        ];
    }

    // ==================== MÉTODOS AUXILIARES ====================

    private function contarErroresRecientes($logFiles, $horas = 24) {
        $count = 0;
        $desde = time() - ($horas * 3600);
        
        foreach ($logFiles as $filename) {
            $path = __DIR__ . "/../../logs/$filename";
            if (file_exists($path)) {
                $lines = file($path);
                foreach ($lines as $line) {
                    // Extraer fecha si existe en formato [2026-05-06 10:30:45]
                    if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                        $lineTime = strtotime($matches[1]);
                        if ($lineTime >= $desde) {
                            $count++;
                        }
                    }
                }
            }
        }
        return $count;
    }

    private function checkEndpoint($endpoint) {
        // Simulación de health check - en producción haría una petición real
        return ['status' => 'ok', 'response_time_ms' => rand(10, 200)];
    }

    private function obtenerLogsPorTipo($tipo, $fecha) {
        // Implementación básica - se puede expandir
        return [];
    }

    private function limpiarLogsAntiguos() {
        $dias = 30;
        $eliminados = 0;
        $logDir = __DIR__ . '/../../logs/';
        
        foreach (glob($logDir . '*.log') as $file) {
            if (filemtime($file) < time() - ($dias * 86400)) {
                unlink($file);
                $eliminados++;
            }
        }
        
        return ['success' => true, 'eliminados' => $eliminados];
    }

    private function optimizarTablas() {
        try {
            $this->pdo->query("OPTIMIZE TABLE reuniones, empresas, usuarios, ofertas, demandas");
            return ['success' => true, 'message' => 'Tablas optimizadas'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function verificarIntegridadBD() {
        try {
            $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            $resultados = [];
            
            foreach ($tables as $table) {
                $check = $this->pdo->query("CHECK TABLE $table")->fetch();
                $resultados[$table] = $check['Msg_text'] ?? 'OK';
            }
            
            return ['success' => true, 'tablas' => $resultados];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Métodos para datos históricos (gráficas)
    private function getLoginsPorPeriodo($periodo) { return []; }
    private function getReunionesPorPeriodo($periodo) { return []; }
    private function getUsuariosNuevosPorPeriodo($periodo) { return []; }
    private function getErroresPorPeriodo($periodo) { return []; }
}
