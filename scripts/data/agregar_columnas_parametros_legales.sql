-- Agregar columnas faltantes en parametros_legales
ALTER TABLE parametros_legales 
ADD COLUMN IF NOT EXISTS auxilio_transporte DECIMAL(10, 2) NULL,
ADD COLUMN IF NOT EXISTS actualizado_por INT NULL,
ADD COLUMN IF NOT EXISTS fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
