<?php
/**
 * Controlador base para todas las respuestas API
 */
require_once __DIR__ . '/../../../includes/JwtService.php';

class BaseApiController {
    protected $pdo;
    protected $currentUser = null; // Datos del usuario autenticado (JWT o sesión)

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Establecer cabecera JSON por defecto
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Respuesta exitosa estandarizada
     */
    protected function sendSuccess($data = [], $message = "Operación exitosa", $code = 200) {
        http_response_code($code);
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c')
        ]);
        exit();
    }

    /**
     * Respuesta de error estandarizada
     */
    protected function sendError($message = "Error interno", $code = 500, $errors = []) {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date('c')
        ]);
        exit();
    }

    /**
     * Verificar si la petición es autenticada (JWT primero, fallback a Sesión)
     * 
     * @return bool
     */
    protected function isAuthenticated() {
        // 1. Intentar autenticar por JWT (para API/móvil)
        $token = JwtService::extractFromHeader();
        if ($token) {
            $payload = JwtService::validate($token);
            if ($payload && isset($payload['sub'])) {
                $this->currentUser = [
                    'usuario_id' => $payload['sub'],
                    'slugRole' => $payload['role'] ?? null,
                    'empresa_id' => $payload['empresa_id'] ?? null,
                    'auth_method' => 'jwt'
                ];
                return true;
            }
        }
        
        // 2. Fallback a sesión (para web compatibilidad)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['usuario_id'])) {
            $this->currentUser = [
                'usuario_id' => $_SESSION['usuario_id'],
                'slugRole' => $_SESSION['slugRole'] ?? null,
                'empresa_id' => $_SESSION['empresa_id'] ?? null,
                'auth_method' => 'session'
            ];
            return true;
        }
        
        return false;
    }
    
    /**
     * Obtener ID del usuario autenticado
     * @return int|null
     */
    protected function getUsuarioId() {
        return $this->currentUser['usuario_id'] ?? null;
    }
    
    /**
     * Obtener rol del usuario autenticado
     * @return string|null
     */
    protected function getSlugRole() {
        return $this->currentUser['slugRole'] ?? null;
    }
    
    /**
     * Obtener empresa ID del usuario autenticado
     * @return int|null
     */
    protected function getEmpresaId() {
        // Si está en currentUser, usarlo
        if (isset($this->currentUser['empresa_id']) && $this->currentUser['empresa_id']) {
            return $this->currentUser['empresa_id'];
        }
        
        // Si no, buscar en BD (fallback)
        $usuarioId = $this->getUsuarioId();
        if (!$usuarioId) return null;
        
        $stmt = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
        $stmt->execute([$usuarioId]);
        $empresaId = $stmt->fetchColumn();
        
        // Cachear para esta request
        $this->currentUser['empresa_id'] = $empresaId;
        
        return $empresaId;
    }
    
    /**
     * Verificar si el método de auth es JWT (vs sesión)
     * @return bool
     */
    protected function isJwtAuth(): bool {
        return ($this->currentUser['auth_method'] ?? null) === 'jwt';
    }
}
