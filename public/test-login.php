<?php
try {
    require_once __DIR__ . '/../config/db.php';
    
    echo "<h2>Test de Login Directo</h2>";
    
    // 1. Ver hash actual
    $stmt = $pdo->query("SELECT password FROM usuarios_admin WHERE email = 'admin@agecso.org'");
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "<p style='color:red'>Usuario no existe</p>";
        exit;
    }
    
    $currentHash = $user['password'];
    echo "<p>Hash actual: " . substr($currentHash, 0, 20) . "...</p>";
    echo "<p>Hash length: " . strlen($currentHash) . "</p>";
    
    // 2. Probar verify con 'admin123'
    $test1 = password_verify('admin123', $currentHash);
    echo "<p>password_verify('admin123', hash): " . ($test1 ? "CORRECTO" : "INCORRECTO") . "</p>";
    
    // 3. Si no funciona, resetear
    if (!$test1) {
        echo "<hr><p>Reseteando contraseña...</p>";
        
        $newHash = password_hash('admin123', PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE usuarios_admin SET password = ? WHERE email = 'admin@agecso.org'");
        $update->execute([$newHash]);
        
        echo "<p>Nuevo hash: " . substr($newHash, 0, 20) . "...</p>";
        
        // 4. Verificar que se guardó
        $stmt = $pdo->query("SELECT password FROM usuarios_admin WHERE email = 'admin@agecso.org'");
        $user2 = $stmt->fetch();
        $storedHash = $user2['password'];
        
        echo "<p>Hash guardado: " . substr($storedHash, 0, 20) . "...</p>";
        
        // 5. Verificar que funciona
        $test2 = password_verify('admin123', $storedHash);
        echo "<h3>" . ($test2 ? "LOGIN FUNCIONA" : "LOGIN SIGUE FALLANDO") . "</h3>";
        
        if ($test2) {
            echo "<p style='color:green;font-weight:bold'>Intenta login ahora: admin@agecso.org / admin123</p>";
        }
    } else {
        echo "<p style='color:green'>El login debería funcionar. Intenta entrar.</p>";
    }
    
    echo "<hr><a href='?page=login'>Ir al login</a>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>" . $e->getMessage() . "</p>";
}
?>
