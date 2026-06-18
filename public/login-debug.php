<?php
try {
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../app/models/BaseModel.php';
    require_once __DIR__ . '/../app/models/AdminModel.php';
    
    $adminModel = new AdminModel($pdo);
    
    echo "<h2>Debug Login</h2>";
    echo "<form method='POST'>";
    echo "<p>Email: <input type='email' name='email' value='admin@agecso.org'></p>";
    echo "<p>Password: <input type='password' name='password' value='admin123'></p>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        echo "<hr>";
        echo "<p>Email recibido: '" . htmlspecialchars($email) . "' (length: " . strlen($email) . ")</p>";
        echo "<p>Password recibido: '" . htmlspecialchars($password) . "' (length: " . strlen($password) . ")</p>";
        
        $usuario = $adminModel->getByEmail($email);
        
        if (!$usuario) {
            echo "<p style='color:red'>Usuario NO encontrado en BD</p>";
        } else {
            echo "<p style='color:green'>Usuario encontrado: " . $usuario['email'] . "</p>";
            echo "<p>Hash en BD: " . substr($usuario['password'], 0, 20) . "...</p>";
            
            $result = $adminModel->verifyPassword($password, $usuario['password']);
            echo "<p>password_verify: " . ($result ? "<b style='color:green'>CORRECTO</b>" : "<b style='color:red'>INCORRECTO</b>") . "</p>";
            
            if ($result) {
                echo "<h3 style='color:green'>LOGIN EXITOSO!</h3>";
                echo "<p><a href='?page=login'>Ir al login real</a></p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
