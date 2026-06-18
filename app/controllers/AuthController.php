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
            header("Location: " . APP_URL . "/?page=admin");
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
                    
                    header("Location: " . APP_URL . "/?page=admin");
                    exit();
                }
            }
        }

        // Mostrar vista de login
        $title = 'Login Administrador';
        require __DIR__ . '/../views/admin/login.php';
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: " . APP_URL . "/?page=login");
        exit();
    }
}
