-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2025 a las 00:52:21
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
-- Base de datos: `usuario`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `cliente`
--
CREATE TABLE `cliente` (
    `id_cliente` int(11) NOT NULL,
    `nombre_cliente` varchar(100) NOT NULL,
    `correo` varchar(100) NOT NULL,
    `celular` int(11) NOT NULL,
    `direccion` varchar(100) NOT NULL,
    `contrasena` varchar(50) NOT NULL,
    `fecha_registro` date NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `cliente`
--
INSERT INTO `cliente` (
        `id_cliente`,
        `nombre_cliente`,
        `correo`,
        `celular`,
        `direccion`,
        `contrasena`,
        `fecha_registro`
    )
VALUES (
        5,
        'Santiago Torrez',
        'santi@gmail.com',
        78956444,
        'Achumani',
        'Santi12345',
        '2025-05-12'
    ),
    (
        6,
        'Natalia Urrutia',
        'natalia.urrutia1325@gmail.com',
        73098719,
        'Sopocachi',
        'Nati12345',
        '2025-05-02'
    ),
    (
        7,
        'Mauricio Fernandez',
        'mauf@gmail.com',
        78955542,
        'Miraflores',
        'Mauri12345',
        '2025-05-03'
    ),
    (
        8,
        'Daniela Oropeza',
        'dani@gmail.com',
        68045615,
        'Miraflores',
        'Dani12345',
        '2025-05-06'
    ),
    (
        9,
        'Brandon Silva',
        'brandon.silva@gmail.com',
        79526685,
        'Irpavi',
        'Brandon12345',
        '2025-05-04'
    ),
    (
        10,
        'Claudia Perez',
        'Claudia.p@gmail.com',
        73098565,
        'Sopocachi',
        'Claudia12345',
        '2025-05-14'
    ),
    (
        12,
        'Mariana Ferreira',
        'mferreira@gmail.com',
        75623252,
        'Achumani',
        'Mariana12345',
        '2025-05-14'
    ),
    (
        13,
        'Vivian Pachecho',
        'vivian@gmail.com',
        78056415,
        'Achumani',
        'vivian12345',
        '2025-05-18'
    );
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `empleado`
--
CREATE TABLE `empleado` (
    `id_empleado` int(11) NOT NULL,
    `nombre_empleado` varchar(100) NOT NULL,
    `correo` varchar(100) NOT NULL,
    `celular` int(11) NOT NULL,
    `contrasena` varchar(100) NOT NULL,
    `fecha_registro` date NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `empleado`
--
INSERT INTO `empleado` (
        `id_empleado`,
        `nombre_empleado`,
        `correo`,
        `celular`,
        `contrasena`,
        `fecha_registro`
    )
VALUES (
        1,
        'Natalia',
        'nati@gmail.com',
        73098719,
        'nati12345',
        '2025-05-13'
    );
--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
ADD PRIMARY KEY (`id_cliente`);
--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
ADD PRIMARY KEY (`id_empleado`);
--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 15;
--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 2;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;