-- =====================================================
-- Migration: Agregar campos de contacto a tabla empresas
-- Fecha: 2026-10-26
-- =====================================================

-- Agregar campos de contacto a la tabla empresas
ALTER TABLE empresas
ADD COLUMN telefono_contacto VARCHAR(50) NULL COMMENT 'Teléfono de contacto de la empresa',
ADD COLUMN pagina_web VARCHAR(255) NULL COMMENT 'Página web de la empresa';

-- Crear índices para búsquedas futuras
CREATE INDEX idx_telefono ON empresas(telefono_contacto);
CREATE INDEX idx_pagina_web ON empresas(pagina_web);
