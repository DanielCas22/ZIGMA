-- Crear tabla para parámetros generales (UVT, SMLV, Períodos de Pago, Formato)
CREATE TABLE IF NOT EXISTS parametros_generales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  uvt DECIMAL(10, 2) NOT NULL DEFAULT 0,
  smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
  periodo_pago VARCHAR(45) DEFAULT 'mensual',
  formato_divisa VARCHAR(10) DEFAULT '$',
  formato_decimales INT DEFAULT 2,
  formato_miles VARCHAR(1) DEFAULT '.',
  ano_vigencia INT DEFAULT YEAR(CURDATE()),
  actualizado_por INT,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
);

-- Insertar valores por defecto
INSERT INTO parametros_generales (uvt, smlv, periodo_pago, formato_divisa, formato_decimales, formato_miles, ano_vigencia) 
VALUES (45286.00, 1300000, 'mensual', '$', 0, '.', YEAR(CURDATE()))
ON DUPLICATE KEY UPDATE id=id;
