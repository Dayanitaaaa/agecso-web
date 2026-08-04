<?php
// Intentar cargar la configuración desde diferentes rutas posibles
$configFiles = [
    __DIR__ . '/config/db.php',
    __DIR__ . '/app/config/db.php',
    __DIR__ . '/rueda/config/db.php'
];

$loaded = false;
foreach ($configFiles as $file) {
    if (file_exists($file)) {
        require_once $file;
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    die("Error: No se encontró el archivo de configuración db.php. Por favor, verifica la ruta.");
}

try {
    // Usar las variables que vienen de db.php (suponiendo que son $host, $dbname, $user, $pass)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Actualización de Base de Datos AGECSO</h3>";

    // 1. Agregar columna a noticias
    try {
        $pdo->exec("ALTER TABLE noticias ADD COLUMN imagenes LONGTEXT DEFAULT NULL AFTER imagen");
        echo "<p style='color:green'>✅ Columna 'imagenes' agregada a la tabla 'noticias'.</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "<p style='color:orange'>ℹ️ La columna 'imagenes' ya existía en 'noticias'.</p>";
        } else {
            echo "<p style='color:red'>❌ Error en noticias: " . $e->getMessage() . "</p>";
        }
    }

    // 2. Agregar columna a eventos
    try {
        $pdo->exec("ALTER TABLE eventos ADD COLUMN imagenes LONGTEXT DEFAULT NULL AFTER imagen");
        echo "<p style='color:green'>✅ Columna 'imagenes' agregada a la tabla 'eventos'.</p>";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "<p style='color:orange'>ℹ️ La columna 'imagenes' ya existía en 'eventos'.</p>";
        } else {
            echo "<p style='color:red'>❌ Error en eventos: " . $e->getMessage() . "</p>";
        }
    }

    echo "<hr><p><b>Ya puedes cerrar esta página y volver al administrador.</b></p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>Error de conexión: " . $e->getMessage() . "</p>";
}
