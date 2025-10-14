-- Script completo para corregir la gestión de seguridad social
USE zigmaog;

-- 1. Asignar sueldos a empleados que no los tienen
UPDATE empleados SET sueldo_actual = 1423000 WHERE sueldo_actual IS NULL OR sueldo_actual = 0;

-- 2. Eliminar tabla incorrecta si existe
DROP TABLE IF EXISTS empleados_riesgo_arl;

-- 3. Crear tabla de riesgo ARL con el nombre de columna correcto
CREATE TABLE IF NOT EXISTS empleados_riesgo_arl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    codigo_riesgo INT NOT NULL DEFAULT 2,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
    UNIQUE KEY unique_empleado_riesgo (id_empleado)
);

-- 4. Crear tabla de niveles de riesgo ARL
CREATE TABLE IF NOT EXISTS niveles_riesgo_arl (
    codigo INT PRIMARY KEY,
    descripcion VARCHAR(100),
    porcentaje DECIMAL(5,3),
    activo BOOLEAN DEFAULT TRUE
);

-- 5. Insertar niveles de riesgo
INSERT INTO niveles_riesgo_arl (codigo, descripcion, porcentaje) VALUES
(1, 'Clase I - Mínimo', 0.522),
(2, 'Clase II - Bajo', 1.044),
(3, 'Clase III - Medio', 2.436),
(4, 'Clase IV - Alto', 4.350),
(5, 'Clase V - Máximo', 6.960)
ON DUPLICATE KEY UPDATE 
descripcion = VALUES(descripcion),
porcentaje = VALUES(porcentaje);

-- 6. Asignar riesgo ARL básico (Clase II) a todos los empleados
INSERT INTO empleados_riesgo_arl (id_empleado, codigo_riesgo)
SELECT id_empleados, 2 FROM empleados
ON DUPLICATE KEY UPDATE codigo_riesgo = 2;

-- 7. Crear tabla horas_extras si no existe (necesaria para DevengadoModel)
CREATE TABLE IF NOT EXISTS horas_extras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    valor DECIMAL(15,2) NOT NULL DEFAULT 0,
    cantidad DECIMAL(5,2) NOT NULL DEFAULT 0,
    tipo VARCHAR(100) DEFAULT 'Normal',
    porcentaje DECIMAL(5,2) DEFAULT 0,
    dia VARCHAR(2) DEFAULT '01',
    mes VARCHAR(2) DEFAULT '01',
    anio VARCHAR(4) DEFAULT '2025',
    FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE
);

-- 8. Crear tabla conceptos_adicionales si no existe (para otros conceptos)
CREATE TABLE IF NOT EXISTS conceptos_adicionales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    valor DECIMAL(15,2) NOT NULL DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE
);

-- 9. Verificar estructura de empleados
SELECT 'Empleados registrados:' as info;
SELECT id_empleados, nombre, apellido, sueldo_actual FROM empleados;

SELECT 'Riesgos ARL asignados:' as info;
SELECT era.id_empleado, e.nombre, e.apellido, era.codigo_riesgo, nra.descripcion 
FROM empleados_riesgo_arl era
JOIN empleados e ON era.id_empleado = e.id_empleados
JOIN niveles_riesgo_arl nra ON era.codigo_riesgo = nra.codigo;
