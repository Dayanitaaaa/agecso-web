<?php
require_once __DIR__ . '/BaseApiController.php';
require_once __DIR__ . '/../../models/RuedaModel.php';

class RuedaApiController extends BaseApiController {
    private $ruedaModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->ruedaModel = new RuedaModel($pdo);
    }

    /**
     * GET /api/ruedas
     * Obtiene todas las ruedas activas
     */
    public function listarActivas() {
        try {
            $ruedas = $this->ruedaModel->getActivas();
            return $this->sendSuccess($ruedas, "Ruedas obtenidas correctamente");
        } catch (Exception $e) {
            return $this->sendError("Error al obtener ruedas: " . $e->getMessage());
        }
    }

    /**
     * GET /api/rueda/{id}
     * Obtiene detalle de una rueda específica
     */
    public function detalle($id) {
        try {
            $rueda = $this->ruedaModel->getById($id);

            if (!$rueda) {
                return $this->sendError("Rueda no encontrada", 404);
            }

            return $this->sendSuccess($rueda);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
