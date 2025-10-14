-- Script completo para implementar ARL correctamente
USE zigmaog;

-- 1. Eliminar tablas si existen (para evitar conflictos)
DROP TABLE IF EXISTS empleados_riesgo_arl;
DROP TABLE IF EXISTS niveles_riesgo_arl;

-- 2. Crear tabla de niveles de riesgo ARL según la tabla oficial
CREATE TABLE niveles_riesgo_arl (
    codigo INT PRIMARY KEY,
    clase VARCHAR(10) NOT NULL,
    descripcion VARCHAR(50) NOT NULL,
    valor_minimo DECIMAL(5,3) NOT NULL,
    valor_inicial DECIMAL(5,3) NOT NULL,
    valor_maximo DECIMAL(5,3) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- 3. Insertar los niveles de riesgo según la tabla oficial
INSERT INTO niveles_riesgo_arl (codigo, clase, descripcion, valor_minimo, valor_inicial, valor_maximo) VALUES
(1, 'I', 'Mínimo', 0.348, 0.522, 0.696),
(2, 'II', 'Bajo', 0.435, 1.044, 1.653),
(3, 'III', 'Medio', 0.783, 2.436, 4.089),
(4, 'IV', 'Alto', 1.740, 4.350, 6.960),
(5, 'V', 'Máximo', 3.219, 6.960, 8.700);

-- 4. Crear tabla de asignación de riesgos por empleado
CREATE TABLE empleados_riesgo_arl (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    codigo_riesgo INT NOT NULL DEFAULT 2,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    observaciones TEXT,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
    FOREIGN KEY (codigo_riesgo) REFERENCES niveles_riesgo_arl(codigo),
    UNIQUE KEY unique_empleado_riesgo (id_empleado)
);

-- 5. Asignar riesgo básico (Clase II) a todos los empleados
INSERT INTO empleados_riesgo_arl (id_empleado, codigo_riesgo, observaciones)
SELECT id_empleados, 2, 'Asignación automática - Clase II (Bajo)'
FROM empleados
ON DUPLICATE KEY UPDATE 
codigo_riesgo = 2,
observaciones = 'Asignación automática - Clase II (Bajo)',
fecha_actualizacion = CURRENT_TIMESTAMP;

-- 6. Verificación: Mostrar empleados con sus riesgos asignados
SELECT 
    e.id_empleados,
    e.nombre,
    e.apellido,
    e.sueldo_actual,
    era.codigo_riesgo,
    nra.clase,
    nra.descripcion,
    nra.valor_inicial as porcentaje_arl
FROM empleados e
LEFT JOIN empleados_riesgo_arl era ON e.id_empleados = era.id_empleado
LEFT JOIN niveles_riesgo_arl nra ON era.codigo_riesgo = nra.codigo
ORDER BY e.nombre;
