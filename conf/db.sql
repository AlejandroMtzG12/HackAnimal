CREATE TABLE `adoptionCenter` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user` varchar(255),
  `password` varchar(255),
  `country` varchar(255),
  `state` varchar(255),
  `city` varchar(255),
  `postalCode` varchar(255),
  `street` varchar(255),
  `address` text
);

CREATE TABLE `pet` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `adoptionCenterId` int,
  `name` varchar(255),
  `age` int,
  `coat` varchar(255),
  `size` varchar(255),
  `color` varchar(255),
  `breed` varchar(255),
  `weight` float,
  `description` text,
  `gender` varchar(255),
  `fileId` int
);

CREATE TABLE `file` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `status` varchar(255),
  `vaccines` text,
  `conditions` text,
  `disability` text,
  `sterilization` boolean,
  `quarantine` boolean
);
CREATE TABLE `adopter` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user` varchar(50) UNIQUE,
  `name` varchar(55),
  `email` tinytext,
  `password` varchar(20),
  `country` varchar(255),
  `state` varchar(255),
  `city` varchar(255),
  `postalCode` varchar(10),
  `street` varchar(255),
  `address` text,
  `age` varchar(3),
  `housingType` varchar(255),
  `otherPets` boolean,
  `children` boolean,
  `freeTime` text,
  `phone` varchar(255),
  `yard` boolean,
  `description` text
);

ALTER TABLE `pet` ADD FOREIGN KEY (`adoptionCenterId`) REFERENCES `adoptionCenter` (`id`);

ALTER TABLE `pet` ADD FOREIGN KEY (`fileId`) REFERENCES `file` (`id`);
