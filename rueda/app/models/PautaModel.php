<?php
class PautaModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Crear una nueva pauta
     */
    public function crear($data) {
        $sql = "INSERT INTO pautas_publicitarias 
                (empresaId, titulo, descripcion, imagen, link_destino, fecha_inicio, fecha_fin, estado, posicion)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente', ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empresaId'],
            $data['titulo'],
            $data['descripcion'],
            $data['imagen'],
            $data['link_destino'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['posicion'] ?? 1
        ]);
    }

    /**
     * Obtener pautas de una empresa
     */
    public function obtenerPorEmpresa($empresaId) {
        $sql = "SELECT p.*, e.razon_social 
                FROM pautas_publicitarias p
                JOIN empresas e ON p.empresaId = e.usuarioId
                WHERE p.empresaId = ?
                ORDER BY p.createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener todas las pautas (para admin)
     */
    public function obtenerTodas($estado = null) {
        if ($estado) {
            $sql = "SELECT p.*, e.razon_social 
                    FROM pautas_publicitarias p
                    JOIN empresas e ON p.empresaId = e.usuarioId
                    WHERE p.estado = ?
                    ORDER BY p.createdAt DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$estado]);
        } else {
            $sql = "SELECT p.*, e.razon_social 
                    FROM pautas_publicitarias p
                    JOIN empresas e ON p.empresaId = e.usuarioId
                    ORDER BY p.createdAt DESC";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }

    /**
     * Obtener pautas activas para mostrar en el home
     */
    public function obtenerActivas() {
        $hoy = date('Y-m-d');
        $sql = "SELECT p.*, e.razon_social, e.logoPath
                FROM pautas_publicitarias p
                JOIN empresas e ON p.empresaId = e.usuarioId
                WHERE p.estado = 'activa'
                AND p.fecha_inicio <= ?
                AND p.fecha_fin >= ?
                ORDER BY p.posicion ASC, p.createdAt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$hoy, $hoy]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener una pauta por ID
     */
    public function obtenerPorId($id) {
        $sql = "SELECT p.*, e.razon_social 
                FROM pautas_publicitarias p
                JOIN empresas e ON p.empresaId = e.usuarioId
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Actualizar estado de una pauta
     */
    public function actualizarEstado($id, $estado, $comentarioRechazo = null) {
        if ($comentarioRechazo) {
            $sql = "UPDATE pautas_publicitarias SET estado = ?, comentario_rechazo = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $comentarioRechazo, $id]);
        } else {
            $sql = "UPDATE pautas_publicitarias SET estado = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $id]);
        }
    }

    /**
     * Actualizar información de pago (para cuando se agregue pasarela)
     */
    public function actualizarPago($id, $metodoPago, $referencia, $monto) {
        $sql = "UPDATE pautas_publicitarias 
                SET metodo_pago = ?, referencia_pago = ?, monto_pagado = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$metodoPago, $referencia, $monto, $id]);
    }

    /**
     * Verificar disponibilidad de posición
     */
    public function verificarDisponibilidad($posicion, $fechaInicio, $fechaFin, $excluirId = null) {
        $sql = "SELECT COUNT(*) as total 
                FROM pautas_publicitarias 
                WHERE posicion = ? 
                AND estado IN ('activa', 'aprobada')
                AND (
                    (fecha_inicio <= ? AND fecha_fin >= ?) OR
                    (fecha_inicio <= ? AND fecha_fin >= ?) OR
                    (fecha_inicio >= ? AND fecha_fin <= ?)
                )";
        
        if ($excluirId) {
            $sql .= " AND id != ?";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [$posicion, $fechaInicio, $fechaInicio, $fechaFin, $fechaFin, $fechaInicio, $fechaFin];
        if ($excluirId) {
            $params[] = $excluirId;
        }
        $stmt->execute($params);
        return $stmt->fetch()['total'] == 0;
    }

    /**
     * Eliminar una pauta
     */
    public function eliminar($id) {
        $pauta = $this->obtenerPorId($id);
        if ($pauta && !empty($pauta['imagen'])) {
            $imagenPath = __DIR__ . '/../../public/uploads/pautas/' . $pauta['imagen'];
            if (file_exists($imagenPath)) {
                unlink($imagenPath);
            }
        }
        
        $sql = "DELETE FROM pautas_publicitarias WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
