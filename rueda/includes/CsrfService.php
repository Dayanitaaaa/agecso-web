<?php
/**
 * Servicio CSRF para protección de formularios
 * Genera y valida tokens CSRF por sesión
 */

class CsrfService {
    
    /**
     * Inicializa el almacenamiento CSRF en sesión
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
    }
    
    /**
     * Genera un nuevo token CSRF
     * 
     * @param string $formId Identificador del formulario (opcional)
     * @return string Token generado
     */
    public static function generateToken(string $formId = 'default'): string {
        self::init();
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$formId] = [
            'token' => $token,
            'time' => time()
        ];
        
        return $token;
    }
    
    /**
     * Valida un token CSRF
     * 
     * @param string $token Token recibido
     * @param string $formId Identificador del formulario
     * @param int $maxAge Tiempo máximo de validez (segundos)
     * @return bool
     */
    public static function validateToken(string $token, string $formId = 'default', int $maxAge = 3600): bool {
        self::init();
        
        if (empty($token) || !isset($_SESSION['csrf_tokens'][$formId])) {
            return false;
        }
        
        $stored = $_SESSION['csrf_tokens'][$formId];
        
        // Verificar expiración
        if ((time() - $stored['time']) > $maxAge) {
            unset($_SESSION['csrf_tokens'][$formId]);
            return false;
        }
        
        // Comparación segura
        $valid = hash_equals($stored['token'], $token);
        
        // Invalidar token usado (one-time use)
        if ($valid) {
            unset($_SESSION['csrf_tokens'][$formId]);
        }
        
        return $valid;
    }
    
    /**
     * Limpia tokens expirados de la sesión
     */
    public static function cleanup(int $maxAge = 3600) {
        self::init();
        
        $now = time();
        foreach ($_SESSION['csrf_tokens'] as $formId => $data) {
            if (($now - $data['time']) > $maxAge) {
                unset($_SESSION['csrf_tokens'][$formId]);
            }
        }
    }
    
    /**
     * Genera el HTML del input oculto CSRF
     */
    public static function getInputField(string $formId = 'default'): string {
        $token = self::generateToken($formId);
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
