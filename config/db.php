<?php
$isLocal = (!isset($_SERVER["HTTP_HOST"]) || in_array($_SERVER["HTTP_HOST"], ["localhost", "127.0.0.1", "::1"]) || strpos($_SERVER["HTTP_HOST"] ?? "", "192.168.") !== false);
if ($isLocal) {
    $host = "127.0.0.1";
    $port = 3306;
    $db_name = "agecso_web";
    $username = "root";
    $password = "";
} else {
    $host = "localhost";
    $port = 3306;
    $db_name = "u152451479_agecso_web";
    $username = "u152451479_admin";
    $password = "Lopez1007645229*";
}
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("[DB_CONNECTION_ERROR] " . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor contacta al administrador.");
}
