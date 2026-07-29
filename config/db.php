<?php
// Configuración de la base de datos para AGECSO-web
$host = "localhost"; // En Hostinger casi siempre es localhost
$port = 3306;
$db_name = "u152451479_agecso_web";
$username = "u152451479_admin";
$password = "Lopez1007645229*"; // Pon aquí la clave que creaste en Hostinger

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('[DB_CONNECTION_ERROR] ' . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor contacta al administrador.");
}
?>
