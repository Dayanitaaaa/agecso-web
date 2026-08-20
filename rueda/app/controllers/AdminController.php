<?php
require_once '../includes/Logger.php';
class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log('[DEBUG] AdminController constructor llamado. Acción: ' . ($_GET['accion'] ?? 'no definida'));
        
        // Verificar si el usuario está logueado y es administrador (slugRole = 'admin' o 'superadmin')
        $userRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
        if (!isset($_SESSION['usuario_id']) || !in_array($userRole, ['admin', 'superadmin'])) {
            error_log('[DEBUG] Usuario no autorizado. Role: ' . $userRole);
            Logger::logRoleError($userRole ?: 'guest', 'Acceso no autorizado al AdminController', [
                'accion' => $_GET['accion'] ?? 'desconocida'
            ]);
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
        error_log('[DEBUG] Usuario autorizado. Role: ' . $userRole);
    }

    public function crearAdmin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {
                $this->pdo->beginTransaction();
                
                $sql = "INSERT INTO usuarios (nombreUsuario, email, password, roleId) VALUES (?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$nombre, $correo, $password, 2]); // Rol 2: Administrador
                
                $usuarioId = $this->pdo->lastInsertId();
                $sql_empresa = "INSERT INTO empresas (usuarioId, razon_social, sectorId) VALUES (?, ?, ?)";
                $stmt_empresa = $this->pdo->prepare($sql_empresa);
                $stmt_empresa->execute([$usuarioId, 'AGECSO Staff', 1131]);

                $this->pdo->commit();
            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                Logger::log("Error al crear admin: " . $e->getMessage(), 'system');
            }
        }
        header("Location: index.php?controlador=admin&accion=dashboard");
        exit();
    }

    public function dashboard() {
        error_log('[DEBUG] dashboard() iniciado');
        require_once '../app/models/ImpactoModel.php';
        $impactoModel = new ImpactoModel($this->pdo);

        try {
            // 1. Ruedas de negocios
            error_log('[DEBUG] dashboard: consultando ruedas');
            $stmt_ruedas = $this->pdo->query("SELECT * FROM ruedas_negocios ORDER BY fechaInicio DESC");
            $ruedas = $stmt_ruedas->fetchAll();

            // 2. Estadísticas globales
            error_log('[DEBUG] dashboard: consultando estadísticas globales');
            $stats = $impactoModel->getEstadisticasGlobales();
            $total_empresas = $this->pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn();
            
            // 3. Empresas pendientes de aprobación
            error_log('[DEBUG] dashboard: consultando empresas pendientes');
            $stmt_pendientes = $this->pdo->query("SELECT e.*, u.email FROM empresas e JOIN usuarios u ON e.usuarioId = u.id WHERE e.estado_verificacion = 'pendiente'");
            $empresas_pendientes = $stmt_pendientes->fetchAll();

            // 4. Solicitudes de reuniones pendientes de aprobación por admin
            error_log('[DEBUG] dashboard: consultando solicitudes reuniones');
            $stmt_solicitudes = $this->pdo->query("
                SELECT r.*, c.razon_social as comprador, v.razon_social as vendedor, rn.nombreRueda as tituloRueda
                FROM reuniones r
                JOIN empresas c ON r.compradorId = c.id
                JOIN empresas v ON r.vendedorId = v.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.estadoCita = 'propuesta'
            ");
            $solicitudes_reuniones = $stmt_solicitudes->fetchAll();

            // 5. CONSULTA DE REUNIONES
            error_log('[DEBUG] dashboard: consultando reuniones detalladas');
            $total_reuniones = (int)$this->pdo->query("SELECT COUNT(*) FROM reuniones")->fetchColumn();
            $stmt_reuniones = $this->pdo->query("
                SELECT r.*, 
                       COALESCE(c.razon_social, 'Empresa no encontrada') as comprador, 
                       COALESCE(v.razon_social, 'Empresa no encontrada') as vendedor, 
                       COALESCE(rn.nombreRueda, 'Rueda eliminada') as rueda
                FROM reuniones r
                LEFT JOIN empresas c ON r.compradorId = c.id
                LEFT JOIN empresas v ON r.vendedorId = v.id
                LEFT JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                ORDER BY r.fechaHora DESC
                LIMIT 20
            ");
            $reuniones_detalladas = $stmt_reuniones->fetchAll();
            
            $negocios_cerrados = $stats['total_volumen_negocio'] ?? 0;

            // 6. CONSULTA DE INSCRIPCIONES PENDIENTES
            error_log('[DEBUG] dashboard: consultando inscripciones pendientes');
            $stmt_inscripciones = $this->pdo->query("
                SELECT ir.*, e.razon_social, e.sectorId, rn.nombreRueda as tituloRueda 
                FROM inscripciones_ruedas ir
                JOIN empresas e ON ir.empresaId = e.id
                JOIN ruedas_negocios rn ON ir.ruedaId = rn.id
                WHERE ir.estadoInscripcion = 'pendiente'
                ORDER BY ir.createdAt ASC
            ");
            $inscripciones_pendientes = $stmt_inscripciones->fetchAll();

            // 7. CONSULTA DE ENCUESTAS
            error_log('[DEBUG] dashboard: consultando encuestas');
            $stmt_encuestas = $this->pdo->query("
                SELECT s.*, 
                       s.calificacionGeneral as calificacion, 
                       s.expectativaNegocio as expectativaCumplida, 
                       s.valorNegocioProyectado as posibilidadNegocio,
                       s.comentarios as comentario,
                       u.nombreUsuario, 
                       e.razon_social, 
                       r.fechaHora, 
                       r.montoEstimado,
                       CASE WHEN r.compradorId = e.id THEN 'comprador' ELSE 'vendedor' END as rolCalificador
                FROM encuestas_satisfaccion s
                LEFT JOIN usuarios u ON s.usuarioId = u.id
                LEFT JOIN empresas e ON u.id = e.usuarioId
                LEFT JOIN reuniones r ON s.reunionId = r.id
                ORDER BY s.createdAt DESC
                LIMIT 20
            ");
            $encuestas_recientes = $stmt_encuestas->fetchAll();

            // 8. CONSULTA DE PAGOS DE MEMBRESÍAS (Omitida por esquema base)
            $recaudado_membresias = 0.0;
            $pagos_recientes = [];
            $labels_grafica = [];
            $valores_grafica = [];

            error_log('[DEBUG] dashboard: cargando vista');
            require_once '../app/views/admin/admin_dashboard.php';
        } catch (Exception $e) {
            error_log('[ERROR] Dashboard error: ' . $e->getMessage());
            throw $e; // Re-lanzar para que index.php lo capture
        }
    }

    public function verRegistrosPaneles() {
        $perPageReuniones = 50;
        $perPageEncuestas = 50;
        $perPageRuedas = 50;

        $pageReuniones = isset($_GET['page_reuniones']) ? max(1, (int)$_GET['page_reuniones']) : 1;
        $pageEncuestas = isset($_GET['page_encuestas']) ? max(1, (int)$_GET['page_encuestas']) : 1;
        $pageRuedas = isset($_GET['page_ruedas']) ? max(1, (int)$_GET['page_ruedas']) : 1;

        $offsetReuniones = ($pageReuniones - 1) * $perPageReuniones;
        $offsetEncuestas = ($pageEncuestas - 1) * $perPageEncuestas;
        $offsetRuedas = ($pageRuedas - 1) * $perPageRuedas;

        $total_reuniones = (int)$this->pdo->query("SELECT COUNT(*) FROM reuniones")->fetchColumn();
        $total_encuestas = (int)$this->pdo->query("SELECT COUNT(*) FROM encuestas_satisfaccion")->fetchColumn();
        $total_ruedas = (int)$this->pdo->query("SELECT COUNT(*) FROM ruedas_negocios")->fetchColumn();

        $stmt_reuniones = $this->pdo->prepare("
            SELECT r.*, 
                   COALESCE(c.razon_social, 'Empresa no encontrada') as comprador, 
                   COALESCE(v.razon_social, 'Empresa no encontrada') as vendedor, 
                   COALESCE(rn.nombreRueda, 'Rueda eliminada') as rueda
            FROM reuniones r
            LEFT JOIN empresas c ON r.compradorId = c.id
            LEFT JOIN empresas v ON r.vendedorId = v.id
            LEFT JOIN ruedas_negocios rn ON r.ruedaId = rn.id
            ORDER BY r.fechaHora DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt_reuniones->bindValue(':limit', $perPageReuniones, PDO::PARAM_INT);
        $stmt_reuniones->bindValue(':offset', $offsetReuniones, PDO::PARAM_INT);
        $stmt_reuniones->execute();
        $reuniones_detalladas = $stmt_reuniones->fetchAll();

        $stmt_encuestas = $this->pdo->prepare("
            SELECT s.*, 
                   s.calificacionGeneral as calificacion, 
                   s.expectativaNegocio as expectativaCumplida, 
                   s.valorNegocioProyectado as posibilidadNegocio,
                   s.comentarios as comentario,
                   u.nombreUsuario, 
                   e.razon_social, 
                   r.fechaHora, 
                   r.montoEstimado,
                   CASE WHEN r.compradorId = e.id THEN 'comprador' ELSE 'vendedor' END as rolCalificador
            FROM encuestas_satisfaccion s
            LEFT JOIN usuarios u ON s.usuarioId = u.id
            LEFT JOIN empresas e ON u.id = e.usuarioId
            LEFT JOIN reuniones r ON s.reunionId = r.id
            ORDER BY s.createdAt DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt_encuestas->bindValue(':limit', $perPageEncuestas, PDO::PARAM_INT);
        $stmt_encuestas->bindValue(':offset', $offsetEncuestas, PDO::PARAM_INT);
        $stmt_encuestas->execute();
        $encuestas_recientes = $stmt_encuestas->fetchAll();

        $totalPagesReuniones = (int)max(1, ceil($total_reuniones / $perPageReuniones));
        $totalPagesEncuestas = (int)max(1, ceil($total_encuestas / $perPageEncuestas));
        $totalPagesRuedas = (int)max(1, ceil($total_ruedas / $perPageRuedas));

        $stmt_ruedas = $this->pdo->prepare("SELECT * FROM ruedas_negocios ORDER BY fechaInicio DESC LIMIT :limit OFFSET :offset");
        $stmt_ruedas->bindValue(':limit', $perPageRuedas, PDO::PARAM_INT);
        $stmt_ruedas->bindValue(':offset', $offsetRuedas, PDO::PARAM_INT);
        $stmt_ruedas->execute();
        $ruedas = $stmt_ruedas->fetchAll();

        require_once '../app/views/admin/registros_paneles.php';
    }

    /**
     * Aprobar o rechazar el registro de una empresa
     */
    public function gestionarEmpresa() {
        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? null; // aprobada | rechazada

        if ($id && in_array($estado, ['aprobada', 'rechazada'])) {
            $sql = "UPDATE empresas SET estado_verificacion = ?, verificada = ? WHERE id = ?";
            $verificada = ($estado === 'aprobada') ? 1 : 0;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$estado, $verificada, $id]);
            
            Logger::log("Empresa ID $id marcada como $estado por Admin", 'system');
        }
        header("Location: index.php?controlador=admin&accion=dashboard&msg=empresa_actualizada");
        exit();
    }

    /**
     * Aprobar o rechazar una solicitud de reunión
     */
    public function gestionarReunion() {
        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? null; // aprobada_admin | rechazada_admin

        if ($id && in_array($estado, ['aprobada_admin', 'rechazada_admin'])) {
            $nuevoEstado = ($estado === 'aprobada_admin') ? 'pendiente' : 'cancelada';
            
            $sql = "UPDATE reuniones SET estadoCita = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nuevoEstado, $id]);
            
            Logger::log("Reunión ID $id marcada como $estado por Admin", 'system');
        }
        header("Location: index.php?controlador=admin&accion=dashboard&msg=reunion_actualizada");
        exit();
    }

    public function crearRueda() {
        error_log('[DEBUG] Método crearRueda llamado. REQUEST_METHOD: ' . $_SERVER['REQUEST_METHOD']);
        
        // Si es GET, mostrar el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            error_log('[DEBUG] Es GET, cargando vista crear_rueda.php');
            require_once '../app/views/admin/crear_rueda.php';
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $titulo = $_POST['titulo'];
                $descripcion = $_POST['descripcion'];
                $fecha_inscripcion_inicio = !empty($_POST['fecha_inscripcion_inicio']) ? $_POST['fecha_inscripcion_inicio'] : null;
                $fecha_inscripcion_fin = !empty($_POST['fecha_inscripcion_fin']) ? $_POST['fecha_inscripcion_fin'] : null;
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $estado = $_POST['estado']; // planeacion, inscripciones, activa, finalizada, cancelada
                $modalidad = $_POST['modalidad'] ?? 'virtual';
                $ubicacion = $_POST['ubicacion'] ?? 'Virtual';
                $cantidad_mesas = $_POST['cantidad_mesas'] ?? 1;
                $usuario_id = $_SESSION['usuario_id'];

                // Validaciones simplificadas para esquema base
                if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
                    throw new Exception("La fecha de finalización no puede ser anterior a la fecha de inicio.");
                }

                $sql = "INSERT INTO ruedas_negocios (nombreRueda, descripcion, fechaInicio, fechaFin, estadoRueda, organizadorId) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $usuario_id]);

                Logger::log("Rueda de Negocios Creada: '$titulo' (ID: " . $this->pdo->lastInsertId() . ") por Admin ID: $usuario_id", 'business');

                header("Location: index.php?controlador=admin&accion=dashboard&msg=rueda_creada");
                exit();
            } catch (Exception $e) {
                Logger::log("Error en CRUD Ruedas: " . $e->getMessage(), 'system');
                Logger::logCurrentRoleError('Error al crear rueda de negocio', [
                    'accion' => 'crearRueda',
                    'error' => $e->getMessage(),
                    'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a'
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
                exit();
            }
        }
    }

    public function verPerfilEmpresa() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?controlador=admin&accion=dashboard");
            exit();
        }

        try {
            $stmt = $this->pdo->prepare(
                "SELECT e.*, u.email, u.nombreUsuario, u.isActive as usuarioActivo, u.roleId, s.nombreSector, s.ciiu_clase
                 FROM empresas e 
                 JOIN usuarios u ON e.usuarioId = u.id 
                 LEFT JOIN sectores s ON e.sectorId = s.id 
                 WHERE e.id = ?"
            );
            $stmt->execute([$id]);
            $perfil = $stmt->fetch();

            if (!$perfil) {
                $_SESSION['flash_error'] = "Empresa no encontrada.";
                header("Location: index.php?controlador=admin&accion=dashboard");
                exit();
            }

            require_once '../app/views/admin/ver_perfil_empresa.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al ver perfil de empresa', [
                'accion' => 'verPerfilEmpresa',
                'empresa_id' => $id,
                'error' => $e->getMessage()
            ]);
            header("Location: index.php?controlador=admin&accion=dashboard");
            exit();
        }
    }

    public function cambiarEstadoRueda() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            try {
                $id = $_GET['id'];
                $estado = $_GET['estado'];
                $stmt = $this->pdo->prepare("UPDATE ruedas_negocios SET estadoRueda = ? WHERE id = ?");
                $stmt->execute([$estado, $id]);
            } catch (PDOException $e) {
                Logger::logCurrentRoleError('Error al cambiar estado de rueda', [
                    'accion' => 'cambiarEstadoRueda',
                    'rueda_id' => $_GET['id'] ?? 'n/a',
                    'estado' => $_GET['estado'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = "Error al cambiar estado: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
                exit();
            }
        }
        header("Location: index.php?controlador=admin&accion=dashboard");
        exit();
    }

    public function verDetalleEncuesta() {
        if (isset($_GET['id'])) {
            try {
                $encuestaId = $_GET['id'];
                $stmt = $this->pdo->prepare("
                    SELECT s.*, 
                           u.nombreUsuario, 
                           e.razon_social, 
                           r.fechaHora, 
                           rn.nombreRueda as tituloRueda,
                           c.razon_social as comprador,
                           v.razon_social as vendedor,
                           CASE WHEN r.compradorId = e.id THEN 'comprador' ELSE 'vendedor' END as rolCalificador
                    FROM encuestas_satisfaccion s
                    JOIN usuarios u ON s.usuarioId = u.id
                    JOIN empresas e ON u.id = e.usuarioId
                    JOIN reuniones r ON s.reunionId = r.id
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                    JOIN empresas c ON r.compradorId = c.id
                    JOIN empresas v ON r.vendedorId = v.id
                    WHERE s.id = ?
                ");
                $stmt->execute([$encuestaId]);
                $encuesta = $stmt->fetch();

                if (!$encuesta) {
                    throw new Exception("Encuesta no encontrada.");
                }

                require_once '../app/views/admin/detalle_encuesta.php';
            } catch (Exception $e) {
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function gestionarInscripcion() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            try {
                $id = $_GET['id'];
                $estado = $_GET['estado']; // 'aceptada' o 'rechazada'
                
                $stmt = $this->pdo->prepare("UPDATE inscripciones_ruedas SET estadoInscripcion = ? WHERE id = ?");
                $stmt->execute([$estado, $id]);
                
                header("Location: index.php?controlador=admin&accion=dashboard&msg=inscripcion_actualizada");
                exit();
            } catch (PDOException $e) {
                $error_msg = "Error al gestionar inscripción: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function verEstadisticas() {
        try {
            // Si no se proporciona ID, mostrar estadísticas generales del sistema
            if (!isset($_GET['id'])) {
                // Estadísticas generales del sistema
                require_once '../app/models/ImpactoModel.php';
                $impactoModel = new ImpactoModel($this->pdo);

                $stats_generales = $impactoModel->getEstadisticasGlobales();

                // Obtener datos para vista de estadísticas generales
                $total_empresas = $this->pdo->query("SELECT COUNT(*) FROM empresas")->fetchColumn();
                $total_reuniones = (int)$this->pdo->query("SELECT COUNT(*) FROM reuniones")->fetchColumn();
                $recaudado_membresias = 0.0;
                $labels_grafica = [];
                $valores_grafica = [];
                $pagos_recientes = [];

                require_once '../app/views/admin/estadisticas_generales.php';
                exit();
            }

            $ruedaId = $_GET['id'];
            require_once '../app/models/ImpactoModel.php';
            $impactoModel = new ImpactoModel($this->pdo);

            // Obtener estadísticas de la vista v_impacto_ruedas
            $stats = $impactoModel->getResumenImpacto($ruedaId);

            if (!$stats) {
                // Si no hay datos en la vista, obtenemos info básica de la rueda
                $stmt_rueda = $this->pdo->prepare("SELECT nombreRueda as tituloRueda FROM ruedas_negocios WHERE id = ?");
                $stmt_rueda->execute([$ruedaId]);
                $rueda_info = $stmt_rueda->fetch();
                
                if (!$rueda_info) {
                    throw new Exception("Rueda de negocios no encontrada.");
                }

                $stats = [
                    'tituloRueda' => $rueda_info['tituloRueda'],
                    'citas_totales' => 0,
                    'citas_exitosas' => 0,
                    'volumen_negocio_proyectado' => 0,
                    'satisfaccion_promedio' => 0
                ];
            }

            // Participación por sectores (Empresas inscritas)
            $stmt_sectores = $this->pdo->prepare("
                SELECT s.nombreSector, COUNT(ir.empresaId) as total
                FROM inscripciones_ruedas ir
                JOIN empresas e ON ir.empresaId = e.id
                JOIN sectores s ON e.sectorId = s.id
                WHERE ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada'
                GROUP BY s.id
                ORDER BY total DESC
            ");
            $stmt_sectores->execute([$ruedaId]);
            $participacion_sectores = $stmt_sectores->fetchAll();

            $total_participantes = array_sum(array_column($participacion_sectores, 'total'));

            // Listado de empresas que participaron y sus citas acordadas
            $stmt_historial_reuniones = $this->pdo->prepare("
                SELECT 
                    e.razon_social,
                    COUNT(r.id) as total_reuniones,
                    SUM(CASE WHEN r.estadoCita = 'realizada' THEN 1 ELSE 0 END) as reuniones_realizadas,
                    GROUP_CONCAT(DISTINCT 
                        CASE 
                            WHEN r.originalCompradorId = e.id THEN (SELECT razon_social FROM empresas WHERE id = r.originalVendedorId)
                            ELSE (SELECT razon_social FROM empresas WHERE id = r.originalCompradorId)
                        END 
                        ORDER BY r.fechaHora SEPARATOR ', '
                    ) as contrapartes
                FROM empresas e
                JOIN (
                    SELECT id, ruedaId, compradorId as empresaId, vendedorId as contraparteId, estadoCita, fechaHora, compradorId as originalCompradorId, vendedorId as originalVendedorId FROM reuniones
                    UNION ALL
                    SELECT id, ruedaId, vendedorId as empresaId, compradorId as contraparteId, estadoCita, fechaHora, compradorId as originalCompradorId, vendedorId as originalVendedorId FROM reuniones
                ) r ON e.id = r.empresaId
                WHERE r.ruedaId = ?
                GROUP BY e.id
                ORDER BY total_reuniones DESC
            ");
            $stmt_historial_reuniones->execute([$ruedaId]);
            $historial_empresas = $stmt_historial_reuniones->fetchAll();

            // Listado de acuerdos comerciales (Reuniones con monto)
            $stmt_negocios = $this->pdo->prepare("
                SELECT r.montoEstimado, c.razon_social as comprador, v.razon_social as vendedor
                FROM reuniones r
                JOIN empresas c ON r.compradorId = c.id
                JOIN empresas v ON r.vendedorId = v.id
                WHERE r.ruedaId = ? AND r.estadoCita = 'realizada' AND r.montoEstimado > 0
                ORDER BY r.montoEstimado DESC
            ");
            $stmt_negocios->execute([$ruedaId]);
            $negocios_detallados = $stmt_negocios->fetchAll();

            // Obtener todas las encuestas de esta rueda específica
            $stmt_encuestas_rueda = $this->pdo->prepare("
                SELECT s.*, 
                       u.nombreUsuario, 
                       e.razon_social, 
                       r.fechaHora, 
                       CASE WHEN r.compradorId = e.id THEN 'comprador' ELSE 'vendedor' END as rolCalificador
                FROM encuestas_satisfaccion s
                JOIN usuarios u ON s.usuarioId = u.id
                JOIN empresas e ON u.id = e.usuarioId
                JOIN reuniones r ON s.reunionId = r.id
                WHERE r.ruedaId = ?
                ORDER BY s.createdAt DESC
            ");
            $stmt_encuestas_rueda->execute([$ruedaId]);
            $encuestas_rueda = $stmt_encuestas_rueda->fetchAll();

            require_once '../app/views/admin/rueda_stats.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al ver estadísticas de rueda', [
                'accion' => 'verEstadisticas',
                'rueda_id' => $_GET['id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    /**
     * Eliminar una rueda de negocios completa y todas sus transacciones asociadas
     */
    public function eliminarRueda() {
        $ruedaId = $_GET['id'] ?? null;
        if ($ruedaId) {
            try {
                $this->pdo->beginTransaction();

                // 1. Desactivar temporalmente la revisión de llaves foráneas
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

                // 2. Eliminar encuestas relacionadas a las reuniones de esta rueda
                $stmt1 = $this->pdo->prepare("DELETE FROM encuestas_satisfaccion WHERE reunionId IN (SELECT id FROM reuniones WHERE ruedaId = ?)");
                $stmt1->execute([$ruedaId]);

                // 3. Eliminar seguimientos de trazabilidad relacionados a las reuniones de esta rueda
                $stmt2 = $this->pdo->prepare("DELETE FROM trazabilidad_seguimiento WHERE reunionId IN (SELECT id FROM reuniones WHERE ruedaId = ?)");
                $stmt2->execute([$ruedaId]);

                // 4. Eliminar reuniones de esta rueda
                $stmt3 = $this->pdo->prepare("DELETE FROM reuniones WHERE ruedaId = ?");
                $stmt3->execute([$ruedaId]);

                // 5. Eliminar ofertas de esta rueda
                $stmt4 = $this->pdo->prepare("DELETE FROM ofertas WHERE ruedaId = ?");
                $stmt4->execute([$ruedaId]);

                // 6. Eliminar demandas de esta rueda
                $stmt5 = $this->pdo->prepare("DELETE FROM demandas WHERE ruedaId = ?");
                $stmt5->execute([$ruedaId]);

                // 7. Eliminar inscripciones a esta rueda
                $stmt6 = $this->pdo->prepare("DELETE FROM inscripciones_ruedas WHERE ruedaId = ?");
                $stmt6->execute([$ruedaId]);

                // 8. Eliminar la rueda en sí
                $stmt7 = $this->pdo->prepare("DELETE FROM ruedas_negocios WHERE id = ?");
                $stmt7->execute([$ruedaId]);

                // 9. Reactivar revisión de llaves foráneas
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

                $this->pdo->commit();
                Logger::log("Rueda de negocios ID $ruedaId eliminada con éxito por Admin", 'system');
                header("Location: index.php?controlador=admin&accion=dashboard&msg=rueda_eliminada");
                exit();
            } catch (Exception $e) {
                $this->pdo->rollBack();
                $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
                Logger::logCurrentRoleError("Error al eliminar la rueda de negocios ID $ruedaId", [
                    'error' => $e->getMessage()
                ]);
                $error_msg = "Error al eliminar la rueda de negocios: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
                exit();
            }
        }
        header("Location: index.php?controlador=admin&accion=dashboard");
        exit();
    }

    public function guardarMembresia() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $empresa_id = $_POST['empresa_id'] ?? null;
                $plan = $_POST['membresia_plan'] ?? 'ninguno';
                $estado = $_POST['membresia_estado'] ?? 'inactivo';
                $vencimiento = !empty($_POST['membresia_vencimiento']) ? $_POST['membresia_vencimiento'] : null;

                if (!$empresa_id) {
                    throw new Exception("ID de empresa no proporcionado.");
                }

                $this->pdo->beginTransaction();

                try {
                    $stmt_check = $this->pdo->query("SHOW COLUMNS FROM empresas LIKE 'membresia_plan'");
                    if ($stmt_check && $stmt_check->fetch()) {
                        $stmt = $this->pdo->prepare("
                            UPDATE empresas 
                            SET membresia_plan = ?, 
                                membresia_estado = ?, 
                                membresia_vencimiento = ? 
                            WHERE id = ?
                        ");
                        $stmt->execute([$plan, $estado, $vencimiento, $empresa_id]);
                    }
                } catch (Exception $e) {
                    // Ignorar si no existe la columna
                }

                // Si el administrador activa manualmente un plan de pago, registramos la transacción en el historial de pagos para las gráficas
                if ($estado === 'activo' && in_array($plan, ['mensual', 'anual'])) {
                    $monto = $plan === 'anual' ? 225000.00 : 25000.00;
                    $stmt_pago = $this->pdo->prepare("
                        INSERT INTO pagos_membresias (empresa_id, plan, monto, estado_pago, fecha_pago)
                        VALUES (?, ?, ?, 'aprobado', NOW())
                    ");
                    $stmt_pago->execute([$empresa_id, $plan, $monto]);
                }

                $this->pdo->commit();

                Logger::log("Administrador actualizó membresía de empresa ID $empresa_id a Plan: $plan, Estado: $estado, Vence: " . ($vencimiento ?? 'N/A'), 'business');

                header("Location: index.php?controlador=admin&accion=verPerfilEmpresa&id=$empresa_id&msg=membresia_actualizada");
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al actualizar membresía', [
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

}
?>
