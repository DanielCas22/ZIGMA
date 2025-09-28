-- Script compatible con esquema actual (horas_extras)
-- Ejecutar después de db_nuevo.sql y sql_tarifas.sql
USE zigmaog;

-- Notas:
-- - Tabla horas_extras actual: (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio)
-- - Porcentajes según tipos: Extra diurna 25, Extra nocturna 75, Extra diurna dominical/festiva 105, Extra nocturna dominical/festiva 155
-- - Para septiembre 2025 se usa valor_hora 6470 (referencial)

-- Juan Pérez (ID: 4)
INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES
(4, 28306, 3.5, 'Extra diurna', 25, '20', '09', '2025'),
(4, 22645, 2.0, 'Extra nocturna', 75, '18', '09', '2025'),
(4, 12131, 1.5, 'Extra diurna', 25, '15', '09', '2025');

-- Carlos Ramírez (ID: 6)
INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES
(6, 45290, 4.0, 'Extra nocturna', 75, '19', '09', '2025'),
(6, 79581, 6.0, 'Extra diurna dominical/festiva', 105, '22', '09', '2025'),
(6, 20219, 2.5, 'Extra diurna', 25, '16', '09', '2025'),
(6, 49496, 3.0, 'Extra nocturna dominical/festiva', 155, '08', '09', '2025');

-- María Gómez (ID: 5)
INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES
(5, 40438, 5.0, 'Extra diurna', 25, '21', '09', '2025'),
(5, 59686, 4.5, 'Extra diurna dominical/festiva', 105, '15', '09', '2025'),
(5, 16984, 1.5, 'Extra nocturna', 75, '17', '09', '2025');

-- Administrador del Sistema (ID: 1)
INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES
(1, 82493, 5.0, 'Extra nocturna dominical/festiva', 155, '22', '09', '2025'),
(1, 39791, 3.0, 'Extra diurna dominical/festiva', 105, '15', '09', '2025'),
(1, 28306, 2.5, 'Extra nocturna', 75, '20', '09', '2025'),
(1, 32350, 4.0, 'Extra diurna', 25, '18', '09', '2025');

-- Coordinador RRHH (ID: 2)
INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES
(2, 24263, 3.0, 'Extra diurna', 25, '19', '09', '2025'),
(2, 22645, 2.0, 'Extra nocturna', 75, '21', '09', '2025'),
(2, 92845, 7.0, 'Extra diurna dominical/festiva', 105, '22', '09', '2025'),
(2, 8088, 1.0, 'Extra diurna', 25, '14', '09', '2025');