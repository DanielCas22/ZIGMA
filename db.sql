-- Base de datos: zigmaog
CREATE DATABASE IF NOT EXISTS zigmaog;
USE zigmaog;

-- Tabla de roles
CREATE TABLE IF NOT EXISTS rol (
  id_rol INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL
);

-- Tabla de empleados
CREATE TABLE IF NOT EXISTS empleados (
  id_empleados INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NULL
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS user (
  id_doc INT PRIMARY KEY AUTO_INCREMENT,
  tipo_doc VARCHAR(45) NULL,
  empleados_id_empleados INT NOT NULL,
  num_doc INT(10) NULL,
  password VARCHAR(255) NOT NULL,
  FOREIGN KEY (empleados_id_empleados) REFERENCES empleados(id_empleados)
);

-- Relación rol-usuario
CREATE TABLE IF NOT EXISTS rol_has_user (
  rol_id_rol INT NOT NULL,
  user_id_doc INT NOT NULL,
  PRIMARY KEY (rol_id_rol, user_id_doc),
  FOREIGN KEY (rol_id_rol) REFERENCES rol(id_rol),
  FOREIGN KEY (user_id_doc) REFERENCES user(id_doc)
);

-- Tabla de total devengado
CREATE TABLE IF NOT EXISTS total_devengado (
  id_total_devengado INT PRIMARY KEY AUTO_INCREMENT,
  sueldo_basico INT NULL,
  comisiones_total INT NULL
);

-- Tabla de total deducido
CREATE TABLE IF NOT EXISTS total_deducido (
  id_total_deducido INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL
);

-- Tabla de nómina
CREATE TABLE IF NOT EXISTS nomina (
  id_nomina INT PRIMARY KEY AUTO_INCREMENT,
  anio VARCHAR(45) NULL,
  total_devengado_id INT NOT NULL,
  total_deducido_id INT NOT NULL,
  valor_total INT NULL,
  mes VARCHAR(45) NULL,
  dia VARCHAR(45) NULL,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado),
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de prestaciones sociales
CREATE TABLE IF NOT EXISTS prestaciones_sociales (
  id_prestaciones INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL
);

-- Relación total devengado - prestaciones sociales
CREATE TABLE IF NOT EXISTS total_devengado_has_prestaciones_sociales (
  total_devengado_id INT NOT NULL,
  prestaciones_sociales_id INT NOT NULL,
  PRIMARY KEY (total_devengado_id, prestaciones_sociales_id),
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado),
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de intereses
CREATE TABLE IF NOT EXISTS intereses (
  id_intereses INT PRIMARY KEY AUTO_INCREMENT,
  total INT NULL,
  prestaciones_sociales_id INT NOT NULL,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de cesantías
CREATE TABLE IF NOT EXISTS cesantias (
  id_cesantias INT PRIMARY KEY AUTO_INCREMENT,
  total_devengado INT NULL,
  prestaciones_sociales_id INT NOT NULL,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de vacaciones
CREATE TABLE IF NOT EXISTS vacaciones (
  id_vacaciones INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT NULL,
  prestaciones_sociales_id INT NOT NULL,
  dia VARCHAR(45) NULL,
  mes VARCHAR(45) NULL,
  anio VARCHAR(45) NULL,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de prima
CREATE TABLE IF NOT EXISTS prima (
  id_prima INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT NULL,
  prestaciones_sociales_id INT NOT NULL,
  FOREIGN KEY (prestaciones_sociales_id) REFERENCES prestaciones_sociales(id_prestaciones)
);

-- Tabla de comisiones
CREATE TABLE IF NOT EXISTS comisiones (
  id_comisiones INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL,
  mes VARCHAR(45) NULL,
  total_devengado_id INT NOT NULL,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de horas extras
CREATE TABLE IF NOT EXISTS horas_extras (
  id_extras INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL,
  total_devengado_id INT NOT NULL,
  dia VARCHAR(45) NULL,
  horas_extrascol VARCHAR(45) NULL,
  mes VARCHAR(45) NULL,
  anio VARCHAR(45) NULL,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de auxilio de transporte
CREATE TABLE IF NOT EXISTS auxilio_transporte (
  id_transporte INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL,
  total_devengado_id INT NOT NULL,
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de parafiscales
CREATE TABLE IF NOT EXISTS parafiscales (
  id_parafiscales INT PRIMARY KEY AUTO_INCREMENT,
  valor_total INT NULL
);

-- Relación parafiscales - total devengado
CREATE TABLE IF NOT EXISTS parafiscales_has_total_devengado (
  parafiscales_id INT NOT NULL,
  total_devengado_id INT NOT NULL,
  PRIMARY KEY (parafiscales_id, total_devengado_id),
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales),
  FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado)
);

-- Tabla de compensación
CREATE TABLE IF NOT EXISTS compensacion (
  id_compensacion INT PRIMARY KEY AUTO_INCREMENT,
  parafiscales_id INT NOT NULL,
  valor INT NULL,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de sena
CREATE TABLE IF NOT EXISTS sena (
  id_sena INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NULL,
  parafiscales_id INT NOT NULL,
  valor INT NULL,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de icbf
CREATE TABLE IF NOT EXISTS icbf (
  id_icbf INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NULL,
  mes VARCHAR(45) NULL,
  parafiscales_id INT NOT NULL,
  valor INT NULL,
  dia VARCHAR(45) NULL,
  anio VARCHAR(45) NULL,
  FOREIGN KEY (parafiscales_id) REFERENCES parafiscales(id_parafiscales)
);

-- Tabla de seguridad social
CREATE TABLE IF NOT EXISTS seguridad_social (
  idseguridad INT PRIMARY KEY AUTO_INCREMENT,
  total INT NULL,
  dia VARCHAR(45) NULL,
  mes VARCHAR(45) NULL,
  total_deducido_id INT NOT NULL,
  anio VARCHAR(45) NULL,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de salud
CREATE TABLE IF NOT EXISTS salud (
  id_salud INT PRIMARY KEY AUTO_INCREMENT,
  total_devengado INT NULL,
  total_deducido_id INT NOT NULL,
  seguridad_social_id INT NOT NULL,
  seguridad_social_total_deducido_id INT NOT NULL,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido),
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de arl
CREATE TABLE IF NOT EXISTS arl (
  id_arl INT PRIMARY KEY AUTO_INCREMENT,
  valor INT NULL,
  seguridad_social_id INT NOT NULL,
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de pensión
CREATE TABLE IF NOT EXISTS pension (
  id_pension INT PRIMARY KEY AUTO_INCREMENT,
  total_devengado INT NULL,
  total_deducido_id INT NOT NULL,
  seguridad_social_id INT NOT NULL,
  seguridad_social_total_deducido_id INT NOT NULL,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido),
  FOREIGN KEY (seguridad_social_id) REFERENCES seguridad_social(idseguridad)
);

-- Tabla de solidaridad
CREATE TABLE IF NOT EXISTS solidaridad (
  id_solidaridad INT PRIMARY KEY AUTO_INCREMENT,
  sueldo INT NULL,
  total_deducido_id INT NOT NULL,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de retención en la fuente
CREATE TABLE IF NOT EXISTS retencion_fuente (
  id_retencion INT PRIMARY KEY AUTO_INCREMENT,
  sueldo INT NULL,
  total_deducido_id INT NOT NULL,
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de rangos uvt
CREATE TABLE IF NOT EXISTS rangos_uvt (
  idrangosuvt INT PRIMARY KEY AUTO_INCREMENT,
  numero INT NULL,
  desde VARCHAR(45) NULL,
  hasta VARCHAR(45) NULL,
  tarifa_marginal INT NULL,
  impuesto INT NULL,
  anio DATE NULL,
  valor INT NULL
);

-- Relación rangosuvt - total deducido
CREATE TABLE IF NOT EXISTS rangosuvt_has_total_deducido (
  rangosuvt_id INT NOT NULL,
  total_deducido_id INT NOT NULL,
  PRIMARY KEY (rangosuvt_id, total_deducido_id),
  FOREIGN KEY (rangosuvt_id) REFERENCES rangos_uvt(idrangosuvt),
  FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido)
);

-- Tabla de gestión de reportes
CREATE TABLE IF NOT EXISTS gestion_de_reportes (
  id_reportes INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NULL,
  fecha DATETIME NULL,
  tipo_reporte VARCHAR(45) NULL
);

-- Relación gestión de reportes - nómina
CREATE TABLE IF NOT EXISTS gestion_de_reportes_has_nomina (
  gestion_de_reportes_id INT NOT NULL,
  nomina_id INT NOT NULL,
  PRIMARY KEY (gestion_de_reportes_id, nomina_id),
  FOREIGN KEY (gestion_de_reportes_id) REFERENCES gestion_de_reportes(id_reportes),
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Tabla de desprendible de nómina
CREATE TABLE IF NOT EXISTS desprendible_de_nomina (
  id_desprendible INT PRIMARY KEY AUTO_INCREMENT,
  empleados_id INT NOT NULL,
  dia VARCHAR(45) NULL,
  mes VARCHAR(45) NULL,
  anio VARCHAR(45) NULL,
  FOREIGN KEY (empleados_id) REFERENCES empleados(id_empleados)
);

-- Relación desprendible de nómina - nómina
CREATE TABLE IF NOT EXISTS desprendible_de_nomina_has_nomina (
  desprendible_de_nomina_id INT NOT NULL,
  desprendible_de_nomina_empleados_id INT NOT NULL,
  nomina_id INT NOT NULL,
  PRIMARY KEY (desprendible_de_nomina_id, nomina_id),
  FOREIGN KEY (desprendible_de_nomina_id) REFERENCES desprendible_de_nomina(id_desprendible),
  FOREIGN KEY (nomina_id) REFERENCES nomina(id_nomina)
);

-- Relación empleados - desprendible de nómina
-- Ya está cubierta por la FK en desprendible_de_nomina

-- Relación user - nomina
ALTER TABLE nomina ADD COLUMN user_id_doc INT NULL, ADD FOREIGN KEY (user_id_doc) REFERENCES user(id_doc);

-- Relación empleados - nomina
ALTER TABLE nomina ADD COLUMN empleados_id INT NULL, ADD FOREIGN KEY (empleados_id) REFERENCES empleados(id_empleados);

-- Relación empleados - desprendible de nómina
-- Ya está cubierta por la FK en desprendible_de_nomina

-- Insertar roles y usuarios por defecto
INSERT INTO rol (nombre) VALUES ('admin'), ('rrhh'), ('empleado');

-- Usuarios de ejemplo (contraseñas encriptadas con bcrypt)
INSERT INTO empleados (nombre) VALUES ('Administrador'), ('Recursos Humanos'), ('Empleado');
INSERT INTO user (tipo_doc, empleados_id_empleados, num_doc, password) VALUES
('CC', 1, 1001, '$2y$10$wH6Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw'), -- admin123
('CC', 2, 1002, '$2y$10$wH6Qw2Qw2Qw2Qw2Qw2Qw2u2Qw2Qw2Qw2Qw2Qw2Qw2Qw2Qw2Qw'), -- rrhh123
('CC', 3, 1003, '$2y$10$wH6Qw3Qw3Qw3Qw3Qw3Qw3u3Qw3Qw3Qw3Qw3Qw3Qw3Qw3Qw3Qw'); -- empleado123
INSERT INTO rol_has_user (rol_id_rol, user_id_doc) VALUES (1, 1), (2, 2), (3, 3);
