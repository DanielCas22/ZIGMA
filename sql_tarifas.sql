-- Script para agregar tabla de tarifas de horas extras
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

USE zigmaog;

-- Crear/ajustar tabla para configuración de tarifas (compatible con el modelo TarifaHora)
CREATE TABLE IF NOT EXISTS tarifas_horas (
    id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    valor_hora DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255) NULL,
    nombre_periodo VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Limpiar datos previos opcionalmente
-- DELETE FROM tarifas_horas;

-- Insertar tarifas de Colombia 2025 (compatibles con el modelo)
INSERT INTO tarifas_horas (fecha_inicio, fecha_fin, valor_hora, descripcion, nombre_periodo) VALUES
('2025-01-01', '2025-07-14', 6189.00, 'Tarifa base primer período 2025', 'Primer período 2025'),
('2025-07-15', '2025-12-31', 6470.00, 'Tarifa base segundo período 2025', 'Segundo período 2025');

-- Crear/ajustar tabla para tipos de horas extras con sus porcentajes (compatible con el modelo TipoHoraExtra)
CREATE TABLE IF NOT EXISTS tipos_horas_extras (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    porcentaje DECIMAL(5,2) NOT NULL,
    descripcion TEXT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Limpiar datos previos opcionalmente
-- DELETE FROM tipos_horas_extras;

-- Insertar tipos de horas extras según legislación colombiana
INSERT INTO tipos_horas_extras (nombre, porcentaje, descripcion) VALUES
('Extra diurna', 25.00, 'Horas extras en horario diurno (6:00 AM - 10:00 PM)'),
('Extra nocturna', 75.00, 'Horas extras en horario nocturno (10:00 PM - 6:00 AM)'),
('Extra diurna dominical/festiva', 105.00, 'Horas extras diurnas en domingos o festivos'),
('Extra nocturna dominical/festiva', 155.00, 'Horas extras nocturnas en domingos o festivos');