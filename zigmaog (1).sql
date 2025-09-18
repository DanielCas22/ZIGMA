-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-09-2025 a las 04:13:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zigmaog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arl`
--

CREATE TABLE `arl` (
  `id_arl` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `seguridad_social_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auxilio_transporte`
--

CREATE TABLE `auxilio_transporte` (
  `id_transporte` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `total_devengado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesantias`
--

CREATE TABLE `cesantias` (
  `id_cesantias` int(11) NOT NULL,
  `total_devengado` int(11) DEFAULT NULL,
  `prestaciones_sociales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id_comisiones` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `total_devengado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compensacion`
--

CREATE TABLE `compensacion` (
  `id_compensacion` int(11) NOT NULL,
  `parafiscales_id` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desprendible_de_nomina`
--

CREATE TABLE `desprendible_de_nomina` (
  `id_desprendible` int(11) NOT NULL,
  `empleados_id` int(11) NOT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `anio` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desprendible_de_nomina_has_nomina`
--

CREATE TABLE `desprendible_de_nomina_has_nomina` (
  `desprendible_de_nomina_id` int(11) NOT NULL,
  `desprendible_de_nomina_empleados_id` int(11) NOT NULL,
  `nomina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleados` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleados`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Recursos Humanos'),
(3, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_de_reportes`
--

CREATE TABLE `gestion_de_reportes` (
  `id_reportes` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `tipo_reporte` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_de_reportes_has_nomina`
--

CREATE TABLE `gestion_de_reportes_has_nomina` (
  `gestion_de_reportes_id` int(11) NOT NULL,
  `nomina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas_extras`
--

CREATE TABLE `horas_extras` (
  `id_extras` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `total_devengado_id` int(11) NOT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `horas_extrascol` varchar(45) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `anio` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `icbf`
--

CREATE TABLE `icbf` (
  `id_icbf` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `parafiscales_id` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `anio` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intereses`
--

CREATE TABLE `intereses` (
  `id_intereses` int(11) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `prestaciones_sociales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `id_nomina` int(11) NOT NULL,
  `anio` varchar(45) DEFAULT NULL,
  `total_devengado_id` int(11) NOT NULL,
  `total_deducido_id` int(11) NOT NULL,
  `valor_total` int(11) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `user_id_doc` int(11) DEFAULT NULL,
  `empleados_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parafiscales`
--

CREATE TABLE `parafiscales` (
  `id_parafiscales` int(11) NOT NULL,
  `valor_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parafiscales_has_total_devengado`
--

CREATE TABLE `parafiscales_has_total_devengado` (
  `parafiscales_id` int(11) NOT NULL,
  `total_devengado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pension`
--

CREATE TABLE `pension` (
  `id_pension` int(11) NOT NULL,
  `total_devengado` int(11) DEFAULT NULL,
  `total_deducido_id` int(11) NOT NULL,
  `seguridad_social_id` int(11) NOT NULL,
  `seguridad_social_total_deducido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestaciones_sociales`
--

CREATE TABLE `prestaciones_sociales` (
  `id_prestaciones` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prima`
--

CREATE TABLE `prima` (
  `id_prima` int(11) NOT NULL,
  `valor_total` int(11) DEFAULT NULL,
  `prestaciones_sociales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rangosuvt_has_total_deducido`
--

CREATE TABLE `rangosuvt_has_total_deducido` (
  `rangosuvt_id` int(11) NOT NULL,
  `total_deducido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rangos_uvt`
--

CREATE TABLE `rangos_uvt` (
  `idrangosuvt` int(11) NOT NULL,
  `numero` int(11) DEFAULT NULL,
  `desde` varchar(45) DEFAULT NULL,
  `hasta` varchar(45) DEFAULT NULL,
  `tarifa_marginal` int(11) DEFAULT NULL,
  `impuesto` int(11) DEFAULT NULL,
  `anio` date DEFAULT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion_fuente`
--

CREATE TABLE `retencion_fuente` (
  `id_retencion` int(11) NOT NULL,
  `sueldo` int(11) DEFAULT NULL,
  `total_deducido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'rrhh'),
(3, 'empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_has_user`
--

CREATE TABLE `rol_has_user` (
  `rol_id_rol` int(11) NOT NULL,
  `user_id_doc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_has_user`
--

INSERT INTO `rol_has_user` (`rol_id_rol`, `user_id_doc`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salud`
--

CREATE TABLE `salud` (
  `id_salud` int(11) NOT NULL,
  `total_devengado` int(11) DEFAULT NULL,
  `total_deducido_id` int(11) NOT NULL,
  `seguridad_social_id` int(11) NOT NULL,
  `seguridad_social_total_deducido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_social`
--

CREATE TABLE `seguridad_social` (
  `idseguridad` int(11) NOT NULL,
  `total` int(11) DEFAULT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `total_deducido_id` int(11) NOT NULL,
  `anio` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sena`
--

CREATE TABLE `sena` (
  `id_sena` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `parafiscales_id` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solidaridad`
--

CREATE TABLE `solidaridad` (
  `id_solidaridad` int(11) NOT NULL,
  `sueldo` int(11) DEFAULT NULL,
  `total_deducido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `total_deducido`
--

CREATE TABLE `total_deducido` (
  `id_total_deducido` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `total_devengado`
--

CREATE TABLE `total_devengado` (
  `id_total_devengado` int(11) NOT NULL,
  `sueldo_basico` int(11) DEFAULT NULL,
  `comisiones_total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `total_devengado_has_prestaciones_sociales`
--

CREATE TABLE `total_devengado_has_prestaciones_sociales` (
  `total_devengado_id` int(11) NOT NULL,
  `prestaciones_sociales_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_doc` int(11) NOT NULL,
  `tipo_doc` varchar(45) DEFAULT NULL,
  `empleados_id_empleados` int(11) NOT NULL,
  `num_doc` int(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id_doc`, `tipo_doc`, `empleados_id_empleados`, `num_doc`, `password`) VALUES
(1, 'CC', 1, 0, '$2y$10$Zz5/ghRIEAz4Z2eopfn41OaYdV3/PKAbpWIqBTgV4Wu4Pw7k/r7ru'),
(2, 'CC', 2, 0, '$2y$10$pROFpITSbfQF39dkm6SHu.jATFds.x/8ZyOWL0nq6MMZbBrPz.Hbe'),
(3, 'CC', 3, 0, '$2y$10$q6faCJk3KiCaTOmWLCc3Oe/CwVMX72X82zsU6Dq3PcZhQmFBvgt5K');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id_vacaciones` int(11) NOT NULL,
  `valor_total` int(11) DEFAULT NULL,
  `prestaciones_sociales_id` int(11) NOT NULL,
  `dia` varchar(45) DEFAULT NULL,
  `mes` varchar(45) DEFAULT NULL,
  `anio` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arl`
--
ALTER TABLE `arl`
  ADD PRIMARY KEY (`id_arl`),
  ADD KEY `seguridad_social_id` (`seguridad_social_id`);

--
-- Indices de la tabla `auxilio_transporte`
--
ALTER TABLE `auxilio_transporte`
  ADD PRIMARY KEY (`id_transporte`),
  ADD KEY `total_devengado_id` (`total_devengado_id`);

--
-- Indices de la tabla `cesantias`
--
ALTER TABLE `cesantias`
  ADD PRIMARY KEY (`id_cesantias`),
  ADD KEY `prestaciones_sociales_id` (`prestaciones_sociales_id`);

--
-- Indices de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD PRIMARY KEY (`id_comisiones`),
  ADD KEY `total_devengado_id` (`total_devengado_id`);

--
-- Indices de la tabla `compensacion`
--
ALTER TABLE `compensacion`
  ADD PRIMARY KEY (`id_compensacion`),
  ADD KEY `parafiscales_id` (`parafiscales_id`);

--
-- Indices de la tabla `desprendible_de_nomina`
--
ALTER TABLE `desprendible_de_nomina`
  ADD PRIMARY KEY (`id_desprendible`),
  ADD KEY `empleados_id` (`empleados_id`);

--
-- Indices de la tabla `desprendible_de_nomina_has_nomina`
--
ALTER TABLE `desprendible_de_nomina_has_nomina`
  ADD PRIMARY KEY (`desprendible_de_nomina_id`,`nomina_id`),
  ADD KEY `nomina_id` (`nomina_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleados`);

--
-- Indices de la tabla `gestion_de_reportes`
--
ALTER TABLE `gestion_de_reportes`
  ADD PRIMARY KEY (`id_reportes`);

--
-- Indices de la tabla `gestion_de_reportes_has_nomina`
--
ALTER TABLE `gestion_de_reportes_has_nomina`
  ADD PRIMARY KEY (`gestion_de_reportes_id`,`nomina_id`),
  ADD KEY `nomina_id` (`nomina_id`);

--
-- Indices de la tabla `horas_extras`
--
ALTER TABLE `horas_extras`
  ADD PRIMARY KEY (`id_extras`),
  ADD KEY `total_devengado_id` (`total_devengado_id`);

--
-- Indices de la tabla `icbf`
--
ALTER TABLE `icbf`
  ADD PRIMARY KEY (`id_icbf`),
  ADD KEY `parafiscales_id` (`parafiscales_id`);

--
-- Indices de la tabla `intereses`
--
ALTER TABLE `intereses`
  ADD PRIMARY KEY (`id_intereses`),
  ADD KEY `prestaciones_sociales_id` (`prestaciones_sociales_id`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`id_nomina`),
  ADD KEY `total_devengado_id` (`total_devengado_id`),
  ADD KEY `total_deducido_id` (`total_deducido_id`),
  ADD KEY `user_id_doc` (`user_id_doc`),
  ADD KEY `empleados_id` (`empleados_id`);

--
-- Indices de la tabla `parafiscales`
--
ALTER TABLE `parafiscales`
  ADD PRIMARY KEY (`id_parafiscales`);

--
-- Indices de la tabla `parafiscales_has_total_devengado`
--
ALTER TABLE `parafiscales_has_total_devengado`
  ADD PRIMARY KEY (`parafiscales_id`,`total_devengado_id`),
  ADD KEY `total_devengado_id` (`total_devengado_id`);

--
-- Indices de la tabla `pension`
--
ALTER TABLE `pension`
  ADD PRIMARY KEY (`id_pension`),
  ADD KEY `total_deducido_id` (`total_deducido_id`),
  ADD KEY `seguridad_social_id` (`seguridad_social_id`);

--
-- Indices de la tabla `prestaciones_sociales`
--
ALTER TABLE `prestaciones_sociales`
  ADD PRIMARY KEY (`id_prestaciones`);

--
-- Indices de la tabla `prima`
--
ALTER TABLE `prima`
  ADD PRIMARY KEY (`id_prima`),
  ADD KEY `prestaciones_sociales_id` (`prestaciones_sociales_id`);

--
-- Indices de la tabla `rangosuvt_has_total_deducido`
--
ALTER TABLE `rangosuvt_has_total_deducido`
  ADD PRIMARY KEY (`rangosuvt_id`,`total_deducido_id`),
  ADD KEY `total_deducido_id` (`total_deducido_id`);

--
-- Indices de la tabla `rangos_uvt`
--
ALTER TABLE `rangos_uvt`
  ADD PRIMARY KEY (`idrangosuvt`);

--
-- Indices de la tabla `retencion_fuente`
--
ALTER TABLE `retencion_fuente`
  ADD PRIMARY KEY (`id_retencion`),
  ADD KEY `total_deducido_id` (`total_deducido_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_has_user`
--
ALTER TABLE `rol_has_user`
  ADD PRIMARY KEY (`rol_id_rol`,`user_id_doc`),
  ADD KEY `user_id_doc` (`user_id_doc`);

--
-- Indices de la tabla `salud`
--
ALTER TABLE `salud`
  ADD PRIMARY KEY (`id_salud`),
  ADD KEY `total_deducido_id` (`total_deducido_id`),
  ADD KEY `seguridad_social_id` (`seguridad_social_id`);

--
-- Indices de la tabla `seguridad_social`
--
ALTER TABLE `seguridad_social`
  ADD PRIMARY KEY (`idseguridad`),
  ADD KEY `total_deducido_id` (`total_deducido_id`);

--
-- Indices de la tabla `sena`
--
ALTER TABLE `sena`
  ADD PRIMARY KEY (`id_sena`),
  ADD KEY `parafiscales_id` (`parafiscales_id`);

--
-- Indices de la tabla `solidaridad`
--
ALTER TABLE `solidaridad`
  ADD PRIMARY KEY (`id_solidaridad`),
  ADD KEY `total_deducido_id` (`total_deducido_id`);

--
-- Indices de la tabla `total_deducido`
--
ALTER TABLE `total_deducido`
  ADD PRIMARY KEY (`id_total_deducido`);

--
-- Indices de la tabla `total_devengado`
--
ALTER TABLE `total_devengado`
  ADD PRIMARY KEY (`id_total_devengado`);

--
-- Indices de la tabla `total_devengado_has_prestaciones_sociales`
--
ALTER TABLE `total_devengado_has_prestaciones_sociales`
  ADD PRIMARY KEY (`total_devengado_id`,`prestaciones_sociales_id`),
  ADD KEY `prestaciones_sociales_id` (`prestaciones_sociales_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_doc`),
  ADD KEY `empleados_id_empleados` (`empleados_id_empleados`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id_vacaciones`),
  ADD KEY `prestaciones_sociales_id` (`prestaciones_sociales_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `arl`
--
ALTER TABLE `arl`
  MODIFY `id_arl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auxilio_transporte`
--
ALTER TABLE `auxilio_transporte`
  MODIFY `id_transporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cesantias`
--
ALTER TABLE `cesantias`
  MODIFY `id_cesantias` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id_comisiones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compensacion`
--
ALTER TABLE `compensacion`
  MODIFY `id_compensacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `desprendible_de_nomina`
--
ALTER TABLE `desprendible_de_nomina`
  MODIFY `id_desprendible` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `gestion_de_reportes`
--
ALTER TABLE `gestion_de_reportes`
  MODIFY `id_reportes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_extras`
--
ALTER TABLE `horas_extras`
  MODIFY `id_extras` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `icbf`
--
ALTER TABLE `icbf`
  MODIFY `id_icbf` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intereses`
--
ALTER TABLE `intereses`
  MODIFY `id_intereses` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id_nomina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parafiscales`
--
ALTER TABLE `parafiscales`
  MODIFY `id_parafiscales` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pension`
--
ALTER TABLE `pension`
  MODIFY `id_pension` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestaciones_sociales`
--
ALTER TABLE `prestaciones_sociales`
  MODIFY `id_prestaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prima`
--
ALTER TABLE `prima`
  MODIFY `id_prima` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rangos_uvt`
--
ALTER TABLE `rangos_uvt`
  MODIFY `idrangosuvt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `retencion_fuente`
--
ALTER TABLE `retencion_fuente`
  MODIFY `id_retencion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salud`
--
ALTER TABLE `salud`
  MODIFY `id_salud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguridad_social`
--
ALTER TABLE `seguridad_social`
  MODIFY `idseguridad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sena`
--
ALTER TABLE `sena`
  MODIFY `id_sena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solidaridad`
--
ALTER TABLE `solidaridad`
  MODIFY `id_solidaridad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `total_deducido`
--
ALTER TABLE `total_deducido`
  MODIFY `id_total_deducido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `total_devengado`
--
ALTER TABLE `total_devengado`
  MODIFY `id_total_devengado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id_vacaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `arl`
--
ALTER TABLE `arl`
  ADD CONSTRAINT `arl_ibfk_1` FOREIGN KEY (`seguridad_social_id`) REFERENCES `seguridad_social` (`idseguridad`);

--
-- Filtros para la tabla `auxilio_transporte`
--
ALTER TABLE `auxilio_transporte`
  ADD CONSTRAINT `auxilio_transporte_ibfk_1` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`);

--
-- Filtros para la tabla `cesantias`
--
ALTER TABLE `cesantias`
  ADD CONSTRAINT `cesantias_ibfk_1` FOREIGN KEY (`prestaciones_sociales_id`) REFERENCES `prestaciones_sociales` (`id_prestaciones`);

--
-- Filtros para la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD CONSTRAINT `comisiones_ibfk_1` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`);

--
-- Filtros para la tabla `compensacion`
--
ALTER TABLE `compensacion`
  ADD CONSTRAINT `compensacion_ibfk_1` FOREIGN KEY (`parafiscales_id`) REFERENCES `parafiscales` (`id_parafiscales`);

--
-- Filtros para la tabla `desprendible_de_nomina`
--
ALTER TABLE `desprendible_de_nomina`
  ADD CONSTRAINT `desprendible_de_nomina_ibfk_1` FOREIGN KEY (`empleados_id`) REFERENCES `empleados` (`id_empleados`);

--
-- Filtros para la tabla `desprendible_de_nomina_has_nomina`
--
ALTER TABLE `desprendible_de_nomina_has_nomina`
  ADD CONSTRAINT `desprendible_de_nomina_has_nomina_ibfk_1` FOREIGN KEY (`desprendible_de_nomina_id`) REFERENCES `desprendible_de_nomina` (`id_desprendible`),
  ADD CONSTRAINT `desprendible_de_nomina_has_nomina_ibfk_2` FOREIGN KEY (`nomina_id`) REFERENCES `nomina` (`id_nomina`);

--
-- Filtros para la tabla `gestion_de_reportes_has_nomina`
--
ALTER TABLE `gestion_de_reportes_has_nomina`
  ADD CONSTRAINT `gestion_de_reportes_has_nomina_ibfk_1` FOREIGN KEY (`gestion_de_reportes_id`) REFERENCES `gestion_de_reportes` (`id_reportes`),
  ADD CONSTRAINT `gestion_de_reportes_has_nomina_ibfk_2` FOREIGN KEY (`nomina_id`) REFERENCES `nomina` (`id_nomina`);

--
-- Filtros para la tabla `horas_extras`
--
ALTER TABLE `horas_extras`
  ADD CONSTRAINT `horas_extras_ibfk_1` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`);

--
-- Filtros para la tabla `icbf`
--
ALTER TABLE `icbf`
  ADD CONSTRAINT `icbf_ibfk_1` FOREIGN KEY (`parafiscales_id`) REFERENCES `parafiscales` (`id_parafiscales`);

--
-- Filtros para la tabla `intereses`
--
ALTER TABLE `intereses`
  ADD CONSTRAINT `intereses_ibfk_1` FOREIGN KEY (`prestaciones_sociales_id`) REFERENCES `prestaciones_sociales` (`id_prestaciones`);

--
-- Filtros para la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `nomina_ibfk_1` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`),
  ADD CONSTRAINT `nomina_ibfk_2` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`),
  ADD CONSTRAINT `nomina_ibfk_3` FOREIGN KEY (`user_id_doc`) REFERENCES `user` (`id_doc`),
  ADD CONSTRAINT `nomina_ibfk_4` FOREIGN KEY (`empleados_id`) REFERENCES `empleados` (`id_empleados`);

--
-- Filtros para la tabla `parafiscales_has_total_devengado`
--
ALTER TABLE `parafiscales_has_total_devengado`
  ADD CONSTRAINT `parafiscales_has_total_devengado_ibfk_1` FOREIGN KEY (`parafiscales_id`) REFERENCES `parafiscales` (`id_parafiscales`),
  ADD CONSTRAINT `parafiscales_has_total_devengado_ibfk_2` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`);

--
-- Filtros para la tabla `pension`
--
ALTER TABLE `pension`
  ADD CONSTRAINT `pension_ibfk_1` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`),
  ADD CONSTRAINT `pension_ibfk_2` FOREIGN KEY (`seguridad_social_id`) REFERENCES `seguridad_social` (`idseguridad`);

--
-- Filtros para la tabla `prima`
--
ALTER TABLE `prima`
  ADD CONSTRAINT `prima_ibfk_1` FOREIGN KEY (`prestaciones_sociales_id`) REFERENCES `prestaciones_sociales` (`id_prestaciones`);

--
-- Filtros para la tabla `rangosuvt_has_total_deducido`
--
ALTER TABLE `rangosuvt_has_total_deducido`
  ADD CONSTRAINT `rangosuvt_has_total_deducido_ibfk_1` FOREIGN KEY (`rangosuvt_id`) REFERENCES `rangos_uvt` (`idrangosuvt`),
  ADD CONSTRAINT `rangosuvt_has_total_deducido_ibfk_2` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`);

--
-- Filtros para la tabla `retencion_fuente`
--
ALTER TABLE `retencion_fuente`
  ADD CONSTRAINT `retencion_fuente_ibfk_1` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`);

--
-- Filtros para la tabla `rol_has_user`
--
ALTER TABLE `rol_has_user`
  ADD CONSTRAINT `rol_has_user_ibfk_1` FOREIGN KEY (`rol_id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `rol_has_user_ibfk_2` FOREIGN KEY (`user_id_doc`) REFERENCES `user` (`id_doc`);

--
-- Filtros para la tabla `salud`
--
ALTER TABLE `salud`
  ADD CONSTRAINT `salud_ibfk_1` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`),
  ADD CONSTRAINT `salud_ibfk_2` FOREIGN KEY (`seguridad_social_id`) REFERENCES `seguridad_social` (`idseguridad`);

--
-- Filtros para la tabla `seguridad_social`
--
ALTER TABLE `seguridad_social`
  ADD CONSTRAINT `seguridad_social_ibfk_1` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`);

--
-- Filtros para la tabla `sena`
--
ALTER TABLE `sena`
  ADD CONSTRAINT `sena_ibfk_1` FOREIGN KEY (`parafiscales_id`) REFERENCES `parafiscales` (`id_parafiscales`);

--
-- Filtros para la tabla `solidaridad`
--
ALTER TABLE `solidaridad`
  ADD CONSTRAINT `solidaridad_ibfk_1` FOREIGN KEY (`total_deducido_id`) REFERENCES `total_deducido` (`id_total_deducido`);

--
-- Filtros para la tabla `total_devengado_has_prestaciones_sociales`
--
ALTER TABLE `total_devengado_has_prestaciones_sociales`
  ADD CONSTRAINT `total_devengado_has_prestaciones_sociales_ibfk_1` FOREIGN KEY (`total_devengado_id`) REFERENCES `total_devengado` (`id_total_devengado`),
  ADD CONSTRAINT `total_devengado_has_prestaciones_sociales_ibfk_2` FOREIGN KEY (`prestaciones_sociales_id`) REFERENCES `prestaciones_sociales` (`id_prestaciones`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`empleados_id_empleados`) REFERENCES `empleados` (`id_empleados`);

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `vacaciones_ibfk_1` FOREIGN KEY (`prestaciones_sociales_id`) REFERENCES `prestaciones_sociales` (`id_prestaciones`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
