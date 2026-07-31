<?php
class DemandaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene demandas de una empresa en una rueda específica
     */
    public function getByEmpresaYRueda($empresaId, $ruedaId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM demandas 
            WHERE empresaId = ? AND ruedaId = ? 
            ORDER BY createdAt DESC
        ");
        $stmt->execute([$empresaId, $ruedaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Registra una nueva demanda
     */
    public function crear($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO demandas (empresaId, ruedaId, tituloDemanda, descripcionDemanda, tagsRequerimiento) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['empresaId'],
            $data['ruedaId'],
            $data['tituloDemanda'],
            $data['descripcionDemanda'],
            $data['tagsRequerimiento']
        ]);
    }
}
