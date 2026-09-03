-- =====================================================
-- AGECSO - Script SQL Limpio de Estructura y Datos
-- =====================================================
-- Fecha de generación: 2026-05-06
-- Base de datos: agecso_prueba5
-- Motor: MariaDB 10.4.28
-- 
-- INSTRUCCIONES:
-- 1. Crear la base de datos: CREATE DATABASE agecso;
-- 2. Usar la base de datos: USE agecso;
-- 3. Ejecutar este script completo
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET AUTOCOMMIT = 0;

-- =====================================================
-- 1. ESTRUCTURA DE TABLAS
-- =====================================================

-- Tabla: roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreRole` varchar(50) NOT NULL,
  `slugRole` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slugRole` (`slugRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roleId` int(10) UNSIGNED NOT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `roleId` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: sectores
CREATE TABLE IF NOT EXISTS `sectores` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreSector` varchar(200) NOT NULL,
  `ciiu_clase` varchar(4) DEFAULT NULL COMMENT 'Código CIIU v4 a 4 dígitos',
  `descripcion` varchar(500) DEFAULT NULL,
  `categoria_sector` varchar(50) DEFAULT NULL COMMENT 'Clasificación comercial',
  PRIMARY KEY (`id`),
  KEY `idx_ciiu` (`ciiu_clase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuarioId` int(10) UNSIGNED NOT NULL,
  `sectorId` bigint(20) UNSIGNED NOT NULL,
  `ciiu_personalizado` varchar(10) DEFAULT NULL,
  `tipo_persona` enum('natural','juridica','esal_otro') DEFAULT 'juridica',
  `razon_social` varchar(255) DEFAULT NULL,
  `tipo_asociacion` varchar(100) DEFAULT 'S.A.S.',
  `sub_tipo_asociacion` varchar(100) DEFAULT NULL,
  `representante_legal` varchar(255) DEFAULT NULL,
  `nit` varchar(50) DEFAULT NULL,
  `digito_verificacion` char(1) DEFAULT NULL,
  `responsable_iva` tinyint(1) DEFAULT 0,
  `tamaño_empresa` enum('micro','pequeña','mediana','grande') DEFAULT 'micro',
  `slugEmpresa` varchar(255) DEFAULT NULL,
  `verificada` tinyint(1) DEFAULT 0,
  `estado_verificacion` enum('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
  `logoPath` varchar(255) DEFAULT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ubicacionGeografica` varchar(255) DEFAULT NULL COMMENT 'Ciudad/Departamento/Región',
  PRIMARY KEY (`id`),
  KEY `usuarioId` (`usuarioId`),
  KEY `sectorId` (`sectorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: ruedas_negocios
CREATE TABLE IF NOT EXISTS `ruedas_negocios` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombreRueda` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `duracionCitaMinutos` int(11) DEFAULT 30,
  `estadoRueda` enum('planeacion','inscripciones','activa','finalizada','cancelada') DEFAULT 'planeacion',
  `autoActivada` tinyint(1) DEFAULT 0,
  `autoFinalizada` tinyint(1) DEFAULT 0,
  `organizadorId` int(10) UNSIGNED DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `organizadorId` (`organizadorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: inscripciones_ruedas
CREATE TABLE IF NOT EXISTS `inscripciones_ruedas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruedaId` int(10) UNSIGNED NOT NULL,
  `empresaId` int(10) UNSIGNED NOT NULL,
  `estadoInscripcion` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `mensajeMotivo` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_inscripcion` (`ruedaId`,`empresaId`),
  KEY `empresaId` (`empresaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: disponibilidad_empresas
CREATE TABLE IF NOT EXISTS `disponibilidad_empresas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empresaId` int(10) UNSIGNED NOT NULL,
  `ruedaId` int(10) UNSIGNED NOT NULL,
  `diaSemana` tinyint(1) DEFAULT NULL COMMENT '1=Lunes, ..., 7=Domingo',
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `empresaId` (`empresaId`),
  KEY `ruedaId` (`ruedaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: ofertas
CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empresaId` int(10) UNSIGNED NOT NULL,
  `ruedaId` int(10) UNSIGNED DEFAULT NULL,
  `sectorId` bigint(20) UNSIGNED DEFAULT NULL,
  `tituloOferta` varchar(255) NOT NULL,
  `descripcionOferta` text DEFAULT NULL,
  `tagsBusqueda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tagsBusqueda`)),
  `isActive` tinyint(1) DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `empresaId` (`empresaId`),
  KEY `ruedaId` (`ruedaId`),
  KEY `sectorId` (`sectorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: demandas
CREATE TABLE IF NOT EXISTS `demandas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `empresaId` int(10) UNSIGNED NOT NULL,
  `ruedaId` int(10) UNSIGNED DEFAULT NULL,
  `tituloDemanda` varchar(255) NOT NULL,
  `descripcionDemanda` text DEFAULT NULL,
  `tagsRequerimiento` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tagsRequerimiento`)),
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `empresaId` (`empresaId`),
  KEY `ruedaId` (`ruedaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: reuniones
CREATE TABLE IF NOT EXISTS `reuniones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `compradorId` int(10) UNSIGNED NOT NULL,
  `vendedorId` int(10) UNSIGNED NOT NULL,
  `ruedaId` int(10) UNSIGNED NOT NULL,
  `fechaHora` datetime NOT NULL,
  `estadoCita` enum('propuesta','confirmada','realizada','cancelada','finalizada','calificada') DEFAULT 'propuesta',
  `motivoCancelacion` text DEFAULT NULL,
  `montoEstimado` decimal(15,2) DEFAULT NULL,
  `resultadoCita` text DEFAULT NULL,
  `compradorCalifico` tinyint(1) DEFAULT 0,
  `vendedorCalifico` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `compradorId` (`compradorId`),
  KEY `vendedorId` (`vendedorId`),
  KEY `ruedaId` (`ruedaId`),
  KEY `estadoCita` (`estadoCita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: reunion_negociaciones
CREATE TABLE IF NOT EXISTS `reunion_negociaciones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reunionId` bigint(20) UNSIGNED NOT NULL,
  `montoPropuesto` decimal(15,2) NOT NULL,
  `notasPropuesta` text DEFAULT NULL,
  `propuestoPor` enum('comprador','vendedor') NOT NULL,
  `estadoPropuesta` enum('pendiente','aceptada','rechazada','contraoferta') DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reunionId` (`reunionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: encuestas_satisfaccion
CREATE TABLE IF NOT EXISTS `encuestas_satisfaccion` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reunionId` bigint(20) UNSIGNED NOT NULL,
  `tipo_encuesta` enum('satisfaccion','trazabilidad_3_meses','trazabilidad_6_meses') DEFAULT 'satisfaccion',
  `usuarioId` int(10) UNSIGNED NOT NULL,
  `asistenciaConfirmada` tinyint(1) DEFAULT 0,
  `calificacionGeneral` tinyint(3) UNSIGNED DEFAULT NULL CHECK (`calificacionGeneral` between 1 and 5),
  `expectativaNegocio` enum('inmediato','corto_plazo','mediano_plazo','ninguno') DEFAULT NULL,
  `valorNegocioProyectado` decimal(15,2) DEFAULT 0.00,
  `interesSeguimiento` tinyint(1) DEFAULT 1,
  `comentarios` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `efectividadCita` tinyint(1) DEFAULT 0 COMMENT '¿Fue una cita efectiva?',
  `asistenciaCompleta` tinyint(1) DEFAULT 1,
  `negocio_concretado` tinyint(1) DEFAULT 0,
  `monto_final` decimal(15,2) DEFAULT 0.00,
  `fecha_cierre` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reunionId` (`reunionId`),
  KEY `usuarioId` (`usuarioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: trazabilidad_seguimiento
CREATE TABLE IF NOT EXISTS `trazabilidad_seguimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reunionId` bigint(20) UNSIGNED NOT NULL,
  `empresaId` int(10) UNSIGNED NOT NULL,
  `tipo_seguimiento` enum('3_meses','6_meses') NOT NULL,
  `fecha_programada` date NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `encuestaId` bigint(20) UNSIGNED DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `completedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reunionId` (`reunionId`),
  KEY `empresaId` (`empresaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: kpi_ruedas
CREATE TABLE IF NOT EXISTS `kpi_ruedas` (
  `ruedaId` int(10) UNSIGNED NOT NULL,
  `totalCitas` int(10) UNSIGNED DEFAULT 0,
  `totalMontoGenerado` decimal(18,2) DEFAULT 0.00,
  `reunionesExitosas` int(10) UNSIGNED DEFAULT 0,
  `lastUpdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ruedaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: ciiu_mapeo_temporal (mapeo de sectores)
CREATE TABLE IF NOT EXISTS `ciiu_mapeo_temporal` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sector_id_original` bigint(20) UNSIGNED NOT NULL,
  `ciiu_clase` varchar(4) NOT NULL,
  `es_categoria_comercial` tinyint(1) DEFAULT 0,
  `descripcion` varchar(200) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. DATOS ESENCIALES (INSERTS)
-- =====================================================

-- Roles del sistema
INSERT INTO `roles` (`nombreRole`, `slugRole`, `descripcion`) VALUES
('Super Administrador', 'superadmin', 'Control total del sistema'),
('Administrador', 'admin', 'Gestión de ruedas de negocios'),
('Comprador', 'comprador', 'Empresa compradora en ruedas'),
('Vendedor', 'vendedor', 'Empresa proveedora en ruedas');

-- Usuario admin por defecto (password: 'admin123' - cambiar en producción)
-- Hash generado con password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO `usuarios` (`nombreUsuario`, `email`, `password`, `roleId`, `isActive`) VALUES
('Administrador AGECSO', 'admin@agecso.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1);

-- =====================================================
-- 3. FOREIGN KEYS (Constraints)
-- =====================================================

-- Tabla usuarios
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);

-- Tabla empresas
ALTER TABLE `empresas`
  ADD CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `empresas_ibfk_2` FOREIGN KEY (`sectorId`) REFERENCES `sectores` (`id`);

-- Tabla ruedas_negocios
ALTER TABLE `ruedas_negocios`
  ADD CONSTRAINT `ruedas_negocios_ibfk_1` FOREIGN KEY (`organizadorId`) REFERENCES `usuarios` (`id`);

-- Tabla inscripciones_ruedas
ALTER TABLE `inscripciones_ruedas`
  ADD CONSTRAINT `inscripciones_ruedas_ibfk_1` FOREIGN KEY (`ruedaId`) REFERENCES `ruedas_negocios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_ruedas_ibfk_2` FOREIGN KEY (`empresaId`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

-- Tabla disponibilidad_empresas
ALTER TABLE `disponibilidad_empresas`
  ADD CONSTRAINT `fk_disponibilidad_empresa` FOREIGN KEY (`empresaId`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_disponibilidad_rueda` FOREIGN KEY (`ruedaId`) REFERENCES `ruedas_negocios` (`id`) ON DELETE CASCADE;

-- Tabla ofertas
ALTER TABLE `ofertas`
  ADD CONSTRAINT `ofertas_ibfk_1` FOREIGN KEY (`empresaId`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ofertas_ibfk_2` FOREIGN KEY (`ruedaId`) REFERENCES `ruedas_negocios` (`id`) ON DELETE CASCADE;

-- Tabla demandas
ALTER TABLE `demandas`
  ADD CONSTRAINT `demandas_ibfk_1` FOREIGN KEY (`empresaId`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandas_ibfk_2` FOREIGN KEY (`ruedaId`) REFERENCES `ruedas_negocios` (`id`) ON DELETE CASCADE;

-- Tabla reuniones
ALTER TABLE `reuniones`
  ADD CONSTRAINT `reuniones_ibfk_1` FOREIGN KEY (`compradorId`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `reuniones_ibfk_2` FOREIGN KEY (`vendedorId`) REFERENCES `empresas` (`id`);

-- Tabla reunion_negociaciones
ALTER TABLE `reunion_negociaciones`
  ADD CONSTRAINT `reunion_negociaciones_ibfk_1` FOREIGN KEY (`reunionId`) REFERENCES `reuniones` (`id`) ON DELETE CASCADE;

-- Tabla encuestas_satisfaccion
ALTER TABLE `encuestas_satisfaccion`
  ADD CONSTRAINT `encuestas_satisfaccion_ibfk_1` FOREIGN KEY (`reunionId`) REFERENCES `reuniones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encuestas_satisfaccion_ibfk_2` FOREIGN KEY (`usuarioId`) REFERENCES `usuarios` (`id`);

-- Tabla kpi_ruedas
ALTER TABLE `kpi_ruedas`
  ADD CONSTRAINT `kpi_ruedas_ibfk_1` FOREIGN KEY (`ruedaId`) REFERENCES `ruedas_negocios` (`id`) ON DELETE CASCADE;

-- Tabla trazabilidad_seguimiento
ALTER TABLE `trazabilidad_seguimiento`
  ADD CONSTRAINT `fk_trazabilidad_reunion` FOREIGN KEY (`reunionId`) REFERENCES `reuniones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_trazabilidad_empresa` FOREIGN KEY (`empresaId`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

-- =====================================================
-- FINALIZAR
-- =====================================================

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- =====================================================
-- DATOS DE PRUEBA (Opcional - Descomentar si se necesitan)
-- =====================================================
/*
-- Insertar sectores de ejemplo
INSERT INTO `sectores` (`nombreSector`, `ciiu_clase`, `descripcion`, `categoria_sector`) VALUES
('Tecnología de la Información', '6201', 'Desarrollo de software y consultoría', 'Tecnología'),
('Comercio Mayorista', '4610', 'Comercio al por mayor', 'Comercio'),
('Manufactura', '1011', 'Procesamiento y conservación de alimentos', 'Manufactura');

-- Insertar empresas de ejemplo
INSERT INTO `usuarios` (`nombreUsuario`, `email`, `password`, `roleId`) VALUES
('Tech Solutions', 'tech@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4);

INSERT INTO `empresas` (`usuarioId`, `sectorId`, `tipo_persona`, `razon_social`, `nit`, `estado_verificacion`) VALUES
(LAST_INSERT_ID(), 1, 'juridica', 'Tech Solutions S.A.S.', '900123456-1', 'aprobada');
*/
