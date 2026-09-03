<?php
require_once __DIR__ . '/../../includes/Logger.php';

class MatchmakingModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Algoritmo de Matchmaking Avanzado: Busca coincidencias inteligentes
     * Incluye: Tags (Relacional), Ubicación (Territorial), Sector y Disponibilidad.
     * @param int $empresaId ID de la empresa
     * @param int $ruedaId ID de la rueda de negocios
     * @return array Sugerencias de matchmaking o array vacío en caso de error
     */
    public function obtenerSugerenciasInteligentes($empresaId, $ruedaId) {
        try {
            // 1. Obtener datos de la empresa actual (para saber si es comprador o vendedor)
            $sqlEmpresa = "SELECT e.*, u.roleId, r.slugRole 
                           FROM empresas e 
                           JOIN usuarios u ON e.usuarioId = u.id 
                           JOIN roles r ON u.roleId = r.id
                           WHERE e.id = ?";
            $stmtEmp = $this->db->prepare($sqlEmpresa);
            $stmtEmp->execute([$empresaId]);
            $miEmpresa = $stmtEmp->fetch();

            if (!$miEmpresa) {
                Logger::logRoleError('system', 'Empresa no encontrada en matchmaking', [
                    'empresaId' => $empresaId,
                    'ruedaId' => $ruedaId
                ]);
                return [];
            }

            $esComprador = ($miEmpresa['slugRole'] == 'comprador');

            // Obtener código CIIU de mi empresa (priorizar personalizado)
            $miCiiuMatch = $miEmpresa['ciiu_personalizado'] ?? '';
            $miSeccion = '';
            
            // Si no tiene personalizado, buscar el de la lista (retrocompatibilidad)
            if (empty($miCiiuMatch)) {
                try {
                    $sqlMiCIIU = "SELECT ciiu_clase, seccion FROM sectores WHERE id = ?";
                    $stmtMiCIIU = $this->db->prepare($sqlMiCIIU);
                    $stmtMiCIIU->execute([$miEmpresa['sectorId']]);
                    $miCIIUData = $stmtMiCIIU->fetch();
                    $miCiiuMatch = $miCIIUData['ciiu_clase'] ?? '';
                    $miSeccion = $miCIIUData['seccion'] ?? '';
                } catch (PDOException $e) {}
            }

            // 2. Base de la consulta según el rol
            if ($esComprador) {
                // Soy comprador, busco ofertas de vendedores
                $sql = "SELECT 
                            o.id AS oferta_demanda_id,
                            o.tituloOferta AS titulo,
                            o.tagsBusqueda AS tags,
                            e.id AS contraparte_id,
                            e.razon_social,
                            e.ubicacionGeografica,
                            e.sectorId,
                            e.ciiu_personalizado,
                            s.nombreSector,
                            s.ciiu_clase,
                            s.seccion,
                            (e.ubicacionGeografica = ?) AS match_territorial
                        FROM ofertas o
                        JOIN empresas e ON o.empresaId = e.id
                        LEFT JOIN sectores s ON e.sectorId = s.id
                        WHERE e.id != ? AND o.isActive = 1
                          AND (o.ruedaId = ? OR o.ruedaId IS NULL)";
                $params = [$miEmpresa['ubicacionGeografica'], $empresaId, $ruedaId];
                
                // Mis tags (demandas)
                $sqlMisTags = "SELECT tagsRequerimiento FROM demandas WHERE empresaId = ?";
            } else {
                // Soy vendedor, busco requerimientos de compradores
                $sql = "SELECT 
                            MAX(d.id) AS oferta_demanda_id,
                            d.tituloDemanda AS titulo,
                            d.tagsRequerimiento AS tags,
                            e.id AS contraparte_id,
                            e.razon_social,
                            e.ubicacionGeografica,
                            e.sectorId,
                            e.ciiu_personalizado,
                            s.nombreSector,
                            s.ciiu_clase,
                            s.seccion,
                            (e.ubicacionGeografica = ?) AS match_territorial
                        FROM demandas d
                        JOIN empresas e ON d.empresaId = e.id
                        LEFT JOIN sectores s ON e.sectorId = s.id
                        WHERE e.id != ? 
                          AND (d.ruedaId = ? OR d.ruedaId IS NULL)
                        GROUP BY e.id, d.tagsRequerimiento, d.tituloDemanda, e.ciiu_personalizado, s.ciiu_clase, s.seccion"; 
                $params = [$miEmpresa['ubicacionGeografica'], $empresaId, $ruedaId];

                // Mis tags (ofertas)
                $sqlMisTags = "SELECT tagsBusqueda FROM ofertas WHERE empresaId = ?";
            }

            $stmtTags = $this->db->prepare($sqlMisTags);
            $stmtTags->execute([$empresaId]);
            $misRegistrosTags = $stmtTags->fetchAll();
            
            $misTagsGlobales = [];
            foreach($misRegistrosTags as $r) {
                $t = json_decode($r[0] ?? '[]', true);
                if(is_array($t)) $misTagsGlobales = array_merge($misTagsGlobales, $t);
            }
            $misTagsGlobales = array_unique($misTagsGlobales);

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $posibles = $stmt->fetchAll();

            $sugerencias = [];
            foreach ($posibles as $p) {
                $tagsContraparte = json_decode($p['tags'] ?? '[]', true);
                $coincidencias = 0;
                $tagsComunes = [];

                if (is_array($tagsContraparte) && !empty($misTagsGlobales)) {
                    $tagsComunes = array_intersect($misTagsGlobales, $tagsContraparte);
                    $coincidencias = count($tagsComunes);
                }

                // Cálculo de Score con CIIU (priorizar personalizado)
                $score = ($coincidencias * 10); // 10 puntos por cada tag común
                if ($p['match_territorial']) $score += 25; // Bono por territorio
                
                $ciiuContraparte = !empty($p['ciiu_personalizado']) ? $p['ciiu_personalizado'] : ($p['ciiu_clase'] ?? '');

                // Bono por código CIIU exacto (misma actividad económica)
                if (!empty($miCiiuMatch) && $ciiuContraparte == $miCiiuMatch) $score += 30;
                // Bono adicional por misma sección CIIU (mismo sector amplio - solo si viene de la lista)
                elseif (!empty($miSeccion) && $p['seccion'] == $miSeccion) $score += 10;

                if ($score > 0) {
                    $p['score'] = $score;
                    $p['tags_comunes'] = $tagsComunes;
                    
                    // Buscar un horario sugerido basado en disponibilidad
                    $p['horario_sugerido'] = $this->sugerirHorarioConDisponibilidad($ruedaId, $empresaId, $p['contraparte_id']);
                    
                    $sugerencias[] = $p;
                }
            }

            usort($sugerencias, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            return $sugerencias;
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error en matchmaking', [
                'empresaId' => $empresaId,
                'ruedaId' => $ruedaId,
                'error' => $e->getMessage()
            ]);
            return [];
        } catch (Exception $e) {
            Logger::logRoleError('system', 'Error inesperado en matchmaking', [
                'empresaId' => $empresaId,
                'ruedaId' => $ruedaId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Sugiere un horario basado en la nueva tabla de disponibilidad_empresas
     * @param int $ruedaId ID de la rueda
     * @param int $miEmpresaId ID de mi empresa
     * @param int $contraparteId ID de la contraparte
     * @return string|null Fecha/hora sugerida o null
     */
    public function sugerirHorarioConDisponibilidad($ruedaId, $miEmpresaId, $contraparteId) {
        try {
            // 1. Obtener disponibilidad de ambas empresas para esta rueda
            $sqlDisp = "SELECT * FROM disponibilidad_empresas 
                        WHERE ruedaId = ? AND empresaId IN (?, ?)";
            $stmtDisp = $this->db->prepare($sqlDisp);
            $stmtDisp->execute([$ruedaId, $miEmpresaId, $contraparteId]);
            $disponibilidades = $stmtDisp->fetchAll();

            if (empty($disponibilidades)) {
                // Si no hay disponibilidad explícita, usamos el método base
                return $this->sugerirHorario($ruedaId, $miEmpresaId, $contraparteId);
            }

            // Lógica simplificada: Buscar el primer slot que coincida en día y esté dentro del rango de ambos
            foreach ($disponibilidades as $d) {
                $fechaHora = $this->buscarSlotLibre($ruedaId, $miEmpresaId, $contraparteId, $d['horaInicio']);
                if ($fechaHora) return $fechaHora;
            }

            return $this->sugerirHorario($ruedaId, $miEmpresaId, $contraparteId);
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error sugiriendo horario con disponibilidad', [
                'ruedaId' => $ruedaId,
                'miEmpresaId' => $miEmpresaId,
                'contraparteId' => $contraparteId,
                'error' => $e->getMessage()
            ]);
            return $this->sugerirHorario($ruedaId, $miEmpresaId, $contraparteId);
        }
    }

    /**
     * Busca un slot libre para ambas empresas
     * @param int $ruedaId ID de la rueda
     * @param int $id1 ID de empresa 1
     * @param int $id2 ID de empresa 2
     * @param string $horaBase Hora base
     * @return string|null Fecha/hora o null
     */
    private function buscarSlotLibre($ruedaId, $id1, $id2, $horaBase) {
        try {
            $sqlRueda = "SELECT fechaInicio FROM ruedas_negocios WHERE id = ?";
            $stmt = $this->db->prepare($sqlRueda);
            $stmt->execute([$ruedaId]);
            $fecha = $stmt->fetchColumn();
            
            if (!$fecha) return null;
            
            // No permitir agendar en días anteriores a hoy
            $hoy = date('Y-m-d');
            if ($fecha < $hoy) {
                $fecha = $hoy;
            }
            
            $fechaHora = $fecha . ' ' . $horaBase;
            if ($this->verificarDisponibilidad($id1, $fechaHora, $ruedaId) && 
                $this->verificarDisponibilidad($id2, $fechaHora, $ruedaId)) {
                return $fechaHora;
            }
            return null;
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error buscando slot libre', [
                'ruedaId' => $ruedaId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Verifica disponibilidad de horario para ambos participantes
     * CON BLOQUEO DE 1 HORA: Si hay una cita a las 10:30, la próxima no puede ser antes de las 11:30
     * @param int $empresaId ID de la empresa
     * @param string $fechaHora Fecha y hora
     * @param int $ruedaId ID de la rueda
     * @return bool True si está disponible, false si no
     */
    public function verificarDisponibilidad($empresaId, $fechaHora, $ruedaId) {
        try {
            // Calcular rango de ±1 hora para bloquear
            $fechaBase = strtotime($fechaHora);
            $horaInicio = date('Y-m-d H:i:s', strtotime('-1 hour', $fechaBase));
            $horaFin = date('Y-m-d H:i:s', strtotime('+1 hour', $fechaBase));
            
            $sql = "SELECT COUNT(*) as ocupado 
                    FROM reuniones 
                    WHERE (vendedorId = ? OR compradorId = ?) 
                    AND fechaHora BETWEEN ? AND ?
                    AND ruedaId = ? 
                    AND estadoCita NOT IN ('cancelada', 'rechazada')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$empresaId, $empresaId, $horaInicio, $horaFin, $ruedaId]);
            $resultado = $stmt->fetch();
            
            return $resultado['ocupado'] == 0;
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error verificando disponibilidad', [
                'empresaId' => $empresaId,
                'fechaHora' => $fechaHora,
                'ruedaId' => $ruedaId,
                'error' => $e->getMessage()
            ]);
            // Por seguridad, asumimos que no está disponible si hay error
            return false;
        }
    }

    /**
     * Auto-finaliza citas cuya fecha + 1 hora ya pasó
     * Las marca como 'realizada' y las mueve al historial
     * @param PDO $pdo Conexión a base de datos
     * @return int Número de citas actualizadas
     */
    public static function autoFinalizarCitasPasadas($pdo) {
        try {
            // Actualizar citas que ya pasaron su fecha + 1 hora de duración
            // y están en estados activos (pendiente, negociando, aceptada, agendada)
            $sql = "
                UPDATE reuniones 
                SET estadoCita = 'realizada',
                    ultimaAccionPor = CASE 
                        WHEN ultimaAccionPor IS NULL THEN 'system' 
                        ELSE ultimaAccionPor 
                    END
                WHERE estadoCita IN ('pendiente', 'negociando', 'aceptada', 'agendada')
                AND DATE_ADD(fechaHora, INTERVAL 1 HOUR) < NOW()
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $afectadas = $stmt->rowCount();
            
            if ($afectadas > 0) {
                Logger::log("Auto-finalizadas $afectadas citas pasadas (fecha + 1h) -> 'realizada'", 'business');
            }
            
            return $afectadas;
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error auto-finalizando citas pasadas', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Sugiere una hora para una reunión entre dos empresas
     * (Simulación simple: busca el primer slot libre en la fecha de la rueda)
     * @param int $ruedaId ID de la rueda
     * @param int $compradorId ID del comprador
     * @param int $vendedorId ID del vendedor
     * @return string|null Fecha/hora sugerida o null
     */
    public function sugerirHorario($ruedaId, $compradorId, $vendedorId) {
        try {
            $sql = "SELECT fechaInicio FROM ruedas_negocios WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ruedaId]);
            $rueda = $stmt->fetch();
            
            if (!$rueda) return null;

            // Horarios base: 8:00 AM a 5:00 PM, cada 30 min
            $slots = ['08:00:00', '08:30:00', '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00', '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00'];
            
            foreach ($slots as $slot) {
                $fechaHora = $rueda['fechaInicio'] . ' ' . $slot;
                if ($this->verificarDisponibilidad($compradorId, $fechaHora, $ruedaId) && 
                    $this->verificarDisponibilidad($vendedorId, $fechaHora, $ruedaId)) {
                    return $fechaHora;
                }
            }
            
            return null;
        } catch (PDOException $e) {
            Logger::logRoleError('system', 'Error sugiriendo horario', [
                'ruedaId' => $ruedaId,
                'compradorId' => $compradorId,
                'vendedorId' => $vendedorId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
?>
