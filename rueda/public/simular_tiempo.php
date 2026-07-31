<?php
/**
 * Script para simular el paso del tiempo en reuniones
 * Usa la variable $simulated_datetime del archivo config/db.php
 *
 * Uso: http://localhost/Proyecto%20AGECSO/public/simular_tiempo.php
 */

require_once '../config/db.php';

// Función para obtener la fecha actual o simulada
function getCurrentDateTime() {
    global $simulated_datetime;
    if ($simulated_datetime) {
        return new DateTime($simulated_datetime);
    }
    return new DateTime();
}

// Verificar si hay una fecha simulada configurada
$fecha_actual = getCurrentDateTime();
$fecha_real = new DateTime();
$fecha_simulada = $simulated_datetime ? new DateTime($simulated_datetime) : null;

// Si no hay fecha simulada, mostrar mensaje
if (!$simulated_datetime) {
    echo "<h1>No hay fecha simulada configurada</h1>";
    echo "<p>Para simular el paso del tiempo, edita el archivo <code>config/db.php</code> y establece:</p>";
    echo "<pre>\$simulated_datetime = '2026-05-01 10:00:00'; // Ejemplo</pre>";
    echo "<p><a href='../config/db.php'>Editar config/db.php</a></p>";
    exit;
}

// Calcular diferencia de tiempo
$diferencia = $fecha_real->diff($fecha_simulada);
$dias_diferencia = $diferencia->invert ? -$diferencia->days : $diferencia->days;
$horas_diferencia = $diferencia->h + ($dias_diferencia * 24);

echo "<h1>Simulación de Paso del Tiempo</h1>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;} h1{color:#333;} pre{background:#f4f4f4;padding:15px;border-radius:5px;overflow-x:auto;} table{width:100%;border-collapse:collapse;margin:20px 0;} th,td{padding:10px;text-align:left;border-bottom:1px solid #ddd;} th{background:#f4f4f4;} .success{color:green;} .error{color:red;} button{padding:10px 20px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;}</style>";

echo "<div style='background:#e7f3ff;padding:15px;border-radius:5px;margin-bottom:20px;'>";
echo "<h2>Configuración Actual</h2>";
echo "<p><strong>Fecha Real del Sistema:</strong> " . $fecha_real->format('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Fecha Simulada:</strong> " . $fecha_simulada->format('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Diferencia:</strong> " . abs($dias_diferencia) . " días " . ($diferencia->invert ? "hacia el futuro" : "hacia el pasado") . "</p>";
echo "</div>";

try {
    // Mostrar reuniones actuales
    echo "<h2>Reuniones Actuales</h2>";
    $stmt = $pdo->query("
        SELECT 
            r.id,
            r.fechaHora,
            r.estadoCita,
            e1.razon_social AS comprador,
            e2.razon_social AS vendedor,
            rn.tituloRueda,
            CASE 
                WHEN r.fechaHora < '{$fecha_simulada->format('Y-m-d H:i:s')}' THEN 'YA PASÓ (según fecha simulada)'
                ELSE 'FUTURO (según fecha simulada)'
            END AS estado_tiempo_simulado
        FROM reuniones r
        JOIN empresas e1 ON r.compradorId = e1.id
        JOIN empresas e2 ON r.vendedorId = e2.id
        JOIN ruedas_negocios rn ON r.ruedaId = rn.id
        ORDER BY r.fechaHora DESC
    ");
    $reuniones = $stmt->fetchAll();

    if (empty($reuniones)) {
        echo "<p>No hay reuniones en la base de datos.</p>";
    } else {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Fecha Hora</th><th>Estado</th><th>Comprador</th><th>Vendedor</th><th>Rueda</th><th>Estado Tiempo</th></tr></thead>";
        echo "<tbody>";
        foreach ($reuniones as $r) {
            echo "<tr>";
            echo "<td>" . $r['id'] . "</td>";
            echo "<td>" . $r['fechaHora'] . "</td>";
            echo "<td>" . $r['estadoCita'] . "</td>";
            echo "<td>" . htmlspecialchars($r['comprador']) . "</td>";
            echo "<td>" . htmlspecialchars($r['vendedor']) . "</td>";
            echo "<td>" . htmlspecialchars($r['tituloRueda']) . "</td>";
            echo "<td>" . $r['estado_tiempo_simulado'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

    // Botón para aplicar cambios
    echo "<div style='margin:30px 0;'>";
    echo "<form method='POST'>";
    echo "<input type='hidden' name='accion' value='aplicar'>";
    echo "<button type='submit'>Aplicar Fecha Simulada a Reuniones</button>";
    echo "</form>";
    echo "<p style='font-size:12px;color:#666;'>Esto actualizará las fechas de las reuniones para que coincidan con la fecha simulada configurada.</p>";
    echo "</div>";

    // Procesar formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'aplicar') {
        // Calcular offset a aplicar
        $offset_segundos = $fecha_simulada->getTimestamp() - $fecha_real->getTimestamp();
        
        // Actualizar reuniones
        $stmt = $pdo->prepare("
            UPDATE reuniones 
            SET fechaHora = DATE_ADD(fechaHora, INTERVAL ? SECOND),
                updatedAt = NOW()
            WHERE fechaHora > NOW()
        ");
        $filas_afectadas = $stmt->execute([$offset_segundos]);
        
        // Cambiar reuniones pasadas a 'realizada'
        $stmt2 = $pdo->prepare("
            UPDATE reuniones 
            SET estadoCita = 'realizada',
                updatedAt = NOW()
            WHERE fechaHora < '{$fecha_simulada->format('Y-m-d H:i:s')}' 
            AND estadoCita IN ('aceptada', 'negociando', 'pendiente')
        ");
        $filas_realizada = $stmt2->execute();
        
        echo "<div class='success' style='background:#d4edda;padding:15px;border-radius:5px;margin:20px 0;'>";
        echo "<h3>✓ Cambios Aplicados Exitosamente</h3>";
        echo "<p><strong>Reuniones actualizadas:</strong> " . $filas_afectadas . "</p>";
        echo "<p><strong>Reuniones cambiadas a 'realizada':</strong> " . $filas_realizada . "</p>";
        echo "<p><a href='simular_tiempo.php'>Recargar página</a></p>";
        echo "</div>";
    }

    // Mostrar reuniones listas para encuesta
    echo "<h2>Reuniones Listas para Encuesta (Estado: Realizada)</h2>";
    $stmt = $pdo->query("
        SELECT 
            r.id AS reunion_id,
            r.fechaHora,
            e1.razon_social AS comprador,
            u1.email AS email_comprador,
            u1.id AS usuario_id_comprador,
            e2.razon_social AS vendedor,
            u2.email AS email_vendedor,
            u2.id AS usuario_id_vendedor,
            rn.tituloRueda,
            CASE 
                WHEN es1.id IS NULL THEN 'SIN ENCUESTA COMPRADOR'
                ELSE 'CON ENCUESTA COMPRADOR'
            END AS estado_encuesta_comprador,
            CASE 
                WHEN es2.id IS NULL THEN 'SIN ENCUESTA VENDEDOR'
                ELSE 'CON ENCUESTA VENDEDOR'
            END AS estado_encuesta_vendedor
        FROM reuniones r
        JOIN empresas e1 ON r.compradorId = e1.id
        JOIN usuarios u1 ON e1.usuarioId = u1.id
        JOIN empresas e2 ON r.vendedorId = e2.id
        JOIN usuarios u2 ON e2.usuarioId = u2.id
        JOIN ruedas_negocios rn ON r.ruedaId = rn.id
        LEFT JOIN encuestas_satisfaccion es1 ON r.id = es1.reunionId AND es1.usuarioId = u1.id
        LEFT JOIN encuestas_satisfaccion es2 ON r.id = es2.reunionId AND es2.usuarioId = u2.id
        WHERE r.estadoCita = 'realizada'
        ORDER BY r.fechaHora DESC
    ");
    $reuniones_encuesta = $stmt->fetchAll();

    if (empty($reuniones_encuesta)) {
        echo "<p>No hay reuniones en estado 'realizada'.</p>";
    } else {
        echo "<table>";
        echo "<thead><tr><th>ID</th><th>Fecha</th><th>Comprador</th><th>Email</th><th>Vendedor</th><th>Email</th><th>Rueda</th><th>Enc. Comprador</th><th>Enc. Vendedor</th></tr></thead>";
        echo "<tbody>";
        foreach ($reuniones_encuesta as $r) {
            echo "<tr>";
            echo "<td>" . $r['reunion_id'] . "</td>";
            echo "<td>" . $r['fechaHora'] . "</td>";
            echo "<td>" . htmlspecialchars($r['comprador']) . "</td>";
            echo "<td>" . htmlspecialchars($r['email_comprador']) . "</td>";
            echo "<td>" . htmlspecialchars($r['vendedor']) . "</td>";
            echo "<td>" . htmlspecialchars($r['email_vendedor']) . "</td>";
            echo "<td>" . htmlspecialchars($r['tituloRueda']) . "</td>";
            echo "<td style='color:" . ($r['estado_encuesta_comprador'] == 'SIN ENCUESTA COMPRADOR' ? 'red' : 'green') . "'>" . $r['estado_encuesta_comprador'] . "</td>";
            echo "<td style='color:" . ($r['estado_encuesta_vendedor'] == 'SIN ENCUESTA VENDEDOR' ? 'red' : 'green') . "'>" . $r['estado_encuesta_vendedor'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

} catch (Exception $e) {
    echo "<div class='error' style='background:#f8d7da;padding:15px;border-radius:5px;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "<div style='margin-top:40px;padding:15px;background:#f8f9fa;border-radius:5px;'>";
echo "<h3>¿Cómo usar?</h3>";
echo "<ol>";
echo "<li>Edita <code>config/db.php</code></li>";
echo "<li>Cambia <code>\$simulated_datetime = null;</code> por la fecha deseada, por ejemplo: <code>\$simulated_datetime = '2026-05-01 10:00:00';</code></li>";
echo "<li>Recarga esta página</li>";
echo "<li>Haz clic en 'Aplicar Fecha Simulada a Reuniones'</li>";
echo "<li>Las reuniones futuras se moverán al pasado según la fecha configurada</li>";
echo "<li>Las reuniones pasadas cambiarán a estado 'realizada' automáticamente</li>";
echo "<li>Podrás ver las reuniones listas para encuesta en la tabla inferior</li>";
echo "</ol>";
echo "<p><strong>Para desactivar la simulación:</strong> Vuelve a poner <code>\$simulated_datetime = null;</code> en config/db.php</p>";
echo "</div>";
?>
