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

CREATE TABLE pet (
  id int PRIMARY KEY AUTO_INCREMENT,
  adoptionCenterId int,
  species ENUM('Dog', 'Cat', 'SmallMammal'),
  name varchar(255),
  age int,
  coat ENUM('None', 'Short', 'Medium', 'Large'),
  size ENUM('ExtraSmall', 'Small', 'Medium', 'Large', 'ExtraLarge'),
  color ENUM(
  'Black','White','Brown','Golden','Cream','Grey','Tan','Brindle','Fawn','Red','Blue','Merle','Sable',
  'Orange','Beige','Calico','Tortoiseshell','Tabby','Bicolor','Tricolor'
),
  breed varchar(255),
  weight float,
  description text,
  image text,
  gender ENUM('Male','Female', 'Intersex'),
  fileId int
);

CREATE TABLE file (
  id int PRIMARY KEY AUTO_INCREMENT,
  status ENUM('UpForAdoption','InQuarantine', 'Adopted'),

  conditions text,
  disability text,
  sterilization boolean,
  quarantine int
);

CREATE TABLE vaccines (
idVaccine int
name VARCHAR(255)
) 



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
ALTER TABLE `vaccines` ADD FOREIGN KEY (`idFile`) REFERENCES `file` (`id`);
