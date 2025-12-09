-- Crear tabla rangos_fondo_solidaridad si no existe
CREATE TABLE IF NOT EXISTS rangos_fondo_solidaridad (
  id INT PRIMARY KEY AUTO_INCREMENT,
  desde_smlv DECIMAL(10, 2) NOT NULL,
  hasta_smlv DECIMAL(10, 2) NOT NULL,
  porcentaje DECIMAL(5, 2) NOT NULL
);

-- Crear tabla tabla_retencion_fuente si no existe
CREATE TABLE IF NOT EXISTS tabla_retencion_fuente (
  id INT PRIMARY KEY AUTO_INCREMENT,
  desde_uvt DECIMAL(10, 2) NOT NULL,
  hasta_uvt DECIMAL(10, 2) NOT NULL,
  porcentaje DECIMAL(5, 2) NOT NULL
);

-- Agregar rangos de Fondo de Solidaridad
INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
(1, 1.5, 1),
(1.5, 2, 1.2),
(2, 2.5, 1.4),
(2.5, 3, 1.6),
(3, 4, 2),
(4, 5, 2.5),
(5, 10, 2.75),
(10, 20, 3);

-- Agregar tabla de Retención en la Fuente
INSERT INTO tabla_retencion_fuente (desde_uvt, hasta_uvt, porcentaje) VALUES
(0, 95, 0),
(95, 150, 5),
(150, 360, 8),
(360, 645, 11),
(645, 999999, 15);
