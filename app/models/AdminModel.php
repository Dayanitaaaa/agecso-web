<?php
require_once __DIR__ . '/BaseModel.php';

class AdminModel extends BaseModel {
    public $table = 'usuarios_admin';

    /**
     * Buscar usuario por email
     */
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Verificar contraseña
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Guardar token de recuperación
     */
    public function setResetToken($email, $token, $expires) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET reset_token = ?, reset_expires = ? WHERE email = ?");
        return $stmt->execute([$token, $expires, $email]);
    }

    /**
     * Buscar usuario por token de recuperación válido
     */
    public function getByResetToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE reset_token = ? AND reset_expires > NOW() AND activo = 1");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Actualizar contraseña y limpiar token
     */
    public function updatePassword($id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }
}
