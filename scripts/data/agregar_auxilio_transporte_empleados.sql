-- Agregar columna auxilio_transporte en empleados
ALTER TABLE empleados 
ADD COLUMN IF NOT EXISTS auxilio_transporte DECIMAL(10, 2) DEFAULT 0;
