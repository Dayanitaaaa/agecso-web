<?php
require_once '/Applications/XAMPP/xamppfiles/htdocs/AGECSO-web/rueda/config/db.php';

try {
    echo "--- ROLES ---\n";
    $stmt = $pdo->query("SELECT * FROM roles");
    $roles = $stmt->fetchAll();
    print_r($roles);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
