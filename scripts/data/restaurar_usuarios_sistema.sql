-- Restaurar roles básicos
INSERT IGNORE INTO rol (id_rol, nombre) VALUES 
(1, 'admin'), 
(2, 'rrhh'), 
(3, 'empleado');

-- Restaurar empleados de sistema (sin ellos, los usuarios no pueden estar vinculados)
INSERT IGNORE INTO empleados (id_empleados, nombre, apellido) VALUES 
(1, 'Sistema', 'Administrador'),
(2, 'Sistema', 'RRHH'),
(3, 'Sistema', 'Empleado');

-- Restaurar usuarios del sistema
INSERT IGNORE INTO user (id_doc, username, password, empleado_id) VALUES
(1, 'admin', '$2y$10$1uIHpk3HVxppsInwqpofbexCSv18ou7J3VVh6Aq03Wpxpt9CXiO6a', 1),
(2, 'rrhh', '$2y$10$u4InYXHoARGT6FU/tjcaM.LOGrOnTbv0bTmc16TVMSrKOTHm1SHFy', 2), 
(3, 'empleado', '$2y$10$k2Z/bmGFazE8lPYK8LpNxe8bbh4zCVsu5qi2L5QT8dJYAzoIOhfqS', 3);

-- Restaurar asignación de roles a usuarios
INSERT IGNORE INTO rol_has_user (user_id, rol_id) VALUES
(1, 1), -- admin tiene rol admin
(2, 2), -- rrhh tiene rol rrhh
(3, 3); -- empleado tiene rol empleado
