-- Base de datos: zigmaog
DROP DATABASE IF EXISTS zigmaog;
CREATE DATABASE zigmaog;
USE zigmaog;

-- Tabla de roles
CREATE TABLE rol (
  id_rol INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL
);

-- Tabla de empleados
CREATE TABLE empleados (
  id_empleados INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE user (
  id_doc INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(45) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  empleado_id INT NOT NULL,
  FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados)
);

-- Tabla de relación usuario-rol
CREATE TABLE user_rol (
  user_id INT NOT NULL,
  rol_id INT NOT NULL,
  PRIMARY KEY (user_id, rol_id),
  FOREIGN KEY (user_id) REFERENCES user(id_doc),
  FOREIGN KEY (rol_id) REFERENCES rol(id_rol)
);

-- Insertar roles
INSERT INTO rol (nombre) VALUES 
('admin'), 
('rrhh'), 
('empleado');

-- Insertar empleados
INSERT INTO empleados (nombre) VALUES 
('Administrador del Sistema'),
('Coordinador RRHH'),
('Empleado General');

-- Insertar usuarios con contraseñas hasheadas correctamente
INSERT INTO user (username, password, empleado_id) VALUES
('admin', '$2y$10$1uIHpk3HVxppsInwqpofbexCSv18ou7J3VVh6Aq03Wpxpt9CXiO6a', 1),
('rrhh', '$2y$10$u4InYXHoARGT6FU/tjcaM.LOGrOnTbv0bTmc16TVMSrKOTHm1SHFy', 2), 
('empleado', '$2y$10$k2Z/bmGFazE8lPYK8LpNxe8bbh4zCVsu5qi2L5QT8dJYAzoIOhfqS', 3);

-- Asignar roles a usuarios
INSERT INTO user_rol (user_id, rol_id) VALUES
(1, 1), -- admin tiene rol admin
(2, 2), -- rrhh tiene rol rrhh
(3, 3); -- empleado tiene rol empleado

-- Resto de tablas del sistema de nómina...
-- (Continuaré con las otras tablas después)
