-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-02-2020 a las 21:24:28
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `torneo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `luchadores`
--

CREATE TABLE `luchadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `raza` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ki` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `transformacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ataque` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `planeta` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `luchadores`
--

INSERT INTO `luchadores` (`id`, `nombre`, `raza`, `ki`, `transformacion`, `ataque`, `planeta`, `password`, `fecha`, `imagen`) VALUES
(6, 'pe', 'tericula', '111', 'SI', 'fisico, onda', 'ppp77', 'edce54bffa23b06e04aa05c6d6800b1bbc6a94e983dd8171325f1729c14363a0', '23/01/2020', 'a9ba15c73683fa3046dd25a49d23b5a3.jpeg'),
(9, 'prueba', 'saiyan', '112', 'SI', 'fisico', 'asa99', '140e0d2deeb6d6b1b803087c03821448c95f3be61ffd27c89f6c391a3288a838', '01/02/2020', '525d8f9cfd95568cf5f0cafdd1c6064c.jpeg'),
(10, 'Son Gokuiyo', 'Terricola', '112', 'SI', 'Fisico', 'PLA21', 'e10adc3949ba59abbe56e057f20f883e', '15/02/2020', '8c93e916b4b1b73fdbfeba7e949c9af6.jpeg'),
(14, 'Jose Terricola', 'Saiyan', '900', 'NO', 'Fisico, Ninguno', 'PLA21', 'fddd21b9d7ce17da93c30fa5a653a1df', '01/01/1970', '4ec13896845fca8cf85a48b84f171e9b.jpeg'),
(15, 'Pacote', 'Terricola', '899', 'SI', 'Onda', 'TIE11', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '01/01/1970', 'af7867a5731411ba360f9f57a12e185f.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`) VALUES
(1, 'admin@admin.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
(2, 'pepe@pepe.com', '7c9e7c1494b2684ab7c19d6aff737e460fa9e98d5a234da1310c97ddf5691834');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `luchadores`
--
ALTER TABLE `luchadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `luchadores`
--
ALTER TABLE `luchadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
