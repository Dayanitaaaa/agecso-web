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
                    header("Location: index.php?controlador=superadmin&accion=dashboard");
                    exit();
                case 'admin':
                    header("Location: index.php?controlador=admin&accion=dashboard");
                    exit();
                case 'comprador':
                    header("Location: index.php?controlador=comprador&accion=dashboard");
                    exit();
                case 'proveedor':
                case 'vendedor':
                    header("Location: index.php?controlador=vendedor&accion=dashboard");
                    exit();
                default:
                    header("Location: index.php?controlador=usuario&accion=logout");
                    exit();
            }
        } else {
            // Si NO está logueado, lo enviamos directo al Login
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
    }
}
?>
