<?php
/**
 * Controlador temporal para pruebas
 * Sin seguridad - Solo para validar formularios
 */

require_once __DIR__ . '/../../includes/Logger.php';

class TestController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Acción para mostrar formulario de encuesta de prueba
     * URL: index.php?controlador=test&accion=encuesta&reunion_id=X&usuario_id=Y
     */
    public function encuesta() {
        // Pasar $pdo a la vista
        $pdo = $this->pdo;
        require_once '../app/views/test_encuesta_form.php';
    }
    
    /**
     * Acción para listar reuniones disponibles para encuesta
     * URL: index.php?contwrolador=test&accion=reuniones_disponibles
     */
    public function reunionesDisponibles() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, 
                       e1.razon_social as nombre_comprador,
                       e2.razon_social as nombre_vendedor,
                       rn.tituloRueda,
                       u1.id as usuario_comprador_id,
                       u2.id as usuario_vendedor_id
                FROM reuniones r
                JOIN empresas e1 ON r.compradorId = e1.id
                JOIN empresas e2 ON r.vendedorId = e2.id
                JOIN usuarios u1 ON e1.usuarioId = u1.id
                JOIN usuarios u2 ON e2.usuarioId = u2.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.estadoCita = 'realizada'
                ORDER BY r.fechaHora DESC
            ");
            $stmt->execute();
            $reuniones = $stmt->fetchAll();
            
            // Mostrar en formato simple
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html><head><title>Reuniones Disponibles</title>';
            echo '<script src="https://cdn.tailwindcss.com"></script>';
            echo '</head><body class="bg-gray-100 p-8">';
            echo '<div class="max-w-4xl mx-auto">';
            echo '<h1 class="text-2xl font-bold mb-6">Reuniones Disponibles para Encuesta</h1>';
            
            if (empty($reuniones)) {
                echo '<div class="bg-yellow-100 p-4 rounded">No hay reuniones en estado "realizada"</div>';
            } else {
                echo '<div class="space-y-4">';
                foreach ($reuniones as $r) {
                    echo '<div class="bg-white p-4 rounded-lg shadow">';
                    echo '<div class="flex justify-between items-start">';
                    echo '<div>';
                    echo '<h3 class="font-bold">' . htmlspecialchars($r['tituloRueda']) . '</h3>';
                    echo '<p class="text-sm text-gray-600">Fecha: ' . date('d/m/Y H:i', strtotime($r['fechaHora'])) . '</p>';
                    echo '<p class="text-sm">Comprador: ' . htmlspecialchars($r['nombre_comprador']) . ' (Usuario ID: ' . $r['usuario_comprador_id'] . ')</p>';
                    echo '<p class="text-sm">Vendedor: ' . htmlspecialchars($r['nombre_vendedor']) . ' (Usuario ID: ' . $r['usuario_vendedor_id'] . ')</p>';
                    echo '</div>';
                    echo '<div class="space-y-2">';
                    echo '<a href="index.php?controlador=test&accion=encuesta&reunion_id=' . $r['id'] . '&usuario_id=' . $r['usuario_comprador_id'] . '" ';
                    echo 'class="block px-3 py-1 bg-blue-500 text-white rounded text-sm text-center hover:bg-blue-600">Encuesta como Comprador</a>';
                    echo '<a href="index.php?controlador=test&accion=encuesta&reunion_id=' . $r['id'] . '&usuario_id=' . $r['usuario_vendedor_id'] . '" ';
                    echo 'class="block px-3 py-1 bg-green-500 text-white rounded text-sm text-center hover:bg-green-600">Encuesta como Vendedor</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
            
            echo '<div class="mt-6"><a href="index.php" class="text-blue-600 hover:underline">← Volver al inicio</a></div>';
            echo '</div></body></html>';
            
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    /**
     * Acción para verificar inscripciones
     * URL: index.php?controlador=test&accion=verificar_inscripciones
     */
    public function verificarInscripciones() {
        try {
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html><head><title>Verificar Inscripciones</title>';
            echo '<script src="https://cdn.tailwindcss.com"></script>';
            echo '</head><body class="bg-gray-100 p-8">';
            echo '<div class="max-w-4xl mx-auto">';
            echo '<h1 class="text-2xl font-bold mb-6">Verificación de Inscripciones</h1>';
            
            // Verificar tablas existentes
            echo '<h2 class="font-bold mb-2">1. Tablas relacionadas con inscripciones:</h2>';
            $tables = ['inscripciones_ruedas', 'participantes_rueda'];
            foreach ($tables as $table) {
                $stmt = $this->pdo->query("SHOW TABLES LIKE '$table'");
                $exists = $stmt->fetch();
                echo '<p class="ml-4">' . $table . ': ' . ($exists ? '<span class="text-green-600">✓ Existe</span>' : '<span class="text-red-600">✗ No existe</span>') . '</p>';
            }
            
            // Si existe inscripciones_ruedas, mostrar datos
            try {
                $stmt = $this->pdo->query("
                    SELECT ir.*, e.razon_social, rn.tituloRueda 
                    FROM inscripciones_ruedas ir
                    JOIN empresas e ON ir.empresaId = e.id
                    JOIN ruedas_negocios rn ON ir.ruedaId = rn.id
                    LIMIT 10
                ");
                $inscripciones = $stmt->fetchAll();
                
                echo '<h2 class="font-bold mt-6 mb-2">2. Inscripciones existentes:</h2>';
                if (empty($inscripciones)) {
                    echo '<p class="ml-4 text-yellow-600">No hay inscripciones registradas</p>';
                } else {
                    echo '<table class="w-full bg-white rounded shadow">';
                    echo '<tr class="bg-gray-200"><th class="p-2 text-left">Empresa</th><th class="p-2 text-left">Rueda</th><th class="p-2 text-left">Estado</th></tr>';
                    foreach ($inscripciones as $i) {
                        echo '<tr class="border-t"><td class="p-2">' . htmlspecialchars($i['razon_social']) . '</td>';
                        echo '<td class="p-2">' . htmlspecialchars($i['tituloRueda']) . '</td>';
                        echo '<td class="p-2"><span class="px-2 py-1 rounded text-sm ' . ($i['estadoInscripcion'] == 'aceptada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') . '">' . $i['estadoInscripcion'] . '</span></td></tr>';
                    }
                    echo '</table>';
                }
            } catch (Exception $e) {
                echo '<p class="ml-4 text-red-600">Error al consultar inscripciones: ' . $e->getMessage() . '</p>';
            }
            
            // Si existe participantes_rueda, mostrar datos
            try {
                $stmt = $this->pdo->query("
                    SELECT pr.*, e.razon_social, rn.tituloRueda 
                    FROM participantes_rueda pr
                    JOIN empresas e ON pr.empresaId = e.id
                    JOIN ruedas_negocios rn ON pr.ruedaId = rn.id
                    LIMIT 10
                ");
                $participantes = $stmt->fetchAll();
                
                echo '<h2 class="font-bold mt-6 mb-2">3. Participantes en participantes_rueda:</h2>';
                if (empty($participantes)) {
                    echo '<p class="ml-4 text-yellow-600">No hay participantes registrados</p>';
                } else {
                    echo '<table class="w-full bg-white rounded shadow">';
                    echo '<tr class="bg-gray-200"><th class="p-2 text-left">Empresa</th><th class="p-2 text-left">Rueda</th><th class="p-2 text-left">Rol</th></tr>';
                    foreach ($participantes as $p) {
                        echo '<tr class="border-t"><td class="p-2">' . htmlspecialchars($p['razon_social']) . '</td>';
                        echo '<td class="p-2">' . htmlspecialchars($p['tituloRueda']) . '</td>';
                        echo '<td class="p-2">' . $p['tipoParticipante'] . '</td></tr>';
                    }
                    echo '</table>';
                }
            } catch (Exception $e) {
                echo '<p class="ml-4 text-red-600">Error al consultar participantes: ' . $e->getMessage() . '</p>';
            }
            
            echo '<div class="mt-6"><a href="index.php" class="text-blue-600 hover:underline">← Volver al inicio</a></div>';
            echo '</div></body></html>';
            
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
