<?php
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

if (!$loaded) die("Error: db.php no encontrado.");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Agregando control de orden a AGECSO</h3>";

    // Agregar columna orden a noticias
    try {
        $pdo->exec("ALTER TABLE noticias ADD COLUMN orden INT DEFAULT 999 AFTER estado");
        echo "<p style='color:green'>✅ Columna 'orden' agregada a 'noticias'.</p>";
    } catch (PDOException $e) { echo "<p>ℹ️ Noticia: " . $e->getMessage() . "</p>"; }

    // Agregar columna orden a eventos
    try {
        $pdo->exec("ALTER TABLE eventos ADD COLUMN orden INT DEFAULT 999 AFTER estado");
        echo "<p style='color:green'>✅ Columna 'orden' agregada a 'eventos'.</p>";
    } catch (PDOException $e) { echo "<p>ℹ️ Evento: " . $e->getMessage() . "</p>"; }

    echo "<hr><p><b>Ya puedes cerrar esta página.</b></p>";
} catch (PDOException $e) { echo "Error: " . $e->getMessage(); }
