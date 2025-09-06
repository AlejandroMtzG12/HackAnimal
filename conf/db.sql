-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-09-2025 a las 01:30:06
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hackanimal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adopter`
--

DROP TABLE IF EXISTS `adopter`;
CREATE TABLE IF NOT EXISTS `adopter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `email` tinytext,
  `password` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postalCode` varchar(10) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `address` text,
  `age` varchar(3) DEFAULT NULL,
  `housingType` varchar(255) DEFAULT NULL,
  `otherPets` tinyint(1) DEFAULT NULL,
  `children` tinyint(1) DEFAULT NULL,
  `freeTime` text,
  `phone` varchar(255) DEFAULT NULL,
  `yard` tinyint(1) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adoptioncenter`
--

DROP TABLE IF EXISTS `adoptioncenter`;
CREATE TABLE IF NOT EXISTS `adoptioncenter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `photo` text,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `adoptioncenter`
--

INSERT INTO `adoptioncenter` (`id`, `user`, `password`, `country`, `state`, `city`, `postalCode`, `street`, `photo`, `name`) VALUES
(6, 'prueba1', '$2y$10$/TqHBd/gVKLcIfhZvKh6y.cqBoKeVXmhjwuwb412pMxaG9xbk4BDW', 'México', 'Pue.', 'Atlixco', '74218', 'Prolongacion del Nardo 1004 Col. Vista Hermosa', 'uploads/shelter_68bb557b0c868_priebapriebahack.jpg', 'prueba1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `file`
--

DROP TABLE IF EXISTS `file`;
CREATE TABLE IF NOT EXISTS `file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` enum('UpForAdoption','InQuarantine','Adopted') DEFAULT NULL,
  `conditions` text,
  `disability` text,
  `sterilization` tinyint(1) DEFAULT NULL,
  `quarantine` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `file`
--

INSERT INTO `file` (`id`, `status`, `conditions`, `disability`, `sterilization`, `quarantine`) VALUES
(1, 'UpForAdoption', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pet`
--

DROP TABLE IF EXISTS `pet`;
CREATE TABLE IF NOT EXISTS `pet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adoptionCenterId` int DEFAULT NULL,
  `species` enum('Dog','Cat','SmallMammal') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int DEFAULT NULL,
  `coat` enum('None','Short','Medium','Large') DEFAULT NULL,
  `size` enum('ExtraSmall','Small','Medium','Large','ExtraLarge') DEFAULT NULL,
  `color` enum('Black','White','Brown','Golden','Cream','Grey','Tan','Brindle','Fawn','Red','Blue','Merle','Sable','Orange','Beige','Calico','Tortoiseshell','Tabby','Bicolor','Tricolor') DEFAULT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `description` text,
  `image` text,
  `gender` enum('Male','Female','Intersex') DEFAULT NULL,
  `fileId` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pet`
--

INSERT INTO `pet` (`id`, `adoptionCenterId`, `species`, `name`, `age`, `coat`, `size`, `color`, `breed`, `weight`, `description`, `image`, `gender`, `fileId`) VALUES
(1, 1, 'Dog', 'Alejandra', 20, 'Short', 'Medium', 'Black', 'Pug', 65, 'Perruna Ale', 'petImages/pet_68bb6285c5b2b_priebapriebahack.jpg', 'Male', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vaccines`
--

DROP TABLE IF EXISTS `vaccines`;
CREATE TABLE IF NOT EXISTS `vaccines` (
  `idVaccine` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `idFile` int DEFAULT NULL,
  PRIMARY KEY (`idVaccine`),
  KEY `idFile` (`idFile`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `vaccines`
--

INSERT INTO `vaccines` (`idVaccine`, `name`, `idFile`) VALUES
(1, 'Rabia', 1),
(2, 'Garrapatas', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
