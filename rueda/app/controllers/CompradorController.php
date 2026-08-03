<?php
require_once '../includes/Logger.php';
class CompradorController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está logueado y es comprador (slugRole = 'comprador')
        $userRole = isset($_SESSION['slugRole']) ? strtolower(trim($_SESSION['slugRole'])) : '';
        if (!isset($_SESSION['usuario_id']) || $userRole !== 'comprador') {
            Logger::logRoleError($userRole ?: 'guest', 'Acceso no autorizado al CompradorController', [
                'accion' => $_GET['accion'] ?? 'desconocida'
            ]);
            header("Location: index.php?controlador=usuario&accion=login");
            exit();
        }
    }

    public function dashboard() {
        try {
            // Obtener datos de la empresa del comprador
            $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa para este usuario.");
            }

            // --- NUEVA LÓGICA DE LIMPIEZA AUTOMÁTICA ---
            // Cancelar citas pendientes/negociando que ya pasaron
            $this->pdo->prepare("
                UPDATE reuniones 
                SET estadoCita = 'cancelada', ultimaAccionPor = NULL
                WHERE compradorId = ? 
                AND estadoCita IN ('pendiente', 'negociando') 
                AND fechaHora < ?
            ")->execute([$empresa['id'], SYSTEM_TIME]);
            // --------------------------------------------

            // Obtener mis inscripciones a ruedas (Aceptadas, Pendientes, etc.)
            $stmt_mis_ruedas = $this->pdo->prepare("
                SELECT rn.*, ir.estadoInscripcion 
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

            // Obtener mis requerimientos actuales agrupados por ruedaId
            $stmt_req = $this->pdo->prepare("SELECT * FROM demandas WHERE empresaId = ?");
            $stmt_req->execute([$empresa['id']]);
            $todas_demandas = $stmt_req->fetchAll();
            $demandas_por_rueda = [];
            foreach ($todas_demandas as $dem) {
                $demandas_por_rueda[$dem['ruedaId']][] = $dem;
            }

            // Obtener solicitudes de citas recibidas agrupadas por ruedaId
            $stmt_citas = $this->pdo->prepare("
                SELECT r.*, e.razon_social as nombre_vendedor, rn.tituloRueda as rueda_titulo
                FROM reuniones r
                JOIN empresas e ON r.vendedorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.compradorId = ?
                ORDER BY r.fechaHora DESC
            ");
            $stmt_citas->execute([$empresa['id']]);
            $todas_citas = $stmt_citas->fetchAll();
            $citas_por_rueda = [];
            foreach ($todas_citas as $cita) {
                $citas_por_rueda[$cita['ruedaId']][] = $cita;
            }

            // Verificar si hay encuestas pendientes para el comprador (Citas realizadas o pasadas sin calificar)
            $stmt_encuestas_pendientes = $this->pdo->prepare("
                SELECT r.id, e.razon_social as nombre_vendedor, r.fechaHora, rn.tituloRueda
                FROM reuniones r
                JOIN empresas e ON r.vendedorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                LEFT JOIN encuestas_satisfaccion s ON r.id = s.reunionId AND s.usuarioId = ?
                WHERE r.compradorId = ? 
                AND (r.estadoCita = 'realizada' OR (r.estadoCita IN ('aceptada', 'agendada') AND DATE(r.fechaHora) < DATE(?)))
                AND s.id IS NULL
                ORDER BY r.fechaHora DESC
            ");
            $stmt_encuestas_pendientes->execute([$_SESSION['usuario_id'], $empresa['id'], SYSTEM_TIME]);
            $encuestas_pendientes = $stmt_encuestas_pendientes->fetchAll();

            // Obtener seguimientos de trazabilidad pendientes (3 y 6 meses)
            require_once '../app/models/TrazabilidadModel.php';
            $trazabilidadModel = new TrazabilidadModel($this->pdo);
            $trazabilidad_pendientes = $trazabilidadModel->getSeguimientosPendientes($_SESSION['usuario_id'], SYSTEM_TIME);

            // Obtener ruedas de negocios activas generales para el panel lateral
            $stmt_ruedas = $this->pdo->query("SELECT * FROM ruedas_negocios WHERE estadoRueda IN ('inscripciones', 'activa')");
            $ruedas = $stmt_ruedas->fetchAll();

            // Obtener mis inscripciones a ruedas (formato indexado para compatibilidad si es necesario)
            $mis_inscripciones = [];
            foreach ($mis_ruedas as $mr) {
                $mis_inscripciones[$mr['id']] = $mr;
            }

            // Filtros de búsqueda (CIIU o Texto)
            $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
            $sector_filtro = isset($_GET['sector_id']) ? trim($_GET['sector_id']) : '';

            // SEGURIDAD: Solo mostrar ofertas de ruedas donde el comprador está inscrito y ACEPTADO
            // Y donde los vendedores también están aceptados en esas ruedas
            $sql_ofertas = "
                SELECT o.*, e.razon_social, s.nombreSector
                FROM ofertas o
                JOIN empresas e ON o.empresaId = e.id
                JOIN sectores s ON o.sectorId = s.id
                JOIN inscripciones_ruedas ir_vendedor ON o.ruedaId = ir_vendedor.ruedaId AND o.empresaId = ir_vendedor.empresaId
                WHERE o.isActive = 1
                AND ir_vendedor.estadoInscripcion = 'aceptada'
                AND o.ruedaId IN (
                    SELECT ruedaId FROM inscripciones_ruedas
                    WHERE empresaId = ? AND estadoInscripcion = 'aceptada'
                )
            ";
            $params_ofertas = [$empresa['id']];

            if (!empty($busqueda)) {
                $sql_ofertas .= " AND (o.tituloOferta LIKE ? OR o.descripcionOferta LIKE ? OR e.razon_social LIKE ?)";
                $search_term = "%$busqueda%";
                $params_ofertas[] = $search_term;
                $params_ofertas[] = $search_term;
                $params_ofertas[] = $search_term;
            }

            if (!empty($sector_filtro)) {
                $sql_ofertas .= " AND o.sectorId = ?";
                $params_ofertas[] = $sector_filtro;
            }

            // Ordenar: primero los que hacen match con el CIIU del comprador, luego por nombre
            $sql_ofertas .= " ORDER BY CASE WHEN o.sectorId = ? THEN 0 ELSE 1 END, o.tituloOferta ASC";
            $params_ofertas[] = $empresa['sectorId'];

            $stmt_sugerencias = $this->pdo->prepare($sql_ofertas);
            $stmt_sugerencias->execute($params_ofertas);
            $ofertas_sugeridas = $stmt_sugerencias->fetchAll();

            // Obtener todos los sectores CIIU para el filtro select, organizados por sección
            $stmt_all_sectores = $this->pdo->query("
                SELECT 
                    s.id, 
                    s.nombreSector, 
                    s.ciiu_clase,
                    CONCAT(s.ciiu_clase, ' - ', s.nombreSector) as display_text
                FROM sectores s 
                WHERE s.is_active = 1 AND s.ciiu_clase IS NOT NULL
                ORDER BY s.ciiu_clase
            ");
            $todos_sectores = $stmt_all_sectores->fetchAll();

            // Resumen de KPIs para el Dashboard
            $stmt_kpis = $this->pdo->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM reuniones WHERE compradorId = ?) as total_citas,
                    (SELECT COUNT(*) FROM reuniones WHERE compradorId = ? AND estadoCita = 'realizada') as citas_realizadas,
                    (SELECT COUNT(*) FROM reuniones WHERE compradorId = ? AND (estadoCita = 'agendada' OR estadoCita = 're-agendado')) as citas_agendadas,
                    (SELECT COUNT(*) FROM reuniones WHERE compradorId = ? AND (estadoCita IN ('pendiente', 'negociando')) AND ultimaAccionPor = 'vendedor') as citas_por_gestionar,
                    (SELECT COUNT(*) FROM demandas WHERE empresaId = ?) as total_demandas,
                    (SELECT COUNT(*) FROM inscripciones_ruedas WHERE empresaId = ? AND estadoInscripcion = 'aceptada') as ruedas_activas_count
            ");
            $stmt_kpis->execute([$empresa['id'], $empresa['id'], $empresa['id'], $empresa['id'], $empresa['id'], $empresa['id']]);
            $kpis = $stmt_kpis->fetch();

            require_once '../app/views/comprador/comprador_dashboard.php';
        } catch (Exception $e) {
            Logger::logCurrentRoleError('Error cargando dashboard comprador', [
                'accion' => 'dashboard',
                'usuario_id' => $_SESSION['usuario_id'] ?? 'n/a',
                'error' => $e->getMessage()
            ]);
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function verParticipantes() {
        try {
            $ruedaId = isset($_GET['id']) ? $_GET['id'] : null;

            // Si no llega ID por URL, buscar la primera rueda inscrita y aceptada del comprador
            // (necesario para que funcione el menú "Mercado de Ofertas" del sidebar)
            if (empty($ruedaId)) {
                $stmt_primera_rueda = $this->pdo->prepare("
                    SELECT rn.id
                    FROM ruedas_negocios rn
                    JOIN inscripciones_ruedas ir ON rn.id = ir.ruedaId
                    WHERE ir.empresaId = (SELECT id FROM empresas WHERE usuarioId = ?)
                      AND ir.estadoInscripcion = 'aceptada'
                      AND rn.estadoRueda IN ('activa', 'inscripciones', 'planeacion')
                    ORDER BY rn.fechaInicio DESC
                    LIMIT 1
                ");
                $stmt_primera_rueda->execute([$_SESSION['usuario_id']]);
                $primera_rueda = $stmt_primera_rueda->fetch();

                if (!$primera_rueda) {
                    throw new Exception("No estás inscrito en ninguna rueda activa. Inscríbete primero para acceder al mercado de ofertas.");
                }

                $ruedaId = $primera_rueda['id'];
            }
            
            // SEGURIDAD: Validar que el comprador está inscrito y ACEPTADO
            $stmt_inscripcion = $this->pdo->prepare("
                SELECT estadoInscripcion, empresaId
                FROM inscripciones_ruedas
                WHERE ruedaId = ? AND empresaId = (SELECT id FROM empresas WHERE usuarioId = ?) AND estadoInscripcion = 'aceptada'
            ");
            $stmt_inscripcion->execute([$ruedaId, $_SESSION['usuario_id']]);
            $inscripcion = $stmt_inscripcion->fetch();

            if (!$inscripcion) {
                throw new Exception("No tienes permisos para ver los participantes de esta rueda. Tu inscripción (rueda ID $ruedaId) no está aceptada o no existe.");
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

            // Filtros de búsqueda para ofertas
            $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
            $sector_filtro = isset($_GET['sector_id']) ? trim($_GET['sector_id']) : '';

            // 1. Obtener Ofertas (Prioridad del Comprador)
            $sql_ofertas = "
                SELECT o.*, e.razon_social, s.nombreSector, e.ubicacionGeografica
                FROM ofertas o
                JOIN empresas e ON o.empresaId = e.id
                JOIN sectores s ON o.sectorId = s.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE o.isActive = 1
                  AND ir.ruedaId = ?
                  AND ir.estadoInscripcion = 'aceptada'
                  AND o.ruedaId = ?
                  AND e.id != ?
            ";
            $params_ofertas = [$ruedaId, $ruedaId, $miEmpresaId];

            if (!empty($busqueda)) {
                $sql_ofertas .= " AND (o.tituloOferta LIKE ? OR o.descripcionOferta LIKE ? OR e.razon_social LIKE ?)";
                $search_term = "%$busqueda%";
                $params_ofertas[] = $search_term;
                $params_ofertas[] = $search_term;
                $params_ofertas[] = $search_term;
            }

            if (!empty($sector_filtro)) {
                $sql_ofertas .= " AND o.sectorId = ?";
                $params_ofertas[] = $sector_filtro;
            }

            $sql_ofertas .= " ORDER BY CASE WHEN o.sectorId = ? THEN 0 ELSE 1 END, o.createdAt DESC";
            $params_ofertas[] = $miSectorId;

            $stmt_ofertas = $this->pdo->prepare($sql_ofertas);
            $stmt_ofertas->execute($params_ofertas);
            $ofertas = $stmt_ofertas->fetchAll();

            // 2. Ofertas de mi mismo sector (Top 5) - SOLO de esta rueda
            $stmt_mismo_sector = $this->pdo->prepare("
                SELECT o.*, e.razon_social
                FROM ofertas o
                JOIN empresas e ON o.empresaId = e.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE o.isActive = 1 AND o.sectorId = ? AND o.ruedaId = ? AND ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada' AND e.id != ?
                LIMIT 5
            ");
            $stmt_mismo_sector->execute([$miSectorId, $ruedaId, $ruedaId, $miEmpresaId]);
            $ofertas_mismo_sector = $stmt_mismo_sector->fetchAll();

            // 3. Obtener participantes (Vendedores) - Para el panel lateral/secundario
            $stmt_participantes = $this->pdo->prepare("
                SELECT DISTINCT e.*, s.nombreSector, u.nombreUsuario as representante
                FROM empresas e
                JOIN usuarios u ON e.usuarioId = u.id
                JOIN sectores s ON e.sectorId = s.id
                JOIN inscripciones_ruedas ir ON e.id = ir.empresaId
                WHERE ir.ruedaId = ? AND ir.estadoInscripcion = 'aceptada' AND u.roleId = 3
                ORDER BY e.razon_social ASC
            ");
            $stmt_participantes->execute([$ruedaId]);
            $participantes = $stmt_participantes->fetchAll();

            // Obtener todos los sectores CIIU para el filtro, organizados por sección
            $stmt_sectores = $this->pdo->query("
                SELECT 
                    s.id, 
                    s.nombreSector, 
                    s.ciiu_clase,
                    CONCAT(s.ciiu_clase, ' - ', s.nombreSector) as display_text
                FROM sectores s 
                WHERE s.is_active = 1 AND s.ciiu_clase IS NOT NULL
                ORDER BY s.ciiu_clase
            ");
            $todos_sectores = $stmt_sectores->fetchAll();

            // 4. Obtener demandas del comprador para esta rueda
            $stmt_demandas = $this->pdo->prepare("
                SELECT * FROM demandas 
                WHERE empresaId = ? AND ruedaId = ? 
                ORDER BY createdAt DESC
            ");
            $stmt_demandas->execute([$miEmpresaId, $ruedaId]);
            $demandas_rueda = $stmt_demandas->fetchAll();

            require_once '../app/views/comprador/ver_participantes.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function gestionarCita() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $cita_id = $_POST['cita_id'];
                $accion = $_POST['accion']; // 'aceptada', 'rechazada', 'contraoferta'
                $link_reunion = isset($_POST['link_reunion']) ? trim($_POST['link_reunion']) : null;
                
                // Si el link no tiene protocolo, se lo agregamos automáticamente para facilitar al usuario
                if ($link_reunion && !preg_match("~^(?:f|ht)tps?://~i", $link_reunion)) {
                    $link_reunion = "https://" . $link_reunion;
                }
                $nueva_fecha = $_POST['nueva_fecha'] ?? null;
                $mensaje = $_POST['mensaje'] ?? null;

                // SEGURIDAD: Validar que la cita pertenece a la empresa del comprador logueado
                $stmt_v = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_v->execute([$_SESSION['usuario_id']]);
                $miEmpresa = $stmt_v->fetch();

                $stmt_cita = $this->pdo->prepare("
                    SELECT r.*, rn.fechaFin 
                    FROM reuniones r 
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id 
                    WHERE r.id = ?
                ");
                $stmt_cita->execute([$cita_id]);
                $cita = $stmt_cita->fetch();

                if (!$miEmpresa || !$cita || $cita['compradorId'] != $miEmpresa['id']) {
                    Logger::logSecurityAlert("Intento de manipulación de cita ajena por usuario ID: " . $_SESSION['usuario_id']);
                    throw new Exception("No tienes permisos para gestionar esta cita.");
                }

                // --- LÓGICA DE LINK COMPARTIDO ---
                // Si el link viene vacío en el POST, pero la cita YA TIENE un link previo, lo conservamos.
                if (empty($link_reunion) && !empty($cita['linkReunion'])) {
                    $link_reunion = $cita['linkReunion'];
                }
                // ---------------------------------

                // VALIDACIÓN: No permitir cambios en citas realizadas o ya canceladas
                if (in_array($cita['estadoCita'], ['realizada', 'cancelada'])) {
                    throw new Exception("No se puede modificar una cita que ya ha sido finalizada o cancelada.");
                }

                if ($accion == 'rechazada') {
                    // Solo el comprador puede rechazar
                    $stmt = $this->pdo->prepare("
                        UPDATE reuniones 
                        SET estadoCita = 'cancelada', ultimaAccionPor = 'comprador'
                        WHERE id = ?
                    ");
                    $stmt->execute([$cita_id]);

                    // Actualizar historial
                    $stmt_hist = $this->pdo->prepare("
                        UPDATE reunion_negociaciones 
                        SET respuesta = 'rechazada', updatedAt = NOW()
                        WHERE reunionId = ? AND respuesta = 'pendiente'
                    ");
                    $stmt_hist->execute([$cita_id]);

                    $msg = "cita_rechazada";

                } elseif ($accion == 'aceptada') {
                    // El comprador acepta - el link lo agrega el PROPOSITOR (quien creó la cita)
                    $stmt = $this->pdo->prepare("
                        UPDATE reuniones 
                        SET estadoCita = 'aceptada', ultimaAccionPor = 'comprador'
                        WHERE id = ?
                    ");
                    $stmt->execute([$cita_id]);

                    // Actualizar historial
                    $stmt_hist = $this->pdo->prepare("
                        UPDATE reunion_negociaciones 
                        SET respuesta = 'aceptada', updatedAt = NOW()
                        WHERE reunionId = ? AND respuesta = 'pendiente'
                    ");
                    $stmt_hist->execute([$cita_id]);

                    $msg = "cita_aceptada";

                } elseif ($accion == 'contraoferta') {
                    // Validar límite de contrapropuestas (máximo 4)
                    if ($cita['contadorContrapropuestas'] >= 4) {
                        throw new Exception("Se ha alcanzado el límite máximo de contrapropuestas (4). Debes aceptar o rechazar la cita actual.");
                    }

                    // Validar que hay nueva fecha
                    if (!$nueva_fecha) {
                        throw new Exception("Debes proporcionar una nueva fecha y hora para la contraoferta.");
                    }

                    // El link se agrega después por el propositor al aceptar, no aquí

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
                    
                    $comprador_id = $cita['compradorId'];
                    $vendedor_id = $cita['vendedorId'];
                    $rueda_id = $cita['ruedaId'];
                    $cita_id_actual = $cita['id'];
                    
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
                        throw new Exception("Ya tienes una cita agendada dentro del rango de 1 hora. Debes dejar al menos 1 hora de separación entre citas.");
                    }
                    
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
                        throw new Exception("El vendedor ya tiene una cita agendada dentro del rango de 1 hora.");
                    }

                    $nuevo_contador = $cita['contadorContrapropuestas'] + 1;

                    // Actualizar reunión (sin link - se agrega después al aceptar)
                    $stmt = $this->pdo->prepare("
                        UPDATE reuniones 
                        SET fechaHora = ?, estadoCita = 'negociando', contadorContrapropuestas = ?, ultimaAccionPor = 'comprador'
                        WHERE id = ?
                    ");
                    $stmt->execute([$nueva_fecha, $nuevo_contador, $cita_id]);

                    // Registrar en historial
                    $stmt_hist = $this->pdo->prepare("
                        INSERT INTO reunion_negociaciones (reunionId, propuestoPor, fechaHoraPropuesta, mensaje, respuesta, numeroContrapropuesta)
                        VALUES (?, 'comprador', ?, ?, 'pendiente', ?)
                    ");
                    $stmt_hist->execute([$cita_id, $nueva_fecha, $mensaje, $nuevo_contador]);

                    $msg = "contraoferta_enviada";
                }

                header("Location: index.php?controlador=comprador&accion=verReuniones&msg=$msg");
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al gestionar cita comprador', [
                    'accion' => 'gestionarCita',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function verReuniones() {
        try {
            // Obtener datos de la empresa del comprador
            $stmt = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt->fetch();

            if (!$empresa) {
                throw new Exception("No se encontró información de la empresa.");
            }

            // Obtener ruedas en las que está inscrito el comprador
            $stmt_ruedas = $this->pdo->prepare("
                SELECT rn.id, rn.tituloRueda, rn.estadoRueda, rn.fechaInicio, rn.fechaFin
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

            // Obtener citas del comprador FILTRADAS POR RUEDA
            $sql_citas = "
                SELECT r.*, e.razon_social as nombre_vendedor, rn.tituloRueda, rn.id as ruedaId
                FROM reuniones r
                JOIN empresas e ON r.vendedorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE r.compradorId = ?
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

            $citas_por_aceptar = [];      // pendiente, negociando (requieren acción del comprador)
            $citas_programadas = [];        // aceptada
            $citas_historial = [];          // cancelada, realizada

            foreach ($todas_citas as $c) {
                // Lógica de turnos: ¿Quién debe actuar?
                $debeActuarComprador = ($c['estadoCita'] == 'negociando' || $c['estadoCita'] == 'pendiente') && $c['ultimaAccionPor'] == 'vendedor';
                $esperandoVendedor = ($c['estadoCita'] == 'negociando' || $c['estadoCita'] == 'pendiente') && $c['ultimaAccionPor'] == 'comprador';

                if ($debeActuarComprador) {
                    // El vendedor envió una propuesta o contraoferta que el comprador debe responder
                    $stmt_hist = $this->pdo->prepare("
                        SELECT * FROM reunion_negociaciones 
                        WHERE reunionId = ? AND propuestoPor = 'vendedor' AND respuesta = 'pendiente'
                        ORDER BY numeroContrapropuesta DESC 
                        LIMIT 1
                    ");
                    $stmt_hist->execute([$c['id']]);
                    $ultima_propuesta = $stmt_hist->fetch();
                    $c['ultima_propuesta'] = $ultima_propuesta;
                    $citas_por_aceptar[] = $c;
                } elseif ($esperandoVendedor) {
                    // El comprador envió la propuesta y espera respuesta del vendedor
                    $citas_pendientes_vendedor[] = $c;
                } elseif (in_array($c['estadoCita'], ['aceptada', 'agendada'])) {
                    $citas_programadas[] = $c;
                } else {
                    $citas_historial[] = $c;
                }
            }

            // Resumen de citas por rueda (para mostrar totales)
            $resumen_por_rueda = [];
            if (!$rueda_id_filtro || empty($ruedas)) {
                // Obtener conteo de citas por rueda
                $stmt_resumen = $this->pdo->prepare("
                    SELECT r.ruedaId, rn.tituloRueda,
                           COUNT(*) as total,
                           SUM(CASE WHEN r.estadoCita IN ('pendiente', 'negociando') THEN 1 ELSE 0 END) as pendientes,
                           SUM(CASE WHEN r.estadoCita = 'aceptada' THEN 1 ELSE 0 END) as aceptadas
                    FROM reuniones r
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                    WHERE r.compradorId = ?
                    GROUP BY r.ruedaId
                    ORDER BY rn.fechaInicio DESC
                ");
                $stmt_resumen->execute([$empresa['id']]);
                $resumen_por_rueda = $stmt_resumen->fetchAll();
            }

            require_once '../app/views/comprador/ver_reuniones.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function registrarRequerimiento() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $empresa_id = $_POST['empresa_id'];
                $rueda_id = $_POST['rueda_id'] ?? null;
                // Soportar ambos formatos de campo (formulario antiguo y nuevo)
                $tituloDemanda = $_POST['tituloDemanda'] ?? $_POST['requerimiento'] ?? '';
                $descripcionDemanda = $_POST['descripcionDemanda'] ?? $_POST['descripcion'] ?? '';
                $tags_input = $_POST['tags'] ?? '';

                if (!$rueda_id) {
                    throw new Exception("Debes seleccionar una rueda de negocios para registrar este requerimiento.");
                }

                // SEGURIDAD: Validar inscripción en la rueda
                $stmt_ins = $this->pdo->prepare("SELECT id FROM inscripciones_ruedas WHERE empresaId = ? AND ruedaId = ? AND estadoInscripcion = 'aceptada'");
                $stmt_ins->execute([$empresa_id, $rueda_id]);
                if (!$stmt_ins->fetch()) {
                    throw new Exception("Debes estar inscrito y aceptado en la rueda de negocios para publicar requerimientos.");
                }

                // Procesar tags: convertir a minúsculas y limpiar
                $tags_array = array_map(function($tag) {
                    return strtolower(trim($tag));
                }, explode(',', $tags_input));
                
                // Eliminar vacíos
                $tags_array = array_filter($tags_array);
                $tags_json = json_encode(array_values($tags_array));

                $stmt = $this->pdo->prepare("INSERT INTO demandas (empresaId, ruedaId, tituloDemanda, descripcionDemanda, tagsRequerimiento) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$empresa_id, $rueda_id, $tituloDemanda, $descripcionDemanda, $tags_json]);

                // Redirigir según el origen
                $redirect_action = $_POST['redirect_to'] ?? 'dashboard';
                if ($redirect_action === 'verReuniones') {
                    header("Location: index.php?controlador=comprador&accion=verReuniones&rueda_id=" . $rueda_id . "&msg=demanda_registrada");
                } elseif ($redirect_action === 'verParticipantes') {
                    header("Location: index.php?controlador=comprador&accion=verParticipantes&id=" . $rueda_id . "&msg=demanda_registrada");
                } else {
                    header("Location: index.php?controlador=comprador&accion=dashboard&msg=demanda_registrada");
                }
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al registrar demanda', [
                    'accion' => 'registrarRequerimiento',
                    'empresa_id' => $_POST['empresa_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
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
                header("Location: index.php?controlador=comprador&accion=dashboard&msg=$msg");
                exit();
            } catch (PDOException $e) {
                $error_msg = "Error al guardar la encuesta: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function verEncuestas() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT s.*, r.fechaHora, e.razon_social as contraparte, rn.tituloRueda
                FROM encuestas_satisfaccion s
                JOIN reuniones r ON s.reunionId = r.id
                JOIN empresas e ON r.vendedorId = e.id
                JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                WHERE s.usuarioId = ?
                ORDER BY s.createdAt DESC
            ");
            $stmt->execute([$_SESSION['usuario_id']]);
            $mis_encuestas = $stmt->fetchAll();

            // --- NUEVA LÓGICA: Obtener también las pendientes ---
            // Primero necesitamos el ID de la empresa del comprador
            $stmt_emp = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
            $stmt_emp->execute([$_SESSION['usuario_id']]);
            $empresa = $stmt_emp->fetch();

            $encuestas_pendientes = [];
            if ($empresa) {
                $stmt_p = $this->pdo->prepare("
                    SELECT r.id, e.razon_social as contraparte, r.fechaHora, rn.tituloRueda
                    FROM reuniones r
                    JOIN empresas e ON r.vendedorId = e.id
                    JOIN ruedas_negocios rn ON r.ruedaId = rn.id
                    LEFT JOIN encuestas_satisfaccion s ON r.id = s.reunionId AND s.usuarioId = ?
                    WHERE r.compradorId = ? 
                    AND (r.estadoCita = 'realizada' OR (r.estadoCita IN ('aceptada', 'agendada', 'agendada') AND DATE(r.fechaHora) < DATE(?)))
                    AND s.id IS NULL
                    ORDER BY r.fechaHora DESC
                ");
                $stmt_p->execute([$_SESSION['usuario_id'], $empresa['id'], SYSTEM_TIME]);
                $encuestas_pendientes = $stmt_p->fetchAll();
            }
            // ----------------------------------------------------

            require_once '../app/views/comprador/historial_encuestas.php';
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            require_once '../app/views/layout/error.php';
        }
    }

    public function inscribirseRueda() {
        if (isset($_GET['id'])) {
            try {
                $rueda_id = $_GET['id'];
                
                $stmt_emp = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_emp->execute([$_SESSION['usuario_id']]);
                $empresa = $stmt_emp->fetch();

                if (!$empresa) {
                    throw new Exception("No se encontró información de tu empresa.");
                }

                // VALIDACIÓN: Verificar si las inscripciones están abiertas
                $stmt_rueda = $this->pdo->prepare("SELECT fechaInscripcionInicio, fechaInscripcionFin FROM ruedas_negocios WHERE id = ?");
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

                // VALIDACIÓN: Evitar duplicados
                $stmt_check = $this->pdo->prepare("SELECT id FROM inscripciones_ruedas WHERE ruedaId = ? AND empresaId = ?");
                $stmt_check->execute([$rueda_id, $empresa['id']]);
                if ($stmt_check->fetch()) {
                    header("Location: index.php?controlador=comprador&accion=dashboard&msg=ya_inscrito");
                    exit();
                }

                $stmt = $this->pdo->prepare("INSERT INTO inscripciones_ruedas (ruedaId, empresaId, estadoInscripcion) VALUES (?, ?, 'pendiente')");
                $stmt->execute([$rueda_id, $empresa['id']]);

                header("Location: index.php?controlador=comprador&accion=dashboard&msg=inscripcion_enviada");
                exit();
            } catch (Exception $e) {
                $error_msg = "Error al inscribirse: " . $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    public function solicitarReunion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $rueda_id = $_POST['rueda_id'];
                $vendedor_id = $_POST['vendedor_id'];
                $comprador_id = $_POST['comprador_id'];
                $fecha_hora = $_POST['fecha_hora'];
                $link = $_POST['link_reunion'] ?? null;
                $mesa = $_POST['numero_mesa'] ?? null;

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

                // VALIDACIÓN: No permitir fechas pasadas
                if (strtotime($fecha_hora) < strtotime(SYSTEM_TIME)) {
                    throw new Exception("No se pueden agendar citas en fechas u horas pasadas.");
                }

                // VALIDACIÓN: La fecha debe estar dentro del rango de la rueda y el estado debe ser 'activa'
                $stmt_rueda = $this->pdo->prepare("SELECT fechaInicio, fechaFin, estadoRueda FROM ruedas_negocios WHERE id = ?");
                $stmt_rueda->execute([$rueda_id]);
                $rueda = $stmt_rueda->fetch();
                if ($rueda) {
                    if ($rueda['estadoRueda'] !== 'activa') {
                        throw new Exception("No se pueden agendar citas mientras la rueda de negocios no esté en estado activa.");
                    }
                    $fecha_cita = strtotime($fecha_hora);
                    $fecha_fin_rueda = strtotime($rueda['fechaFin'] . ' 23:59:59');
                    if ($fecha_cita > $fecha_fin_rueda) {
                        throw new Exception("La fecha de la reunión no puede exceder la fecha de fin de la rueda (" . date('d/m/Y', $fecha_fin_rueda) . ").");
                    }
                }

                // VALIDACIÓN: 45 minutos de separación entre citas para AMBAS empresas
                $fechaBase = strtotime($fecha_hora);
                $horaInicio = date('Y-m-d H:i:s', strtotime('-44 minutes', $fechaBase));
                $horaFin = date('Y-m-d H:i:s', strtotime('+44 minutes', $fechaBase));
                
                // Verificar para el comprador
                $stmt_disp_c = $this->pdo->prepare("
                    SELECT COUNT(*) as ocupado FROM reuniones 
                    WHERE (vendedorId = ? OR compradorId = ?) 
                    AND fechaHora BETWEEN ? AND ?
                    AND ruedaId = ? 
                    AND estadoCita NOT IN ('cancelada', 'rechazada')
                ");
                $stmt_disp_c->execute([$comprador_id, $comprador_id, $horaInicio, $horaFin, $rueda_id]);
                if ($stmt_disp_c->fetch()['ocupado'] > 0) {
                    throw new Exception("Ya tienes una cita agendada en un horario muy cercano. Por favor, deja al menos 45 minutos entre citas.");
                }
                
                // Verificar para el vendedor
                $stmt_disp_v = $this->pdo->prepare("
                    SELECT COUNT(*) as ocupado FROM reuniones 
                    WHERE (vendedorId = ? OR compradorId = ?) 
                    AND fechaHora BETWEEN ? AND ?
                    AND ruedaId = ? 
                    AND estadoCita NOT IN ('cancelada', 'rechazada')
                ");
                $stmt_disp_v->execute([$vendedor_id, $vendedor_id, $horaInicio, $horaFin, $rueda_id]);
                if ($stmt_disp_v->fetch()['ocupado'] > 0) {
                    throw new Exception("El vendedor seleccionado ya tiene una cita agendada en un horario muy cercano.");
                }

                // OBTENER MESA ASIGNADA PREVIAMENTE (SI EXISTE)
                $stmt_mesa_prev = $this->pdo->prepare("
                    SELECT numero_mesa FROM reuniones 
                    WHERE ruedaId = ? AND compradorId = ? 
                    AND numero_mesa IS NOT NULL 
                    AND estadoCita NOT IN ('cancelada', 'rechazada')
                    LIMIT 1
                ");
                $stmt_mesa_prev->execute([$rueda_id, $comprador_id]);
                $mesa_existente = $stmt_mesa_prev->fetchColumn();

                // Si el comprador ya tiene una mesa en esta rueda, la forzamos
                if ($mesa_existente) {
                    $mesa = $mesa_existente;
                }

                $stmt = $this->pdo->prepare("
                    INSERT INTO reuniones (ruedaId, compradorId, vendedorId, fechaHora, linkReunion, numero_mesa, estadoCita, ultimaAccionPor, propositor, contadorContrapropuestas) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pendiente', 'comprador', 'comprador', 0)
                ");
                $stmt->execute([$rueda_id, $comprador_id, $vendedor_id, $fecha_hora, $link, $mesa]);

                $reunionId = $this->pdo->lastInsertId();

                // Programar seguimientos de trazabilidad (3 y 6 meses)
                require_once '../app/models/TrazabilidadModel.php';
                $trazabilidadModel = new TrazabilidadModel($this->pdo);
                $trazabilidadModel->programarSeguimientos(
                    $reunionId, 
                    $comprador_id, 
                    $vendedor_id, 
                    $fecha_hora
                );

                Logger::log("Comprador ID $comprador_id solicitó reunión con Vendedor ID $vendedor_id", 'business');

                header("Location: index.php?controlador=comprador&accion=dashboard&msg=reunion_solicitada");
                exit();
            } catch (Exception $e) {
                Logger::logCurrentRoleError('Error al solicitar reunión desde comprador', [
                    'accion' => 'solicitarReunion',
                    'vendedor_id' => $_POST['vendedor_id'] ?? 'n/a',
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }

    /**
     * Agregar link de reunión a una cita aceptada
     * Solo el PROPOSITOR (quien creó la cita originalmente) puede agregar el link, una sola vez
     * Regla: El campo 'propositor' indica quién creó la cita (comprador|vendedor)
     * Ej: Si propositor='comprador' → comprador agrega link (independientemente de quién aceptó)
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

                // Seguridad: validar que el comprador es parte de esta reunión
                $stmt_c = $this->pdo->prepare("SELECT id FROM empresas WHERE usuarioId = ?");
                $stmt_c->execute([$_SESSION['usuario_id']]);
                $miEmpresa = $stmt_c->fetch();

                if (!$miEmpresa || $cita['compradorId'] != $miEmpresa['id']) {
                    throw new Exception("No tienes permisos para agregar link a esta cita.");
                }

                // Validar que el comprador es el PROPOSITOR (quien creó la cita originalmente)
                if (($cita['propositor'] ?? '') !== 'comprador') {
                    throw new Exception("Solo el propositor de la cita puede agregar el link de reunión.");
                }

                // Agregar link
                $stmt = $this->pdo->prepare("UPDATE reuniones SET linkReunion = ? WHERE id = ?");
                $stmt->execute([$link_reunion, $cita_id]);

                Logger::log("Comprador agregó link de reunión a cita ID $cita_id", 'business');

                header("Location: index.php?controlador=comprador&accion=verReuniones&msg=link_agregado");
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

    public function convertirseEnVendedor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $usuario_id = $_SESSION['usuario_id'];

                $this->pdo->beginTransaction();

                // 1. Cambiar roleId en la tabla usuarios a 3 (Proveedor / Vendedor)
                $stmt_user = $this->pdo->prepare("UPDATE usuarios SET roleId = 3 WHERE id = ?");
                $stmt_user->execute([$usuario_id]);

                // 2. Inicializar membresía de vendedor en la tabla empresas
                $stmt_emp = $this->pdo->prepare("
                    UPDATE empresas 
                    SET membresia_plan = 'ninguno', 
                        membresia_estado = 'inactivo', 
                        membresia_vencimiento = NULL 
                    WHERE usuarioId = ?
                ");
                $stmt_emp->execute([$usuario_id]);

                $this->pdo->commit();

                // 3. Actualizar variables de sesión para que el cambio de rol tenga efecto de inmediato
                $_SESSION['roleId'] = 3;
                $_SESSION['slugRole'] = 'proveedor';
                $_SESSION['nombreRole'] = 'Proveedor';

                Logger::log("Usuario ID $usuario_id actualizó su cuenta de Comprador a Vendedor", 'business');

                header("Location: index.php?controlador=vendedor&accion=dashboard&msg=perfil_vendedor_activado");
                exit();
            } catch (Exception $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                Logger::logCurrentRoleError('Error al convertirse en vendedor', [
                    'error' => $e->getMessage()
                ]);
                $error_msg = $e->getMessage();
                require_once '../app/views/layout/error.php';
            }
        }
    }
}
?>
