<?php
class HomeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya está logueado, lo redirigimos inteligentemente a su Dashboard correspondiente
        if (isset($_SESSION['usuario_id']) && isset($_SESSION['slugRole'])) {
            $userRole = strtolower(trim($_SESSION['slugRole']));
            switch ($userRole) {
                case 'superadmin':
                    header("Location: /superadmin/dashboard");
                    exit();
                case 'admin':
                    header("Location: /admin/dashboard");
                    exit();
                case 'comprador':
                    header("Location: /comprador/dashboard");
                    exit();
                case 'proveedor':
                case 'vendedor':
                    header("Location: /vendedor/dashboard");
                    exit();
                default:
                    header("Location: /usuario/logout");
                    exit();
            }
        } else {
            // Si NO está logueado, lo enviamos directo al Login
            header("Location: /usuario/login");
            exit();
        }
    }
}
?>
