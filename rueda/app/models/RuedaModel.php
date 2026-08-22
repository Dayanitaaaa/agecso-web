<?php
class RuedaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene ruedas activas o en periodo de inscripción
     */
    public function getActivas() {
        $stmt = $this->pdo->prepare("
            SELECT id, nombreRueda as tituloRueda, descripcion as descripcionRueda, fechaInicio, fechaFin, estadoRueda 
            FROM ruedas_negocios 
            WHERE estadoRueda IN ('activa', 'inscripciones')
            ORDER BY fechaInicio DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene detalle de una rueda
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT *, nombreRueda as tituloRueda, descripcion as descripcionRueda FROM ruedas_negocios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica inscripción de una empresa en una rueda
     */
    public function getInscripcion($ruedaId, $empresaId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM inscripciones_ruedas 
            WHERE ruedaId = ? AND empresaId = ?
        ");
        $stmt->execute([$ruedaId, $empresaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
