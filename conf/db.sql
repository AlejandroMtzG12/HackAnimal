CREATE TABLE `CentroAdopcion` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `usuario` varchar(255),
  `contrasenia` varchar(255),
  `pais` varchar(255),
  `estado` varchar(255),
  `ciudad` varchar(255),
  `cp` varchar(255),
  `calle` varchar(255),
  `direccion` text
);

CREATE TABLE `Mascota` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `centro_id` int,
  `nombre` varchar(255),
  `edad` int,
  `pelaje` varchar(255),
  `tamanio` varchar(255),
  `color` varchar(255),
  `raza` varchar(255),
  `peso` float,
  `descripcion` text,
  `sexo` varchar(255),
  `expediente_id` int
);

CREATE TABLE `Expediente` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `estatus` varchar(255),
  `vacunas` text,
  `padecimientos` text,
  `discapacidad` text,
  `esterilizacion` boolean,
  `cuarentena` boolean
);

CREATE TABLE `Adoptante` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(255),
  `correo` varchar(255),
  `contrasena` varchar(255),
  `pais` varchar(255),
  `estado` varchar(255),
  `ciudad` varchar(255),
  `cp` varchar(255),
  `calle` varchar(255),
  `direccion` text,
  `edad` int,
  `tipo_vivienda` varchar(255),
  `otras_mascotas` boolean,
  `ninos` boolean,
  `tiempo_libre` text,
  `telefono` varchar(255),
  `patio` boolean,
  `descripcion` text
);

ALTER TABLE `Mascota` ADD FOREIGN KEY (`centro_id`) REFERENCES `CentroAdopcion` (`id`);

ALTER TABLE `Mascota` ADD FOREIGN KEY (`expediente_id`) REFERENCES `Expediente` (`id`);
