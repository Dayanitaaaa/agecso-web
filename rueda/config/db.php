<?php
// Configurar Zona Horaria para Colombia
date_default_timezone_set('America/Bogota');

// Configuración de la base de datos
$host = "localhost";
$port = 3306;
$db_name = "u152451479_agecso_rueda";
$username = "u152451479_agecso_user";
$password = "Lopez1007645229*"; // Reemplaza con la contraseña que creaste en Hostinger

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    // Configurar el modo de error de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Configurar el modo de obtención por defecto a array asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Producción: usar hora real del sistema
    define('SYSTEM_TIME', date('Y-m-d H:i:s'));

} catch (PDOException $e) {
    // No exponer detalles del error al usuario
    error_log('[DB_CONNECTION_ERROR] ' . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor contacta al administrador.");
}
?>
