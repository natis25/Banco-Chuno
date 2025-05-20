-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2025 a las 01:42:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Base de datos: `pago`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `pago`
--
CREATE TABLE `pago` (
    `idPago` int(11) NOT NULL,
    `idCuenta` varchar(20) DEFAULT NULL,
    `idTipoServicio` int(11) DEFAULT NULL,
    `monto` int(11) DEFAULT NULL,
    `fechaPago` date DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tiposervicio`
--
CREATE TABLE `tiposervicio` (
    `idTipoServicio` int(11) NOT NULL,
    `nombreServicio` varchar(50) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `tiposervicio`
--
INSERT INTO `tiposervicio` (`idTipoServicio`, `nombreServicio`)
VALUES (1, 'Luz'),
    (2, 'Agua'),
    (3, 'Telefono'),
    (4, 'Gas');
--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
ADD PRIMARY KEY (`idPago`);
--
-- Indices de la tabla `tiposervicio`
--
ALTER TABLE `tiposervicio`
ADD PRIMARY KEY (`idTipoServicio`);
--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
MODIFY `idPago` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tiposervicio`
--
ALTER TABLE `tiposervicio`
MODIFY `idTipoServicio` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 8;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;