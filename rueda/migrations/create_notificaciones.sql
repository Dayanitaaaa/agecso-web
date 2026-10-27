-- =====================================================
-- Migration: Crear tabla de notificaciones
-- Fecha: 2026-10-27
-- =====================================================

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuarioId` int(10) UNSIGNED NOT NULL COMMENT 'Usuario destinatario de la notificación',
  `tipo` varchar(50) NOT NULL COMMENT 'Tipo de notificación: nueva_inscripcion, nueva_demanda, nueva_oferta, cita_agendada, cita_aceptada, cita_rechazada, encuesta_completada',
  `titulo` varchar(255) NOT NULL COMMENT 'Título corto de la notificación',
  `mensaje` text NOT NULL COMMENT 'Mensaje completo de la notificación',
  `link_destino` varchar(255) NULL COMMENT 'Enlace para redirigir al hacer clic',
  `leida` tinyint(1) DEFAULT 0 COMMENT '0 = no leída, 1 = leída',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  KEY `leida` (`leida`),
  KEY `tipo` (`tipo`),
  KEY `createdAt` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notificaciones del sistema para usuarios';
