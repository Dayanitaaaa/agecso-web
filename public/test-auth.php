<?php
try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../app/models/BaseModel.php';
    require_once __DIR__ . '/../app/models/AdminModel.php';
    
    $adminModel = new AdminModel($pdo);
    
    echo "<h2>Simulando AuthController</h2>";
    
    $email = 'admin@agecso.org';
    $password = 'admin123';
    
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";
    
    // Paso 1: Obtener usuario
    $usuario = $adminModel->getByEmail($email);
    
    if (!$usuario) {
        echo "<p style='color:red'><strong>ERROR:</strong> Usuario no encontrado o inactivo</p>";
        echo "<p>Esto significa que el problema está en getByEmail()</p>";
    } else {
        echo "<p style='color:green'>Usuario encontrado: " . $usuario['email'] . "</p>";
        echo "<p>Activo: " . $usuario['activo'] . "</p>";
        
        // Paso 2: Verificar password
        $result = $adminModel->verifyPassword($password, $usuario['password']);
        
        echo "<p>password_verify result: " . ($result ? "<span style='color:green'>CORRECTO</span>" : "<span style='color:red'>INCORRECTO</span>") . "</p>";
        
        if ($result) {
            echo "<h3 style='color:green'>Login debería funcionar!</h3>";
            echo "<p>El AuthController tiene un bug. Vamos a revisar el index.php</p>";
        } else {
            echo "<h3 style='color:red'>El hash está mal en la BD</h3>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
}
?>
