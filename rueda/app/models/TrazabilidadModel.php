<?php
/**
 * Modelo para gestionar la trazabilidad de negocios (encuestas a 3 y 6 meses)
 */
class TrazabilidadModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Programa encuestas de trazabilidad cuando se agenda una reunión
     */
    public function programarSeguimientos($reunionId, $compradorId, $vendedorId, $fechaReunion) {
        try {
            // Calcular fechas de seguimiento (3 y 6 meses después de la reunión)
            $fecha3Meses = date('Y-m-d', strtotime($fechaReunion . ' +3 months'));
            $fecha6Meses = date('Y-m-d', strtotime($fechaReunion . ' +6 months'));

            // Insertar seguimiento para el comprador
            $sql = "INSERT INTO trazabilidad_seguimiento 
                    (reunionId, usuarioId, tipo, fecha_programada, estado) 
                    VALUES (?, ?, '3_meses', ?, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$reunionId, $compradorId, $fecha3Meses]);

            $sql = "INSERT INTO trazabilidad_seguimiento 
                    (reunionId, usuarioId, tipo, fecha_programada, estado) 
                    VALUES (?, ?, '6_meses', ?, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$reunionId, $compradorId, $fecha6Meses]);

            // Insertar seguimiento para el vendedor
            $sql = "INSERT INTO trazabilidad_seguimiento 
                    (reunionId, usuarioId, tipo, fecha_programada, estado) 
                    VALUES (?, ?, '3_meses', ?, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$reunionId, $vendedorId, $fecha3Meses]);

            $sql = "INSERT INTO trazabilidad_seguimiento 
                    (reunionId, usuarioId, tipo, fecha_programada, estado) 
                    VALUES (?, ?, '6_meses', ?, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$reunionId, $vendedorId, $fecha6Meses]);

            return true;
        } catch (PDOException $e) {
            error_log("Error al programar seguimientos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene seguimientos pendientes para un usuario
     *
     * Nota: se usa fecha de referencia desde PHP para soportar simulaciones (SYSTEM_TIME)
     */
    public function getSeguimientosPendientes($usuarioId, $fechaReferencia = null) {
        if ($fechaReferencia === null) {
            $fechaReferencia = date('Y-m-d');
        } else {
            // Acepta timestamp o string datetime
            if (is_numeric($fechaReferencia)) {
                $fechaReferencia = date('Y-m-d', (int)$fechaReferencia);
            } else {
                $fechaReferencia = date('Y-m-d', strtotime((string)$fechaReferencia));
            }
        }

        // Primero obtener el ID de la empresa del usuario
        $stmtEmp = $this->db->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
        $stmtEmp->execute([$usuarioId]);
        $empresa = $stmtEmp->fetch();
        
        if (!$empresa) {
            return [];
        }
        
        $empresaId = $empresa['id'];
        
        $sql = "SELECT ts.*, 
                       r.fechaHora as fecha_reunion,
                       CASE 
                           WHEN r.compradorId = ? THEN e_vend.razon_social
                           ELSE e_comp.razon_social
                       END as nombre_contraparte,
                       rn.tituloRueda
                FROM trazabilidad_seguimiento ts
                JOIN reuniones r ON ts.reunionId = r.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                LEFT JOIN empresas e_comp ON r.compradorId = e_comp.id
                LEFT JOIN empresas e_vend ON r.vendedorId = e_vend.id
                WHERE ts.usuarioId = ? 
                  AND ts.estado = 'pendiente'
                  AND ts.fecha_programada <= ?
                ORDER BY ts.fecha_programada ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empresaId, $usuarioId, $fechaReferencia]);
        return $stmt->fetchAll();
    }

    /**
     * Marca un seguimiento como completado
     */
    public function completarSeguimiento($seguimientoId, $encuestaId) {
        $sql = "UPDATE trazabilidad_seguimiento 
                SET estado = 'completada', encuestaId = ?, updatedAt = NOW() 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$encuestaId, $seguimientoId]);
    }

    /**
     * Verifica si hay seguimientos pendientes para una reunión específica
     */
    public function verificarSeguimientosPendientes($reunionId, $usuarioId) {
        $sql = "SELECT COUNT(*) FROM trazabilidad_seguimiento 
                WHERE reunionId = ? AND usuarioId = ? AND estado = 'pendiente'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reunionId, $usuarioId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Obtiene el historial de encuestas de trazabilidad respondidas
     */
    public function getHistorialTrazabilidad($usuarioId) {
        $sql = "SELECT es.*, 
                       r.fechaHora as fecha_reunion,
                       e.razon_social as contraparte,
                       rn.tituloRueda,
                       CASE 
                           WHEN es.tipo_encuesta = 'trazabilidad_3_meses' THEN '3 Meses'
                           WHEN es.tipo_encuesta = 'trazabilidad_6_meses' THEN '6 Meses'
                           ELSE 'Satisfacción'
                       END as tipo_label
                FROM encuestas_satisfaccion es
                JOIN reuniones r ON es.reunionId = r.id
                JOIN empresas e ON 
                    CASE 
                        WHEN r.compradorId = (SELECT id FROM empresas WHERE usuarioId = es.usuarioId) 
                        THEN r.vendedorId 
                        ELSE r.compradorId 
                    END = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE es.usuarioId = ? 
                  AND es.tipo_encuesta IN ('trazabilidad_3_meses', 'trazabilidad_6_meses')
                ORDER BY es.createdAt DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
}
