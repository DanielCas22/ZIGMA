-- Script para agregar horas extras variadas a cada empleado
-- Fecha: Septiembre 2025

-- Para Laura Ospina (ID: 19) - Empleado regular
INSERT INTO horas_extras (empleado_id, tipo_hora_extra_id, cantidad_horas, fecha, descripcion) VALUES
(19, 1, 3.5, '2025-09-20', 'Finalización de proyecto urgente - horas diurnas'),
(19, 2, 2.0, '2025-09-18', 'Soporte nocturno sistema crítico'),
(19, 1, 1.5, '2025-09-15', 'Reunión extendida con cliente');

-- Para Carlos Mendoza (ID: 20) - Empleado regular  
INSERT INTO horas_extras (empleado_id, tipo_hora_extra_id, cantidad_horas, fecha, descripcion) VALUES
(20, 2, 4.0, '2025-09-19', 'Mantenimiento nocturno de servidores'),
(20, 3, 6.0, '2025-09-22', 'Trabajo dominical - implementación nueva funcionalidad'),
(20, 1, 2.5, '2025-09-16', 'Capacitación extendida equipo'),
(20, 4, 3.0, '2025-09-08', 'Soporte crítico domingo en la noche');

-- Para María González (ID: 21) - Empleado regular
INSERT INTO horas_extras (empleado_id, tipo_hora_extra_id, cantidad_horas, fecha, descripcion) VALUES
(21, 1, 5.0, '2025-09-21', 'Procesamiento masivo de datos - horas extra diurnas'),
(21, 3, 4.5, '2025-09-15', 'Trabajo festivo - Independencia de Colombia'),
(21, 2, 1.5, '2025-09-17', 'Respaldo nocturno de base de datos');

-- Para Juan David Martínez (ID: 22) - Admin (salario alto)
INSERT INTO horas_extras (empleado_id, tipo_hora_extra_id, cantidad_horas, fecha, descripcion) VALUES
(22, 4, 5.0, '2025-09-22', 'Supervisión crítica domingo nocturno'),
(22, 3, 3.0, '2025-09-15', 'Coordinación equipos día festivo'),
(22, 2, 2.5, '2025-09-20', 'Reuniones estratégicas nocturnas'),
(22, 1, 4.0, '2025-09-18', 'Planificación y supervisión extendida');

-- Para Romero Quiñones (ID: 24) - RRHH (salario medio)
INSERT INTO horas_extras (empleado_id, tipo_hora_extra_id, cantidad_horas, fecha, descripcion) VALUES
(24, 1, 3.0, '2025-09-19', 'Procesos de selección extendidos'),
(24, 2, 2.0, '2025-09-21', 'Atención emergencias laborales nocturnas'),
(24, 3, 7.0, '2025-09-22', 'Capacitaciones dominicales equipo completo'),
(24, 1, 1.0, '2025-09-14', 'Entrevistas adicionales candidatos');