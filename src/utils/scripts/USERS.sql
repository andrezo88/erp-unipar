CREATE TABLE IF NOT EXISTS users (
  idUser INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  idAddress INT,
  idCart INT,
  PRIMARY KEY (`idUser`),
  CONSTRAINT `idAddress`
    FOREIGN KEY (`idAddress`)
    REFERENCES address (`idAddress`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idCart`
    FOREIGN KEY (`idCart`)
    REFERENCES carts (`idCart`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)