<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../models/OfertaModel.php';

class OfertaApiController extends BaseApiController {
    private $ofertaModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->ofertaModel = new OfertaModel($pdo);
    }

    /**
     * GET /api/ofertas?rueda_id=X
     */
    public function listar() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        $ruedaId = $_GET['rueda_id'] ?? null;
        if (!$ruedaId) {
            return $this->sendError("Falta rueda_id", 400);
        }

        // SEGURIDAD: Verificar que el usuario está inscrito y ACEPTADO en la rueda
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            return $this->sendError("No se pudo determinar la empresa del usuario", 403);
        }

        $stmt_ins = $this->pdo->prepare("SELECT estadoInscripcion FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ? AND estadoInscripcion = 'aceptada'");
        $stmt_ins->execute([$ruedaId, $empresaId]);
        if (!$stmt_ins->fetch()) {
            return $this->sendError("No estás inscrito o aceptado en esta rueda", 403);
        }

        $filters = [
            'busqueda' => $_GET['busqueda'] ?? '',
            'sector_id' => $_GET['sector_id'] ?? ''
        ];

        try {
            $ofertas = $this->ofertaModel->getByRueda($ruedaId, $filters);
            return $this->sendSuccess($ofertas);
        } catch (Exception $e) {
            error_log('[API_OFERTA_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al obtener ofertas", 500);
        }
    }

    /**
     * GET /api/ofertas/recomendadas?rueda_id=X
     */
    public function recomendadas() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        $ruedaId = $_GET['rueda_id'] ?? null;
        if (!$ruedaId) {
            return $this->sendError("Falta rueda_id", 400);
        }

        try {
            // Obtener sector del usuario
            $usuarioId = $this->getUsuarioId();
            $stmt = $this->pdo->prepare("SELECT e.id, e.sectorId FROM empresas e JOIN usuarios u ON e.usuarioId = u.id WHERE u.id = ?");
            $stmt->execute([$usuarioId]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                return $this->sendError("Empresa no encontrada", 404);
            }

            // SEGURIDAD: Verificar que el usuario está inscrito y ACEPTADO en la rueda
            $stmt_ins = $this->pdo->prepare("SELECT estadoInscripcion FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ? AND estadoInscripcion = 'aceptada'");
            $stmt_ins->execute([$ruedaId, $empresa['id']]);
            if (!$stmt_ins->fetch()) {
                return $this->sendError("No estás inscrito o aceptado en esta rueda", 403);
            }

            $recomendaciones = $this->ofertaModel->getBySector($empresa['sectorId'], $ruedaId, $empresa['id']);
            return $this->sendSuccess($recomendaciones);
        } catch (Exception $e) {
            error_log('[API_OFERTA_RECOMENDACION_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al obtener recomendaciones", 500);
        }
    }
}
