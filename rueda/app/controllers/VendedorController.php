<?php
require_once '../includes/Logger.php';
class VendedorController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado y es proveedor (slugRole = 'proveedor' o 'vendedor')
        $userRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
        if (!isset($_SESSION['usuario_id']) || !in_array($userRole, ['proveedor', 'vendedor'])) {
            Logger::logRoleError($userRole ?: 'guest', 'Acceso no autorizado al VendedorController', [
                'accion' => $_GET['accion'] ?? 'desconocida'
            ]);
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
    }

    public function dashboard() {
        try {
            // Asegurar que existan columnas de membresía en la tabla empresas
            try {
                $stmt_col = $this->pdo->query("SHOW COLUMNS FROM empresas LIKE 'membresia_plan'");
                if ($stmt_col && !$stmt_col->fetch()) {
                    $this->pdo->exec("
                        ALTER TABLE empresas 
                        ADD COLUMN membresia_plan VARCHAR(50) DEFAULT 'ninguno',
                        ADD COLUMN membresia_estado VARCHAR(50) DEFAULT 'inactivo',
                        ADD COLUMN membresia_vencimiento DATETIME NULL
                    ");
                }
            } catch (Exception $e) {}

            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            // Si el estado de membresía no está definido o viene de activación reciente
            if (isset($_GET['msg']) && $_GET['msg'] === 'membresia_activada') {
                $empresa['membresia_estado'] = 'activo';
            }

            // --- NUEVA LÓGICA DE LIMPIEZA AUTOMÁTICA ---
            // Cancelar citas pendientes/negociando que ya pasaron
            $this->pdo->prepare("
                UPDATE reuniones 
                SET estadoCita = 'cancelada', ultimaAccionPor = NULL
                WHERE vendedorId = ? 
                AND estadoCita IN ('pendiente', 'negociando') 
                AND fechaHora < ?
            ")->execute([$empresa['id'], SYSTEM_TIME]);
            // --------------------------------------------

            $stmt_oferta = $this->pdo->prepare("SELECT * FROM ofertas WHERE empresaId = ?");
            $stmt_oferta->execute([$empresa['id']]);
            $ofertas = $stmt_oferta->fetchAll();

            // SEGURIDAD: Solo mostrar requerimientos de ruedas donde el vendedor está inscrito y ACEPTADO
            $stmt_reqs = $this->pdo->prepare("
                SELECT d.*, e.razon_social, rn.nombreRueda as rueda_titulo 
                FROM demandas d 
                JOIN empresas e ON d.empresaId = e.id 
                JOIN ruedas_negocios rn ON d.ruedaId = rn.id
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                AND rn.estadoRueda IN ('inscripciones', 'activa')
            ");
            $stmt_reqs->execute([$empresa['id']]);
            $oportunidades = $stmt_reqs->fetchAll();

            // Obtener mis citas (como vendedor)
            $stmt_mis_citas = $this->pdo->prepare("
                SELECT r.*, e.razon_social as nombre_comprador, rn.nombreRueda as rueda_titulo
                FROM reuniones r
                JOIN empresas e ON r.compradorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.vendedorId = ?
                ORDER BY r.fechaHora DESC
            ");
            $stmt_mis_citas->execute([$empresa['id']]);
            $mis_citas = $stmt_mis_citas->fetchAll();

            // Verificar si hay encuestas pendientes para el vendedor (Citas realizadas o pasadas sin calificar)
            $stmt_encuestas_pendientes = $this->pdo->prepare("
                SELECT r.id, e.razon_social as nombre_comprador, r.fechaHora, rn.nombreRueda as tituloRueda
                FROM reuniones r
                JOIN empresas e ON r.compradorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                LEFT JOIN encuestas_satisfaccion s ON r.id = s.reunionId AND s.usuarioId = ?
                WHERE r.vendedorId = ? 
                AND (r.estadoCita = 'realizada' OR (r.estadoCita IN ('aceptada', 'agendada') AND DATE(r.fechaHora) < DATE(?)))
                AND s.id IS NULL
                ORDER BY r.fechaHora DESC
            ");
            $stmt_encuestas_pendientes->execute([$_SESSION['usuario_id'], $empresa['id'], SYSTEM_TIME]);
            $encuestas_pendientes = $stmt_encuestas_pendientes->fetchAll();

            // Obtener seguimientos de trazabilidad pendientes (3 y 6 meses)
            $trazabilidad_pendientes = [];
            try {
                require_once '../app/models/TrazabilidadModel.php';
                $trazabilidadModel = new TrazabilidadModel($this->pdo);
                $trazabilidad_pendientes = $trazabilidadModel->getSeguimientosPendientes($_SESSION['usuario_id'], SYSTEM_TIME);
            } catch (Exception $e) {
                Logger::logRoleError('vendedor', 'Error silencioso en trazabilidad dashboard', ['error' => $e->getMessage()]);
            }

            $stmt_ruedas = $this->pdo->query("SELECT *, nombreRueda as tituloRueda, descripcion as descripcionRueda FROM ruedas_negocios WHERE estadoRueda IN ('inscripciones', 'activa')");
            $ruedas = $stmt_ruedas->fetchAll();

            // Obtener mis inscripciones a ruedas (Aceptadas, Pendientes, etc.)
            $stmt_mis_ruedas = $this->pdo->prepare("
                SELECT rn.*, ir.estadoInscripcion, rn.nombreRueda as tituloRueda, rn.descripcion as descripcionRueda
                FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ?
                ORDER BY CASE WHEN rn.estadoRueda = 'activa' THEN 0 WHEN rn.estadoRueda = 'inscripciones' THEN 1 ELSE 2 END, rn.fechaInicio DESC
            ");
            $stmt_mis_ruedas->execute([$empresa['id']]);
            $mis_ruedas = $stmt_mis_ruedas->fetchAll();

            // Dividir ruedas en Activas y Pasadas
            $ruedas_activas = [];
            $ruedas_pasadas = [];
            foreach ($mis_ruedas as $mr) {
                if (in_array($mr['estadoRueda'], ['activa', 'inscripciones', 'planeacion'])) {
                    $ruedas_activas[] = $mr;
                } else {
                    $ruedas_pasadas[] = $mr;
                }
            }

            // Obtener mis ofertas agrupadas por ruedaId
            $stmt_mis_ofertas = $this->pdo->prepare("SELECT * FROM ofertas WHERE empresaId = ?");
            $stmt_mis_ofertas->execute([$empresa['id']]);
            $todas_ofertas = $stmt_mis_ofertas->fetchAll();
            $ofertas_por_rueda = [];
            foreach ($todas_ofertas as $ofe) {
                $ofertas_por_rueda[$ofe['ruedaId']][] = $ofe;
            }

            // Obtener mis citas agrupadas por ruedaId
            $stmt_mis_reuniones = $this->pdo->prepare("
                SELECT r.*, e.razon_social as nombre_comprador
                FROM reuniones r
                JOIN empresas e ON r.compradorId = e.id
                WHERE r.vendedorId = ?
            ");
            $stmt_mis_reuniones->execute([$empresa['id']]);
            $todas_reuniones = $stmt_mis_reuniones->fetchAll();
            $reuniones_por_rueda = [];
            foreach ($todas_reuniones as $reu) {
                $reuniones_por_rueda[$reu['ruedaId']][] = $reu;
            }

            // Matchmaking Inteligente
            require_once '../app/models/MatchmakingModel.php';
            $matchModel = new MatchmakingModel($this->pdo);
            $sugerencias_inteligentes = [];
            foreach ($ruedas_activas as $r) {
                if ($r['estadoRueda'] == 'activa' || $r['estadoRueda'] == 'inscripciones') {
                    if ($r['estadoInscripcion'] == 'aceptada') {
                        $sug = $matchModel->obtenerSugerenciasInteligentes($empresa['id'], $r['id']);
                        $sugerencias_inteligentes[$r['id']] = $sug;
                    }
                }
            }

            // Oportunidades de Negocio (Demandas) de las ruedas donde estoy inscrito
            $stmt_reqs = $this->pdo->prepare("
                SELECT d.*, e.razon_social, rn.tituloRueda as rueda_titulo 
                FROM demandas d 
                JOIN empresas e ON d.empresaId = e.id 
                JOIN ruedas_negocios rn ON d.ruedaId = rn.id
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                  AND rn.estadoRueda IN ('inscripciones', 'activa')
                  AND e.id != ?
            ");
            $stmt_reqs->execute([$empresa['id'], $empresa['id']]);
            $oportunidades_raw = $stmt_reqs->fetchAll();
            $oportunidades_por_rueda = [];
            foreach ($oportunidades_raw as $op) {
                $oportunidades_por_rueda[$op['ruedaId']][] = $op;
            }

            // Obtener todos los sectores CIIU para el filtro select, organizados por sección
            $stmt_all_sectores = $this->pdo->query("
                SELECT 
                    s.id, 
                    s.nombreSector, 
                    s.ciiu_clase,
                    CONCAT(s.ciiu_clase, ' - ', s.nombreSector) as display_text
                FROM sectores s 
                WHERE s.ciiu_clase IS NOT NULL
                ORDER BY s.ciiu_clase
            ");
            $todos_sectores = $stmt_all_sectores->fetchAll();

            // Resumen de KPIs para el Dashboard (Vendedor)
            $stmt_kpis = $this->pdo->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM reuniones WHERE vendedorId = ?) as total_citas,
                    (SELECT COUNT(*) FROM reuniones WHERE vendedorId = ? AND estadoCita = 'realizada') as citas_realizadas,
                    (SELECT COUNT(*) FROM reuniones WHERE vendedorId = ? AND (estadoCita = 'agendada' OR estadoCita = 're-agendado')) as citas_agendadas,
                    (SELECT COUNT(*) FROM reuniones WHERE vendedorId = ? AND (estadoCita IN ('pendiente', 'negociando')) AND ultimaAccionPor = 'comprador') as citas_por_gestionar,
                    (SELECT SUM(montoEstimado) FROM reuniones WHERE vendedorId = ? AND estadoCita = 'realizada') as volumen_negocio,
                    (SELECT COUNT(*) FROM ofertas WHERE empresaId = ?) as total_ofertas,
                    (SELECT COUNT(*) FROM inscripciones_ruedas WHERE empresaId = ? AND estadoInscripcion = 'aceptada') as ruedas_activas_count
            ");
            $stmt_kpis->execute([$empresa['id'], $empresa['id'], $empresa['id'], $empresa['id'], $empresa['id'], $empresa['id'], $empresa['id']]);
            $kpis = $stmt_kpis->fetch();

            require_once '../app/views/vendedor/vendedor_dashboard.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error cargando dashboard proveedor', [
                'accion' => 'dashboard',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function registrarResultado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $cita_id = $_POST['cita_id'];
                $monto = $_POST['monto_negocio'] ?? 0;
                $notas = $_POST['notas_resultado'] ?? '';

                $stmt = $this->pdo->prepare("UPDATE reuniones SET montoEstimado = ?, estadoCita = 'realizada' WHERE id = ?");
                $stmt->execute([$monto, $cita_id]);

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=resultado_registrado");
                exit();
            } catch (PDOException $e) {
                Logger::logCurrentRoleError('Error al registrar resultado de reunión', [
                    'accion' => 'registrarResultado',
                    'cita_id' => $_POST['cita_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = "Error al registrar el resultado: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function registrarEncuesta() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $reunion_id = $_POST['reunion_id'];
                $usuario_id = $_SESSION['usuario_id'];
                $tipo_encuesta = $_POST['tipo_encuesta'] ?? 'satisfaccion';
                $seguimiento_id = $_POST['seguimiento_id'] ?? '';
                
                // Campos de satisfacción (solo para encuestas normales)
                $calificacion = $_POST['calificacion'] ?? 0;
                $expectativa = $_POST['expectativa_cumplida'] ?? 'inmediato';
                $posibilidad = $_POST['posibilidad_negocio'] ?? 0;
                $efectividad = $_POST['efectividad_cita'] ?? 1;
                $asistencia = $_POST['asistencia_completa'] ?? 1;
                $comentario = $_POST['comentario'] ?? '';
                
                // Campos de trazabilidad (solo para seguimientos)
                $negocio_concretado = isset($_POST['negocio_concretado']) ? (int)$_POST['negocio_concretado'] : 0;
                $monto_final = $_POST['monto_final'] ?? 0;
                $fecha_cierre = !empty($_POST['fecha_cierre']) ? $_POST['fecha_cierre'] : null;

                // Insertar encuesta según tipo
                $stmt = $this->pdo->prepare("
                    INSERT INTO encuestas_satisfaccion 
                    (reunionId, usuarioId, tipo_encuesta, calificacionGeneral, comentarios, 
                     expectativaNegocio, valorNegocioProyectado, efectividadCita, asistenciaCompleta,
                     negocio_concretado, monto_final, fecha_cierre) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $reunion_id, 
                    $usuario_id, 
                    $tipo_encuesta,
                    $calificacion, 
                    $comentario, 
                    $expectativa, 
                    $posibilidad, 
                    $efectividad, 
                    $asistencia,
                    $negocio_concretado,
                    $monto_final,
                    $fecha_cierre
                ]);

                // Si es trazabilidad, marcar seguimiento como completado
                if (strpos($tipo_encuesta, 'trazabilidad') !== false && !empty($seguimiento_id)) {
                    require_once '../app/models/TrazabilidadModel.php';
                    $trazabilidadModel = new TrazabilidadModel($this->pdo);
                    $trazabilidadModel->completarSeguimiento($seguimiento_id, $this->pdo->lastInsertId());
                }

                // Si el negocio se concretó, actualizar el monto en la reunión
                if ($negocio_concretado && $monto_final > 0) {
                    $this->pdo->prepare("UPDATE reuniones SET montoEstimado = ? WHERE id = ?")
                        ->execute([$monto_final, $reunion_id]);
                }

                $msg = $tipo_encuesta === 'satisfaccion' ? 'encuesta_enviada' : 'trazabilidad_enviada';
                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=$msg");
                exit();
            } catch (PDOException $e) {
                $error_msg = "Error al guardar la encuesta: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function verReuniones() {
        try {
            // Obtener datos de la empresa del vendedor
            $stmt = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            // Obtener ruedas en las que está inscrito el vendedor
            $stmt_ruedas = $this->pdo->prepare("
                SELECT rn.id, rn.nombreRueda as tituloRueda, rn.estadoRueda, rn.fechaInicio, rn.fechaFin
                FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                ORDER BY rn.fechaInicio DESC
            ");
            $stmt_ruedas->execute([$empresa['id']]);
            $ruedas = $stmt_ruedas->fetchAll();

            // Verificar si se solicitó filtrar por una rueda específica
            $rueda_id_filtro = isset($_GET['rueda_id']) ? (int)$_GET['rueda_id'] : null;
            
            // Si no hay filtro pero hay ruedas, usar la primera (más reciente)
            if (!$rueda_id_filtro && !empty($ruedas)) {
                $rueda_id_filtro = $ruedas[0]['id'];
            }

            // Obtener rueda actual seleccionada
            $rueda_actual = null;
            if ($rueda_id_filtro) {
                foreach ($ruedas as $r) {
                    if ($r['id'] == $rueda_id_filtro) {
                        $rueda_actual = $r;
                        break;
                    }
                }
            }

            // Obtener citas del vendedor FILTRADAS POR RUEDA
            $sql_citas = "
                SELECT r.*, e.razon_social as nombre_comprador, rn.nombreRueda as tituloRueda, rn.id as ruedaId
                FROM reuniones r
                JOIN empresas e ON r.compradorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.vendedorId = ?
            ";
            $params = [$empresa['id']];
            
            if ($rueda_id_filtro) {
                $sql_citas .= " AND r.ruedaId = ?";
                $params[] = $rueda_id_filtro;
            }
            
            $sql_citas .= " ORDER BY r.fechaHora DESC";
            
            $stmt_citas = $this->pdo->prepare($sql_citas);
            $stmt_citas->execute($params);
            $todas_citas = $stmt_citas->fetchAll();

            $citas_por_aceptar = [];      // negociando (requieren acción del vendedor - contraofertas del comprador)
            $citas_pendientes_comprador = []; // pendiente (esperando que comprador responda por primera vez)
            $citas_programadas = [];        // aceptada
            $citas_historial = [];          // cancelada, realizada

            foreach ($todas_citas as $c) {
                // Lógica de turnos: ¿Quién debe actuar?
                $debeActuarVendedor = false;
                $esperandoComprador = false;
                
                try {
                    $debeActuarVendedor = ($c['estadoCita'] == 'negociando' || $c['estadoCita'] == 'pendiente') && ($c['ultimaAccionPor'] ?? '') == 'comprador';
                    $esperandoComprador = ($c['estadoCita'] == 'negociando' || $c['estadoCita'] == 'pendiente') && ($c['ultimaAccionPor'] ?? '') == 'vendedor';
                } catch (Exception $e) {
                    // Fallback si fallan las columnas de turno
                }

                if ($debeActuarVendedor) {
                    // El comprador envió una propuesta o contraoferta que el vendedor debe responder
                    $stmt_hist = $this->pdo->prepare("
                        SELECT * FROM reunion_negociaciones 
                        WHERE reunionId = ? AND propuestoPor = 'comprador' AND respuesta = 'pendiente'
                        ORDER BY numeroContrapropuesta DESC 
                        LIMIT 1
                    ");
                    $stmt_hist->execute([$c['id']]);
                    $ultima_propuesta = $stmt_hist->fetch();
                    $c['ultima_propuesta'] = $ultima_propuesta;
                    $citas_por_aceptar[] = $c;
                } elseif ($esperandoComprador) {
                    // El vendedor envió la propuesta y espera respuesta del comprador
                    $citas_pendientes_comprador[] = $c;
                } elseif (in_array($c['estadoCita'], ['aceptada', 'agendada'])) {
                    $citas_programadas[] = $c;
                } elseif (in_array($c['estadoCita'], ['cancelada', 'rechazada', 'realizada'])) {
                    $citas_historial[] = $c;
                } else {
                    $citas_historial[] = $c;
                }
            }

            // Resumen de citas por rueda (para mostrar totales)
            $resumen_por_rueda = [];
            if (!$rueda_id_filtro || empty($ruedas)) {
                // Obtener conteo de citas por rueda
                $stmt_resumen = $this->pdo->prepare("
                    SELECT r.ruedaId, rn.nombreRueda as tituloRueda,
                           COUNT(*) as total,
                           SUM(CASE WHEN r.estadoCita IN ('pendiente', 'negociando') THEN 1 ELSE 0 END) as pendientes,
                           SUM(CASE WHEN r.estadoCita = 'aceptada' THEN 1 ELSE 0 END) as aceptadas
                    FROM reuniones r
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                    WHERE r.vendedorId = ?
                    GROUP BY r.ruedaId
                    ORDER BY rn.fechaInicio DESC
                ");
                $stmt_resumen->execute([$empresa['id']]);
                $resumen_por_rueda = $stmt_resumen->fetchAll();
            }

            require_once '../app/views/vendedor/ver_reuniones.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function registrarOferta() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $empresa_id = $_POST['empresa_id'] ?? null;
                $rueda_id = $_POST['rueda_id'] ?? null;
                $sector_id = $_POST['sector_id'] ?? null;
                $titulo = isset($_POST['producto_servicio']) ? trim($_POST['producto_servicio']) : '';
                $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
                $tags_input = $_POST['tags'] ?? '';

                if (!$rueda_id) {
                    throw new Exception("Debes seleccionar una rueda de negocios para registrar esta oferta.");
                }

                if (empty($titulo)) {
                    throw new Exception("El nombre del producto o servicio es obligatorio.");
                }

                if (empty($descripcion)) {
                    throw new Exception("La descripción comercial es obligatoria.");
                }

                if (empty($sector_id)) {
                    throw new Exception("Debes seleccionar la categoría del producto.");
                }

                // SEGURIDAD: Validar inscripción en la rueda
                $stmt_ins = $this->pdo->prepare("SELECT id FROM inscripciones_ruedas WHERE empresaId = ? AND ruedaId = ? AND estadoInscripcion = 'aceptada'");
                $stmt_ins->execute([$empresa_id, $rueda_id]);
                if (!$stmt_ins->fetch()) {
                    throw new Exception("Debes estar inscrito y aceptado en la rueda de negocios para publicar ofertas.");
                }

                // Procesar tags: convertir a minúsculas y limpiar
                $tags_array = array_map(function($tag) {
                    return strtolower(trim($tag));
                }, explode(',', $tags_input));
                
                // Eliminar vacíos
                $tags_array = array_filter($tags_array);
                $tags_json = json_encode(array_values($tags_array));

                $stmt = $this->pdo->prepare("INSERT INTO ofertas (empresaId, ruedaId, sectorId, tituloOferta, descripcionOferta, tagsBusqueda, isActive) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$empresa_id, $rueda_id, $sector_id, $titulo, $descripcion, $tags_json]);

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=oferta_registrada");
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al registrar oferta', [
                    'accion' => 'registrarOferta',
                    'empresa_id' => $_POST['empresa_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function inscribirseRueda() {
        if (isset($_GET['id'])) {
            try {
                $rueda_id = $_GET['id'];
                
                // Obtener ID de la empresa del vendedor
                $stmt_emp = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_emp->execute([$_SESSION['usuario_id']]);
                $empresa = $stmt_emp->fetch();

                if (!$empresa) {
                    throw new Exception("No se encontró información de tu empresa.");
                }

                // VALIDACIÓN: Verificar si las inscripciones están abiertas
                $stmt_rueda = $this->pdo->prepare("SELECT * FROM ruedas_negocios WHERE id = ?");
                $stmt_rueda->execute([$rueda_id]);
                $rueda = $stmt_rueda->fetch();
                if ($rueda) {
                    $hoy = date('Y-m-d', strtotime(SYSTEM_TIME));
                    if (!empty($rueda['fechaInscripcionInicio']) && $hoy < $rueda['fechaInscripcionInicio']) {
                        throw new Exception("Las inscripciones para esta rueda de negocios aún no han comenzado (Inician el " . date('d/m/Y', strtotime($rueda['fechaInscripcionInicio'])) . ").");
                    }
                    if (!empty($rueda['fechaInscripcionFin']) && $hoy > $rueda['fechaInscripcionFin']) {
                        throw new Exception("Las inscripciones para esta rueda de negocios ya finalizaron el " . date('d/m/Y', strtotime($rueda['fechaInscripcionFin'])) . ".");
                    }
                }

                // VALIDACIÓN: Evitar duplicados antes de insertar
                $stmt_check = $this->pdo->prepare("SELECT id FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ?");
                $stmt_check->execute([$rueda_id, $empresa['id']]);
                if ($stmt_check->fetch()) {
                    header("Location: index.php?controlador=vendedor&accion=dashboard&msg=ya_inscrito");
                    exit();
                }

                $stmt = $this->pdo->prepare("INSERT INTO inscripciones_ruedas (ruedaId, empresaId, estadoInscripcion) VALUES (?, ?, 'pendiente')");
                $stmt->execute([$rueda_id, $empresa['id']]);

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=inscripcion_enviada");
                exit();
            } catch (Exception $e) {
                $error_msg = "Error al inscribirse: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function solicitarCita() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $rueda_id = $_POST['rueda_id'];
                $comprador_id = $_POST['comprador_id'];
                $fecha_hora = $_POST['fecha_hora'] ?? null;
                $link_reunion = isset($_POST['link_reunion']) ? trim($_POST['link_reunion']) : null;
                $numero_mesa = isset($_POST['numero_mesa']) ? trim($_POST['numero_mesa']) : null;

                if (empty($fecha_hora)) {
                    throw new Exception("Debes seleccionar la fecha y hora para la reunión.");
                }

                // Obtener automáticamente la empresa del vendedor logueado (Máxima seguridad y sin fallos)
                $stmt_v = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_v->execute([$_SESSION['usuario_id']]);
                $miEmpresa = $stmt_v->fetch();

                if (!$miEmpresa) {
                    throw new Exception("No se encontró tu empresa registrada para realizar esta solicitud.");
                }

                $vendedor_id = $miEmpresa['id'];

                // VALIDACIÓN: Ambas empresas deben estar inscritas en la rueda
                $stmt_ins_check = $this->pdo->prepare("
                    SELECT COUNT(*) as inscritos FROM inscripciones_ruedas 
                    WHERE ruedaId = ? AND estadoInscripcion = 'aceptada' 
                    AND empresaId IN (?, ?)
                ");
                $stmt_ins_check->execute([$rueda_id, $vendedor_id, $comprador_id]);
                $check_ins = $stmt_ins_check->fetch();
                if ($check_ins['inscritos'] < 2) {
                    throw new Exception("Ambas empresas deben estar inscritas y aceptadas en la rueda de negocios.");
                }

                // VALIDACIÓN: Obtener fechas y estado de la rueda para validar
                $stmt_rueda = $this->pdo->prepare("SELECT fechaInicio, fechaFin, estadoRueda FROM ruedas_negocios WHERE id = ?");
                $stmt_rueda->execute([$rueda_id]);
                $rueda = $stmt_rueda->fetch();
                
                if (!$rueda) {
                    throw new Exception("Rueda de negocios no encontrada.");
                }
                if ($rueda['estadoRueda'] !== 'activa') {
                    throw new Exception("No se pueden agendar citas mientras la rueda de negocios no esté en estado activa.");
                }

                // 1. Buscar el apartado de mesa existente
                $stmt_mesa = $this->pdo->prepare("SELECT id FROM reuniones WHERE ruedaId = ? AND compradorId = ? AND estadoCita = 'mesa_apartada' LIMIT 1");
                $stmt_mesa->execute([$rueda_id, $comprador_id]);
                $apartado = $stmt_mesa->fetch();

                $this->pdo->beginTransaction();

                try {
                    if ($apartado) {
                        $reunion_id = $apartado['id'];
                        // Actualizar el registro existente SIN la columna inexistente fechaLimiteNegociacion
                        $stmt = $this->pdo->prepare("
                            UPDATE reuniones 
                            SET vendedorId = ?, 
                                fechaHora = ?, 
                                estadoCita = 'pendiente', 
                                ultimaAccionPor = 'vendedor', 
                                propositor = 'vendedor',
                                contadorContrapropuestas = 1
                            WHERE id = ?
                        ");
                        $stmt->execute([$vendedor_id, $fecha_hora, $reunion_id]);
                    } else {
                        // Crear uno nuevo si no existe
                        $stmt = $this->pdo->prepare("
                            INSERT INTO reuniones (ruedaId, compradorId, vendedorId, fechaHora, estadoCita, linkReunion, numero_mesa, contadorContrapropuestas, ultimaAccionPor, propositor) 
                            VALUES (?, ?, ?, ?, 'pendiente', ?, ?, 1, 'vendedor', 'vendedor')
                        ");
                        $stmt->execute([$rueda_id, $comprador_id, $vendedor_id, $fecha_hora, $link_reunion, $numero_mesa]);
                        $reunion_id = $this->pdo->lastInsertId();
                    }

                    // Intentar guardar el historial
                    try {
                        $this->pdo->prepare("
                            INSERT INTO reunion_negociaciones (reunionId, propuestoPor, fechaHoraPropuesta, respuesta, numeroContrapropuesta)
                            VALUES (?, 'vendedor', ?, 'pendiente', 1)
                        ")->execute([$reunion_id, $fecha_hora]);
                    } catch (Throwable $e_neg) {
                        // Opcional
                    }

                    $this->pdo->commit();
                } catch (Throwable $inner_e) {
                    $this->pdo->rollBack();
                    throw new Exception("Error en base de datos: " . $inner_e->getMessage());
                }

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=solicitud_enviada");
                exit();
            } catch (Throwable $e) {
                Logger::logCurrentRoleError('Error al solicitar cita', [
                    'accion' => 'solicitarCita',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function verCompradores() {
        try {
            $ruedaId = $_GET['id'] ?? null;
            
            if (!$ruedaId) {
                throw new Exception("ID de rueda no especificado.");
            }

            // Obtener datos de la empresa del vendedor
            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $miEmpresa = $stmt->fetch();

            if (!$miEmpresa) {
                throw new Exception("No se encontró información de tu empresa.");
            }

            // Obtener datos de la rueda
            $stmt_rueda = $this->pdo->prepare("SELECT * FROM ruedas_negocios WHERE id = ?");
            $stmt_rueda->execute([$ruedaId]);
            $rueda = $stmt_rueda->fetch();

            if (!$rueda) {
                throw new Exception("Rueda de negocios no encontrada.");
            }

            // Obtener todos los sectores para filtros
            $stmt_sectores = $this->pdo->query("SELECT * FROM sectores ORDER BY nombreSector ASC");
            $todos_sectores = $stmt_sectores->fetchAll();

            // Obtener compradores inscritos en la rueda que YA APARTARON MESA
            $busqueda = $_GET['busqueda'] ?? '';
            $sector_id = $_GET['sector_id'] ?? '';

            $sql = "
                SELECT e.id as empresaId, e.razon_social, e.ubicacionGeografica, e.sectorId, r.numero_mesa, r.fechaHora
                FROM empresas e
                JOIN reuniones r ON e.id = r.compradorId
                WHERE r.ruedaId = ? 
                AND r.estadoCita = 'mesa_apartada'
                AND e.id != ?
            ";
            
            $params = [$ruedaId, $miEmpresa['id']];

            if (!empty($busqueda)) {
                $sql .= " AND (e.razon_social LIKE ? OR e.descripcion LIKE ?)";
                $params[] = "%$busqueda%";
                $params[] = "%$busqueda%";
            }

            if (!empty($sector_id)) {
                $sql .= " AND e.sectorId = ?";
                $params[] = $sector_id;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $compradores = $stmt->fetchAll();

            // Obtener demandas y verificar si tiene mesa apartada
            foreach ($compradores as &$c) {
                // Demandas
                $stmt_dem = $this->pdo->prepare("
                    SELECT tituloDemanda, descripcionDemanda 
                    FROM demandas 
                    WHERE empresaId = ? AND ruedaId = ?
                ");
                $stmt_dem->execute([$c['empresaId'], $ruedaId]);
                $c['demandas'] = $stmt_dem->fetchAll();

                // Verificar mesa apartada
                $stmt_mesa = $this->pdo->prepare("
                    SELECT numero_mesa, fechaHora, id as reunionId
                    FROM reuniones 
                    WHERE compradorId = ? 
                    AND ruedaId = ? 
                    AND estadoCita = 'mesa_apartada'
                    LIMIT 1
                ");
                $stmt_mesa->execute([$c['empresaId'], $ruedaId]);
                $mesaInfo = $stmt_mesa->fetch();
                $c['mesa_apartada'] = $mesaInfo ? $mesaInfo['numero_mesa'] : null;
                $c['fecha_apartado'] = $mesaInfo ? $mesaInfo['fechaHora'] : null;
                $c['reunion_apartada_id'] = $mesaInfo ? $mesaInfo['reunionId'] : null;
            }

            require_once '../app/views/vendedor/ver_compradores.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function verEncuestas() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT s.*, r.fechaHora, e.razon_social as contraparte, rn.nombreRueda as tituloRueda
                FROM encuestas_satisfaccion s
                JOIN reuniones r ON s.reunionId = r.id
                JOIN empresas e ON r.compradorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE s.usuarioId = ?
                ORDER BY s.createdAt DESC
            ");
            $stmt->execute([$_SESSION['usuario_id']]);
            $mis_encuestas = $stmt->fetchAll();

            // --- NUEVA LÓGICA: Obtener también las pendientes ---
            $stmt_emp = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
            $stmt_emp->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt_emp->fetch();

            $encuestas_pendientes = [];
            if ($empresa) {
                $stmt_p = $this->pdo->prepare("
                    SELECT r.id, e.razon_social as contraparte, r.fechaHora, rn.nombreRueda as tituloRueda
                    FROM reuniones r
                    JOIN empresas e ON r.compradorId = e.id
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                    LEFT JOIN encuestas_satisfaccion s ON r.id = s.reunionId AND s.usuarioId = ?
                    WHERE r.vendedorId = ? 
                    AND (r.estadoCita = 'realizada' OR (r.estadoCita IN ('aceptada', 'agendada') AND DATE(r.fechaHora) < DATE(?)))
                    AND s.id IS NULL
                    ORDER BY r.fechaHora DESC
                ");
                $stmt_p->execute([$_SESSION['usuario_id'], $empresa['id'], SYSTEM_TIME]);
                $encuestas_pendientes = $stmt_p->fetchAll();
            }
            // ----------------------------------------------------

            require_once '../app/views/vendedor/historial_encuestas.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function gestionarCitaRecibida() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $cita_id = $_POST['cita_id'];
                $accion = $_POST['accion_cita']; // 'aceptada', 'contraoferta' (NO 'rechazada' - vendedor no puede rechazar)
                $nueva_fecha = $_POST['nueva_fecha'] ?? null;
                $mensaje = $_POST['mensaje'] ?? null;

                // Obtener datos actuales de la reunión
                $stmt_cita = $this->pdo->prepare("
                    SELECT r.*, rn.fechaFin 
                    FROM reuniones r 
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id 
                    WHERE r.id = ?
                ");
                $stmt_cita->execute([$cita_id]);
                $cita = $stmt_cita->fetch();

                if (!$cita) {
                    throw new Exception("Cita no encontrada.");
                }

                // Seguridad: validar que el vendedor es parte de esta reunión
                $stmt_v = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_v->execute([$_SESSION['usuario_id']]);
                $miEmpresa = $stmt_v->fetch();
                
                if (!$miEmpresa || $cita['vendedorId'] != $miEmpresa['id']) {
                    Logger::logSecurityAlert("Intento de gestionar cita ajena por usuario ID: " . $_SESSION['usuario_id']);
                    throw new Exception("No tienes permisos para gestionar esta cita.");
                }

                // El vendedor NO puede rechazar - solo aceptar o contraofertar
                if ($accion == 'rechazada') {
                    throw new Exception("Como vendedor, no puedes rechazar una propuesta. Solo puedes aceptarla o hacer una contraoferta.");
                }

                if ($accion == 'contraoferta') {
                    // Validar límite de contrapropuestas (máximo 4)
                    if ($cita['contadorContrapropuestas'] >= 4) {
                        throw new Exception("Se ha alcanzado el límite máximo de contrapropuestas (4). Debes aceptar o cancelar la cita actual.");
                    }

                    // Validar que hay nueva fecha
                    if (!$nueva_fecha) {
                        throw new Exception("Debes proporcionar una nueva fecha y hora para la contraoferta.");
                    }

                    // Validar que la nueva fecha está dentro del rango de la rueda
                    $fecha_nueva = strtotime($nueva_fecha);
                    $fecha_fin_rueda = strtotime($cita['fechaFin'] . ' 23:59:59');
                    
                    if ($fecha_nueva > $fecha_fin_rueda) {
                        throw new Exception("La fecha propuesta no puede ser después de que termine la rueda de negocios.");
                    }

                    if ($fecha_nueva < time()) {
                        throw new Exception("No puedes proponer una fecha en el pasado.");
                    }

                    // VALIDACIÓN: 1 hora de separación entre citas para AMBAS empresas (excluyendo la cita actual)
                    $fechaBase = strtotime($nueva_fecha);
                    $horaInicio = date('Y-m-d H:i:s', strtotime('-1 hour', $fechaBase));
                    $horaFin = date('Y-m-d H:i:s', strtotime('+1 hour', $fechaBase));
                    
                    $vendedor_id = $cita['vendedorId'];
                    $comprador_id = $cita['compradorId'];
                    $rueda_id = $cita['ruedaId'];
                    $cita_id_actual = $cita['id'];
                    
                    // Verificar para el vendedor (excluyendo la cita actual)
                    $stmt_disp_v = $this->pdo->prepare("
                        SELECT COUNT(*) as ocupado FROM reuniones 
                        WHERE (vendedorId = ? OR compradorId = ?) 
                        AND fechaHora BETWEEN ? AND ?
                        AND ruedaId = ? 
                        AND estadoCita NOT IN ('cancelada', 'rechazada')
                        AND id != ?
                    ");
                    $stmt_disp_v->execute([$vendedor_id, $vendedor_id, $horaInicio, $horaFin, $rueda_id, $cita_id_actual]);
                    if ($stmt_disp_v->fetch()['ocupado'] > 0) {
                        throw new Exception("Ya tienes una cita agendada dentro del rango de 1 hora. Debes dejar al menos 1 hora de separación entre citas.");
                    }
                    
                    // Verificar para el comprador (excluyendo la cita actual)
                    $stmt_disp_c = $this->pdo->prepare("
                        SELECT COUNT(*) as ocupado FROM reuniones 
                        WHERE (vendedorId = ? OR compradorId = ?) 
                        AND fechaHora BETWEEN ? AND ?
                        AND ruedaId = ? 
                        AND estadoCita NOT IN ('cancelada', 'rechazada')
                        AND id != ?
                    ");
                    $stmt_disp_c->execute([$comprador_id, $comprador_id, $horaInicio, $horaFin, $rueda_id, $cita_id_actual]);
                    if ($stmt_disp_c->fetch()['ocupado'] > 0) {
                        throw new Exception("El comprador ya tiene una cita agendada dentro del rango de 1 hora.");
                    }

                    $nuevo_contador = $cita['contadorContrapropuestas'] + 1;

                    // Actualizar reunión
                    $stmt = $this->pdo->prepare("
                        UPDATE reuniones 
                        SET fechaHora = ?, estadoCita = 'negociando', contadorContrapropuestas = ?, ultimaAccionPor = 'vendedor'
                        WHERE id = ?
                    ");
                    $stmt->execute([$nueva_fecha, $nuevo_contador, $cita_id]);

                    // Registrar en historial
                    $stmt_hist = $this->pdo->prepare("
                        INSERT INTO reunion_negociaciones (reunionId, propuestoPor, fechaHoraPropuesta, mensaje, respuesta, numeroContrapropuesta)
                        VALUES (?, 'vendedor', ?, ?, 'pendiente', ?)
                    ");
                    $stmt_hist->execute([$cita_id, $nueva_fecha, $mensaje, $nuevo_contador]);

                    $msg = "contraoferta_enviada";

                } elseif ($accion == 'aceptada') {
                    // El vendedor acepta la propuesta del comprador (o su propia propuesta inicial)
                    // El link se agrega cuando el comprador acepta, no aquí
                    $stmt = $this->pdo->prepare("
                        UPDATE reuniones 
                        SET estadoCita = 'aceptada', ultimaAccionPor = 'vendedor'
                        WHERE id = ?
                    ");
                    $stmt->execute([$cita_id]);

                    // Actualizar historial de la última propuesta a 'aceptada'
                    $stmt_hist = $this->pdo->prepare("
                        UPDATE reunion_negociaciones 
                        SET respuesta = 'aceptada' 
                        WHERE reunionId = ? AND respuesta = 'pendiente'
                    ");
                    $stmt_hist->execute([$cita_id]);

                    $msg = "cita_aceptada";
                }

                header("Location: index.php?controlador=vendedor&accion=verReuniones&msg=$msg");
                exit();
            } catch (PDOException $e) {
                Logger::logCurrentRoleError('Error al gestionar cita recibida', [
                    'accion' => 'gestionarCitaRecibida',
                    'cita_id' => $_POST['cita_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = "Error al procesar la cita: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al gestionar cita recibida', [
                    'accion' => 'gestionarCitaRecibida',
                    'cita_id' => $_POST['cita_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    /**
     * Vista para seleccionar la rueda de negocios antes de explorar demandas
     */
    public function seleccionarRuedaDemandas() {
        try {
            // Obtener la empresa del vendedor
            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            // Obtener ruedas donde el vendedor está inscrito y aceptado, con conteo de demandas
            $stmt_ruedas = $this->pdo->prepare("
                SELECT rn.*, COUNT(d.id) as total_demandas
                FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                LEFT JOIN demandas d ON rn.id = d.ruedaId
                LEFT JOIN empresas e_dem ON d.empresaId = e_dem.id
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                  AND rn.estadoRueda IN ('inscripciones', 'activa')
                  AND (e_dem.id IS NULL OR e_dem.id != ?)
                GROUP BY rn.id
                ORDER BY rn.fechaInicio ASC
            ");
            $stmt_ruedas->execute([$empresa['id'], $empresa['id']]);
            $ruedas_disponibles = $stmt_ruedas->fetchAll();

            require_once '../app/views/vendedor/seleccionar_rueda_demandas.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al listar ruedas para demandas', [
                'accion' => 'seleccionarRuedaDemandas',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    /**
     * Vista de exploración de demandas para una rueda específica
     * Recibe el ruedaId como parámetro GET
     */
    public function explorarDemandas() {
        try {
            $rueda_id = $_GET['ruedaId'] ?? null;
            
            if (!$rueda_id) {
                // Si no hay rueda seleccionada, redirigir a selección de rueda
                header("Location: index.php?controlador=vendedor&accion=seleccionarRuedaDemandas");
                exit();
            }

            // Obtener la empresa del vendedor
            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            // Verificar que el vendedor está inscrito y aceptado en esta rueda
            $stmt_check = $this->pdo->prepare("
                SELECT rn.* FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE rn.id = ? AND ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                  AND rn.estadoRueda IN ('inscripciones', 'activa')
            ");
            $stmt_check->execute([$rueda_id, $empresa['id']]);
            $rueda_actual = $stmt_check->fetch();

            if (!$rueda_actual) {
                throw new Exception("No tienes acceso a esta rueda de negocios o no está activa.");
            }

            // Obtener todas las demandas de compradores en esta rueda específica
            $stmt_demandas = $this->pdo->prepare("
                SELECT d.*, e.razon_social, e.ubicacionGeografica, e.sectorId, e.tipo_persona,
                       s.nombreSector, s.ciiu_clase, e.id as empresaId
                FROM demandas d
                JOIN empresas e ON d.empresaId = e.id
                JOIN sectores s ON e.sectorId = s.id
                WHERE d.ruedaId = ? AND e.id != ?
                ORDER BY d.createdAt DESC
            ");
            $stmt_demandas->execute([$rueda_id, $empresa['id']]);
            $demandas = $stmt_demandas->fetchAll();

            // Obtener sectores únicos de las demandas para filtros
            $sectores = [];
            $sector_ids = array_unique(array_column($demandas, 'sectorId'));
            if (!empty($sector_ids)) {
                $placeholders = implode(',', array_fill(0, count($sector_ids), '?'));
                $stmt_sectores = $this->pdo->prepare("
                    SELECT id, nombreSector, ciiu_clase 
                    FROM sectores 
                    WHERE id IN ($placeholders)
                    ORDER BY ciiu_clase
                ");
                $stmt_sectores->execute($sector_ids);
                $sectores = $stmt_sectores->fetchAll();
            }

            // Obtener reuniones existentes del vendedor en esta rueda
            $stmt_reuniones = $this->pdo->prepare("
                SELECT r.compradorId, r.estadoCita
                FROM reuniones r
                WHERE r.vendedorId = ? AND r.ruedaId = ? AND r.estadoCita NOT IN ('cancelada')
            ");
            $stmt_reuniones->execute([$empresa['id'], $rueda_id]);
            $reuniones_existentes = $stmt_reuniones->fetchAll();
            
            // Indexar reuniones existentes por compradorId
            $reuniones_index = [];
            foreach ($reuniones_existentes as $reunion) {
                $reuniones_index[$reunion['compradorId']] = $reunion['estadoCita'];
            }

            // Obtener otras ruedas disponibles para cambiar rápidamente
            $stmt_otras = $this->pdo->prepare("
                SELECT rn.id, rn.tituloRueda, rn.estadoRueda
                FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                  AND rn.estadoRueda IN ('inscripciones', 'activa')
                  AND rn.id != ?
                ORDER BY rn.fechaInicio ASC
            ");
            $stmt_otras->execute([$empresa['id'], $rueda_id]);
            $otras_ruedas = $stmt_otras->fetchAll();

            require_once '../app/views/vendedor/explorar_demandas.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al explorar demandas', [
                'accion' => 'explorarDemandas',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    /**
     * Vista tipo "Mercado" para el vendedor: ve sus ofertas + demandas de compradores en una rueda
     * Equivalente a verParticipantes del comprador
     */
    public function verMisOfertas() {
        try {
            if (!isset($_GET['id'])) {
                header("Location: index.php?controlador=vendedor&accion=dashboard");
                exit();
            }

            $ruedaId = $_GET['id'];
            
            // SEGURIDAD: Validar que el vendedor está inscrito y ACEPTADO
            $stmt_inscripcion = $this->pdo->prepare("
                SELECT estadoInscripcion, empresaId
                FROM inscripciones_ruedas
                WHERE ruedaId = ? AND empresaId = (SELECT id FROM empresas WHERE usuarioId = ?) AND estadoInscripcion = 'aceptada'
            ");
            $stmt_inscripcion->execute([$ruedaId, $_SESSION['usuario_id']]);
            $inscripcion = $stmt_inscripcion->fetch();

            if (!$inscripcion) {
                throw new Exception("No tienes permisos para ver esta rueda. Tu inscripción debe estar aceptada.");
            }

            $miEmpresaId = $inscripcion['empresaId'];

            // Obtener info de la rueda
            $stmt_rueda = $this->pdo->prepare("SELECT * FROM ruedas_negocios WHERE id = ?");
            $stmt_rueda->execute([$ruedaId]);
            $rueda = $stmt_rueda->fetch();

            if (!$rueda) {
                throw new Exception("Rueda de negocios no encontrada.");
            }

            // Obtener mi sector para las sugerencias
            $stmt_mi_sector = $this->pdo->prepare("SELECT sectorId FROM empresas WHERE id = ?");
            $stmt_mi_sector->execute([$miEmpresaId]);
            $miSectorId = $stmt_mi_sector->fetchColumn();

            // Filtros de búsqueda para demandas
            $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
            $sector_filtro = isset($_GET['sector_id']) ? trim($_GET['sector_id']) : '';

            // 1. Obtener Demandas de compradores (Prioridad del Vendedor)
            $sql_demandas = "
                SELECT d.*, e.razon_social, s.nombreSector, e.ubicacionGeografica
                FROM demandas d
                JOIN empresas e ON d.empresaId = e.id
                JOIN sectores s ON e.sectorId = s.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE ir.ruedaId = ? 
                  AND ir.estadoInscripcion = 'aceptada'
                  AND d.ruedaId = ?
                  AND e.id != ?
            ";
            $params_demandas = [$ruedaId, $ruedaId, $miEmpresaId];

            if (!empty($busqueda)) {
                $sql_demandas .= " AND (d.tituloDemanda LIKE ? OR d.descripcionDemanda LIKE ? OR e.razon_social LIKE ?)";
                $search_term = "%$busqueda%";
                $params_demandas[] = $search_term;
                $params_demandas[] = $search_term;
                $params_demandas[] = $search_term;
            }

            if (!empty($sector_filtro)) {
                $sql_demandas .= " AND e.sectorId = ?";
                $params_demandas[] = $sector_filtro;
            }

            $sql_demandas .= " ORDER BY CASE WHEN e.sectorId = ? THEN 0 ELSE 1 END, d.createdAt DESC";
            $params_demandas[] = $miSectorId;

            $stmt_demandas = $this->pdo->prepare($sql_demandas);
            $stmt_demandas->execute($params_demandas);
            $demandas = $stmt_demandas->fetchAll();

            // 2. Demandas de mi mismo sector (Top 5 recomendados)
            $stmt_mismo_sector = $this->pdo->prepare("
                SELECT d.*, e.razon_social
                FROM demandas d
                JOIN empresas e ON d.empresaId = e.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE d.ruedaId = ? AND ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada' 
                  AND e.id != ? AND e.sectorId = ?
                LIMIT 5
            ");
            $stmt_mismo_sector->execute([$ruedaId, $ruedaId, $miEmpresaId, $miSectorId]);
            $demandas_mismo_sector = $stmt_mismo_sector->fetchAll();

            // 3. Obtener participantes (Compradores)
            $stmt_participantes = $this->pdo->prepare("
                SELECT DISTINCT e.*, s.nombreSector, u.nombreUsuario as representante
                FROM empresas e
                JOIN usuarios u ON e.usuarioId = u.id
                JOIN sectores s ON e.sectorId = s.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada' AND u.roleId = 2
                ORDER BY e.razon_social ASC
            ");
            $stmt_participantes->execute([$ruedaId]);
            $participantes = $stmt_participantes->fetchAll();

            // Obtener todos los sectores para el filtro
            $stmt_sectores = $this->pdo->query("
                SELECT 
                    s.id, 
                    s.nombreSector, 
                    s.ciiu_clase,
                    CONCAT(s.ciiu_clase, ' - ', s.nombreSector) as display_text
                FROM sectores s 
                WHERE s.ciiu_clase IS NOT NULL
                ORDER BY s.ciiu_clase
            ");
            $todos_sectores = $stmt_sectores->fetchAll();

            // 4. Obtener ofertas del vendedor para esta rueda
            $stmt_ofertas = $this->pdo->prepare("
                SELECT * FROM ofertas 
                WHERE empresaId = ? AND ruedaId = ? 
                ORDER BY createdAt DESC
            ");
            $stmt_ofertas->execute([$miEmpresaId, $ruedaId]);
            $ofertas_rueda = $stmt_ofertas->fetchAll();

            require_once '../app/views/vendedor/ver_mis_ofertas.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al ver mis ofertas', [
                'accion' => 'verMisOfertas',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    /**
     * Vista general de todas las ofertas del vendedor agrupadas por rueda
     * Permite ver todas las ofertas sin necesidad de seleccionar una rueda específica
     */
    public function verTodasMisOfertas() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            $miEmpresaId = $empresa['id'];

            // Obtener todas las ofertas del vendedor con información de la rueda
            $stmt_ofertas = $this->pdo->prepare("
                SELECT o.*, rn.nombreRueda as tituloRueda, rn.estadoRueda, rn.fechaInicio, rn.fechaFin
                FROM ofertas o
                JOIN ruedas_negocios rn ON o.ruedaId = rn.id
                WHERE o.empresaId = ?
                ORDER BY rn.fechaInicio DESC, o.createdAt DESC
            ");
            $stmt_ofertas->execute([$miEmpresaId]);
            $todas_ofertas = $stmt_ofertas->fetchAll();

            // Agrupar ofertas por rueda
            $ofertas_por_rueda = [];
            foreach ($todas_ofertas as $oferta) {
                $rueda_id = $oferta['ruedaId'];
                if (!isset($ofertas_por_rueda[$rueda_id])) {
                    $ofertas_por_rueda[$rueda_id] = [
                        'rueda_id' => $rueda_id,
                        'tituloRueda' => $oferta['tituloRueda'],
                        'estadoRueda' => $oferta['estadoRueda'],
                        'fechaInicio' => $oferta['fechaInicio'],
                        'fechaFin' => $oferta['fechaFin'],
                        'ofertas' => []
                    ];
                }
                $ofertas_por_rueda[$rueda_id]['ofertas'][] = $oferta;
            }

            // Obtener todas las ruedas donde el vendedor está inscrito para mostrar opciones de agregar ofertas
            $stmt_ruedas = $this->pdo->prepare("
                SELECT rn.*, ir.estadoInscripcion
                FROM ruedas_negocios rn
                JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                WHERE ir.empresaId = ? AND ir.estadoInscripcion = 'aceptada'
                ORDER BY rn.fechaInicio DESC
            ");
            $stmt_ruedas->execute([$miEmpresaId]);
            $ruedas_inscrito = $stmt_ruedas->fetchAll();

            // Obtener todos los sectores para el formulario de nueva oferta
            $stmt_sectores = $this->pdo->query("
                SELECT id, nombreSector 
                FROM sectores 
                ORDER BY nombreSector
            ");
            $todos_sectores = $stmt_sectores->fetchAll();

            // Obtener el sector del vendedor
            $miSectorId = $empresa['sectorId'];

            require_once '../app/views/vendedor/todas_mis_ofertas.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error al ver todas mis ofertas', [
                'accion' => 'verTodasMisOfertas',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    /**
     * Agregar link de reunión a una cita aceptada
     * Solo el PROPOSITOR (quien creó la cita originalmente) puede agregar el link, una sola vez
     * Regla: El campo 'propositor' indica quién creó la cita (comprador|vendedor)
     * Ej: Si propositor='vendedor' → vendedor agrega link (independientemente de quién aceptó)
     */
    public function agregarLinkReunion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $cita_id = $_POST['cita_id'];
                $link_reunion = trim($_POST['link_reunion'] ?? '');

                if (empty($link_reunion)) {
                    throw new Exception("Debes proporcionar un link de reunión válido.");
                }

                // Normalizar link
                if (!preg_match("~^(?:f|ht)tps?://~i", $link_reunion)) {
                    $link_reunion = "https://" . $link_reunion;
                }

                // Validar formato URL
                if (!filter_var($link_reunion, FILTER_VALIDATE_URL)) {
                    throw new Exception("El link de reunión debe ser una URL válida.");
                }

                // Obtener datos de la cita
                $stmt_cita = $this->pdo->prepare("SELECT * FROM reuniones WHERE id = ?");
                $stmt_cita->execute([$cita_id]);
                $cita = $stmt_cita->fetch();

                if (!$cita) {
                    throw new Exception("Cita no encontrada.");
                }

                // Validar que la cita está aceptada/agendada
                if (!in_array($cita['estadoCita'], ['aceptada', 'agendada'])) {
                    throw new Exception("Solo se puede agregar link a citas que hayan sido aceptadas.");
                }

                // Validar que NO tenga link ya (solo se pone una vez)
                if (!empty($cita['linkReunion'])) {
                    throw new Exception("Esta cita ya tiene un link de reunión asignado. No se puede modificar.");
                }

                // Seguridad: validar que el vendedor es parte de esta reunión
                $stmt_v = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_v->execute([$_SESSION['usuario_id']]);
                $miEmpresa = $stmt_v->fetch();

                if (!$miEmpresa || $cita['vendedorId'] != $miEmpresa['id']) {
                    throw new Exception("No tienes permisos para agregar link a esta cita.");
                }

                // Validar que el vendedor es el PROPOSITOR (quien creó la cita originalmente)
                if (($cita['propositor'] ?? '') !== 'vendedor') {
                    throw new Exception("Solo el propositor de la cita puede agregar el link de reunión.");
                }

                // Agregar link
                $stmt = $this->pdo->prepare("UPDATE reuniones SET linkReunion = ? WHERE id = ?");
                $stmt->execute([$link_reunion, $cita_id]);

                Logger::log("Vendedor agregó link de reunión a cita ID $cita_id", 'business');

                header("Location: index.php?controlador=vendedor&accion=verReuniones&msg=link_agregado");
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al agregar link de reunión', [
                    'accion' => 'agregarLinkReunion',
                    'cita_id' => $_POST['cita_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function pagarMembresia() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $usuario_id = $_SESSION['usuario_id'];
                $plan = $_POST['plan_membresia'] ?? 'mensual';

                // Obtener ID de la empresa
                $stmt_emp = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_emp->execute([$usuario_id]);
                $empresa = $stmt_emp->fetch();

                if (!$empresa) {
                    throw new Exception("No se encontró la empresa del usuario.");
                }

                $empresa_id = $empresa['id'];

                // Activación gratuita para pruebas (sin pasarela de pagos)
                $interval = $plan === 'anual' ? '+1 year' : '+1 month';
                $fecha_vencimiento = date('Y-m-d H:i:s', strtotime($interval, strtotime(SYSTEM_TIME)));
                $monto = $plan === 'anual' ? 225000.00 : 25000.00;
                $payment_id = 'GRATIS_' . uniqid();

                $this->pdo->beginTransaction();

                // 1. Activar membresía en la tabla de empresas (asegurar columnas primero)
                try {
                    $stmt_check = $this->pdo->query("SHOW COLUMNS FROM empresas LIKE 'membresia_plan'");
                    if ($stmt_check && !$stmt_check->fetch()) {
                        $this->pdo->exec("
                            ALTER TABLE empresas 
                            ADD COLUMN membresia_plan VARCHAR(50) DEFAULT 'ninguno',
                            ADD COLUMN membresia_estado VARCHAR(50) DEFAULT 'inactivo',
                            ADD COLUMN membresia_vencimiento DATETIME NULL
                        ");
                    }

                    $stmt = $this->pdo->prepare("
                        UPDATE empresas
                        SET membresia_plan = ?,
                            membresia_estado = 'activo',
                            membresia_vencimiento = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$plan, $fecha_vencimiento, $empresa_id]);
                } catch (Exception $e) {
                    // Ignorar si no existen columnas
                }

                // 2. Insertar transacción gratuita en el historial
                try {
                    $stmt_pago = $this->pdo->prepare("
                        INSERT INTO pagos_membresias (empresa_id, plan, monto, estado_pago, id_pago_externo, fecha_pago)
                        VALUES (?, ?, ?, 'aprobado', ?, NOW())
                    ");
                    $stmt_pago->execute([$empresa_id, $plan, $monto, $payment_id]);
                } catch (Exception $e) {
                    // Ignorar si no existe tabla
                }

                $this->pdo->commit();

                Logger::log("Membresía activada de forma gratuita para pruebas. Empresa ID $empresa_id. Plan: $plan", 'business');

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=membresia_activada");
                exit();
            } catch (Exception $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                Logger::logCurrentRoleError('Error al activar membresía gratuita', [
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function pagoExitoso() {
        try {
            require_once '../config/mercadopago.php';
            
            $payment_id = $_GET['payment_id'] ?? null;
            $status = $_GET['status'] ?? null;
            $external_reference = $_GET['external_reference'] ?? null;

            if (!$payment_id || !$status || !$external_reference) {
                throw new Exception("Parámetros de retorno de pago incompletos.");
            }

            // Verificar que el estado del pago sea aprobado
            if ($status !== 'approved' && $status !== 'pending' && $status !== 'in_process') {
                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=pago_fallido");
                exit();
            }

            // Decodificar la referencia externa segura (empresa_id|plan|uniqid)
            $parts = explode('|', $external_reference);
            $empresa_id = isset($parts[0]) ? (int)$parts[0] : null;
            $plan = isset($parts[1]) ? $parts[1] : 'mensual';

            if (!$empresa_id) {
                throw new Exception("Referencia de empresa no válida.");
            }

            // Prevenir duplicidades si refrescan la página de éxito
            $stmt_check = $this->pdo->prepare("SELECT COUNT(*) FROM pagos_membresias WHERE id_pago_externo = ?");
            $stmt_check->execute([$payment_id]);
            $pago_procesado = $stmt_check->fetchColumn() > 0;

            if (!$pago_procesado) {
                // Calcular nueva fecha de vencimiento
                $interval = $plan === 'anual' ? '+1 year' : '+1 month';
                $fecha_vencimiento = date('Y-m-d H:i:s', strtotime($interval, strtotime(SYSTEM_TIME)));
                $monto = $plan === 'anual' ? 225000.00 : 25000.00;

                $this->pdo->beginTransaction();

                // 1. Activar membresía en la tabla de empresas si existen las columnas
                try {
                    $stmt_check_col = $this->pdo->query("SHOW COLUMNS FROM empresas LIKE 'membresia_plan'");
                    if ($stmt_check_col && $stmt_check_col->fetch()) {
                        $stmt = $this->pdo->prepare("
                            UPDATE empresas 
                            SET membresia_plan = ?, 
                                membresia_estado = 'activo', 
                                membresia_vencimiento = ? 
                            WHERE id = ?
                        ");
                        $stmt->execute([$plan, $fecha_vencimiento, $empresa_id]);
                    }
                } catch (Exception $e) {
                    // Ignorar si no existe la columna
                }

                // 2. Insertar transacción oficial en el historial con ID de Mercado Pago
                try {
                    $stmt_pago = $this->pdo->prepare("
                        INSERT INTO pagos_membresias (empresa_id, plan, monto, estado_pago, id_pago_externo, fecha_pago)
                        VALUES (?, ?, ?, 'aprobado', ?, NOW())
                    ");
                    $stmt_pago->execute([$empresa_id, $plan, $monto, $payment_id]);
                } catch (Exception $e) {
                    // Ignorar si no existe tabla
                }

                $this->pdo->commit();

                Logger::log("Membresía activada con éxito para Empresa ID $empresa_id. Plan: $plan, Pago MP ID: $payment_id", 'business');
            }

            header("Location: index.php?controlador=vendedor&accion=dashboard&msg=membresia_activada");
            exit();

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Logger::logCurrentRoleError('Error en callback de confirmación de pago', [
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function convertirseEnComprador() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $usuario_id = $_SESSION['usuario_id'];

                $this->pdo->beginTransaction();

                // 1. Obtener el ID del rol de comprador en la base de datos
                $stmt_r = $this->pdo->prepare("SELECT id, slugRole, nombreRole FROM roles WHERE slugRole = 'comprador' LIMIT 1");
                $stmt_r->execute();
                $rolComprador = $stmt_r->fetch();

                $compradorRoleId = $rolComprador ? (int)$rolComprador['id'] : 3;
                $slugRole = $rolComprador ? $rolComprador['slugRole'] : 'comprador';
                $nombreRole = $rolComprador ? $rolComprador['nombreRole'] : 'Comprador';

                // 2. Cambiar roleId en la tabla usuarios
                $stmt_user = $this->pdo->prepare("UPDATE usuarios SET roleId = ? WHERE id = ?");
                $stmt_user->execute([$compradorRoleId, $usuario_id]);

                // 3. Limpiar membresía si la columna existe en la base de datos
                try {
                    $stmt_check = $this->pdo->query("SHOW COLUMNS FROM empresas LIKE 'membresia_plan'");
                    if ($stmt_check && $stmt_check->fetch()) {
                        $stmt_emp = $this->pdo->prepare("
                            UPDATE empresas 
                            SET membresia_plan = 'ninguno', 
                                membresia_estado = 'inactivo', 
                                membresia_vencimiento = NULL 
                            WHERE usuarioId = ?
                        ");
                        $stmt_emp->execute([$usuario_id]);
                    }
                } catch (Exception $e) {
                    // Ignorar si la tabla no tiene las columnas de membresía
                }

                $this->pdo->commit();

                // 4. Actualizar variables de sesión para que el cambio de rol tenga efecto de inmediato
                $_SESSION['roleId'] = $compradorRoleId;
                $_SESSION['slugRole'] = $slugRole;
                $_SESSION['nombreRole'] = $nombreRole;

                Logger::log("Usuario ID $usuario_id actualizó su cuenta de Vendedor de vuelta a Comprador", 'business');

                header("Location: index.php?controlador=comprador&accion=dashboard&msg=perfil_comprador_activado");
                exit();
            } catch (Exception $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                Logger::logCurrentRoleError('Error al convertirse en comprador', [
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }
}
?>
