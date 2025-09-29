-- Tabla para conceptos adicionales en prestaciones sociales
CREATE TABLE IF NOT EXISTS conceptos_adicionales_prestaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    descripcion TEXT,
    valor DECIMAL(15,2) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    creado_por VARCHAR(100),
    INDEX idx_empleado_id (empleado_id),
    FOREIGN KEY (empleado_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Datos de ejemplo
INSERT INTO conceptos_adicionales_prestaciones (empleado_id, concepto, descripcion, valor, creado_por) VALUES 
(1, 'Bonificación Especial', 'Bonificación por desempeño excepcional en el proyecto XYZ', 500000.00, 'admin'),
(2, 'Auxilio Educativo', 'Apoyo para estudios técnicos', 300000.00, 'admin'),
(3, 'Compensación Extra', 'Compensación por horas adicionales no registradas', 200000.00, 'admin');