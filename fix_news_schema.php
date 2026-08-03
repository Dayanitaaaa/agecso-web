<?php
require_once __DIR__ . '/config/db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Intentar agregar la columna imagenes a noticias
    try {
        $pdo->exec("ALTER TABLE noticias ADD COLUMN imagenes LONGTEXT DEFAULT NULL AFTER imagen");
        echo "Columna 'imagenes' agregada a la tabla 'noticias'.\n";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "La columna 'imagenes' ya existe en 'noticias'.\n";
        } else {
            throw $e;
        }
    }

    // Intentar agregar la columna imagenes a eventos (por si acaso también lo quieren)
    try {
        $pdo->exec("ALTER TABLE eventos ADD COLUMN imagenes LONGTEXT DEFAULT NULL AFTER imagen");
        echo "Columna 'imagenes' agregada a la tabla 'eventos'.\n";
    } catch (PDOException $e) {
        if ($e->getCode() == '42S21') {
            echo "La columna 'imagenes' ya existe en 'eventos'.\n";
        } else {
            throw $e;
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
