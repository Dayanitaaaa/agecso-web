<?php
// Script para ejecutar la migration de campos de contacto
require_once __DIR__ . '/../config/db.php';

echo "Ejecutando migration: Agregar campos de contacto a empresas...\n";

try {
    // Leer el archivo SQL
    $sql = file_get_contents(__DIR__ . '/add_contact_fields.sql');

    // Separar por punto y coma y ejecutar cada statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            echo "Ejecutando: " . substr($statement, 0, 50) . "...\n";
            $pdo->exec($statement);
        }
    }

    echo "\n✅ Migration ejecutada exitosamente.\n";
    echo "Campos agregados: telefono_contacto, pagina_web\n";

} catch (PDOException $e) {
    echo "\n❌ Error ejecutando migration: " . $e->getMessage() . "\n";
    exit(1);
}
?>
