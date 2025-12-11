-- Agregar columnas estado y fecha_creacion a la tabla horas_extras
-- Este script mejora el seguimiento y control de las horas extras

-- Agregar columna estado (valores posibles: pendiente, aprobado, rechazado)
ALTER TABLE horas_extras 
ADD COLUMN estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente'
AFTER total_devengado_id;

-- Agregar columna fecha_creacion para registrar cuándo se creó el registro
ALTER TABLE horas_extras 
ADD COLUMN fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
AFTER estado;

-- Agregar columna fecha_actualizacion para tracking de cambios
ALTER TABLE horas_extras 
ADD COLUMN fecha_actualizacion DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
AFTER fecha_creacion;

-- Agregar columna para registrar quién aprobó/rechazó (opcional)
ALTER TABLE horas_extras 
ADD COLUMN aprobado_por INT NULL DEFAULT NULL
AFTER fecha_actualizacion;

-- Agregar constraint de clave foránea para aprobado_por
ALTER TABLE horas_extras 
ADD CONSTRAINT fk_horas_extras_aprobador 
FOREIGN KEY (aprobado_por) REFERENCES user(id_user)
ON DELETE SET NULL;

-- Actualizar registros existentes para establecer fecha_creacion
-- (Se establece basándose en la información disponible)
UPDATE horas_extras 
SET fecha_creacion = STR_TO_DATE(CONCAT(anio, '-', LPAD(mes, 2, '0'), '-01'), '%Y-%m-%d')
WHERE fecha_creacion IS NULL OR fecha_creacion = '0000-00-00 00:00:00';

-- Crear índice para mejorar el rendimiento de consultas por estado
CREATE INDEX idx_estado ON horas_extras(estado);

-- Crear índice para mejorar el rendimiento de consultas por fecha
CREATE INDEX idx_fecha_creacion ON horas_extras(fecha_creacion);

SELECT 'Columnas agregadas exitosamente a la tabla horas_extras' AS Resultado;
