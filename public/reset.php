<?php
try {
    require_once __DIR__ . '/../config/db.php';
    
    // Verificar si existe el usuario
    $check = $pdo->query("SELECT id, email, activo FROM usuarios_admin WHERE email = 'admin@agecso.org'");
    $user = $check->fetch();
    
    if (!$user) {
        echo "<h3 style='color:red'>Usuario admin@agecso.org NO existe</h3>";
        echo "<p>Creando usuario...</p>";
        
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios_admin (nombre, email, password, rol, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Administrador', 'admin@agecso.org', $hash, 'admin', 1]);
        echo "<h3 style='color:green'>Usuario creado exitosamente</h3>";
    } else {
        echo "<h3>Usuario encontrado</h3>";
        echo "<p>Email: {$user['email']}</p>";
        echo "<p>Activo: {$user['activo']}</p>";
        
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios_admin SET password = ?, activo = 1 WHERE email = 'admin@agecso.org'");
        $stmt->execute([$hash]);
        echo "<h3 style='color:green'>Contraseña actualizada</h3>";
    }
    
    echo "<hr>";
    echo "<p><strong>Email:</strong> admin@agecso.org</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p><a href='?page=login'>Ir al login</a></p>";
    
} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
