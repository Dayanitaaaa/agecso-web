<?php
class NotificacionModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Crear una nueva notificación
     */
    public function crear($usuarioId, $tipo, $titulo, $mensaje, $linkDestino = null) {
        $sql = "INSERT INTO notificaciones (usuarioId, tipo, titulo, mensaje, link_destino) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $tipo, $titulo, $mensaje, $linkDestino]);
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function obtenerNoLeidas($usuarioId) {
        $sql = "SELECT * FROM notificaciones WHERE usuarioId = ? AND leida = 0 ORDER BY createdAt DESC LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener todas las notificaciones de un usuario
     */
    public function obtenerTodas($usuarioId, $limite = 20) {
        $sql = "SELECT * FROM notificaciones WHERE usuarioId = ? ORDER BY createdAt DESC LIMIT " . intval($limite);
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNoLeidas($usuarioId) {
        $sql = "SELECT COUNT(*) as total FROM notificaciones WHERE usuarioId = ? AND leida = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetch()['total'];
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida($notificacionId) {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$notificacionId]);
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasLeidas($usuarioId) {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE usuarioId = ? AND leida = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId]);
    }

    /**
     * Notificar a los administradores sobre nuevos eventos
     */
    public function notificarAdmins($tipo, $titulo, $mensaje, $linkDestino = null) {
        $sql = "SELECT u.id FROM usuarios u JOIN roles r ON u.roleId = r.id WHERE r.slugRole = 'admin' AND u.isActive = 1";
        $stmt = $this->db->query($sql);
        $admins = $stmt->fetchAll();

        foreach ($admins as $admin) {
            $this->crear($admin['id'], $tipo, $titulo, $mensaje, $linkDestino);
        }
    }

    /**
     * Notificar directamente a una empresa por su empresaId
     */
    public function notificarEmpresa($empresaId, $tipo, $titulo, $mensaje, $linkDestino = null) {
        try {
            $stmt = $this->db->prepare("SELECT usuarioId FROM empresas WHERE id = ? LIMIT 1");
            $stmt->execute([$empresaId]);
            $usuarioId = $stmt->fetchColumn();
            if ($usuarioId) {
                return $this->crear($usuarioId, $tipo, $titulo, $mensaje, $linkDestino);
            }
        } catch (Exception $e) {
            error_log('[NOTIFICACION_ERROR] Error al notificar empresa: ' . $e->getMessage());
        }
        return false;
    }
}
