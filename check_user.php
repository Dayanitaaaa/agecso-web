<?php
require_once '/Applications/XAMPP/xamppfiles/htdocs/AGECSO-web/rueda/config/db.php';

try {
    echo "--- USUARIO ---\n";
    $stmt = $pdo->prepare("SELECT id, nombreUsuario, roleId FROM usuarios WHERE nombreUsuario LIKE '%Dayana%'");
    $stmt->execute();
    $user = $stmt->fetch();
    print_r($user);

    if ($user) {
        echo "\n--- EMPRESA ---\n";
        $stmt = $pdo->prepare("SELECT id, razon_social, sectorId FROM empresas WHERE usuarioId = ?");
        $stmt->execute([$user['id']]);
        $empresa = $stmt->fetch();
        print_r($empresa);

        if ($empresa) {
            echo "\n--- INSCRIPCIONES ---\n";
            $stmt = $pdo->prepare("SELECT ir.*, rn.tituloRueda, rn.estadoRueda FROM inscripciones_ruedas ir JOIN ruedas_negocios rn ON ir.ruedaId = rn.id WHERE ir.empresaId = ?");
            $stmt->execute([$empresa['id']]);
            $inscripciones = $stmt->fetchAll();
            print_r($inscripciones);
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
