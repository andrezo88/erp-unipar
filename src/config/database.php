<?php

class Database {
 private $host = 'localhost';
 private $db = 'erp_unipar';
 private $user = 'root';
 private $pass = '';
 
 public function connection() {
  try {
   $conn = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->pass);
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   return $conn;
  } catch(PDOException $e) {
   echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
   exit();
  }
 }
 
//  private function createTable() {
//   try {
//    $conn = $this->connection();
//    $sql = "CREATE TABLE IF NOT EXISTS users (
//     idUser INT NOT NULL AUTO_INCREMENT,
//     name VARCHAR(50) NOT NULL,
//     email VARCHAR(255) NOT NULL UNIQUE,
//     password VARCHAR(32) NOT NULL,
//     idAddress INT,
//     idCart INT,
//     PRIMARY KEY (`idUser`),
//     CONSTRAINT `fk_users_idAddress`
//       FOREIGN KEY (`idAddress`)
//       REFERENCES address (`idAddress`)
//       ON DELETE NO ACTION
//       ON UPDATE NO ACTION,
//     CONSTRAINT `fk_users_idCart`
//       FOREIGN KEY (`idCart`)
//       REFERENCES carts (`idCart`)
//       ON DELETE NO ACTION
//       ON UPDATE NO ACTION
//   )";
//    $conn->exec($sql);
//    return $conn;
//   } catch(PDOException $e) {
//    echo 'Erro ao criar a tabela: ' . $e->getMessage();
//    exit();
//   }
//  }

// function execSql($sql) {
//   try {
//    $conn = $this->createTable();
//    $result = $conn->query($sql);
//    return $result;
//   } catch(PDOException $e) {
//    echo 'Erro ao executar a query: ' . $e->getMessage();
//    exit();
//   }
//  }
}

?>