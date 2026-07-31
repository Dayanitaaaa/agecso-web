<?php
class ImpactoModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Registra una encuesta de satisfacción después de una reunión
     */
    public function registrarEncuesta($reunionId, $usuarioId, $calificacion, $expectativa, $seguimiento, $comentarios) {
        $sql = "INSERT INTO encuestas_satisfaccion (reunionId, usuarioId, calificacionGeneral, expectativaNegocio, interesSeguimiento, comentarios) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$reunionId, $usuarioId, $calificacion, $expectativa, $seguimiento, $comentarios]);
    }

    /**
     * Obtiene el resumen de impacto de una rueda de negocios específica
     */
    public function getResumenImpacto($ruedaId) {
        $sql = "SELECT * FROM v_impacto_ruedas WHERE ruedaId = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ruedaId]);
        return $stmt->fetch();
    }

    /**
     * Actualiza el monto de cierre de una reunión
     */
    public function actualizarMontoReunion($reunionId, $monto, $acuerdoCerrado = true) {
        $sql = "UPDATE reuniones SET montoEstimado = ?, acuerdoCerrado = ?, estadoCita = 'realizada' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$monto, $acuerdoCerrado, $reunionId]);
    }

    /**
     * Obtiene estadísticas globales para el Administrador
     */
    public function getEstadisticasGlobales() {
        $sql = "SELECT 
                    COUNT(DISTINCT ruedaId) as total_ruedas,
                    SUM(citas_totales) as total_citas,
                    SUM(citas_exitosas) as total_citas_exitosas,
                    SUM(volumen_negocio_proyectado) as total_volumen_negocio
                FROM v_impacto_ruedas";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
?>
