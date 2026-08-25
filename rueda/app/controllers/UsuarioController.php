<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../../includes/Logger.php';
require_once __DIR__ . '/../../includes/CsrfService.php';
require_once __DIR__ . '/../../includes/RateLimitService.php';

class UsuarioController {
    private $pdo;
    private $usuarioModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->usuarioModel = new UsuarioModel($this->pdo);
    }

    public function registro() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Si ya está logueado, redirigir a su dashboard según el rol
            if (isset($_SESSION['usuario_id']) && isset($_SESSION['slugRole'])) {
                $this->redirigirPorRol($_SESSION['slugRole']);
                exit();
            }

            $mensaje = "";
            $sectores = [];
            
            // Obtener sectores CIIU
            try {
                $stmt_sectores = $this->pdo->query("
                    SELECT 
                        s.id,
                        s.nombreSector,
                        s.ciiu_clase,
                        CONCAT(s.ciiu_clase, ' - ', s.nombreSector) as display_text
                    FROM sectores s
                    WHERE s.ciiu_clase IS NOT NULL
                    ORDER BY s.ciiu_clase ASC
                ");
                $sectores = $stmt_sectores->fetchAll();
            } catch (PDOException $e) {
                Logger::logRoleError('guest', 'Error cargando sectores CIIU', ['error' => $e->getMessage()]);
            }

            // Si es GET sin editar, limpiar sesión y mostrar Paso 1 vacío
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['editar'])) {
                unset($_SESSION['reg_data']);

                // Capturar rol preseleccionado desde la web principal
                if (isset($_GET['rol'])) {
                    $rol_slug = strtolower(trim($_GET['rol']));
                    $rol_id = in_array($rol_slug, ['vendedor', 'proveedor']) ? '3' : '4';
                    $_SESSION['reg_data']['rol_id'] = $rol_id;
                } elseif (isset($_GET['rol_id'])) {
                    $_SESSION['reg_data']['rol_id'] = (int)$_GET['rol_id'];
                }

                // Guardar ID de rueda de negocio a la que se desea postular
                if (!empty($_GET['rueda_id'])) {
                    $_SESSION['rueda_registro_id'] = (int)$_GET['rueda_id'];
                }
            }
            // Si viene de editar (Corregir), mantener datos en sesión para precargar Paso 1

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $paso = $_POST['paso'] ?? '1';
                
                // Validar CSRF según el paso
                $csrfToken = $_POST['csrf_token'] ?? '';
                $formId = ($paso == '2') ? 'registro_paso2' : (($paso == 'confirmar') ? 'registro_confirmar' : 'registro_paso1');
                
                if (!CsrfService::validateToken($csrfToken, $formId)) {
                    throw new Exception("Token de seguridad inválido o expirado. Por favor recarga la página.");
                }

                if ($paso == '1') {
                    // Obtener información del sector CIIU seleccionado
                    $sectorId = $_POST['sector_id'];
                    $stmt_sector = $this->pdo->prepare("SELECT ciiu_clase, nombreSector FROM sectores WHERE id = ?");
                    $stmt_sector->execute([$sectorId]);
                    $sectorInfo = $stmt_sector->fetch();

                    $nit = preg_replace('/\D+/', '', (string)($_POST['nit'] ?? ''));
                    $dv = preg_replace('/\D+/', '', (string)($_POST['digito_verificacion'] ?? ''));

                    if ($nit === '' || !preg_match('/^\d{1,10}$/', $nit)) {
                        throw new Exception("El NIT/Identificación debe contener solo números y máximo 10 dígitos.");
                    }

                    if ($dv !== '' && !preg_match('/^\d{1}$/', $dv)) {
                        throw new Exception("El dígito de verificación (DV) debe contener solo 1 número.");
                    }

                    // Guardar datos del paso 1 en sesión
                    $_SESSION['reg_data'] = [
                        'tipo_persona' => $_POST['tipo_persona'],
                        'razon_social' => $_POST['razon_social'],
                        'nit' => $nit,
                        'digito_verificacion' => $dv,
                        'responsable_iva' => $_POST['responsable_iva'],
                        'tamaño_empresa' => $_POST['tamaño_empresa'],
                        'ubicacion_geografica' => $_POST['ubicacion_geografica'],
                        'tipo_asociacion' => $_POST['tipo_asociacion'],
                        'sub_tipo_asociacion' => $_POST['sub_tipo_asociacion'] ?? '',
                        'representante_legal' => $_POST['representante_legal'],
                        'sector_id' => $sectorId,
                        'ciiu_clase' => $sectorInfo['ciiu_clase'] ?? 'N/A',
                        'nombre_sector' => $sectorInfo['nombreSector'] ?? 'N/A',
                        'rol_id' => $_POST['rol_id']
                    ];
                    require_once __DIR__ . '/../views/usuario/user_registro_confirmar.php';
                    exit();
                } elseif ($paso == 'confirmar') {
                    // Ir al paso 2: Credenciales de acceso
                    require_once __DIR__ . '/../views/usuario/user_registro_paso2.php';
                    exit();
                } elseif ($paso == '2') {
                    // Procesar registro final
                    $email = trim($_POST['correo'] ?? '');
                    $password = $_POST['password'] ?? '';
                    $passwordConfirm = $_POST['password_confirm'] ?? '';
                    $regData = $_SESSION['reg_data'] ?? null;

                    if (!$regData || empty($email) || empty($password)) {
                        throw new Exception("Datos incompletos para el registro.");
                    }

                    if (strlen($password) < 6) {
                        throw new Exception("La contraseña debe tener mínimo 6 caracteres.");
                    }

                    if ($password !== $passwordConfirm) {
                        throw new Exception("Las contraseñas no coinciden.");
                    }

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Ingresa un correo válido (ej: usuario@empresa.com)");
                    }

                    // Requiere TLD con al menos 2 caracteres (ej: .co, .org, .com)
                    if (!preg_match('/\.[A-Za-z]{2,}$/', $email)) {
                        throw new Exception("El correo debe incluir una extensión válida (ej: .com, .co, .org)");
                    }

                    // Resolver el ID real del rol dinámicamente desde la BD
                    $rolInput = $regData['rol_id'] ?? '3';
                    
                    // Simplificar lógica: 3 = vendedor/proveedor, 4 = comprador
                    if ($rolInput == 3 || $rolInput == '3') {
                        // Vendedor/Proveedor
                        $stmt_role = $this->pdo->query("SELECT id FROM roles WHERE slugRole IN ('vendedor', 'proveedor') LIMIT 1");
                        $roleRow = $stmt_role ? $stmt_role->fetch() : null;
                        $realRoleId = $roleRow ? (int)$roleRow['id'] : 3;
                    } else {
                        // Comprador
                        $stmt_role = $this->pdo->query("SELECT id FROM roles WHERE slugRole = 'comprador' LIMIT 1");
                        $roleRow = $stmt_role ? $stmt_role->fetch() : null;
                        $realRoleId = $roleRow ? (int)$roleRow['id'] : 4;
                    }

                    $registro = $this->usuarioModel->registrar(
                        $regData['representante_legal'], 
                        $email, 
                        $password, 
                        $realRoleId, 
                        $regData['sector_id'],
                        $regData['razon_social'],
                        $regData['tipo_asociacion'],
                        $regData['nit'],
                        $regData['representante_legal'],
                        [
                            'tipo_persona' => $regData['tipo_persona'],
                            'digito_verificacion' => $regData['digito_verificacion'],
                            'responsable_iva' => $regData['responsable_iva'],
                            'tamaño_empresa' => $regData['tamaño_empresa'],
                            'ubicacion_geografica' => $regData['ubicacion_geografica'],
                            'sub_tipo_asociacion' => $regData['sub_tipo_asociacion']
                        ]
                    );

                    if ($registro === true) {
                        unset($_SESSION['reg_data']);
                        header("Location: index.php?controlador=usuario&accion=login&msg=success");
                        exit();
                    } else {
                        throw new Exception($registro);
                    }
                }
            }
            require_once __DIR__ . '/../views/usuario/user_registro.php';
        } catch (Exception $e) {
            $mensaje = "<div class='bg-red-100 p-3 rounded mb-4'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            require_once __DIR__ . '/../views/usuario/user_registro.php';
        }
    }

    public function login() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Si ya está logueado, redirigir a su dashboard según el rol
            if (isset($_SESSION['usuario_id']) && isset($_SESSION['slugRole'])) {
                $this->redirigirPorRol($_SESSION['slugRole']);
                exit();
            }

            $mensaje = "";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Validar CSRF
                $csrfToken = $_POST['csrf_token'] ?? '';
                if (!CsrfService::validateToken($csrfToken, 'login')) {
                    throw new Exception("Token de seguridad inválido o expirado. Por favor recarga la página.");
                }
                
                $email = trim($_POST['correo'] ?? '');
                $ip = RateLimitService::getClientIp();
                
                // Rate limiting por IP
                $ipCheck = RateLimitService::check($ip, 'login_ip');
                if (!$ipCheck['allowed']) {
                    throw new Exception($ipCheck['message']);
                }
                
                // Rate limiting por email (si se proporcionó)
                if (!empty($email)) {
                    $emailCheck = RateLimitService::check($email, 'login_email');
                    if (!$emailCheck['allowed']) {
                        throw new Exception($emailCheck['message']);
                    }
                }
                
                try {
                    $password = $_POST['password'] ?? '';

                    if (empty($email) || empty($password)) {
                        throw new Exception("Por favor ingresa correo y contraseña.");
                    }

                    $usuario = $this->usuarioModel->login($email, $password);

                    if ($usuario) {
                        // Verificar si la empresa está aprobada (excepto para admins)
                        if (!in_array($usuario['slugRole'], ['admin', 'superadmin'])) {
                            $perfil = $this->usuarioModel->obtenerPerfilEmpresa($usuario['id']);
                            if ($perfil && ($perfil['estado_verificacion'] ?? 'pendiente') !== 'aprobada') {
                                if ($perfil['estado_verificacion'] === 'rechazada') {
                                    throw new Exception("Tu registro ha sido rechazado. Contacta al administrador.");
                                }
                                throw new Exception("Tu cuenta está pendiente de aprobación por el administrador.");
                            }
                        }

                        // REGENERAR ID DE SESIÓN (previene session fixation)
                        session_regenerate_id(true);
                        
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
                        $_SESSION['roleId'] = $usuario['roleId'];
                        $_SESSION['slugRole'] = strtolower(trim($usuario['slugRole']));
                        $_SESSION['nombreRole'] = $usuario['nombreRole'];
                        $_SESSION['login_time'] = time();

                        // LOG DE REDIRECCIÓN
                        try {
                            $log_file = '../logs/debug_login.txt';
                            $timestamp = date('Y-m-d H:i:s');
                            file_put_contents($log_file, "$timestamp - INFO: Login exitoso. Redirigiendo a {$_SESSION['slugRole']}\n", FILE_APPEND);
                        } catch (Exception $e) {
                            // Silenciar error de logs para no interrumpir el flujo de login
                            error_log("Error al escribir en log de login: " . $e->getMessage());
                        }

                        // Login exitoso: limpiar contadores de rate limiting
                        RateLimitService::clear($ip, 'login_ip');
                        RateLimitService::clear($email, 'login_email');

                        $this->redirigirPorRol($_SESSION['slugRole']);
                        exit();
                    } else {
                        // Incrementar contadores de intentos fallidos
                        RateLimitService::increment($ip, 'login_ip');
                        RateLimitService::increment($email, 'login_email');
                        
                        Logger::logRoleError('guest', 'Credenciales inválidas en login', [
                            'accion' => 'login',
                            'email' => $email
                        ]);
                        throw new Exception("Credenciales no válidas. Verifica el correo y la contraseña.");
                    }
                } catch (PDOException $e) {
                    Logger::logRoleError('guest', 'Error de base de datos en login', [
                        'accion' => 'login',
                        'error' => $e->getMessage()
                    ]);
                    $mensaje = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>Error del sistema. Por favor intenta más tarde.</div>";
                } catch (Exception $e) {
                    $mensaje = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>" . htmlspecialchars($e->getMessage()) . "</div>";
                }
            }
            require_once __DIR__ . '/../views/usuario/user_login.php';
        } catch (Exception $e) {
            Logger::logRoleError('guest', 'Error crítico en login', [
                'accion' => 'login',
                'error' => $e->getMessage()
            ]);
            die("Error crítico del sistema. Por favor contacta al administrador.");
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    /**
     * Olvidé mi contraseña - Solicitar token
     */
    public function forgotPassword() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $mensaje = "";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = trim($_POST['correo'] ?? '');

                if (empty($email)) {
                    throw new Exception("Por favor ingresa tu correo electrónico.");
                }

                $usuario = $this->usuarioModel->getByEmail($email);
                if ($usuario) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    if ($this->usuarioModel->setResetToken($email, $token, $expires)) {
                        $resetLink = "https://rueda.agecso.org/index.php?controlador=usuario&accion=resetPassword&token=" . $token;
                        
                        // Preparar correo
                        $to = $email;
                        $subject = "Recuperar Contraseña - Rueda de Negocios AGECSO";
                        $message = "Hola " . $usuario['nombreUsuario'] . ",\n\n";
                        $message .= "Has solicitado restablecer tu contraseña en la Rueda de Negocios. Haz clic en el siguiente enlace para continuar:\n\n";
                        $message .= $resetLink . "\n\n";
                        $message .= "Este enlace expirará en 1 hora.\n\n";
                        $message .= "Si no solicitaste esto, puedes ignorar este correo.\n";
                        $headers = "From: no-reply@agecso.org" . "\r\n" .
                                   "Reply-To: contacto@agecso.org" . "\r\n" .
                                   "X-Mailer: PHP/" . phpversion();

                        if (@mail($to, $subject, $message, $headers)) {
                            $mensaje = "<div class='bg-green-100 p-3 rounded mb-4 text-green-700'>Se ha enviado un enlace de recuperación a tu correo.</div>";
                        } else {
                            error_log("Error enviando correo a $email. Link: $resetLink");
                            $mensaje = "<div class='bg-green-100 p-3 rounded mb-4 text-green-700'>Se ha generado el enlace (ver logs). Por favor revisa tu bandeja de entrada.</div>";
                        }
                    }
                } else {
                    $mensaje = "<div class='bg-green-100 p-3 rounded mb-4 text-green-700'>Si el correo existe en nuestro sistema, recibirás un enlace pronto.</div>";
                }
            }
            require_once __DIR__ . '/../views/usuario/forgot_password.php';
        } catch (Exception $e) {
            $mensaje = "<div class='bg-red-100 p-3 rounded mb-4 text-red-700'>" . $e->getMessage() . "</div>";
            require_once __DIR__ . '/../views/usuario/forgot_password.php';
        }
    }

    /**
     * Restablecer contraseña - Procesar nuevo password
     */
    public function resetPassword() {
        try {
            $token = $_GET['token'] ?? '';
            if (empty($token)) {
                header("Location: index.php?controlador=usuario&accion=login");
                exit();
            }

            $usuario = $this->usuarioModel->getByResetToken($token);
            if (!$usuario) {
                throw new Exception("El enlace ha expirado o es inválido.");
            }

            $mensaje = "";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if (strlen($password) < 6) {
                    throw new Exception("La contraseña debe tener al menos 6 caracteres.");
                }
                if ($password !== $confirm) {
                    throw new Exception("Las contraseñas no coinciden.");
                }

                if ($this->usuarioModel->updatePassword($usuario['id'], $password)) {
                    header("Location: index.php?controlador=usuario&accion=login&msg=reset_success");
                    exit();
                } else {
                    throw new Exception("Hubo un error al actualizar la contraseña.");
                }
            }
            require_once __DIR__ . '/../views/usuario/reset_password.php';
        } catch (Exception $e) {
            $mensaje = "<div class='bg-red-100 p-3 rounded mb-4 text-red-700'>" . $e->getMessage() . "</div>";
            require_once __DIR__ . '/../views/usuario/forgot_password.php';
        }
    }

    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }

        try {
            $perfil = $this->usuarioModel->obtenerPerfilEmpresa($_SESSION['usuario_id']);
            if (!$perfil) {
                // Si es admin o superadmin, armamos un perfil básico para evitar que falle al no ser una empresa
                $slugRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
                if (in_array($slugRole, ['admin', 'superadmin'])) {
                    // Consultamos el usuario en la BD para tener su nombre y fecha de registro real
                    $stmt = $this->pdo->prepare("SELECT u.id, u.nombreUsuario, u.email, u.createdAt, r.nombreRole FROM usuarios u JOIN roles r ON u.roleId = r.id WHERE u.id = ?");
                    $stmt->execute([$_SESSION['usuario_id']]);
                    $usuario = $stmt->fetch();
                    
                    if ($usuario) {
                        $perfil = [
                            'id' => $usuario['id'],
                            'usuarioId' => $usuario['id'],
                            'nombreUsuario' => $usuario['nombreUsuario'],
                            'email' => $usuario['email'],
                            'razon_social' => $usuario['nombreUsuario'],
                            'ciiu_clase' => 'N/A',
                            'nombreSector' => 'Soporte y Administración de la Rueda',
                            'ubicacionGeografica' => 'Sede Principal AGECSO',
                            'verificada' => 1,
                            'tamaño_empresa' => 'Sistema',
                            'nit' => 'N/A',
                            'digito_verificacion' => '',
                            'tipo_persona' => 'natural',
                            'tipo_asociacion' => 'Personal Administrativo',
                            'sub_tipo_asociacion' => 'TI/Soporte',
                            'responsable_iva' => 0,
                            'representante_legal' => $usuario['nombreUsuario'],
                            'descripcion' => 'Cuenta administrativa oficial encargada de la supervisión técnica, soporte y mantenimiento de la plataforma de matchmaking empresarial AGECSO.',
                            'createdAt' => $usuario['createdAt']
                        ];
                    }
                }
            }
            if (!$perfil) {
                throw new Exception("No se encontró información del perfil para esta empresa.");
            }
            require_once __DIR__ . '/../views/usuario/perfil.php';
        } catch (Exception $e) {
            $mensaje = "<div class='bg-red-100 p-3 rounded mb-4'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            require_once __DIR__ . '/../views/usuario/user_login.php';
        }
    }

    /**
     * Helper para centralizar la redirección por rol
     */
    private function redirigirPorRol($slugRole) {
        switch ($slugRole) {
            case 'superadmin':
                header("Location: index.php?controlador=superadmin&accion=dashboard");
                break;
            case 'admin':
                header("Location: index.php?controlador=admin&accion=dashboard");
                break;
            case 'comprador':
                header("Location: index.php?controlador=comprador&accion=dashboard");
                break;
            case 'proveedor':
            case 'vendedor':
                header("Location: index.php?controlador=vendedor&accion=dashboard");
                break;
            default:
                header("Location: index.php");
                break;
        }
    }
}
?>
