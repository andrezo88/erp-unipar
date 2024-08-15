CREATE TABLE IF NOT EXISTS suppliers (
  `idSupplier` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `idAddress` INT,
  `idProduct` INT,
  PRIMARY KEY (`idSupplier`),
  CONSTRAINT fk_suppliers_idAddress
    FOREIGN KEY (`idAddress`)
    REFERENCES address (`idAddress`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_suppliers_idProduct
    FOREIGN KEY (`idProduct`)
    REFERENCES products (`idProduct`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)