-- Migración para agregar campos de trazabilidad (3 y 6 meses)
-- Fecha: 2026-05-05

-- 1. Agregar campo tipo_encuesta a la tabla existente
ALTER TABLE encuestas_satisfaccion 
ADD COLUMN IF NOT EXISTS tipo_encuesta ENUM('satisfaccion', 'trazabilidad_3_meses', 'trazabilidad_6_meses') 
DEFAULT 'satisfaccion' 
AFTER reunionId;

-- 2. Crear tabla para programar seguimientos de trazabilidad
CREATE TABLE IF NOT EXISTS trazabilidad_seguimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reunionId INT NOT NULL,
    usuarioId INT NOT NULL,
    tipo ENUM('3_meses', '6_meses') NOT NULL,
    fecha_programada DATE NOT NULL,
    estado ENUM('pendiente', 'completada') DEFAULT 'pendiente',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reunionId) REFERENCES reuniones(id) ON DELETE CASCADE,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_fecha_programada (fecha_programada),
    INDEX idx_estado (estado),
    INDEX idx_reunion_usuario (reunionId, usuarioId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Agregar campos adicionales a encuestas para trazabilidad
ALTER TABLE encuestas_satisfaccion 
ADD COLUMN IF NOT EXISTS negocio_concretado TINYINT(1) DEFAULT 0 COMMENT 'Si el negocio se concretó o no (solo para trazabilidad)',
ADD COLUMN IF NOT EXISTS monto_final DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Monto final del negocio concretado',
ADD COLUMN IF NOT EXISTS fecha_cierre DATE NULL COMMENT 'Fecha de cierre del negocio';
