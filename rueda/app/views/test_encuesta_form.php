<?php
/**
 * VISTA TEMPORAL PARA SIMULAR FORMULARIO DE ENCUESTA
 * Sin seguridad - solo para pruebas
 * Acceso: index.php?controlador=test&accion=encuesta&reunion_id=X&usuario_id=Y
 */

// Simular reunion_id y usuario_id desde GET o usar valores por defecto
$reunion_id = isset($_GET['reunion_id']) ? (int)$_GET['reunion_id'] : 1;
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 3;

// Obtener información de la reunión (si existe conexión a DB)
$reunion_info = null;
$error_db = null;

try {
    require_once __DIR__ . '/../../config/db.php';
    if (isset($pdo)) {
        $stmt = $pdo->prepare("
            SELECT r.*, 
                   e1.razon_social as nombre_comprador,
                   e2.razon_social as nombre_vendedor,
                   rn.tituloRueda,
                   u.id as usuario_comprador_id,
                   u2.id as usuario_vendedor_id
            FROM reuniones r
            JOIN empresas e1 ON r.compradorId = e1.id
            JOIN empresas e2 ON r.vendedorId = e2.id
            JOIN usuarios u ON e1.usuarioId = u.id
            JOIN usuarios u2 ON e2.usuarioId = u2.id
            JOIN ruedas_negocios rn ON r.ruedaId = rn.id
            WHERE r.id = ?
        ");
        $stmt->execute([$reunion_id]);
        $reunion_info = $stmt->fetch();
    }
} catch (Exception $e) {
    $error_db = $e->getMessage();
}

// Determinar rol del usuario
$rol_usuario = 'desconocido';
$nombre_contraparte = 'Empresa contraparte';
if ($reunion_info) {
    if ($usuario_id == $reunion_info['usuario_comprador_id']) {
        $rol_usuario = 'comprador';
        $nombre_contraparte = $reunion_info['nombre_vendedor'];
    } elseif ($usuario_id == $reunion_info['usuario_vendedor_id']) {
        $rol_usuario = 'vendedor';
        $nombre_contraparte = $reunion_info['nombre_comprador'];
    }
}

// Procesar envío de formulario
$mensaje = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($pdo)) {
            $stmt = $pdo->prepare("
                INSERT INTO encuestas_satisfaccion 
                (reunionId, usuarioId, calificacionGeneral, comentarios, expectativaNegocio, valorNegocioProyectado, efectividadCita, asistenciaConfirmada) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['reunion_id'],
                $_POST['usuario_id'],
                $_POST['calificacion'],
                $_POST['comentario'],
                $_POST['expectativa_cumplida'],
                $_POST['valor_negocio'] ?? 0,
                isset($_POST['efectividad_cita']) ? 1 : 0,
                isset($_POST['asistencia_confirmada']) ? 1 : 0
            ]);
            $mensaje = ['tipo' => 'exito', 'texto' => '¡Encuesta guardada exitosamente!'];
        }
    } catch (Exception $e) {
        $mensaje = ['tipo' => 'error', 'texto' => 'Error: ' . $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST - Encuesta de Satisfacción</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .star-rating input { display: none; }
        .star-rating label { cursor: pointer; font-size: 2rem; color: #d1d5db; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #fbbf24; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        
        <!-- Header de prueba -->
        <div class="bg-yellow-400 rounded-t-xl p-4 text-center">
            <h1 class="text-xl font-bold text-yellow-900">
                <i class="fas fa-flask mr-2"></i>
                MODO PRUEBA - Formulario de Encuesta
            </h1>
            <p class="text-sm text-yellow-800 mt-1">
                Esta vista es temporal y sin seguridad - Solo para validar funcionamiento
            </p>
        </div>
        
        <div class="bg-white rounded-b-xl shadow-lg p-6 mb-6">
            
            <?php if ($mensaje): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $mensaje['tipo'] == 'exito' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <i class="fas <?php echo $mensaje['tipo'] == 'exito' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                <?php echo htmlspecialchars($mensaje['texto']); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error_db): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Error de conexión: <?php echo htmlspecialchars($error_db); ?>
            </div>
            <?php endif; ?>
            
            <!-- Info de la reunión -->
            <?php if ($reunion_info): ?>
            <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                <h3 class="font-bold text-blue-900 mb-2">
                    <i class="fas fa-handshake mr-2"></i>Información de la Reunión
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Rueda:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($reunion_info['tituloRueda']); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Fecha:</span>
                        <span class="font-medium"><?php echo date('d/m/Y H:i', strtotime($reunion_info['fechaHora'])); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Tu rol:</span>
                        <span class="font-medium capitalize text-blue-600"><?php echo $rol_usuario; ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Contraparte:</span>
                        <span class="font-medium"><?php echo htmlspecialchars($nombre_contraparte); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form method="POST" action="">
                <input type="hidden" name="reunion_id" value="<?php echo $reunion_id; ?>">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                
                <!-- Pregunta 1: Calificación -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        1. ¿Cómo calificas la reunión?
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="star-rating flex flex-row-reverse justify-center gap-2">
                        <input type="radio" id="star5" name="calificacion" value="5" required>
                        <label for="star5"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="calificacion" value="4">
                        <label for="star4"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="calificacion" value="3">
                        <label for="star3"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="calificacion" value="2">
                        <label for="star2"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="calificacion" value="1">
                        <label for="star1"><i class="fas fa-star"></i></label>
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-2">Selecciona de 1 a 5 estrellas</p>
                </div>
                
                <!-- Pregunta 2: Expectativa -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        2. ¿Se cumplieron tus expectativas?
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="expectativa_cumplida" value="inmediato" class="hidden peer" checked>
                            <div class="p-3 rounded-lg border-2 border-gray-200 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <i class="fas fa-check-circle text-green-500 mb-1 block"></i>
                                <span class="text-sm">Sí, inmediatamente</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="expectativa_cumplida" value="mediano_plazo" class="hidden peer">
                            <div class="p-3 rounded-lg border-2 border-gray-200 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <i class="fas fa-clock text-yellow-500 mb-1 block"></i>
                                <span class="text-sm">A mediano plazo</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="expectativa_cumplida" value="no_cumplida" class="hidden peer">
                            <div class="p-3 rounded-lg border-2 border-gray-200 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                <i class="fas fa-times-circle text-red-500 mb-1 block"></i>
                                <span class="text-sm">No se cumplieron</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Pregunta 3: Valor del negocio -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        3. ¿Cuál es el valor estimado del negocio proyectado?
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">$</span>
                        <input type="number" name="valor_negocio" min="0" step="0.01"
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Deja en 0 si no aplica</p>
                </div>
                
                <!-- Pregunta 4: Efectividad -->
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="efectividad_cita" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 relative"></div>
                        <span class="ml-3 text-gray-700">
                            <i class="fas fa-handshake text-blue-500 mr-1"></i>
                            ¿Consideras que fue una cita efectiva?
                        </span>
                    </label>
                </div>
                
                <!-- Pregunta 5: Asistencia -->
                <div class="mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="asistencia_confirmada" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600 relative"></div>
                        <span class="ml-3 text-gray-700">
                            <i class="fas fa-user-check text-green-500 mr-1"></i>
                            ¿Asistieron ambas partes a la reunión?
                        </span>
                    </label>
                </div>
                
                <!-- Pregunta 6: Comentarios -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-3">
                        6. Comentarios adicionales
                    </label>
                    <textarea name="comentario" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Escribe tus comentarios aquí..."></textarea>
                </div>
                
                <!-- Botones -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Guardar Encuesta
                    </button>
                    <a href="index.php" 
                       class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </form>
            
            <!-- Debug info -->
            <div class="mt-8 p-4 bg-gray-100 rounded-lg text-xs font-mono">
                <p class="font-bold text-gray-700 mb-2">DEBUG INFO:</p>
                <p>Reunion ID: <?php echo $reunion_id; ?></p>
                <p>Usuario ID: <?php echo $usuario_id; ?></p>
                <p>Rol detectado: <?php echo $rol_usuario; ?></p>
                <?php if ($reunion_info): ?>
                <p>Reunión encontrada: SÍ</p>
                <p>Estado: <?php echo $reunion_info['estadoCita']; ?></p>
                <?php else: ?>
                <p>Reunión encontrada: NO</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Instrucciones -->
        <div class="bg-gray-800 text-white rounded-xl p-4 text-sm">
            <h4 class="font-bold mb-2"><i class="fas fa-info-circle mr-2"></i>Parámetros URL:</h4>
            <code class="block bg-gray-900 p-2 rounded text-green-400">
                index.php?controlador=test&accion=encuesta&reunion_id=1&usuario_id=3
            </code>
            <p class="mt-2 text-gray-400">
                Cambia <code>reunion_id</code> y <code>usuario_id</code> según tus necesidades.
            </p>
        </div>
    </div>
</body>
</html>
