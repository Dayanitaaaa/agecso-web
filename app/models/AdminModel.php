<?php
require_once __DIR__ . '/BaseModel.php';

class AdminModel extends BaseModel {
    public $table = 'usuarios_admin';

    /**
     * Buscar usuario por email
     */
    public function getByEmail($email) {
        $email = strtolower(trim((string)$email));
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE LOWER(TRIM(email)) = ? AND (activo = 1 OR activo IS NULL) LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Asegurar que existan las columnas de reset_token y reset_expires
     */
    private function ensureResetColumnsExist() {
        try {
            $stmt = $this->pdo->query("SHOW COLUMNS FROM {$this->table} LIKE 'reset_token'");
            if ($stmt && !$stmt->fetch()) {
                $this->pdo->exec("ALTER TABLE {$this->table} ADD COLUMN reset_token VARCHAR(255) NULL, ADD COLUMN reset_expires DATETIME NULL");
            }
        } catch (Exception $e) {}
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
        $this->ensureResetColumnsExist();
        $email = strtolower(trim((string)$email));
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET reset_token = ?, reset_expires = ? WHERE LOWER(TRIM(email)) = ?");
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
