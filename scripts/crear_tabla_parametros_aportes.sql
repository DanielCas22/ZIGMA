-- Tabla para almacenar los valores de aportes y parafiscales
CREATE TABLE parametros_aportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salud_empleador DECIMAL(5,2) NOT NULL,
    salud_empleado DECIMAL(5,2) NOT NULL,
    pension_empleador DECIMAL(5,2) NOT NULL,
    pension_empleado DECIMAL(5,2) NOT NULL,
    parafiscales DECIMAL(5,2) NOT NULL,
    prestaciones DECIMAL(5,2) NOT NULL,
    actualizado_por VARCHAR(100) NOT NULL,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
