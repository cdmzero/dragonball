SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Base de datos: `torneo`
CREATE DATABASE IF NOT EXISTS `torneo` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `torneo`;

-- tabla `administradores`
DROP TABLE IF EXISTS `administradores`;
CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `administradores` (`id`, `email`, `password`) VALUES 
('1', 'admin@admin.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
('2', 'pepe@pepe.com', '7c9e7c1494b2684ab7c19d6aff737e460fa9e98d5a234da1310c97ddf5691834');

-- tabla `luchadores`
DROP TABLE IF EXISTS `luchadores`;
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
  `imagen` text  COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Indices 
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `luchadores`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `luchadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
