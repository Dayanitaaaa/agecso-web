<?php
class OfertaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene ofertas activas para una rueda específica
     */
    public function getByRueda($ruedaId, $filters = []) {
        $sql = "
            SELECT o.*, e.razon_social, s.nombreSector, e.ubicacionGeografica
            FROM ofertas o
            JOIN empresas e ON o.empresaId = e.id
            JOIN sectores s ON o.sectorId = s.id
            JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
            WHERE o.isActive = 1 AND ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada'
        ";
        $params = [$ruedaId];

        if (!empty($filters['busqueda'])) {
            $sql .= " AND (o.tituloOferta LIKE ? OR o.descripcionOferta LIKE ? OR e.razon_social LIKE ?)";
            $search = "%{$filters['busqueda']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['sector_id'])) {
            $sql .= " AND o.sectorId = ?";
            $params[] = $filters['sector_id'];
        }

        $sql .= " ORDER BY o.createdAt DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene ofertas de un sector específico (recomendaciones)
     */
    public function getBySector($sectorId, $ruedaId, $excludeEmpresaId = null, $limit = 5) {
        $sql = "
            SELECT o.*, e.razon_social
            FROM ofertas o
            JOIN empresas e ON o.empresaId = e.id
            JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
            WHERE o.isActive = 1 AND o.sectorId = ? AND ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada'
        ";
        $params = [$sectorId, $ruedaId];

        if ($excludeEmpresaId) {
            $sql .= " AND e.id != ?";
            $params[] = $excludeEmpresaId;
        }

        $sql .= " LIMIT " . (int)$limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
