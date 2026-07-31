<?php
class Logger {
    /**
     * Sistema de logs especializados para AGECSO
     * @param string $message Mensaje a guardar
     * @param string $type Tipo de log: 'auth', 'business', 'system'
     */
    public static function log($message, $type = 'system') {
        $log_dir = __DIR__ . '/../logs/';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }

        $files = [
            'auth'     => $log_dir . 'auth_events.log',   // Login, Registro, Logout
            'business' => $log_dir . 'business_ops.log', // CRUD Ruedas, Cierres de montos
            'system'   => $log_dir . 'system_errors.log' // Excepciones PDO, Errores PHP
        ];
        
        $path = $files[$type] ?? $files['system'];
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] " . strtoupper($type) . ": $message" . PHP_EOL;
        
        file_put_contents($path, $log_entry, FILE_APPEND);
    }

    /**
     * Registra errores por rol para facilitar trazabilidad.
     * @param string $roleSlug superadmin|admin|comprador|proveedor|vendedor
     * @param string $message Mensaje de error
     * @param array $context Datos adicionales (usuario, email, accion, etc)
     */
    public static function logRoleError($roleSlug, $message, $context = []) {
        $log_dir = __DIR__ . '/../logs/';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }

        $normalizedRole = strtolower(trim((string)$roleSlug));
        if ($normalizedRole === 'vendedor') {
            $normalizedRole = 'proveedor';
        }

        $roleFiles = [
            'superadmin' => $log_dir . 'superadmin_errors.log',
            'admin' => $log_dir . 'admin_errors.log',
            'comprador' => $log_dir . 'comprador_errors.log',
            'proveedor' => $log_dir . 'proveedor_errors.log',
            'guest' => $log_dir . 'guest_errors.log'
        ];

        $path = $roleFiles[$normalizedRole] ?? $roleFiles['guest'];
        $timestamp = date('Y-m-d H:i:s');
        $contextText = '';

        if (!empty($context)) {
            $safeContext = [];
            foreach ($context as $key => $value) {
                $safeContext[] = $key . '=' . str_replace(["\n", "\r"], ' ', (string)$value);
            }
            $contextText = ' | ' . implode(' | ', $safeContext);
        }

        $logEntry = "[$timestamp] ROLE_ERROR[$normalizedRole]: $message$contextText" . PHP_EOL;
        try {
            file_put_contents($path, $logEntry, FILE_APPEND);
        } catch (Exception $e) {
            // Silenciar error de logs para no interrumpir el flujo
            error_log("Error al escribir en log de rol ($normalizedRole): " . $e->getMessage());
        }
    }

    /**
     * Registra error usando el rol actual en sesión.
     */
    public static function logCurrentRoleError($message, $context = []) {
        $roleSlug = 'guest';

        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['slugRole'])) {
            $roleSlug = $_SESSION['slugRole'];
        }

        self::logRoleError($roleSlug, $message, $context);
    }
}
?>
