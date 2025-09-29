-- Tabla para conceptos adicionales deducibles (separada de devengado)
-- Esta tabla será específicamente para descuentos/deducciones adicionales

CREATE TABLE IF NOT EXISTS conceptos_adicionales_deducibles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    empleado_id INT NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    valor DECIMAL(15,2) NOT NULL DEFAULT 0,
    creado_por VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
    INDEX idx_empleado_activo (empleado_id, activo),
    INDEX idx_fecha_creacion (fecha_creacion)
);

-- Comentario para distinguir las tablas
ALTER TABLE conceptos_adicionales_prestaciones 
    COMMENT = 'Conceptos adicionales para TOTAL DEVENGADO (ingresos adicionales)';

ALTER TABLE conceptos_adicionales_deducibles 
    COMMENT = 'Conceptos adicionales para TOTAL DEDUCIDO (descuentos adicionales)';