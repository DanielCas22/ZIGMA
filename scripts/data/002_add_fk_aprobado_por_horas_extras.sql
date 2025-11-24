-- Crear la FK aprobado_por -> user(id_doc) solo si la columna existe y la constraint no existe ya.
-- Esto evita que el runner falle en entornos donde la tabla `user` no está presente.

SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user' AND COLUMN_NAME = 'id_doc') > 0
  AND
  (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'horas_extras' AND CONSTRAINT_NAME = 'fk_horas_extras_aprobado_por') = 0,
  'ALTER TABLE `horas_extras` ADD CONSTRAINT fk_horas_extras_aprobado_por FOREIGN KEY (aprobado_por) REFERENCES `user`(id_doc) ON DELETE SET NULL;',
  'SELECT "skip_fk";'
) INTO @sql;

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
