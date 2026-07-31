<?php
class ReunionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene reuniones de una empresa filtradas por rueda y estado
     */
    public function getByEmpresa($empresaId, $rol, $filters = []) {
        $idField = ($rol === 'comprador') ? 'compradorId' : 'vendedorId';
        $contraparteField = ($rol === 'comprador') ? 'vendedorId' : 'compradorId';

        $sql = "
            SELECT r.*, e.razon_social as nombre_contraparte, rn.tituloRueda
            FROM reuniones r
            JOIN empresas e ON r.{$contraparteField} = e.id
            JOIN ruedas_negocios rn ON r.ruedaId = rn.id
            WHERE r.{$idField} = ?
        ";
        $params = [$empresaId];

        if (!empty($filters['rueda_id'])) {
            $sql .= " AND r.ruedaId = ?";
            $params[] = $filters['rueda_id'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND r.estadoCita = ?";
            $params[] = $filters['estado'];
        }

        $sql .= " ORDER BY r.fechaHora DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene detalle de una reunión con su última negociación
     */
    public function getDetalle($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, 
                   e1.razon_social as nombre_comprador, 
                   e2.razon_social as nombre_vendedor,
                   rn.tituloRueda,
                   neg.propuestoPor, neg.mensaje, neg.linkPropuesto
            FROM reuniones r
            JOIN empresas e1 ON r.compradorId = e1.id
            JOIN empresas e2 ON r.vendedorId = e2.id
            JOIN ruedas_negocios rn ON r.ruedaId = rn.id
            LEFT JOIN (
                SELECT * FROM reunion_negociaciones 
                WHERE id IN (SELECT MAX(id) FROM reunion_negociaciones GROUP BY reunionId)
            ) neg ON r.id = neg.reunionId
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
