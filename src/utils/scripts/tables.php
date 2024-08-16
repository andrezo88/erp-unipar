<?php 

require_once '../../config/database.php';

try {
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
        PRIMARY KEY (`idSupplier`)
    );
    
    CREATE TABLE IF NOT EXISTS carts (
        `idCart` INT NOT NULL AUTO_INCREMENT,
        `idProduct` INT NOT NULL,
        `idUser` INT NOT NULL,
        `idSupplier` INT NOT NULL,
        `idAddress` INT NOT NULL,
        `total` DOUBLE NOT NULL,
        PRIMARY KEY (`idCart`)
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

    CREATE TABLE IF NOT EXISTS supplier_products (
        `supplier_id` INT NOT NULL,
        `product_id` INT NOT NULL,
        PRIMARY KEY (`supplier_id`, `product_id`),
        FOREIGN KEY (`supplier_id`) REFERENCES suppliers(`idSupplier`),
        FOREIGN KEY (`product_id`) REFERENCES products(`idProduct`)
    );

    CREATE TABLE IF NOT EXISTS user_carts (
        `user_id` INT NOT NULL,
        `carts_id` INT NOT NULL,
        PRIMARY KEY (`user_id`, `carts_id`),
        FOREIGN KEY (`user_id`) REFERENCES users(`idUser`),
        FOREIGN KEY (`cart_id`) REFERENCES carts(`idCart`)
    );

    CREATE TABLE IF NOT EXISTS product_cart (
      `product_id` INT NOT NULL,
      `cart_id` INT NOT NULL,
      PRIMARY KEY (`product_id`, `cart_id`),
      FOREIGN KEY (`product_id`) REFERENCES products(`idProduct`),
      FOREIGN KEY (`cart_id`) REFERENCES carts(`idCart`)
    );


    CREATE TABLE IF NOT EXISTS supplier_address (
        `supplier_id` INT NOT NULL,
        `address_id` INT NOT NULL,
        PRIMARY KEY (`supplier_id`, `address_id`),
        FOREIGN KEY (`supplier_id`) REFERENCES suppliers(`idSupplier`),
        FOREIGN KEY (`address_id`) REFERENCES address(`idAddress`)
    );

    CREATE TABLE IF NOT EXISTS user_address (
        `user_id` INT NOT NULL,
        `address_id` INT NOT NULL,
        PRIMARY KEY (`user_id`, `address_id`),
        FOREIGN KEY (`user_id`) REFERENCES users(`idUser`),
        FOREIGN KEY (`address_id`) REFERENCES address(`idAddress`)
    );

    CREATE VIEW supplier_products_view AS
    SELECT 
        s.name AS supplier_name 
        p.name AS product_name
    FROM 
        suppliers s
    JOIN 
        supplier_products sp ON s.idSupplier = sp.supplier_id
    JOIN 
        products p ON sp.product_id = p.idProduct;
    
    
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
    echo "Tabelas e view criadas com sucesso.";
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
}
?>