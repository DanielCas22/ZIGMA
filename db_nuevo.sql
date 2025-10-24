
DROP DATABASE IF EXISTS zigmaog;
CREATE DATABASE zigmaog;
USE zigmaog;

-- Tabla de roles
CREATE TABLE rol (
  id_rol INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  apellido VARCHAR(45)
);

-- Tabla de empleados
CREATE TABLE empleados (
  id_empleados INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  apellido VARCHAR(45),
  usuario VARCHAR(45),
  contrasena VARCHAR(255),
  sueldo_actual INT
);

-- Tabla de usuarios
CREATE TABLE user (
  id_doc INT PRIMARY KEY AUTO_INCREMENT,
  tipo_doc VARCHAR(45),
  num_doc BIGINT,
  empleado_id INT,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados)
);

-- Relación user-rol
CREATE TABLE user_rol (
  user_id INT NOT NULL,
  rol_id INT NOT NULL,
  PRIMARY KEY (user_id, rol_id),
  FOREIGN KEY (user_id) REFERENCES user(id_doc),
  FOREIGN KEY (rol_id) REFERENCES rol(id_rol)
);

-- Tabla de nomina
CREATE TABLE nomina (
  id_nomina INT PRIMARY KEY AUTO_INCREMENT,
  anio VARCHAR(45),
  mes VARCHAR(45),
  dia VARCHAR(45),
  valor_pagar INT,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES user(id_doc)
);

-- Tabla de total_devengado
CREATE TABLE total_devengado (
  id_total_devengado INT PRIMARY KEY AUTO_INCREMENT,
  salario INT,
  dias VARCHAR(45),
  total INT,
  nomina_id INT,
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Tabla de total_deducido
CREATE TABLE total_deducido (
  id_total_deducido INT PRIMARY KEY AUTO_INCREMENT,
  salario INT,
  valor INT,
  otros VARCHAR(45),
  total INT,
  nomina_id INT,
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Tabla de prestaciones sociales
CREATE TABLE prestaciones_sociales (
  id_prestaciones INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  total VARCHAR(45),
  total_devengado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de intereses
CREATE TABLE intereses (
  id_intereses INT PRIMARY KEY AUTO_INCREMENT,
  total INT,
  prestaciones_id INT,
  FOREIGN KEY (prestaciones_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de cesantias
CREATE TABLE cesantias (
  id_cesantias INT PRIMARY KEY AUTO_INCREMENT,
  tipo VARCHAR(45),
  prestaciones_id INT,
  FOREIGN KEY (prestaciones_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de vacaciones
CREATE TABLE vacaciones (
  id_vacaciones INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  prestaciones_id INT,
  FOREIGN KEY (prestaciones_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de prima
CREATE TABLE prima (
  id_prima INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  prestaciones_id INT,
  FOREIGN KEY (prestaciones_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de comisiones
CREATE TABLE comisiones (
  id_comisiones INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  mes VARCHAR(45),
  anio VARCHAR(45),
  total_devengado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de horas extras
CREATE TABLE horas_extras (
  id_extras INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  cantidad INT NOT NULL,
  tipo VARCHAR(45),
  porcentaje DECIMAL(5,2) NOT NULL DEFAULT 0,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  empleado_id INT,
  aprobado TINYINT(1) DEFAULT 0,
  total_devengado_id INT,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados),
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de auxilio_transporte
CREATE TABLE auxilio_transporte (
  id_transporte INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  total_devengado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de parafiscales
CREATE TABLE parafiscales (
  id_parafiscales INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  total_devengado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de compensacion
CREATE TABLE compensacion (
  id_compensacion INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  parafiscales_id INT,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de sena
CREATE TABLE sena (
  id_sena INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45),
  valor INT,
  parafiscales_id INT,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de icbf
CREATE TABLE icbf (
  id_icbf INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45),
  mes VARCHAR(45),
  dia VARCHAR(45),
  anio VARCHAR(45),
  valor INT,
  parafiscales_id INT,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de seguridad social
CREATE TABLE seguridad_social (
  idseguridad INT PRIMARY KEY AUTO_INCREMENT,
  total INT,
  total_devengado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de pension
CREATE TABLE pension (
  id_pension INT PRIMARY KEY AUTO_INCREMENT,
  valor VARCHAR(45),
  seguridad_id INT,
  FOREIGN KEY (seguridad_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de arl
CREATE TABLE arl (
  id_arl INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  seguridad_id INT,
  FOREIGN KEY (seguridad_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de salud
CREATE TABLE salud (
  id_salud INT PRIMARY KEY AUTO_INCREMENT,
  valor VARCHAR(45),
  seguridad_id INT,
  FOREIGN KEY (seguridad_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de solidaridad
CREATE TABLE solidaridad (
  id_solidaridad INT PRIMARY KEY AUTO_INCREMENT,
  sueldo INT,
  valor VARCHAR(45),
  total_deducido_id INT,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de retencion_fuente
CREATE TABLE retencion_fuente (
  id_retencion INT PRIMARY KEY AUTO_INCREMENT,
  sueldo INT,
  limite_30_salario VARCHAR(45),
  promedio_anio_anterior_salud VARCHAR(45),
  aporte_afc VARCHAR(45),
  certificado_dependientes VARCHAR(45),
  salud_prepagados VARCHAR(45),
  pago_interes_vivienda VARCHAR(45),
  rentas_extensas VARCHAR(45),
  pension_voluntaria VARCHAR(45),
  afc VARCHAR(45),
  subtotal_1 VARCHAR(45),
  dependientes_uvt_32 VARCHAR(45),
  salud_prepagada_16_uvt VARCHAR(45),
  intereses_vivienda_100_uvt VARCHAR(45),
  subtotal_2 VARCHAR(45),
  renta_exenta VARCHAR(45),
  base_retencion VARCHAR(45),
  base_retencion_uvt VARCHAR(45),
  retencion_art833 VARCHAR(45),
  total_deducido_id INT,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de gestion_reportes
CREATE TABLE gestion_reportes (
  id_reportes INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45),
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  nomina_id INT,
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Tabla de desprendible_nomina
CREATE TABLE desprendible_nomina (
  id_desprendible INT PRIMARY KEY AUTO_INCREMENT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  nomina_id INT,
  empleado_id INT,
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina),
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados)
);

-- Tabla de rangos_uvt
CREATE TABLE rangos_uvt (
  idrangosuvt INT PRIMARY KEY AUTO_INCREMENT,
  numero INT,
  desde VARCHAR(45),
  hasta VARCHAR(45)
);

-- Parámetros del sistema
CREATE TABLE IF NOT EXISTS parametros (
  clave VARCHAR(64) PRIMARY KEY,
  valor INT NOT NULL
);

INSERT INTO parametros (clave, valor) VALUES
('SMLV', 1300000),
('AUXILIO_TRANSPORTE', 162000),
('UVT', 47065)
ON DUPLICATE KEY UPDATE valor = VALUES(valor);

-- =========================
-- DATOS DE EJEMPLO INICIALES
-- =========================

INSERT INTO rol (nombre, apellido) VALUES 
('admin', NULL), 
('rrhh', NULL), 
('empleado', NULL);

INSERT INTO empleados (nombre, apellido, usuario, contrasena, sueldo_actual) VALUES 
('Administrador', 'Sistema', 'admin', '$2y$10$1uIHpk3HVxppsInwqpofbexCSv18ou7J3VVh6Aq03Wpxpt9CXiO6a', 5000000),
('Coordinador', 'RRHH', 'rrhh', '$2y$10$u4InYXHoARGT6FU/tjcaM.LOGrOnTbv0bTmc16TVMSrKOTHm1SHFy', 3500000),
('Empleado', 'General', 'empleado', '$2y$10$k2Z/bmGFazE8lPYK8LpNxe8bbh4zCVsu5qi2L5QT8dJYAzoIOhfqS', 2000000);

INSERT INTO user (tipo_doc, num_doc, empleado_id) VALUES
('CC', 10000001, 1),
('CC', 10000002, 2),
('CC', 10000003, 3);

INSERT INTO user_rol (user_id, rol_id) VALUES
(1, 1),
(2, 2),
(3, 3);