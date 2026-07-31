SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- ===============================
-- AGECSO - Views + Triggers
-- Base de datos: agecso_prueba5
-- Generado: 2026-05-08 11:18:51
-- ===============================

-- Recomendación: ejecutar DESPUÉS de crear tablas

-- ===============================
-- VIEWS
-- ===============================


DROP VIEW IF EXISTS `v_empresas_detallado`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_empresas_detallado` AS select `e`.`id` AS `empresa_id`,`e`.`razon_social` AS `razon_social`,`e`.`nit` AS `nit`,`s`.`nombreSector` AS `nombreSector`,`u`.`email` AS `correo_contacto`,(select count(0) from `ofertas` where `ofertas`.`empresaId` = `e`.`id`) AS `total_ofertas`,(select count(0) from `demandas` where `demandas`.`empresaId` = `e`.`id`) AS `total_demandas` from ((`empresas` `e` join `sectores` `s` on(`e`.`sectorId` = `s`.`id`)) join `usuarios` `u` on(`e`.`usuarioId` = `u`.`id`)) where `e`.`deletedAt` is null;

DROP VIEW IF EXISTS `v_impacto_ruedas`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_impacto_ruedas` AS select `r`.`ruedaId` AS `ruedaId`,`rn`.`nombreRueda` AS `tituloRueda`,count(`r`.`id`) AS `citas_totales`,sum(case when `r`.`estadoCita` = 'realizada' then 1 else 0 end) AS `citas_exitosas`,sum(`r`.`montoEstimado`) AS `volumen_negocio_proyectado`,(select avg(`es`.`calificacionGeneral`) from `encuestas_satisfaccion` `es` where `es`.`reunionId` in (select `reuniones`.`id` from `reuniones` where `reuniones`.`ruedaId` = `r`.`ruedaId`)) AS `satisfaccion_promedio` from (`reuniones` `r` join `ruedas_negocios` `rn` on(`r`.`ruedaId` = `rn`.`id`)) group by `r`.`ruedaId`;

DROP VIEW IF EXISTS `v_sectores_ciiu`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_sectores_ciiu` AS select `sectores`.`id` AS `id`,`sectores`.`nombreSector` AS `nombreSector`,`sectores`.`ciiu_clase` AS `ciiu_clase`,concat(`sectores`.`ciiu_clase`,' - ',`sectores`.`nombreSector`) AS `display_text` from `sectores` order by `sectores`.`ciiu_clase`;

DROP VIEW IF EXISTS `v_seguimiento_kpis_detallado`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_seguimiento_kpis_detallado` AS select `rn`.`id` AS `ruedaId`,`rn`.`nombreRueda` AS `tituloRueda`,count(distinct `r`.`id`) AS `total_citas_agendadas`,sum(case when `r`.`estadoCita` = 'realizada' then 1 else 0 end) AS `citas_efectivas`,sum(case when `r`.`estadoCita` = 'realizada' then 1 else 0 end) / count(`r`.`id`) * 100 AS `tasa_asistencia`,sum(`es`.`valorNegocioProyectado`) AS `valor_estimado_negocios`,(select sum(`es2`.`monto_final`) from `encuestas_satisfaccion` `es2` join `reuniones` `r2` on(`es2`.`reunionId` = `r2`.`id`) where `r2`.`ruedaId` = `rn`.`id` and `es2`.`negocio_concretado` = 1) AS `valor_real_generado` from ((`ruedas_negocios` `rn` left join `reuniones` `r` on(`rn`.`id` = `r`.`ruedaId`)) left join `encuestas_satisfaccion` `es` on(`r`.`id` = `es`.`reunionId`)) group by `rn`.`id`;


-- ===============================
-- TRIGGERS
-- ===============================

DELIMITER $$

DROP TRIGGER IF EXISTS `tr_reuniones_after_update_seguimiento`$$
CREATE TRIGGER `tr_reuniones_after_update_seguimiento` AFTER UPDATE ON `reuniones` FOR EACH ROW BEGIN        
    IF NEW.estadoCita = 'realizada' AND OLD.estadoCita != 'realizada' THEN
        
        INSERT INTO `trazabilidad_seguimiento` (reunionId, empresaId, tipo_seguimiento, fecha_programada)
        VALUES (NEW.id, NEW.compradorId, '3_meses', DATE_ADD(NEW.fechaHora, INTERVAL 3 MONTH)),
               (NEW.id, NEW.vendedorId, '3_meses', DATE_ADD(NEW.fechaHora, INTERVAL 3 MONTH));
                                  
        
        INSERT INTO `trazabilidad_seguimiento` (reunionId, empresaId, tipo_seguimiento, fecha_programada)
        VALUES (NEW.id, NEW.compradorId, '6_meses', DATE_ADD(NEW.fechaHora, INTERVAL 6 MONTH)),
               (NEW.id, NEW.vendedorId, '6_meses', DATE_ADD(NEW.fechaHora, INTERVAL 6 MONTH));
                      END IF;
END$$

DROP TRIGGER IF EXISTS `tr_reuniones_bi_concurrencia_inteligente`$$
CREATE TRIGGER `tr_reuniones_bi_concurrencia_inteligente` BEFORE INSERT ON `reuniones` FOR EACH ROW BEGIN    IF EXISTS (SELECT 1 FROM reuniones WHERE vendedorId = NEW.vendedorId AND fechaHora = NEW.fechaHora AND ruedaId = NEW.ruedaId AND estadoCita != 'cancelada') THEN                                                                              SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ERROR: El vendedor ya tiene una cita activa en este horario.';                                              END IF;
 
    IF EXISTS (SELECT 1 FROM reuniones WHERE compradorId = NEW.compradorId AND fechaHora = NEW.fechaHora AND ruedaId = NEW.ruedaId AND estadoCita != 'cancelada') THEN                                                                            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ERROR: El comprador ya tiene una cita activa en este horario.';                                             END IF;
END$$

DROP TRIGGER IF EXISTS `tr_reuniones_bi_validar_roles`$$
CREATE TRIGGER `tr_reuniones_bi_validar_roles` BEFORE INSERT ON `reuniones` FOR EACH ROW BEGIN               DECLARE v_role_comprador TINYINT;
    DECLARE v_role_vendedor TINYINT;
    SELECT id INTO v_role_comprador FROM roles WHERE slugRole = 'comprador' LIMIT 1;
    SELECT id INTO v_role_vendedor FROM roles WHERE slugRole = 'proveedor' LIMIT 1;
                                                                          
    IF (SELECT roleId FROM usuarios WHERE id = (SELECT usuarioId FROM empresas WHERE id = NEW.compradorId)) != v_role_comprador THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ERROR: El ID de comprador no tiene el rol permitido.';
                                                      END IF;
 
    IF (SELECT roleId FROM usuarios WHERE id = (SELECT usuarioId FROM empresas WHERE id = NEW.vendedorId)) != v_role_vendedor THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ERROR: El ID de vendedor no tiene el rol permitido.';
                                                       END IF;
END$$

DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;
