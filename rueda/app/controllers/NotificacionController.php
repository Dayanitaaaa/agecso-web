<?php
require_once __DIR__ . '/../models/NotificacionModel.php';
require_once __DIR__ . '/../../includes/Logger.php';

class NotificacionController {
    private $pdo;
    private $notificacionModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->notificacionModel = new NotificacionModel($this->pdo);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
    }

    /**
     * Obtener notificaciones no leídas (vía AJAX para dropdown)
     */
    public function obtenerNoLeidas() {
        try {
            $usuarioId = $_SESSION['usuario_id'];
            $notificaciones = $this->notificacionModel->obtenerNoLeidas($usuarioId);
            
            $result = [];
            foreach ($notificaciones as $notif) {
                $result[] = [
                    'tipo' => $notif['tipo'],
                    'mensaje' => $notif['titulo'] . ': ' . $notif['mensaje'],
                    'link' => $notif['link_destino'] ?? '#'
                ];
            }
            
            echo json_encode(['success' => true, 'notificaciones' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'notificaciones' => []]);
        }
        exit;
    }

    /**
     * Mostrar panel de notificaciones
     */
    public function panel() {
        try {
            $usuarioId = $_SESSION['usuario_id'];
            $notificaciones = $this->notificacionModel->obtenerTodas($usuarioId, 50);
            $noLeidas = $this->notificacionModel->obtenerNoLeidas($usuarioId);

            require_once __DIR__ . '/../views/notificaciones/panel.php';
        } catch (Exception $e) {
            Logger::logRoleError($_SESSION['slugRole'] ?? 'guest', 'Error cargando panel de notificaciones', [
                'error' => $e->getMessage()
            ]);
            // Mostrar error específico para debug
            die("Error al cargar notificaciones: " . $e->getMessage());
        }
    }

    /**
     * Marcar notificación como leída (vía AJAX)
     */
    public function marcarLeida() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $notificacionId = $_POST['id'] ?? null;
                
                if (!$notificacionId) {
                    echo json_encode(['success' => false, 'message' => 'ID de notificación no proporcionado']);
                    exit;
                }
                
                // Verificar que la notificación pertenezca al usuario
                $stmt = $this->pdo->prepare("SELECT usuarioId FROM notificaciones WHERE id = ?");
                $stmt->execute([$notificacionId]);
                $notif = $stmt->fetch();
                
                if (!$notif || $notif['usuarioId'] != $_SESSION['usuario_id']) {
                    echo json_encode(['success' => false, 'message' => 'No tienes permiso para marcar esta notificación']);
                    exit;
                }
                
                $resultado = $this->notificacionModel->marcarLeida($notificacionId);
                
                if ($resultado) {
                    $contador = $this->notificacionModel->contarNoLeidas($_SESSION['usuario_id']);
                    echo json_encode(['success' => true, 'contador' => $contador]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al marcar como leída']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error del servidor']);
            }
        }
        exit;
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $resultado = $this->notificacionModel->marcarTodasLeidas($_SESSION['usuario_id']);
                
                if ($resultado) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al marcar notificaciones']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error del servidor']);
            }
        }
        exit;
    }

    /**
     * Obtener contador de notificaciones no leídas (vía AJAX)
     */
    public function contador() {
        try {
            $contador = $this->notificacionModel->contarNoLeidas($_SESSION['usuario_id']);
            echo json_encode(['success' => true, 'contador' => $contador]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'contador' => 0]);
        }
        exit;
    }
}
