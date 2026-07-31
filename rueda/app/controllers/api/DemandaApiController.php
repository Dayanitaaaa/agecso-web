<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../models/DemandaModel.php';
require_once __DIR__ . '/../../models/RuedaModel.php';

class DemandaApiController extends BaseApiController {
    private $demandaModel;
    private $ruedaModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->demandaModel = new DemandaModel($pdo);
        $this->ruedaModel = new RuedaModel($pdo);
    }

    /**
     * GET /api/demandas?rueda_id=X
     * Lista demandas del usuario actual en una rueda
     */
    public function listarPorRueda() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        $ruedaId = $_GET['rueda_id'] ?? null;
        if (!$ruedaId) {
            return $this->sendError("Falta rueda_id", 400);
        }

        // Obtener empresaId (funciona con JWT o sesión)
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            return $this->sendError("No se pudo determinar la empresa del usuario", 403);
        }

        try {
            $demandas = $this->demandaModel->getByEmpresaYRueda($empresaId, $ruedaId);
            return $this->sendSuccess($demandas);
        } catch (Exception $e) {
            error_log('[API_DEMANDA_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al obtener demandas", 500);
        }
    }

    /**
     * POST /api/demandas
     * Crea una nueva demanda
     */
    public function crear() {
        if (!$this->isAuthenticated()) {
            return $this->sendError("No autenticado", 401);
        }

        // Leer JSON del body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data) {
            return $this->sendError("Datos inválidos", 400);
        }

        $ruedaId = $data['rueda_id'] ?? null;
        $titulo = $data['titulo'] ?? null;
        $descripcion = $data['descripcion'] ?? null;
        $tags = $data['tags'] ?? [];

        if (!$ruedaId || !$titulo || !$descripcion) {
            return $this->sendError("Faltan campos obligatorios", 400);
        }

        // Obtener empresaId (funciona con JWT o sesión)
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            return $this->sendError("No se pudo determinar la empresa del usuario", 403);
        }

        try {
            // Validar inscripción
            $inscripcion = $this->ruedaModel->getInscripcion($ruedaId, $empresaId);
            if (!$inscripcion || $inscripcion['estadoInscripcion'] !== 'aceptada') {
                return $this->sendError("No estás aceptado en esta rueda", 403);
            }

            $tagsJson = json_encode($tags);
            $nuevaDemanda = [
                'empresaId' => $empresaId,
                'ruedaId' => $ruedaId,
                'tituloDemanda' => $titulo,
                'descripcionDemanda' => $descripcion,
                'tagsRequerimiento' => $tagsJson
            ];

            if ($this->demandaModel->crear($nuevaDemanda)) {
                return $this->sendSuccess([], "Demanda creada correctamente", 201);
            } else {
                return $this->sendError("No se pudo crear la demanda", 500);
            }
        } catch (Exception $e) {
            error_log('[API_DEMANDA_CREAR_ERROR] ' . $e->getMessage());
            return $this->sendError("Error al crear demanda", 500);
        }
    }
}
