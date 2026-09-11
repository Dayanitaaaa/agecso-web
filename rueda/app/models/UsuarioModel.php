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
                usuarioId, sectorId, ciiu_personalizado, ciiu_nombre_personalizado, razon_social, tipo_persona, 
                tipo_asociacion, sub_tipo_asociacion, nit, 
                digito_verificacion, responsable_iva, tamaño_empresa, 
                representante_legal, ubicacionGeografica
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_empresa = $this->db->prepare($sql_empresa);
            $stmt_empresa->execute([
                $usuarioId, 
                $sectorId, 
                $extraData['ciiu_personalizado'] ?? NULL,
                $extraData['ciiu_nombre_personalizado'] ?? NULL,
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
        $email = strtolower(trim((string)$email));
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE LOWER(TRIM(email)) = ? AND (isActive = 1 OR isActive IS NULL) LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Asegurar que existan las columnas de reset_token y reset_expires
     */
    private function ensureResetColumnsExist() {
        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'reset_token'");
            if ($stmt && !$stmt->fetch()) {
                $this->db->exec("ALTER TABLE usuarios ADD COLUMN reset_token VARCHAR(255) NULL, ADD COLUMN reset_expires DATETIME NULL");
            }
        } catch (Exception $e) {}
    }

    /**
     * Guardar token de recuperación
     */
    public function setResetToken($email, $token, $expires) {
        $this->ensureResetColumnsExist();
        $email = strtolower(trim((string)$email));
        $stmt = $this->db->prepare("UPDATE usuarios SET reset_token = ?, reset_expires = ? WHERE LOWER(TRIM(email)) = ?");
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
    public function updateCiiuPersonalizado($usuarioId, $nuevoCiiu, $nuevoNombre = null) {
        $stmt = $this->db->prepare("UPDATE empresas SET ciiu_personalizado = ?, ciiu_nombre_personalizado = ? WHERE usuarioId = ?");
        return $stmt->execute([$nuevoCiiu, $nuevoNombre, $usuarioId]);
    }

    /**
     * Actualizar perfil completo (Usuario + Empresa) para cualquier rol
     */
    public function actualizarPerfilCompleto($usuarioId, $data) {
        // 1. Asegurar columnas en empresas antes de iniciar cualquier transacción
        try {
            $stmt_col = $this->db->query("SHOW COLUMNS FROM empresas LIKE 'descripcion'");
            if ($stmt_col && !$stmt_col->fetch()) {
                $this->db->exec("ALTER TABLE empresas ADD COLUMN descripcion TEXT NULL");
            }
            $stmt_ciiu = $this->db->query("SHOW COLUMNS FROM empresas LIKE 'ciiu_personalizado'");
            if ($stmt_ciiu && !$stmt_ciiu->fetch()) {
                $this->db->exec("ALTER TABLE empresas ADD COLUMN ciiu_personalizado VARCHAR(20) NULL, ADD COLUMN ciiu_nombre_personalizado VARCHAR(255) NULL");
            }
        } catch (Exception $e) {}

        try {
            $this->db->beginTransaction();

            // 2. Actualizar datos de usuario
            if (!empty($data['email']) || !empty($data['nombreUsuario'])) {
                $params_u = [];
                $sql_u = "UPDATE usuarios SET ";
                $fields_u = [];
                if (!empty($data['nombreUsuario'])) {
                    $fields_u[] = "nombreUsuario = ?";
                    $params_u[] = $data['nombreUsuario'];
                }
                if (!empty($data['email'])) {
                    $fields_u[] = "email = ?";
                    $params_u[] = $data['email'];
                }
                if (!empty($data['password'])) {
                    $fields_u[] = "password = ?";
                    $params_u[] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                if (!empty($fields_u)) {
                    $sql_u .= implode(', ', $fields_u) . " WHERE id = ?";
                    $params_u[] = $usuarioId;
                    $stmt_u = $this->db->prepare($sql_u);
                    $stmt_u->execute($params_u);
                }
            }

            // 3. Verificar si existe registro en la tabla empresas
            $stmt_check = $this->db->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
            $stmt_check->execute([$usuarioId]);
            $empresaId = $stmt_check->fetchColumn();

            $razonSocial = !empty($data['razon_social']) ? $data['razon_social'] : ($data['nombreUsuario'] ?? 'Empresa');
            $repLegal = !empty($data['representante_legal']) ? $data['representante_legal'] : ($data['nombreUsuario'] ?? '');
            $nit = !empty($data['nit']) ? $data['nit'] : 'N/A';
            $dv = !empty($data['digito_verificacion']) ? $data['digito_verificacion'] : null;
            $tipoPersona = !empty($data['tipo_persona']) ? $data['tipo_persona'] : 'juridica';
            $tipoAsociacion = !empty($data['tipo_asociacion']) ? $data['tipo_asociacion'] : 'S.A.S.';
            $subTipoAsociacion = !empty($data['sub_tipo_asociacion']) ? $data['sub_tipo_asociacion'] : null;
            $respIva = isset($data['responsable_iva']) ? (int)$data['responsable_iva'] : 0;
            $tamano = !empty($data['tamaño_empresa']) ? $data['tamaño_empresa'] : 'micro';
            $ubicacion = !empty($data['ubicacionGeografica']) ? $data['ubicacionGeografica'] : '';
            $descripcion = !empty($data['descripcion']) ? $data['descripcion'] : '';
            $ciiu = !empty($data['ciiu_personalizado']) ? $data['ciiu_personalizado'] : null;
            $ciiuNombre = !empty($data['ciiu_nombre_personalizado']) ? $data['ciiu_nombre_personalizado'] : null;

            if ($empresaId) {
                $sql_e = "UPDATE empresas SET 
                            razon_social = ?, 
                            representante_legal = ?, 
                            nit = ?, 
                            digito_verificacion = ?, 
                            tipo_persona = ?, 
                            tipo_asociacion = ?, 
                            sub_tipo_asociacion = ?, 
                            responsable_iva = ?, 
                            tamaño_empresa = ?, 
                            ubicacionGeografica = ?, 
                            descripcion = ?,
                            ciiu_personalizado = ?,
                            ciiu_nombre_personalizado = ?
                          WHERE id = ?";
                $stmt_e = $this->db->prepare($sql_e);
                $stmt_e->execute([
                    $razonSocial,
                    $repLegal,
                    $nit,
                    $dv,
                    $tipoPersona,
                    $tipoAsociacion,
                    $subTipoAsociacion,
                    $respIva,
                    $tamano,
                    $ubicacion,
                    $descripcion,
                    $ciiu,
                    $ciiuNombre,
                    $empresaId
                ]);
            } else {
                // Si el usuario (por ejemplo Admin) no tenía fila en empresas, crearla
                $sql_e = "INSERT INTO empresas (
                            usuarioId, razon_social, representante_legal, nit, digito_verificacion,
                            tipo_persona, tipo_asociacion, sub_tipo_asociacion, responsable_iva,
                            tamaño_empresa, ubicacionGeografica, descripcion, ciiu_personalizado,
                            ciiu_nombre_personalizado, sectorId, estado_verificacion
                          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 'aprobada')";
                $stmt_e = $this->db->prepare($sql_e);
                $stmt_e->execute([
                    $usuarioId,
                    $razonSocial,
                    $repLegal,
                    $nit,
                    $dv,
                    $tipoPersona,
                    $tipoAsociacion,
                    $subTipoAsociacion,
                    $respIva,
                    $tamano,
                    $ubicacion,
                    $descripcion,
                    $ciiu,
                    $ciiuNombre
                ]);
            }

            if ($this->db->inTransaction()) {
                $this->db->commit();
            }
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return $e->getMessage();
        }
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
