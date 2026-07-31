<?php
/**
 * Servicio de Rate Limiting para protección contra fuerza bruta
 * Almacena intentos en archivo JSON para persistencia
 */

class RateLimitService {
    
    private static $storageFile;
    private static $maxAttempts = 5;        // Intentos máximos permitidos
    private static $windowSeconds = 900;   // Ventana de tiempo: 15 minutos
    private static $blockDuration = 1800;  // Bloqueo: 30 minutos tras exceder
    
    /**
     * Inicializar servicio
     */
    private static function init() {
        if (!self::$storageFile) {
            self::$storageFile = __DIR__ . '/../storage/ratelimit.json';
        }
        // Crear directorio si no existe
        $dir = dirname(self::$storageFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
        }
    }
    
    /**
     * Verificar si una acción está permitida
     * 
     * @param string $identifier Identificador único (IP, email, user_id)
     * @param string $action Tipo de acción ('login', 'api_login', etc.)
     * @return array ['allowed' => bool, 'remaining' => int, 'reset_at' => int, 'retry_after' => int]
     */
    public static function check(string $identifier, string $action = 'login'): array {
        self::init();
        
        $key = self::sanitizeKey($identifier . ':' . $action);
        $data = self::loadData();
        $now = time();
        
        // Limpiar entradas expiradas
        self::cleanup($data, $now);
        
        // Si no existe o expiró, crear nueva entrada
        if (!isset($data[$key]) || $data[$key]['reset_at'] < $now) {
            $data[$key] = [
                'attempts' => 0,
                'reset_at' => $now + self::$windowSeconds,
                'blocked_until' => 0,
                'first_attempt' => $now
            ];
        }
        
        $entry = &$data[$key];
        
        // Verificar si está bloqueado
        if ($entry['blocked_until'] > $now) {
            $result = [
                'allowed' => false,
                'remaining' => 0,
                'reset_at' => $entry['reset_at'],
                'blocked_until' => $entry['blocked_until'],
                'retry_after' => $entry['blocked_until'] - $now,
                'message' => "Demasiados intentos fallidos. Espera " . ceil(($entry['blocked_until'] - $now) / 60) . " minutos."
            ];
            self::saveData($data);
            return $result;
        }
        
        // Calcular intentos restantes
        $remaining = max(0, self::$maxAttempts - $entry['attempts']);
        
        $result = [
            'allowed' => true,
            'remaining' => $remaining,
            'reset_at' => $entry['reset_at'],
            'retry_after' => 0,
            'message' => $remaining <= 2 ? "Quedan {$remaining} intentos." : null
        ];
        
        self::saveData($data);
        return $result;
    }
    
    /**
     * Registrar un intento fallido
     * 
     * @param string $identifier Identificador único
     * @param string $action Tipo de acción
     * @return array Resultado actualizado
     */
    public static function increment(string $identifier, string $action = 'login'): array {
        self::init();
        
        $key = self::sanitizeKey($identifier . ':' . $action);
        $data = self::loadData();
        $now = time();
        
        if (!isset($data[$key]) || $data[$key]['reset_at'] < $now) {
            $data[$key] = [
                'attempts' => 0,
                'reset_at' => $now + self::$windowSeconds,
                'blocked_until' => 0,
                'first_attempt' => $now
            ];
        }
        
        $data[$key]['attempts']++;
        
        // Si excede el límite, bloquear
        if ($data[$key]['attempts'] >= self::$maxAttempts) {
            $data[$key]['blocked_until'] = $now + self::$blockDuration;
            $data[$key]['reset_at'] = $data[$key]['blocked_until']; // Extender ventana
            
            // Log del bloqueo
            error_log("[RATE_LIMIT] Bloqueado: {$identifier} para {$action}. Intentos: {$data[$key]['attempts']}");
        }
        
        self::saveData($data);
        
        return self::check($identifier, $action);
    }
    
    /**
     * Registrar intento exitoso (limpia el contador)
     * 
     * @param string $identifier Identificador único
     * @param string $action Tipo de acción
     */
    public static function clear(string $identifier, string $action = 'login'): void {
        self::init();
        
        $key = self::sanitizeKey($identifier . ':' . $action);
        $data = self::loadData();
        
        if (isset($data[$key])) {
            unset($data[$key]);
            self::saveData($data);
        }
    }
    
    /**
     * Obtener IP del cliente (considerando proxies)
     * 
     * @return string
     */
    public static function getClientIp(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Si es lista, tomar el primero
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                // Validar IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
                // Si es privada, igual devolverla (para desarrollo local)
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Cargar datos de archivo (con bloqueo para concurrencia)
     */
    private static function loadData(): array {
        if (!file_exists(self::$storageFile)) {
            return [];
        }
        
        $content = file_get_contents(self::$storageFile);
        if (!$content) {
            return [];
        }
        
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }
    
    /**
     * Guardar datos en archivo (con bloqueo para concurrencia)
     */
    private static function saveData(array $data): bool {
        $fp = fopen(self::$storageFile, 'c+');
        if (!$fp) {
            error_log('[RATE_LIMIT] No se pudo abrir archivo de rate limiting');
            return false;
        }
        
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            error_log('[RATE_LIMIT] No se pudo obtener lock');
            return false;
        }
        
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        
        return true;
    }
    
    /**
     * Limpiar entradas expiradas
     */
    private static function cleanup(array &$data, int $now): void {
        foreach ($data as $key => $entry) {
            // Eliminar si ya pasó el tiempo de bloqueo + ventana
            $expireTime = max($entry['reset_at'], $entry['blocked_until'] ?? 0) + 3600;
            if ($expireTime < $now) {
                unset($data[$key]);
            }
        }
    }
    
    /**
     * Sanitizar clave para almacenamiento seguro
     */
    private static function sanitizeKey(string $key): string {
        // Reemplazar caracteres problemáticos
        return preg_replace('/[^a-zA-Z0-9:@._-]/', '', $key);
    }
}
