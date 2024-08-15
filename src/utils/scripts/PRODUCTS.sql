CREATE TABLE IF NOT EXISTS products (
  `idProduct` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` DOUBLE NOT NULL,
  `stock` INT NOT NULL,
  PRIMARY KEY (`idProduct`)
  );