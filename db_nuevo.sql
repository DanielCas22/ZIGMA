-- Base de datos: zigmaog
DROP DATABASE IF EXISTS zigmaog;
CREATE DATABASE zigmaog;
USE zigmaog;



-- Estructura de tablas y relaciones normalizadas

-- Tabla de roles
CREATE TABLE rol (
  id_rol INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL
);

-- Tabla de empleados
CREATE TABLE empleados (
  id_empleados INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  apellido VARCHAR(45) NOT NULL,
  usuario VARCHAR(45),
  contrasena VARCHAR(255),
  sueldo_actual INT
);

-- Tabla de usuarios
CREATE TABLE user (
  id_doc INT PRIMARY KEY AUTO_INCREMENT,
  tipo_doc VARCHAR(45),
  num_doc VARCHAR(45),
  username VARCHAR(45) UNIQUE,
  password VARCHAR(255),
  empleado_id INT,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados)
);

-- Relación usuario-rol (muchos a muchos)
CREATE TABLE rol_has_user (
  user_id INT NOT NULL,
  rol_id INT NOT NULL,
  PRIMARY KEY (user_id, rol_id),
  FOREIGN KEY (user_id) REFERENCES user(id_doc),
  FOREIGN KEY (rol_id) REFERENCES rol(id_rol)
);

-- Tabla de total devengado
CREATE TABLE total_devengado (
  id_total_devengado INT PRIMARY KEY AUTO_INCREMENT,
  salario INT,
  dias VARCHAR(45),
  total INT
);

-- Tabla de total deducido
CREATE TABLE total_deducido (
  id_total_deducido INT PRIMARY KEY AUTO_INCREMENT,
  salario INT,
  valor INT,
  otros VARCHAR(45),
  total INT
);

-- Tabla de nómina
CREATE TABLE nomina (
  id_nomina INT PRIMARY KEY AUTO_INCREMENT,
  anio VARCHAR(45),
  mes VARCHAR(45),
  dia VARCHAR(45),
  valor_pagar INT,
  total_devengado_id INT,
  total_deducido_id INT,
  empleado_id INT,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado),
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido),
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados)
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
  prestaciones_sociales_id INT,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de cesantias
CREATE TABLE cesantias (
  id_cesantias INT PRIMARY KEY AUTO_INCREMENT,
  tipo VARCHAR(45),
  prestaciones_sociales_id INT,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de vacaciones
CREATE TABLE vacaciones (
  id_vacaciones INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  prestaciones_sociales_id INT,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de prima
CREATE TABLE prima (
  id_prima INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  prestaciones_sociales_id INT,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
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
  empleado_id INT,
  valor INT,
  cantidad VARCHAR(45),
  tipo VARCHAR(45),
  porcentaje VARCHAR(45),
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  total_devengado_id INT,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados),
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de auxilio de transporte
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

-- Tabla de salud
CREATE TABLE salud (
  id_salud INT PRIMARY KEY AUTO_INCREMENT,
  valor VARCHAR(45),
  seguridad_social_id INT,
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de pension
CREATE TABLE pension (
  id_pension INT PRIMARY KEY AUTO_INCREMENT,
  valor VARCHAR(45),
  seguridad_social_id INT,
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de arl
CREATE TABLE arl (
  id_arl INT PRIMARY KEY AUTO_INCREMENT,
  valor INT,
  seguridad_social_id INT,
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de solidaridad
CREATE TABLE solidaridad (
  id_solidaridad INT PRIMARY KEY AUTO_INCREMENT,
  sueldo INT,
  valor VARCHAR(45),
  total_deducido_id INT,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de retencion fuente
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

-- Tabla de gestion de reportes
CREATE TABLE gestion_reportes (
  id_reportes INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45),
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  nomina_id INT,
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Tabla de desprendible de nomina
CREATE TABLE desprendible_nomina (
  id_desprendible INT PRIMARY KEY AUTO_INCREMENT,
  dia VARCHAR(45),
  mes VARCHAR(45),
  anio VARCHAR(45),
  empleado_id INT,
  nomina_id INT,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados),
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Insertar roles
INSERT INTO rol (nombre) VALUES 
('admin'), 
('rrhh'), 
('empleado');

-- Insertar empleados (nombre, apellido)
INSERT INTO empleados (nombre, apellido) VALUES 
('Administrador', 'del Sistema'),
('Coordinador', 'RRHH'),
('Empleado', 'General'),
('Juan', 'Pérez'),
('María', 'Gómez'),
('Carlos', 'Ramírez'),
('Ana', 'Torres'),
('Luis', 'Fernández');

-- Insertar usuarios con contraseñas hasheadas correctamente
INSERT INTO user (username, password, empleado_id) VALUES
('admin', '$2y$10$1uIHpk3HVxppsInwqpofbexCSv18ou7J3VVh6Aq03Wpxpt9CXiO6a', 1),
('rrhh', '$2y$10$u4InYXHoARGT6FU/tjcaM.LOGrOnTbv0bTmc16TVMSrKOTHm1SHFy', 2), 
('empleado', '$2y$10$k2Z/bmGFazE8lPYK8LpNxe8bbh4zCVsu5qi2L5QT8dJYAzoIOhfqS', 3),
('juanp', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 4),
('mariag', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 5),
('carlosr', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 6),
('anatorres', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 7),
('luisf', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 8);

-- Asignar roles a los usuarios
INSERT INTO rol_has_user (user_id, rol_id) VALUES
(1, 1), -- admin tiene rol admin
(2, 2), -- rrhh tiene rol rrhh
(3, 3), -- empleado tiene rol empleado
(4, 1), -- Juan Pérez admin
(4, 3), -- Juan Pérez empleado
(5, 2), -- María Gómez rrhh
(6, 3), -- Carlos Ramírez empleado
(7, 1), -- Ana Torres admin
(8, 3), -- Luis Fernández empleado
(8, 2); -- Luis Fernández rrhh
-- Resto de tablas del sistema de nómina...
-- (Continuaré con las otras tablas después)
