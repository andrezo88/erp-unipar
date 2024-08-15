CREATE TABLE IF NOT EXISTS carts (
  `idCart` INT NOT NULL AUTO_INCREMENT,
  `idProduct` INT NOT NULL,
  `total` DOUBLE NOT NULL,
  PRIMARY KEY (`idCart`),
  CONSTRAINT `fk_carts_idProduct`
    FOREIGN KEY (`idProduct`)
    REFERENCES products (`idProduct`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);