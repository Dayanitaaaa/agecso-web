<?php
// Configuración de la base de datos para AGECSO-web
$host = "127.0.0.1";
$port = 3306;
$socket = "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock";
$db_name = "agecso_web";
$username = "root";
$password = ""; // Por defecto en XAMPP está vacío

try {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4", $username, $password);
    } catch (PDOException $e) {
        $pdo = new PDO("mysql:unix_socket=$socket;dbname=$db_name;charset=utf8mb4", $username, $password);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('[DB_CONNECTION_ERROR] ' . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor contacta al administrador.");
}
?>
