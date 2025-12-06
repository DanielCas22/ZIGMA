-- Tabla para almacenar los salarios base por rol
CREATE TABLE salarios_por_rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(50) NOT NULL UNIQUE,
    salario DECIMAL(12,2) NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL
);
