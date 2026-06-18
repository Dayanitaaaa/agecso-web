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
}
