-- Agregar columna faltante en historial_parametros
ALTER TABLE historial_parametros 
ADD COLUMN IF NOT EXISTS actualizado_por INT NULL;
