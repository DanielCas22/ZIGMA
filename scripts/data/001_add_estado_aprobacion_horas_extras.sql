-- Añadir columnas para estado y aprobación en horas_extras
ALTER TABLE horas_extras
  ADD COLUMN IF NOT EXISTS estado VARCHAR(45) DEFAULT 'pendiente',
  ADD COLUMN IF NOT EXISTS aprobado_por INT NULL,
  ADD COLUMN IF NOT EXISTS fecha_aprobacion DATETIME NULL,
  ADD COLUMN IF NOT EXISTS comentario_aprobacion TEXT NULL;

-- Índices opcionales
CREATE INDEX IF NOT EXISTS idx_horas_extras_estado ON horas_extras(estado);
CREATE INDEX IF NOT EXISTS idx_horas_extras_aprobado_por ON horas_extras(aprobado_por);
