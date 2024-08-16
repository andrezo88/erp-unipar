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
    `idSupplier` INT,
    PRIMARY KEY (`idProduct`)
   );

   CREATE TABLE IF NOT EXISTS suppliers (
    `idSupplier` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `cnpj` VARCHAR(14) NOT NULL UNIQUE,
    `idProduct` INT NOT NULL,
    PRIMARY KEY (`idSupplier`),
  );
    
  CREATE TABLE IF NOT EXISTS carts (
    `idCart` INT NOT NULL AUTO_INCREMENT,
    `idProduct` INT NOT NULL,
    `idUser` INT NOT NULL,
    `idSupplier` INT NOT NULL,
    `idAddress` INT NOT NULL,
    `total` DOUBLE NOT NULL,
    PRIMARY KEY (`idCart`),
  );

  CREATE TABLE IF NOT EXISTS address (
    `idAddress` INT NOT NULL AUTO_INCREMENT,
    `street` VARCHAR(255) NOT NULL,
    `number` INT NOT NULL,
    `district` VARCHAR(45) NOT NULL,
    `city` VARCHAR(45) NOT NULL,
    `state` VARCHAR(45) NOT NULL,
    `country` VARCHAR(45) NOT NULL,
    `idSupplier` INT NOT NULL,
    `idUser` INT NOT NULL,
    PRIMARY KEY (`idAddress`)
    );
    
    CREATE TABLE IF NOT EXISTS users (
      `idUser` INT NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(50) NOT NULL,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `idCart` INT,
      PRIMARY KEY (`idUser`),
    );

    ALTER TABLE products
    ADD CONSTRAINT fk_products_idSupplier
      FOREIGN KEY (idSupplier)
      REFERENCES suppliers (idSupplier)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;

    ALTER TABLE suppliers
    ADD CONSTRAINT fk_suppliers_idProduct
      FOREIGN KEY (`idProduct`)
      REFERENCES products (`idProduct`)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;

    ALTER TABLE carts
    ADD CONSTRAINT `fk_carts_idProduct`
      FOREIGN KEY (`idProduct`)
      REFERENCES products (`idProduct`)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;
    
    ALTER TABLE carts
    ADD CONSTRAINT fk_carts_idUser
      FOREIGN KEY (idUser)
      REFERENCES users (idUser)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;

    ALTER TABLE carts
    ADD CONSTRAINT fk_carts_idSupplier
      FOREIGN KEY (idSupplier)
      REFERENCES suppliers (idSupplier)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;
    
    ALTER TABLE carts
    ADD CONSTRAINT fk_carts_idAddress
      FOREIGN KEY (idAddress)
      REFERENCES address (idAddress)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;
    
    ALTER TABLE address
    ADD CONSTRAINT fk_address_idSupplier
      FOREIGN KEY (idSupplier)
      REFERENCES suppliers (idSupplier)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;
    
    ALTER TABLE address
    ADD CONSTRAINT fk_address_idUser
      FOREIGN KEY (idUser)
      REFERENCES users (idUser)
      ON UPDATE CASCADE 
      ON DELETE CASCADE;

    ALTER TABLE users
    ADD CONSTRAINT `fk_users_idCart`
      FOREIGN KEY (`idCart`)
      REFERENCES carts (`idCart`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION;

";
      
$conn->exec($query);
?>