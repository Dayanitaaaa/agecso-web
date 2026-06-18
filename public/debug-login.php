<?php
try {
    require_once __DIR__ . '/../config/db.php';
    
    $stmt = $pdo->query("SELECT id, nombre, email, password, activo, rol FROM usuarios_admin WHERE email = 'admin@agecso.org'");
    $user = $stmt->fetch();
    
    echo "<h2>Diagnostico de login</h2>";
    
    if (!$user) {
        echo "<p style='color:red'><strong>Usuario admin@agecso.org NO existe</strong></p>";
        
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios_admin (nombre, email, password, rol, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Administrador', 'admin@agecso.org', $hash, 'admin', 1]);
        echo "<p style='color:green'>Usuario creado exitosamente</p>";
        
        // Verificar
        $stmt = $pdo->query("SELECT id, nombre, email, password, activo, rol FROM usuarios_admin WHERE email = 'admin@agecso.org'");
        $user = $stmt->fetch();
    }
    
    echo "<h3>Datos del usuario:</h3>";
    echo "<pre>";
    echo "ID: " . ($user['id'] ?? 'N/A') . "\n";
    echo "Email: " . ($user['email'] ?? 'N/A') . "\n";
    echo "Nombre: " . ($user['nombre'] ?? 'N/A') . "\n";
    echo "Rol: " . ($user['rol'] ?? 'N/A') . "\n";
    echo "Activo: " . ($user['activo'] ?? 'N/A') . "\n";
    echo "Password hash length: " . strlen($user['password'] ?? '') . " chars\n";
    echo "Password hash starts with: " . substr($user['password'] ?? '', 0, 7) . "\n";
    echo "</pre>";
    
    // Probar verificacion
    $test = password_verify('admin123', $user['password'] ?? '');
    echo "<p><strong>password_verify('admin123', hash):</strong> " . ($test ? "<span style='color:green'>CORRECTO</span>" : "<span style='color:red'>INCORRECTO</span>") . "</p>";
    
    // Resetear contraseña de todas formas
    $newHash = password_hash('admin123', PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE usuarios_admin SET password = ?, activo = 1 WHERE email = 'admin@agecso.org'");
    $update->execute([$newHash]);
    
    echo "<hr>";
    echo "<h3 style='color:green'>Contraseña reseteada a: admin123</h3>";
    echo "<p><a href='?page=login'><strong>Ir al login ahora</strong></a></p>";
    
} catch (Exception $e) {
    echo "<h3 style='color:red'>Error:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>" . htmlspecialchars($e->getTraceAsString()) . "</p>";
}
?>
