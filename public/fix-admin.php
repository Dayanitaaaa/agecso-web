<?php
try {
    require_once __DIR__ . '/../config/db.php';
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios_admin SET password = ? WHERE email = 'admin@agecso.org'");
    $stmt->execute([$hash]);
    echo "<h2 style='color:green'>✓ Contraseña actualizada correctamente</h2>";
    echo "<p><strong>Email:</strong> admin@agecso.org</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<p><a href='?page=login'>Ir al login</a></p>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
