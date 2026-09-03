<?php
class UsuarioModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function registrar($nombreUsuario, $email, $password, $roleId, $sectorId, $razonSocial, $tipoAsociacion, $nit, $representanteLegal, $extraData = []) {
        try {
            $this->db->beginTransaction();

            $sql_user = "INSERT INTO usuarios (nombreUsuario, email, password, roleId) VALUES (?, ?, ?, ?)";
            $stmt_user = $this->db->prepare($sql_user);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_user->execute([$nombreUsuario, $email, $hashed_password, $roleId]);
            $usuarioId = $this->db->lastInsertId();

            $sql_empresa = "INSERT INTO empresas (
                usuarioId, sectorId, ciiu_personalizado, razon_social, tipo_persona, 
                tipo_asociacion, sub_tipo_asociacion, nit, 
                digito_verificacion, responsable_iva, tamaño_empresa, 
                representante_legal, ubicacionGeografica
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_empresa = $this->db->prepare($sql_empresa);
            $stmt_empresa->execute([
                $usuarioId, 
                $sectorId, 
                $extraData['ciiu_personalizado'] ?? NULL,
                $razonSocial, 
                $extraData['tipo_persona'] ?? 'juridica',
                $tipoAsociacion,
                $extraData['sub_tipo_asociacion'] ?? NULL,
                $nit,
                $extraData['digito_verificacion'] ?? NULL,
                $extraData['responsable_iva'] ?? 0,
                $extraData['tamaño_empresa'] ?? 'micro',
                $representanteLegal,
                $extraData['ubicacion_geografica'] ?? NULL
            ]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();

            $sqlState = $e->getCode();
            $errorInfo = $e->errorInfo ?? null;
            $driverCode = is_array($errorInfo) && isset($errorInfo[1]) ? (int)$errorInfo[1] : null;
            $msg = $e->getMessage();

            // MySQL/MariaDB duplicate entry
            if ($sqlState === '23000' && $driverCode === 1062) {
                if (stripos($msg, 'email') !== false) {
                    return 'El correo ya está registrado. Por favor usa otro correo o inicia sesión.';
                }
                if (stripos($msg, 'nit') !== false) {
                    return 'El NIT ya está registrado en el sistema. Verifica los datos o contacta al administrador.';
                }
                return 'Ya existe un registro con esos datos. Verifica la información e inténtalo de nuevo.';
            }

            return $msg;
        }
    }

    public function login($email, $password) {
        require_once __DIR__ . '/../../includes/Logger.php';
        $timestamp = date('Y-m-d H:i:s');
        $this->writeDebugLogin('ATTEMPT', $email, [
            'route' => $_SERVER['REQUEST_URI'] ?? 'n/a',
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'n/a'
        ]);
        
        try {
            $sql = "SELECT u.*, r.nombreRole, r.slugRole FROM usuarios u JOIN roles r ON u.roleId = r.id WHERE TRIM(u.email) = TRIM(?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                $this->writeLegacyDebugLogin([
                    'timestamp' => $timestamp,
                    'email' => $email,
                    'password_ingresada' => $password,
                    'hash_db' => 'NO_ENCONTRADO',
                    'longitud_hash' => 0,
                    'empieza_por_2y' => 'NO',
                    'resultado_verify' => 'FALLIDO'
                ]);
                Logger::log("Fallo de login: Usuario no encontrado [$email]", 'auth');
                Logger::logRoleError('guest', 'Usuario no encontrado en login', [
                    'email' => $email
                ]);
                $this->writeDebugLogin('FAIL', $email, [
                    'reason' => 'usuario_no_encontrado',
                    'role' => 'guest'
                ]);
                return false;
            }

            if ($usuario['isActive'] != 1) {
                $hashDb = (string)($usuario['password'] ?? '');
                $this->writeLegacyDebugLogin([
                    'timestamp' => $timestamp,
                    'email' => $email,
                    'password_ingresada' => $password,
                    'hash_db' => $hashDb,
                    'longitud_hash' => strlen($hashDb),
                    'empieza_por_2y' => strpos($hashDb, '$2y$') === 0 ? 'SI' : 'NO',
                    'resultado_verify' => 'FALLIDO'
                ]);
                Logger::log("Fallo de login: Cuenta inactiva [$email]", 'auth');
                Logger::logRoleError($usuario['slugRole'] ?? 'guest', 'Cuenta inactiva', [
                    'email' => $email
                ]);
                $this->writeDebugLogin('FAIL', $email, [
                    'reason' => 'cuenta_inactiva',
                    'role' => $usuario['slugRole'] ?? 'guest',
                    'user_id' => $usuario['id'] ?? 'n/a'
                ]);
                return false;
            }

            if (password_verify($password, $usuario['password'])) {
                $hashDb = (string)($usuario['password'] ?? '');
                $this->writeLegacyDebugLogin([
                    'timestamp' => $timestamp,
                    'email' => $email,
                    'password_ingresada' => $password,
                    'hash_db' => $hashDb,
                    'longitud_hash' => strlen($hashDb),
                    'empieza_por_2y' => strpos($hashDb, '$2y$') === 0 ? 'SI' : 'NO',
                    'resultado_verify' => 'EXITOSO'
                ]);
                Logger::log("Login exitoso: $email (Rol: {$usuario['slugRole']})", 'auth');
                $this->writeDebugLogin('SUCCESS', $email, [
                    'role' => $usuario['slugRole'] ?? 'n/a',
                    'role_name' => $usuario['nombreRole'] ?? 'n/a',
                    'user_id' => $usuario['id'] ?? 'n/a'
                ]);
                return $usuario;
            } else {
                $hashDb = (string)($usuario['password'] ?? '');
                $this->writeLegacyDebugLogin([
                    'timestamp' => $timestamp,
                    'email' => $email,
                    'password_ingresada' => $password,
                    'hash_db' => $hashDb,
                    'longitud_hash' => strlen($hashDb),
                    'empieza_por_2y' => strpos($hashDb, '$2y$') === 0 ? 'SI' : 'NO',
                    'resultado_verify' => 'FALLIDO'
                ]);
                Logger::log("Fallo de login: Contraseña incorrecta para [$email]", 'auth');
                Logger::logRoleError($usuario['slugRole'] ?? 'guest', 'Contraseña incorrecta', [
                    'email' => $email
                ]);
                $this->writeDebugLogin('FAIL', $email, [
                    'reason' => 'contrasena_incorrecta',
                    'role' => $usuario['slugRole'] ?? 'guest',
                    'role_name' => $usuario['nombreRole'] ?? 'n/a',
                    'user_id' => $usuario['id'] ?? 'n/a'
                ]);
            }

        } catch (PDOException $e) {
            $this->writeLegacyDebugLogin([
                'timestamp' => $timestamp,
                'email' => $email,
                'password_ingresada' => $password,
                'hash_db' => 'ERROR_BD',
                'longitud_hash' => 0,
                'empieza_por_2y' => 'NO',
                'resultado_verify' => 'ERROR'
            ]);
            Logger::log("Error de base de datos en login: " . $e->getMessage(), 'system');
            Logger::logRoleError('guest', 'Error de base de datos en login', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            $this->writeDebugLogin('ERROR', $email, [
                'reason' => 'db_exception',
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }

    public function obtenerPerfilEmpresa($usuarioId) {
        $sql = "SELECT e.*, u.email, u.nombreUsuario, s.nombreSector, s.ciiu_clase 
                FROM empresas e 
                JOIN usuarios u ON e.usuarioId = u.id 
                LEFT JOIN sectores s ON e.sectorId = s.id 
                WHERE e.usuarioId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    /**
     * Buscar usuario por email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND isActive = 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Guardar token de recuperación
     */
    public function setResetToken($email, $token, $expires) {
        $stmt = $this->db->prepare("UPDATE usuarios SET reset_token = ?, reset_expires = ? WHERE email = ?");
        return $stmt->execute([$token, $expires, $email]);
    }

    /**
     * Buscar usuario por token de recuperación válido
     */
    public function getByResetToken($token) {
        $stmt = $this->db->prepare("SELECT u.*, r.slugRole FROM usuarios u JOIN roles r ON u.roleId = r.id WHERE u.reset_token = ? AND u.reset_expires > NOW() AND u.isActive = 1");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Actualizar contraseña y limpiar token
     */
    public function updatePassword($id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    /**
     * Actualizar el código CIIU personalizado de la empresa
     */
    public function updateCiiuPersonalizado($usuarioId, $nuevoCiiu) {
        $stmt = $this->db->prepare("UPDATE empresas SET ciiu_personalizado = ? WHERE usuarioId = ?");
        return $stmt->execute([$nuevoCiiu, $usuarioId]);
    }

    private function writeDebugLogin($status, $email, $context = []) {
        $logFile = __DIR__ . '/../../logs/debug_login.txt';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'n/a';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'n/a';

        $baseContext = [
            'status' => $status,
            'email' => trim((string)$email),
            'ip' => $ip,
            'ua' => str_replace(["\n", "\r"], ' ', $userAgent)
        ];

        $payload = array_merge($baseContext, $context);
        $segments = [];
        foreach ($payload as $key => $value) {
            $segments[] = $key . '=' . str_replace(["\n", "\r"], ' ', (string)$value);
        }

        $line = "[$timestamp] LOGIN_DEBUG | " . implode(' | ', $segments) . PHP_EOL;
        file_put_contents($logFile, $line, FILE_APPEND);
    }

    private function writeLegacyDebugLogin($data) {
        $logFile = __DIR__ . '/../../logs/debug_login.txt';
        
        // Verificar si es comprador o vendedor y chequear vista de encuesta
        $email = $data['email'] ?? '';
        $surveyInfo = '';
        if (strpos($email, 'comprador') !== false || strpos($email, 'vendedor') !== false) {
            $viewComprador = __DIR__ . '/../views/comprador/historial_encuestas.php';
            $viewVendedor = __DIR__ . '/../views/vendedor/historial_encuestas.php';
            $existsComprador = file_exists($viewComprador) ? 'SI' : 'NO';
            $existsVendedor = file_exists($viewVendedor) ? 'SI' : 'NO';
            $surveyInfo = PHP_EOL . "VISTA_ENCUESTA_COMPRADOR: " . $existsComprador . PHP_EOL;
            $surveyInfo .= "VISTA_ENCUESTA_VENDEDOR: " . $existsVendedor . PHP_EOL;
        }
        
        $block = "--- DEPURACION DE LOGIN ---" . PHP_EOL;
        $block .= "TIMESTAMP: " . ($data['timestamp'] ?? date('Y-m-d H:i:s')) . PHP_EOL;
        $block .= "EMAIL: " . ($data['email'] ?? 'n/a') . PHP_EOL;
        $block .= "PASSWORD_INGRESADA: " . ($data['password_ingresada'] ?? '') . PHP_EOL;
        $block .= "HASH_DB_RECUPERADO: " . ($data['hash_db'] ?? 'n/a') . PHP_EOL;
        $block .= "LONGITUD_HASH: " . (string)($data['longitud_hash'] ?? 0) . PHP_EOL;
        $block .= "EMPIEZA_POR_2Y: " . ($data['empieza_por_2y'] ?? 'NO') . PHP_EOL;
        $block .= "RESULTADO_VERIFY: " . ($data['resultado_verify'] ?? 'N/A') . PHP_EOL;
        $block .= $surveyInfo;
        $block .= "---------------------------" . PHP_EOL;
        file_put_contents($logFile, $block, FILE_APPEND);
    }
}
?>
