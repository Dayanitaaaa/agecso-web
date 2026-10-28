<?php
require_once __DIR__ . '/../models/PautaModel.php';
require_once __DIR__ . '/../models/NotificacionModel.php';
require_once __DIR__ . '/../../includes/Logger.php';

class PautaController {
    private $pdo;
    private $pautaModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->pautaModel = new PautaModel($this->pdo);
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
     * Formulario para crear una nueva pauta
     */
    public function crear() {
        try {
            require_once __DIR__ . '/../views/pauta/crear.php';
        } catch (Exception $e) {
            Logger::logRoleError($_SESSION['slugRole'] ?? 'guest', 'Error cargando formulario de pauta', [
                'error' => $e->getMessage()
            ]);
            die("Error al cargar formulario. Por favor intenta más tarde.");
        }
    }

    /**
     * Guardar una nueva pauta
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $usuarioId = $_SESSION['usuario_id'];
                
                // Validar campos requeridos
                $titulo = trim($_POST['titulo'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $linkDestino = trim($_POST['link_destino'] ?? '');
                $fechaInicio = $_POST['fecha_inicio'] ?? '';
                $fechaFin = $_POST['fecha_fin'] ?? '';
                $posicion = (int)($_POST['posicion'] ?? 1);
                
                if (empty($titulo) || empty($descripcion) || empty($fechaInicio) || empty($fechaFin)) {
                    throw new Exception("Todos los campos son obligatorios excepto el enlace.");
                }
                
                // Validar fechas
                if ($fechaInicio > $fechaFin) {
                    throw new Exception("La fecha de inicio debe ser anterior a la fecha de fin.");
                }
                
                // Procesar imagen
                if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Debes subir una imagen para la pauta.");
                }
                
                $imagenFile = $_FILES['imagen'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($imagenFile['type'], $allowedTypes)) {
                    throw new Exception("La imagen debe ser JPG, PNG, GIF o WEBP.");
                }
                
                if ($imagenFile['size'] > $maxSize) {
                    throw new Exception("La imagen no puede superar 5MB.");
                }
                
                // Generar nombre único
                $extension = pathinfo($imagenFile['name'], PATHINFO_EXTENSION);
                $imagenNombre = 'pauta_' . $usuarioId . '_' . time() . '.' . $extension;
                $uploadDir = __DIR__ . '/../../public/uploads/pautas/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (!move_uploaded_file($imagenFile['tmp_name'], $uploadDir . $imagenNombre)) {
                    throw new Exception("Error al subir la imagen. Por favor intenta de nuevo.");
                }
                
                // Guardar pauta
                $data = [
                    'empresaId' => $usuarioId,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'imagen' => $imagenNombre,
                    'link_destino' => $linkDestino,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'posicion' => 1 // Siempre posición 1
                ];
                
                if ($this->pautaModel->crear($data)) {
                    // Notificar a los administradores sobre la nueva pauta
                    $this->notificacionModel->notificarAdmins(
                        'nueva_pauta',
                        'Nueva Pauta Publicitaria',
                        "La empresa ha solicitado una pauta: \"$titulo\"",
                        'index.php?controlador=pauta&accion=listarAdmin&estado=pendiente'
                    );
                    
                    header("Location: index.php?controlador=pauta&accion=misPautas&msg=creada");
                } else {
                    throw new Exception("Error al guardar la pauta.");
                }
            } catch (Exception $e) {
                header("Location: index.php?controlador=pauta&accion=crear&msg=error&error_text=" . urlencode($e->getMessage()));
            }
        }
        exit();
    }

    /**
     * Ver mis pautas
     */
    public function misPautas() {
        try {
            $usuarioId = $_SESSION['usuario_id'];
            $pautas = $this->pautaModel->obtenerPorEmpresa($usuarioId);
            require_once __DIR__ . '/../views/pauta/mis_pautas.php';
        } catch (Exception $e) {
            Logger::logRoleError($_SESSION['slugRole'] ?? 'guest', 'Error cargando mis pautas', [
                'error' => $e->getMessage()
            ]);
            die("Error al cargar pautas. Por favor intenta más tarde.");
        }
    }

    /**
     * Listar pautas para admin
     */
    public function listarAdmin() {
        try {
            if (!in_array($_SESSION['slugRole'] ?? '', ['admin', 'superadmin'])) {
                header("Location: index.php?controlador=usuario&accion=login");
                exit();
            }
            
            $estado = $_GET['estado'] ?? null;
            $pautas = $this->pautaModel->obtenerTodas($estado);
            require_once __DIR__ . '/../views/pauta/admin_listar.php';
        } catch (Exception $e) {
            Logger::logRoleError($_SESSION['slugRole'] ?? 'guest', 'Error cargando pautas admin', [
                'error' => $e->getMessage()
            ]);
            die("Error al cargar pautas. Por favor intenta más tarde.");
        }
    }

    /**
     * Aprobar pauta
     */
    public function aprobar() {
        try {
            if (!in_array($_SESSION['slugRole'] ?? '', ['admin', 'superadmin'])) {
                header("Location: index.php?controlador=usuario&accion=login");
                exit();
            }
            
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception("ID de pauta no proporcionado.");
            }
            
            // Obtener información de la pauta antes de aprobar
            $pauta = $this->pautaModel->obtenerPorId($id);
            
            if ($this->pautaModel->actualizarEstado($id, 'activa')) {
                // Notificar al dueño de la pauta
                $this->notificacionModel->crear(
                    $pauta['empresaId'],
                    'pauta_aprobada',
                    '¡Pauta Aprobada!',
                    "Tu pauta \"$pauta[titulo]\" ha sido aprobada y está activa en la página principal.",
                    'index.php?controlador=pauta&accion=misPautas'
                );
                
                header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=aprobada");
            } else {
                throw new Exception("Error al aprobar la pauta.");
            }
        } catch (Exception $e) {
            header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=error&error_text=" . urlencode($e->getMessage()));
        }
        exit();
    }

    /**
     * Rechazar pauta
     */
    public function rechazar() {
        try {
            if (!in_array($_SESSION['slugRole'] ?? '', ['admin', 'superadmin'])) {
                header("Location: index.php?controlador=usuario&accion=login");
                exit();
            }
            
            $id = $_POST['id'] ?? null;
            $comentario = trim($_POST['comentario_rechazo'] ?? '');
            
            if (!$id) {
                throw new Exception("ID de pauta no proporcionado.");
            }
            
            if (empty($comentario)) {
                throw new Exception("Debes proporcionar un comentario de rechazo.");
            }
            
            // Obtener información de la pauta antes de rechazar
            $pauta = $this->pautaModel->obtenerPorId($id);
            
            if ($this->pautaModel->actualizarEstado($id, 'rechazada', $comentario)) {
                // Notificar al dueño de la pauta
                $this->notificacionModel->crear(
                    $pauta['empresaId'],
                    'pauta_rechazada',
                    'Pauta Rechazada',
                    "Tu pauta \"$pauta[titulo]\" ha sido rechazada. Motivo: $comentario",
                    'index.php?controlador=pauta&accion=misPautas'
                );
                
                header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=rechazada");
            } else {
                throw new Exception("Error al rechazar la pauta.");
            }
        } catch (Exception $e) {
            header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=error&error_text=" . urlencode($e->getMessage()));
        }
        exit();
    }

    /**
     * Finalizar pauta
     */
    public function finalizar() {
        try {
            if (!in_array($_SESSION['slugRole'] ?? '', ['admin', 'superadmin'])) {
                header("Location: index.php?controlador=usuario&accion=login");
                exit();
            }
            
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception("ID de pauta no proporcionado.");
            }
            
            if ($this->pautaModel->actualizarEstado($id, 'finalizada')) {
                header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=finalizada");
            } else {
                throw new Exception("Error al finalizar la pauta.");
            }
        } catch (Exception $e) {
            header("Location: index.php?controlador=pauta&accion=listarAdmin&msg=error&error_text=" . urlencode($e->getMessage()));
        }
        exit();
    }

    /**
     * Eliminar pauta
     */
    public function eliminar() {
        try {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception("ID de pauta no proporcionado.");
            }
            
            // Verificar que sea el dueño o admin
            $pauta = $this->pautaModel->obtenerPorId($id);
            if (!$pauta) {
                throw new Exception("Pauta no encontrada.");
            }
            
            if ($pauta['empresaId'] != $_SESSION['usuario_id'] && !in_array($_SESSION['slugRole'] ?? '', ['admin', 'superadmin'])) {
                throw new Exception("No tienes permiso para eliminar esta pauta.");
            }
            
            if ($this->pautaModel->eliminar($id)) {
                header("Location: index.php?controlador=pauta&accion=misPautas&msg=eliminada");
            } else {
                throw new Exception("Error al eliminar la pauta.");
            }
        } catch (Exception $e) {
            header("Location: index.php?controlador=pauta&accion=misPautas&msg=error&error_text=" . urlencode($e->getMessage()));
        }
        exit();
    }
}
