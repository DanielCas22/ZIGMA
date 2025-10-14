-- Script simplificado para corregir ARL
USE zigmaog;

-- 1. Crear tabla simple de niveles de riesgo
DROP TABLE IF EXISTS empleados_riesgo_arl;
DROP TABLE IF EXISTS niveles_riesgo_arl;

CREATE TABLE niveles_riesgo_arl (
    codigo INT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    porcentaje DECIMAL(5,3) NOT NULL
);

-- 2. Insertar niveles con porcentajes correctos
INSERT INTO niveles_riesgo_arl (codigo, descripcion, porcentaje) VALUES
(1, 'Clase I - Mínimo', 0.522),
(2, 'Clase II - Bajo', 1.044),
(3, 'Clase III - Medio', 2.436),
(4, 'Clase IV - Alto', 4.350),
(5, 'Clase V - Máximo', 6.960);

-- 3. Crear tabla de empleados con riesgo
CREATE TABLE empleados_riesgo_arl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    codigo_riesgo INT NOT NULL DEFAULT 2,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
    FOREIGN KEY (codigo_riesgo) REFERENCES niveles_riesgo_arl(codigo),
    UNIQUE KEY unique_empleado (id_empleado)
);

-- 4. Asignar riesgo a todos los empleados
INSERT INTO empleados_riesgo_arl (id_empleado, codigo_riesgo)
SELECT id_empleados, 2 FROM empleados
ON DUPLICATE KEY UPDATE codigo_riesgo = 2;

-- 5. Verificar datos
SELECT 'Verificación de datos ARL:' as info;
SELECT e.id_empleados, e.nombre, era.codigo_riesgo, nra.descripcion, nra.porcentaje
FROM empleados e
JOIN empleados_riesgo_arl era ON e.id_empleados = era.id_empleado
JOIN niveles_riesgo_arl nra ON era.codigo_riesgo = nra.codigo;
