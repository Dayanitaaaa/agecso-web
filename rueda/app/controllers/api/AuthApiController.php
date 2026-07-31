<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../models/UsuarioModel.php';
require_once __DIR__ . '/../../includes/RateLimitService.php';

/**
 * Controlador de autenticación para API
 * Endpoints para login y generación de JWT
 */
class AuthApiController extends BaseApiController {
    private $usuarioModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    /**
     * POST /api/auth/login
     * 
     * Body JSON: {"email": "...", "password": "..."}
     * Response: {"token": "...", "user": {...}}
     */
    public function login() {
        // Solo permitir POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendError("Método no permitido", 405);
        }

        // Rate limiting por IP
        $ip = RateLimitService::getClientIp();
        $ipCheck = RateLimitService::check($ip, 'api_login_ip');
        if (!$ipCheck['allowed']) {
            return $this->sendError($ipCheck['message'], 429, [
                'retry_after' => $ipCheck['retry_after'],
                'blocked_until' => $ipCheck['blocked_until']
            ]);
        }

        // Leer body JSON
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            RateLimitService::increment($ip, 'api_login_ip');
            return $this->sendError("Formato JSON inválido", 400);
        }

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        
        // Rate limiting por email (si se proporcionó)
        if (!empty($email)) {
            $emailCheck = RateLimitService::check($email, 'api_login_email');
            if (!$emailCheck['allowed']) {
                return $this->sendError($emailCheck['message'], 429, [
                    'retry_after' => $emailCheck['retry_after'],
                    'blocked_until' => $emailCheck['blocked_until']
                ]);
            }
        }

        // Validaciones básicas
        if (empty($email) || empty($password)) {
            return $this->sendError("Email y contraseña son obligatorios", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError("Formato de email inválido", 400);
        }

        try {
            // Autenticar usuario
            $usuario = $this->usuarioModel->login($email, $password);

            if (!$usuario) {
                // Incrementar contadores de intentos fallidos
                RateLimitService::increment($ip, 'api_login_ip');
                if (!empty($email)) {
                    RateLimitService::increment($email, 'api_login_email');
                }
                return $this->sendError("Credenciales inválidas", 401, [
                    'remaining_attempts' => $ipCheck['remaining'] ?? 0
                ]);
            }

            // Verificar estado de verificación de empresa (si aplica)
            if (!in_array($usuario['slugRole'], ['admin', 'superadmin'])) {
                $perfil = $this->usuarioModel->obtenerPerfilEmpresa($usuario['id']);
                
                if ($perfil && ($perfil['estado_verificacion'] ?? 'pendiente') !== 'aprobada') {
                    if ($perfil['estado_verificacion'] === 'rechazada') {
                        return $this->sendError("Cuenta rechazada. Contacte al administrador.", 403);
                    }
                    return $this->sendError("Cuenta pendiente de aprobación", 403);
                }
                
                // Obtener empresa_id para incluir en JWT
                $empresaId = $perfil['id'] ?? null;
            } else {
                $empresaId = null;
            }

            // Login exitoso: limpiar contadores de rate limiting
            RateLimitService::clear($ip, 'api_login_ip');
            RateLimitService::clear($email, 'api_login_email');
            
            // Limpiar datos sensibles antes de devolver
            unset($usuario['password']);
            unset($usuario['isActive']);
            
            // Generar JWT
            $token = JwtService::generate([
                'sub' => $usuario['id'],
                'role' => $usuario['slugRole'],
                'empresa_id' => $empresaId,
                'email' => $usuario['email']
            ]);

            // Respuesta exitosa (sin datos sensibles)
            return $this->sendSuccess([
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWT_EXPIRATION,
                'user' => [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombreUsuario'],
                    'email' => $usuario['email'],
                    'role' => $usuario['slugRole'],
                    'empresa_id' => $empresaId
                ]
            ], "Autenticación exitosa");

        } catch (Exception $e) {
            error_log('[API_LOGIN_ERROR] ' . $e->getMessage());
            return $this->sendError("Error en autenticación", 500);
        }
    }

    /**
     * GET /api/auth/me
     * 
     * Obtener información del usuario autenticado (vía JWT)
     */
    public function me() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        // Si es JWT, devolver info del token
        if ($this->isJwtAuth()) {
            return $this->sendSuccess([
                'usuario_id' => $this->getUsuarioId(),
                'role' => $this->getSlugRole(),
                'empresa_id' => $this->getEmpresaId(),
                'auth_method' => 'jwt'
            ]);
        }

        // Si es sesión, devolver info de sesión
        return $this->sendSuccess([
            'usuario_id' => $this->getUsuarioId(),
            'role' => $this->getSlugRole(),
            'empresa_id' => $this->getEmpresaId(),
            'auth_method' => 'session'
        ]);
    }

    /**
     * POST /api/auth/refresh
     * 
     * Refrescar token JWT (generar nuevo con nueva expiración)
     * Requiere: Authorization: Bearer <token_válido>
     */
    public function refresh() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        // Solo permitir refresh si viene por JWT
        if (!$this->isJwtAuth()) {
            return $this->sendError("Refresh solo disponible para tokens JWT", 400);
        }

        // Generar nuevo token con misma info pero nuevo tiempo
        $token = JwtService::generate([
            'sub' => $this->getUsuarioId(),
            'role' => $this->getSlugRole(),
            'empresa_id' => $this->getEmpresaId()
        ]);

        return $this->sendSuccess([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWT_EXPIRATION
        ], "Token refrescado");
    }
}
