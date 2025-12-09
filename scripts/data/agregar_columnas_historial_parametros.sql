-- Agregar columnas faltantes en historial_parametros
ALTER TABLE historial_parametros 
ADD COLUMN IF NOT EXISTS accion VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS detalle TEXT NULL;
