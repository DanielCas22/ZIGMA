-- Tabla para gestionar plazos en conceptos adicionales
CREATE TABLE IF NOT EXISTS conceptos_adicionales_plazos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    concepto_id INT NOT NULL,
    periodo_numero INT NOT NULL COMMENT 'Número de período (1, 2, 3, etc)',
    valor_periodo DECIMAL(15,2) NOT NULL COMMENT 'Valor a cancelar en este período',
    tipo_periodo ENUM('quincena', 'mes') NOT NULL DEFAULT 'quincena' COMMENT 'Tipo de período',
    fecha_vencimiento DATE,
    estado ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_concepto_id (concepto_id),
    FOREIGN KEY (concepto_id) REFERENCES conceptos_adicionales_prestaciones(id) ON DELETE CASCADE
);

-- Agregar columnas a conceptos_adicionales_prestaciones para soportar plazos
ALTER TABLE conceptos_adicionales_prestaciones 
ADD COLUMN IF NOT EXISTS total_plazos INT DEFAULT 1 COMMENT 'Cantidad de plazos en que se divide el pago',
ADD COLUMN IF NOT EXISTS tipo_plazo ENUM('quincena', 'mes') DEFAULT 'quincena' COMMENT 'Tipo de plazo',
ADD COLUMN IF NOT EXISTS tiene_plazo BOOLEAN DEFAULT FALSE COMMENT 'Si el concepto tiene plazo o es de pago único';
