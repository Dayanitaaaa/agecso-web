<?php
require_once __DIR__ . '/../models/AdminModel.php';

class AuthController {
    private $pdo;
    private $adminModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->adminModel = new AdminModel($pdo);
    }

    /**
     * Mostrar formulario de login
     */
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Si ya está logueado, redirigir al panel
        if (isset($_SESSION['admin_id'])) {
            header("Location: " . APP_URL . "/admin");
            exit();
        }

        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Por favor complete todos los campos.';
            } else {
                $usuario = $this->adminModel->getByEmail($email);
                
                if (!$usuario) {
                    $error = 'Usuario no encontrado.';
                } elseif (!isset($usuario['password'])) {
                    $error = 'No se encontró el campo password.';
                } elseif (!$this->adminModel->verifyPassword($password, $usuario['password'])) {
                    $error = 'Contraseña incorrecta.';
                } else {
                    $_SESSION['admin_id'] = $usuario['id'];
                    $_SESSION['admin_nombre'] = $usuario['nombre'];
                    $_SESSION['admin_email'] = $usuario['email'];
                    $_SESSION['admin_rol'] = $usuario['rol'];
                    
                    header("Location: " . APP_URL . "/admin");
                    exit();
                }
            }
        }

        // Mostrar vista de login
        $title = 'Login Administrador';
        require __DIR__ . '/../views/admin/login.php';
    }

    /**
     * Olvidé mi contraseña - Solicitar token
     */
    public function forgotPassword() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if (empty($email)) {
                $error = 'Por favor ingresa tu correo electrónico.';
            } else {
                $usuario = $this->adminModel->getByEmail($email);
                if ($usuario) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    if ($this->adminModel->setResetToken($email, $token, $expires)) {
                        $resetLink = APP_URL . "/?page=reset-password&token=" . $token;
                        
                        // Preparar correo
                        $to = $email;
                        $subject = "Recuperar Contraseña - " . APP_NAME;
                        $message = "Hola " . $usuario['nombre'] . ",\n\n";
                        $message .= "Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:\n\n";
                        $message .= $resetLink . "\n\n";
                        $message .= "Este enlace expirará en 1 hora.\n\n";
                        $message .= "Si no solicitaste esto, puedes ignorar este correo.\n";
                        $headers = "From: no-reply@agecso.org" . "\r\n" .
                                   "Reply-To: contacto@agecso.org" . "\r\n" .
                                   "X-Mailer: PHP/" . phpversion();

                        // Enviar correo (usando mail() básico para Hostinger)
                        if (@mail($to, $subject, $message, $headers)) {
                            $success = 'Se ha enviado un enlace de recuperación a tu correo.';
                        } else {
                            // Fallback para debug si mail() falla en local
                            error_log("Error enviando correo a $email. Link: $resetLink");
                            $success = 'Se ha generado el enlace (ver logs). Por favor revisa tu bandeja de entrada.';
                        }
                    }
                } else {
                    // Por seguridad, no revelamos si el correo existe o no
                    $success = 'Si el correo existe en nuestro sistema, recibirás un enlace pronto.';
                }
            }
        }

        require __DIR__ . '/../views/admin/forgot-password.php';
    }

    /**
     * Restablecer contraseña - Procesar nuevo password
     */
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            header("Location: " . APP_URL . "/login");
            exit();
        }

        $usuario = $this->adminModel->getByResetToken($token);
        if (!$usuario) {
            $error = 'El enlace ha expirado o es inválido.';
            require __DIR__ . '/../views/admin/forgot-password.php';
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } elseif ($password !== $confirm) {
                $error = 'Las contraseñas no coinciden.';
            } else {
                if ($this->adminModel->updatePassword($usuario['id'], $password)) {
                    header("Location: " . APP_URL . "/login?reset=success");
                    exit();
                } else {
                    $error = 'Hubo un error al actualizar la contraseña.';
                }
            }
        }

        require __DIR__ . '/../views/admin/reset-password.php';
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: " . APP_URL . "/login");
        exit();
    }
}
