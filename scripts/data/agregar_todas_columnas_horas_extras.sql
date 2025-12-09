-- Agregar todas las columnas faltantes en horas_extras
ALTER TABLE horas_extras 
ADD COLUMN IF NOT EXISTS fecha_aprobacion DATETIME NULL,
ADD COLUMN IF NOT EXISTS aprobado_por INT NULL,
ADD COLUMN IF NOT EXISTS comentario_aprobacion TEXT NULL;
