-- Agregar columnas faltantes en horas_extras si no existen
ALTER TABLE horas_extras 
ADD COLUMN IF NOT EXISTS fecha_aprobacion DATETIME NULL,
ADD COLUMN IF NOT EXISTS aprobado_por INT NULL;
