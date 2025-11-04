-- Agregar columna fecha_creacion a horas_extras si no existe
ALTER TABLE horas_extras
    ADD COLUMN IF NOT EXISTS fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Agregar índice opcional para ordenamiento/consultas
CREATE INDEX IF NOT EXISTS idx_horas_extras_fecha_creacion ON horas_extras(fecha_creacion);
