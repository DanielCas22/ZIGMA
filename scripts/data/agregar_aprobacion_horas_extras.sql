-- Script para agregar sistema de aprobación de horas extras
-- Ejecutar en MySQL/phpMyAdmin

ALTER TABLE horas_extras 
ADD COLUMN estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente' AFTER valor,
ADD COLUMN fecha_aprobacion DATETIME NULL AFTER estado,
ADD COLUMN aprobado_por INT NULL AFTER fecha_aprobacion,
ADD COLUMN comentario_aprobacion TEXT NULL AFTER aprobado_por,
ADD CONSTRAINT fk_horas_extras_aprobado_por 
    FOREIGN KEY (aprobado_por) REFERENCES user(id_doc) ON DELETE SET NULL;

-- Actualizar horas extras existentes como aprobadas (para mantener compatibilidad)
UPDATE horas_extras SET estado = 'aprobada' WHERE estado IS NULL OR estado = 'pendiente';

-- Índice para mejorar consultas de aprobación
CREATE INDEX idx_horas_extras_estado ON horas_extras(estado);
CREATE INDEX idx_horas_extras_empleado_estado ON horas_extras(empleado_id, estado);
