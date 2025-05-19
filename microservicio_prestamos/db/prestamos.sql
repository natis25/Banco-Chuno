-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2025 a las 06:49:49
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
-- Base de datos: `prestamos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `es_porcentaje` tinyint(1) DEFAULT 0,
  `esta_activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condiciones_contrato`
--

CREATE TABLE `condiciones_contrato` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_prestamo`
--

CREATE TABLE `estados_prestamo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plazos_prestamos`
--

CREATE TABLE `plazos_prestamos` (
  `id` int(11) NOT NULL,
  `duracion` int(11) NOT NULL,
  `tipo_periodo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `usuario_externo_id` int(11) NOT NULL,
  `tipo_prestamo_id` int(11) NOT NULL,
  `monto_total` decimal(15,2) NOT NULL,
  `plazo_prestamo_id` int(11) NOT NULL,
  `tasa_interes_id` int(11) NOT NULL,
  `puntaje_crediticio_id` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado_id` int(11) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_comisiones`
--

CREATE TABLE `prestamos_comisiones` (
  `prestamo_id` int(11) NOT NULL,
  `comision_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos_condiciones`
--

CREATE TABLE `prestamos_condiciones` (
  `prestamo_id` int(11) NOT NULL,
  `condicion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntajes_crediticios`
--

CREATE TABLE `puntajes_crediticios` (
  `id` int(11) NOT NULL,
  `nivel` varchar(20) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `puntaje_minimo` int(11) NOT NULL,
  `puntaje_maximo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasas_interes`
--

CREATE TABLE `tasas_interes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `esta_activa` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_periodo`
--

CREATE TABLE `tipos_periodo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_prestamo`
--

CREATE TABLE `tipos_prestamo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `condiciones_contrato`
--
ALTER TABLE `condiciones_contrato`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados_prestamo`
--
ALTER TABLE `estados_prestamo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `plazos_prestamos`
--
ALTER TABLE `plazos_prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_periodo_id` (`tipo_periodo_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_prestamo_id` (`tipo_prestamo_id`),
  ADD KEY `plazo_prestamo_id` (`plazo_prestamo_id`),
  ADD KEY `tasa_interes_id` (`tasa_interes_id`),
  ADD KEY `puntaje_crediticio_id` (`puntaje_crediticio_id`),
  ADD KEY `idx_prestamos_usuario` (`usuario_externo_id`),
  ADD KEY `idx_prestamos_estado` (`estado_id`);

--
-- Indices de la tabla `prestamos_comisiones`
--
ALTER TABLE `prestamos_comisiones`
  ADD PRIMARY KEY (`prestamo_id`,`comision_id`),
  ADD KEY `comision_id` (`comision_id`);

--
-- Indices de la tabla `prestamos_condiciones`
--
ALTER TABLE `prestamos_condiciones`
  ADD PRIMARY KEY (`prestamo_id`,`condicion_id`),
  ADD KEY `condicion_id` (`condicion_id`);

--
-- Indices de la tabla `puntajes_crediticios`
--
ALTER TABLE `puntajes_crediticios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nivel` (`nivel`);

--
-- Indices de la tabla `tasas_interes`
--
ALTER TABLE `tasas_interes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_periodo`
--
ALTER TABLE `tipos_periodo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipos_prestamo`
--
ALTER TABLE `tipos_prestamo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `condiciones_contrato`
--
ALTER TABLE `condiciones_contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados_prestamo`
--
ALTER TABLE `estados_prestamo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plazos_prestamos`
--
ALTER TABLE `plazos_prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puntajes_crediticios`
--
ALTER TABLE `puntajes_crediticios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tasas_interes`
--
ALTER TABLE `tasas_interes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_periodo`
--
ALTER TABLE `tipos_periodo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_prestamo`
--
ALTER TABLE `tipos_prestamo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `plazos_prestamos`
--
ALTER TABLE `plazos_prestamos`
  ADD CONSTRAINT `plazos_prestamos_ibfk_1` FOREIGN KEY (`tipo_periodo_id`) REFERENCES `tipos_periodo` (`id`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`tipo_prestamo_id`) REFERENCES `tipos_prestamo` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`plazo_prestamo_id`) REFERENCES `plazos_prestamos` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`tasa_interes_id`) REFERENCES `tasas_interes` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_4` FOREIGN KEY (`puntaje_crediticio_id`) REFERENCES `puntajes_crediticios` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_5` FOREIGN KEY (`estado_id`) REFERENCES `estados_prestamo` (`id`);

--
-- Filtros para la tabla `prestamos_comisiones`
--
ALTER TABLE `prestamos_comisiones`
  ADD CONSTRAINT `prestamos_comisiones_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`id`),
  ADD CONSTRAINT `prestamos_comisiones_ibfk_2` FOREIGN KEY (`comision_id`) REFERENCES `comisiones` (`id`);

--
-- Filtros para la tabla `prestamos_condiciones`
--
ALTER TABLE `prestamos_condiciones`
  ADD CONSTRAINT `prestamos_condiciones_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`id`),
  ADD CONSTRAINT `prestamos_condiciones_ibfk_2` FOREIGN KEY (`condicion_id`) REFERENCES `condiciones_contrato` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
