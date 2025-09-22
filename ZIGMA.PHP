Table `zigmaog`.`total_devengado`
TABLE IF NOT EXISTS `zigmaog`.`total_devengado` (
  `id_total devengado` INT NOT NULL,
  `salario` INT NULL,
  `dias` VARCHAR(45) NULL,
  `total` INT NULL,


Table `zigmaog`.`total_deducido`
TABLE IF NOT EXISTS `zigmaog`.`total_deducido` (
  `*id_total deducido` INT NOT NULL,
  `salario` INT NULL,
  `valor` INT NULL,
  `otros` VARCHAR(45) NULL,
  `total` INT NULL,


Table `zigmaog`.`nomina`
TABLE IF NOT EXISTS `zigmaog`.`nomina` (
  `*id_nomina` INT NOT NULL,
  `año` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `dia` VARCHAR(45) NULL,
 `valor_pagar` INT NULL,

 Table `zigmaog`.`empleados`
 TABLE IF NOT EXISTS `zigmaog`.`empleados` (
  `id_empleados` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `apellido` VARCHAR(45) NULL,
  `usuario` VARCHAR(45) NULL,
  `contraseña` VARCHAR(45) NULL,
  `sueldo_actual` VARCHAR(45) NULL,

Table `zigmaog`.`user`
 TABLE IF NOT EXISTS `zigmaog`.`user` (
  `id_doc` INT NOT NULL,
  `tipo_doc` VARCHAR(45) NULL,
  `num_doc` INT(10) NULL,

 Table `zigmaog`.`seguridad_social`
TABLE IF NOT EXISTS `zigmaog`.`seguridad_social` (
  `idseguridad` INT NOT NULL,
  `total` INT NULL,

Table `zigmaog`.`horas_extras`
TABLE IF NOT EXISTS `zigmaog`.`horas_extras` (
  `id_extras` INT NOT NULL,
  `valor` INT NULL,
  `cantidad` VARCHAR(45) NULL,
  `tipo` VARCHAR(45) NULL,
  `porcentaje` VARCHAR(45) NULL,
  `dia` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,

Table `zigmaog`.`salud`
TABLE IF NOT EXISTS `zigmaog`.`salud` (
  `id_salud` INT NOT NULL,
   `valor` VARCHAR(45) NULL,


Table `zigmaog`.`auxilio de transporte`
TABLE IF NOT EXISTS `zigmaog`.`auxilio de transporte` (
  `id_transporte` INT NOT NULL,
  `valor` INT NULL,

Table `zigmaog`.`solidaridad`
TABLE IF NOT EXISTS `zigmaog`.`solidaridad` (
  `id_solidaridad` INT NOT NULL,
  `sueldo` INT NULL,
   `valor` VARCHAR(45) NULL,

 
Table `zigmaog`.`pension`
 TABLE IF NOT EXISTS `zigmaog`.`pension` (
  `id_pension` INT NOT NULL,
   `valor` VARCHAR(45) NULL,

 Table `zigmaog`.`comisiones`
 TABLE IF NOT EXISTS `zigmaog`.`comisiones` (
  `id_comisiones` INT NOT NULL,
  `valor` INT NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,


Table `zigmaog`.`gestion_de _reportes`
TABLE IF NOT EXISTS `zigmaog`.`gestion_de _reportes` (
  `id_ reportes` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `dia` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,











Table `zigmaog`.`prestaciones_sociales`
TABLE IF NOT EXISTS `zigmaog`.`prestaciones_sociales` (
  `id_prestaciones` INT NOT NULL,
  `valor` INT NULL,
  `total` VARCHAR(45) NULL,


Table `zigmaog`.`rol`
TABLE IF NOT EXISTS `zigmaog`.`rol` (
  `id_rol` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `apellido` VARCHAR(45) NULL,

Table `zigmaog`.`parafiscales`
TABLE IF NOT EXISTS `zigmaog`.`parafiscales` (
  `id_parafiscales` INT NOT NULL,
  `valor_total` INT NULL,

Table `zigmaog`.`compensacion`
 TABLE IF NOT EXISTS `zigmaog`.`compensacion` (
  `id_compensacion` INT NOT NULL,
  `valor_total` INT NULL,

Table `zigmaog`.`cesantias`
TABLE IF NOT EXISTS `zigmaog`.`cesantias` (
  `id_cesantias` INT NOT NULL,
  `tipo` VARCHAR(45) NULL,

Table `zigmaog`.`vacaciones`
TABLE IF NOT EXISTS `zigmaog`.`vacaciones` (
  `id_vacaciones` INT NOT NULL,
  `valor_total` INT NULL,
  `dia` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,

Table `zigmaog`.`arl`
TABLE IF NOT EXISTS `zigmaog`.`arl` (
  `id_arl` INT NOT NULL,
  `valor` INT NULL,
Table `zigmaog`.`prima`
 TABLE IF NOT EXISTS `zigmaog`.`prima` (
  `id_prima` INT NOT NULL,
  `valor_total` INT NULL,
  `dia` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,
Table `zigmaog`.`retencion_ fuente`
TABLE IF NOT EXISTS `zigmaog`.`retencion_ fuente` (
  `id_retencion` INT NOT NULL,
  `sueldo` INT NULL,
  `limite_30%_salario` VARCHAR(45) NULL,
  `promedio_año_anterior_salud` VARCHAR(45) NULL,
  `aporte_afc` VARCHAR(45) NULL,
  `certificado_dependientes` VARCHAR(45) NULL,
  `salud_prepagados` VARCHAR(45) NULL,
  `pago_interes_vivienda` VARCHAR(45) NULL,
  `rentas_extensas` VARCHAR(45) NULL,
  `pension_voluntaria` VARCHAR(45) NULL,
  `afc` VARCHAR(45) NULL,
  `subtotal_1` VARCHAR(45) NULL,
  `promedio_año_anterior_salud` VARCHAR(45) NULL,
  `dependientes_uvt_32` VARCHAR(45) NULL,
  `salud_prepagada_16_uvt` VARCHAR(45) NULL,
  `intereses_vivienda_100_uvt` VARCHAR(45) NULL,
  `subtotal_2` VARCHAR(45) NULL,
  `renta_exenta` VARCHAR(45) NULL,
  `base_retencion` VARCHAR(45) NULL,
  `base_retencion_uvt` VARCHAR(45) NULL,
  `retencion_art833` VARCHAR(45) NULL,







Table `zigmaog`.`desprendible_de_nomina`
TABLE IF NOT EXISTS `zigmaog`.`desprendible_de_nomina` (
  `id_desprendible` INT NOT NULL,
  `dia` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,

Table `zigmaog`.`intereses`
TABLE IF NOT EXISTS `zigmaog`.`intereses` (
  `id_intereses` INT NOT NULL,
  `total` INT NULL,

Table `zigmaog`.`sena`
TABLE IF NOT EXISTS `zigmaog`.`sena` (
  `id_sena` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `valor` INT NULL,

Table `zigmaog`.`icbf`
TABLE IF NOT EXISTS `zigmaog`.`icbf` (
  `idi_cbf` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `mes` VARCHAR(45) NULL,
  `dia` VARCHAR(45) NULL,
  `año` VARCHAR(45) NULL,
  `valor` INT NULL,

Table `zigmaog`.`rangos_uvt’
TABLE IF NOT EXISTS `zigmaog`.`rangos_uvt` (
  `idrangosuvt` INT NOT NULL,
  `numero` INT NULL,
  `desde` VARCHAR(45) NULL,
  `hasta` VARCHAR(45) NULL,
  `tarifa_marginal` INT NULL,
  `impuesto` INT NULL,
  `año` DATE NULL,
  `valor` INT NULL,
12.   Lo vamos a desarrollar por gestiones, garantizamos que funcione y continuamos la siguiente












