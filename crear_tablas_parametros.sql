-- Crear tablas para el sistema de parámetros legales y configuraciones

-- Tabla de parámetros legales (SMLV, Auxilio de Transporte)
CREATE TABLE IF NOT EXISTS parametros_legales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
  auxilio_transporte DECIMAL(10, 2) NOT NULL DEFAULT 0,
  año_vigencia INT NOT NULL UNIQUE,
  actualizado_por INT,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
);

-- Tabla de rangos para fondo de solidaridad pensional
CREATE TABLE IF NOT EXISTS rangos_fondo_solidaridad (
  id INT PRIMARY KEY AUTO_INCREMENT,
  desde_smlv DECIMAL(10, 2) NOT NULL,
  hasta_smlv DECIMAL(10, 2) NOT NULL,
  porcentaje DECIMAL(5, 2) NOT NULL
);

-- Tabla de retención en la fuente
CREATE TABLE IF NOT EXISTS tabla_retencion_fuente (
  id INT PRIMARY KEY AUTO_INCREMENT,
  desde_uvt DECIMAL(10, 2) NOT NULL,
  hasta_uvt DECIMAL(10, 2) NOT NULL,
  porcentaje DECIMAL(5, 2) NOT NULL
);

-- Tabla de historial de cambios en parámetros
CREATE TABLE IF NOT EXISTS historial_parametros (
  id INT PRIMARY KEY AUTO_INCREMENT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  actualizado_por INT,
  accion VARCHAR(255) NOT NULL,
  detalle TEXT,
  FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
);

-- Tabla de parámetros de aportes y parafiscales
CREATE TABLE IF NOT EXISTS parametros_aportes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  salud_empleador DECIMAL(5, 2) NOT NULL DEFAULT 8.5,
  salud_empleado DECIMAL(5, 2) NOT NULL DEFAULT 4.0,
  pension_empleador DECIMAL(5, 2) NOT NULL DEFAULT 12.0,
  pension_empleado DECIMAL(5, 2) NOT NULL DEFAULT 4.0,
  parafiscales DECIMAL(5, 2) NOT NULL DEFAULT 9.0,
  sena DECIMAL(5, 2) NOT NULL DEFAULT 2.0,
  icbf DECIMAL(5, 2) NOT NULL DEFAULT 3.0,
  prestaciones DECIMAL(5, 2) NOT NULL DEFAULT 21.83,
  actualizado_por INT,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
);

-- Insertar datos iniciales para parámetros legales 2025
INSERT INTO parametros_legales (smlv, auxilio_transporte, año_vigencia) 
VALUES (1300000, 162000, 2025)
ON DUPLICATE KEY UPDATE id=id;

-- Insertar rangos de fondo de solidaridad pensional
INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
(4, 16, 1.0),
(16, 17, 1.2),
(17, 18, 1.4),
(18, 19, 1.6),
(19, 20, 1.8),
(20, 999, 2.0)
ON DUPLICATE KEY UPDATE id=id;

-- Insertar tabla de retención en la fuente
INSERT INTO tabla_retencion_fuente (desde_uvt, hasta_uvt, porcentaje) VALUES
(0, 95, 0),
(95, 150, 19),
(150, 360, 28),
(360, 640, 33),
(640, 945, 35),
(945, 2300, 37),
(2300, 999999, 39)
ON DUPLICATE KEY UPDATE id=id;

-- Insertar parámetros de aportes por defecto
INSERT INTO parametros_aportes (salud_empleador, salud_empleado, pension_empleador, pension_empleado, parafiscales, sena, icbf, prestaciones) 
VALUES (8.5, 4.0, 12.0, 4.0, 9.0, 2.0, 3.0, 21.83)
ON DUPLICATE KEY UPDATE id=id;

SELECT 'Tablas de parámetros creadas exitosamente' AS Resultado;
