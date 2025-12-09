-- Primero eliminar los registros de rol_has_user
DELETE FROM rol_has_user WHERE user_id IN (
    SELECT id_doc FROM user WHERE empleado_id IN (1, 2, 3)
);

-- Luego eliminar los usuarios relacionados
DELETE FROM user WHERE empleado_id IN (1, 2, 3);

-- Eliminar las horas extras relacionadas
DELETE FROM horas_extras WHERE empleado_id IN (1, 2, 3);

-- Finalmente eliminar los empleados que son solo roles y no usuarios reales
DELETE FROM empleados WHERE id_empleados IN (1, 2, 3);

-- Resetear el auto_increment
ALTER TABLE empleados AUTO_INCREMENT = 1;
