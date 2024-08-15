<?php 
 require_once 'database.php';
 
 $db = new Database();
 $conn = $db->connection();
 
 $query = "
  CREATE TABLE IF NOT EXISTS products (
   `idProduct` INT NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `price` DOUBLE NOT NULL,
   `stock` INT NOT NULL,
   PRIMARY KEY (`idProduct`)
   );
   
  CREATE TABLE IF NOT EXISTS address (
    `idAddress` INT NOT NULL AUTO_INCREMENT,
    `street` VARCHAR(255) NOT NULL,
    `number` INT NOT NULL,
    `district` VARCHAR(45) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `city` VARCHAR(45) NOT NULL,
    `state` VARCHAR(45) NOT NULL,
    `country` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`idAddress`)
   );
   
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

  CREATE TABLE IF NOT EXISTS users (
   idUser INT NOT NULL AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   email VARCHAR(255) NOT NULL UNIQUE,
   password VARCHAR(32) NOT NULL,
   idAddress INT,
   idCart INT,
   PRIMARY KEY (`idUser`),
   CONSTRAINT `fk_users_idAddress`
     FOREIGN KEY (`idAddress`)
     REFERENCES address (`idAddress`)
     ON DELETE NO ACTION
     ON UPDATE NO ACTION,
   CONSTRAINT `fk_users_idCart`
     FOREIGN KEY (`idCart`)
     REFERENCES carts (`idCart`)
     ON DELETE NO ACTION
     ON UPDATE NO ACTION
 );

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
 );

";

 $conn->exec($query);
?>