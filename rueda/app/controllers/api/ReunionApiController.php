<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../models/ReunionModel.php';

class ReunionApiController extends BaseApiController {
    private $reunionModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->reunionModel = new ReunionModel($pdo);
    }

    /**
     * GET /api/reuniones?rueda_id=X&rol=comprador
     */
    public function listar() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        $ruedaId = $_GET['rueda_id'] ?? null;
        $rol = $_GET['rol'] ?? $_SESSION['slugRole'] ?? 'comprador';

        // Normalizar rol
        if ($rol === 'vendedor' || $rol === 'proveedor') $rol = 'vendedor';
        else $rol = 'comprador';

        try {
            // Obtener empresaId
            $empresaId = $this->getEmpresaId();
            if (!$empresaId) {
                return $this->sendError("No se pudo determinar la empresa del usuario", 403);
            }

            // SEGURIDAD: Verificar que la empresa está aceptada en la rueda
            if ($ruedaId) {
                $stmt_ins = $this->pdo->prepare("SELECT estadoInscripcion FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ? AND estadoInscripcion = 'aceptada'");
                $stmt_ins->execute([$ruedaId, $empresaId]);
                if (!$stmt_ins->fetch()) {
                    return $this->sendError("No estás aceptado en esta rueda", 403);
                }
            }

            $filters = ['rueda_id' => $ruedaId];
            $reuniones = $this->reunionModel->getByEmpresa($empresaId, $rol, $filters);

            return $this->sendSuccess($reuniones);
        } catch (Exception $e) {
            error_log('[API_REUNION_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al obtener reuniones", 500);
        }
    }

    /**
     * GET /api/reunion/{id}
     */
    public function detalle($id) {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        try {
            $detalle = $this->reunionModel->getDetalle($id);
            if (!$detalle) {
                return $this->sendError("Reunión no encontrada", 404);
            }

            // Validar pertenencia
            $miEmpresaId = $this->getEmpresaId();
            if (!$miEmpresaId) {
                return $this->sendError("No se pudo determinar la empresa del usuario", 403);
            }

            if ($detalle['compradorId'] != $miEmpresaId && $detalle['vendedorId'] != $miEmpresaId) {
                return $this->sendError("No tienes permiso para ver esta reunión", 403);
            }

            // SEGURIDAD: Verificar que la empresa está aceptada en la rueda
            $stmt_ins = $this->pdo->prepare("SELECT estadoInscripcion FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ? AND estadoInscripcion = 'aceptada'");
            $stmt_ins->execute([$detalle['ruedaId'], $miEmpresaId]);
            if (!$stmt_ins->fetch()) {
                return $this->sendError("No estás aceptado en esta rueda", 403);
            }

            return $this->sendSuccess($detalle);
        } catch (Exception $e) {
            error_log('[API_REUNION_DETALLE_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al obtener detalle de reunión", 500);
        }
    }

    /**
     * GET /api/reunion/getMesasDisponibles?rueda_id=X&fecha_hora=Y&comprador_id=Z
     */
    public function getMesasDisponibles() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        $rueda_id = $_GET['rueda_id'] ?? null;
        $fecha_hora = $_GET['fecha_hora'] ?? null;
        $comprador_id = $_GET['comprador_id'] ?? null;

        if (!$rueda_id || !$fecha_hora) {
            return $this->sendError("Parámetros incompletos", 400);
        }

        try {
            // 1. Obtener cantidad de mesas de la rueda
            $stmt_rueda = $this->pdo->prepare("SELECT cantidadMesas FROM ruedas_negocios WHERE id = ?");
            $stmt_rueda->execute([$rueda_id]);
            $rueda = $stmt_rueda->fetch();
            $total_mesas = ($rueda) ? (int)$rueda['cantidadMesas'] : 0;
            
            error_log("[MESAS_DEBUG] Rueda ID: $rueda_id, Total mesas configuradas: $total_mesas");
            
            // Validar que haya mesas configuradas
            if ($total_mesas <= 0) {
                error_log("[MESAS_DEBUG] ERROR: No hay mesas configuradas");
                return $this->sendError("La rueda de negocios no tiene mesas configuradas. Contacta al administrador.", 400);
            }

            // 2. Verificar si el comprador ya tiene una mesa asignada en esta rueda
            $mesa_asignada = null;
            if ($comprador_id) {
                $stmt_mi_mesa = $this->pdo->prepare("
                    SELECT numero_mesa FROM reuniones 
                    WHERE ruedaId = ? AND compradorId = ? 
                    AND numero_mesa IS NOT NULL 
                    AND estadoCita NOT IN ('cancelada', 'rechazada')
                    LIMIT 1
                ");
                $stmt_mi_mesa->execute([$rueda_id, $comprador_id]);
                $mi_mesa = $stmt_mi_mesa->fetch();
                if ($mi_mesa) {
                    $mesa_asignada = $mi_mesa['numero_mesa'];
                }
            }

            // 3. Obtener mesas ocupadas en ese rango (bloque de 30 minutos)
            $fechaBase = strtotime($fecha_hora);
            // Margen de 29 minutos para permitir citas seguidas (back-to-back de 30 min)
            $horaInicio = date('Y-m-d H:i:s', strtotime('-29 minutes', $fechaBase));
            $horaFin = date('Y-m-d H:i:s', strtotime('+29 minutes', $fechaBase));

            error_log("[MESAS_DEBUG] FechaHora solicitada: $fecha_hora");
            error_log("[MESAS_DEBUG] Rango búsqueda: $horaInicio a $horaFin");
            error_log("[MESAS_DEBUG] Comprador ID: $comprador_id");

            // Si el comprador ya tiene una mesa, solo verificamos si ESA mesa está libre para el nuevo horario
            // Pero permitimos que ÉL mismo la use aunque tenga citas cercanas (la validación de 45 min del controlador ya evita solapamientos del comprador)
            $stmt_ocupadas = $this->pdo->prepare("
                SELECT numero_mesa FROM reuniones 
                WHERE ruedaId = ? 
                AND fechaHora BETWEEN ? AND ?
                AND estadoCita NOT IN ('cancelada', 'rechazada')
                AND numero_mesa IS NOT NULL
                " . ($comprador_id ? "AND compradorId != ?" : "") . "
            ");
            
            $params_ocupadas = [$rueda_id, $horaInicio, $horaFin];
            if ($comprador_id) $params_ocupadas[] = $comprador_id;
            
            $stmt_ocupadas->execute($params_ocupadas);
            $ocupadas_por_otros = $stmt_ocupadas->fetchAll(PDO::FETCH_COLUMN);
            
            error_log("[MESAS_DEBUG] Mesas ocupadas por otros: " . json_encode($ocupadas_por_otros));

            // 4. Generar lista de mesas disponibles
            $disponibles = [];
            for ($i = 1; $i <= $total_mesas; $i++) {
                $nombre_mesa = "Mesa $i";
                // Una mesa está disponible si:
                // - No está ocupada por OTRO comprador en ese horario
                if (!in_array($nombre_mesa, $ocupadas_por_otros)) {
                    $disponibles[] = $nombre_mesa;
                }
            }
            
            error_log("[MESAS_DEBUG] Mesas disponibles: " . json_encode($disponibles));
            error_log("[MESAS_DEBUG] Mesa asignada al comprador: " . ($mesa_asignada ?? 'ninguna'));

            // Si no hay mesas disponibles, incluir información de depuración
            $response = [
                'mesas' => $disponibles,
                'mesa_sugerida' => $mesa_asignada
            ];
            
            if (empty($disponibles)) {
                $response['debug'] = [
                    'total_mesas_configuradas' => $total_mesas,
                    'mesas_ocupadas' => $ocupadas_por_otros,
                    'fecha_hora_solicitada' => $fecha_hora,
                    'rango_busqueda' => "$horaInicio a $horaFin"
                ];
            }

            return $this->sendSuccess($response);
        } catch (Exception $e) {
            error_log('[API_MESAS_ERROR] ' . $e->getMessage());
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
