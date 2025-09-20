

-- Base de datos: zigmaog
DROP DATABASE IF EXISTS zigmaog;
CREATE DATABASE zigmaog;
USE zigmaog;



-- Insertar roles
INSERT INTO rol (nombre) VALUES 
('admin'), 
('rrhh'), 
('empleado');

-- Insertar empleados (nombre, apellidos)
INSERT INTO empleados (nombre, apellidos) VALUES 
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
