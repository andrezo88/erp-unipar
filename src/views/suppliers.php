<?php

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if (empty($name) || empty($price) || empty($stock)) {
        echo 'Todos os campos são obrigatórios!';
    } 
    try {
        $db = new Database();
        $conn = $db->connection();

        $query = "INSERT INTO suppliers (name, price, stock) VALUES (:name, :price, :stock)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Erro de conexão: ' . $e->getMessage();
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Nome" required>
    <input type="double" name="price" placeholder="Valor" required>
    <input type="text" name="stock" placeholder="Estoque" required>
    <button type="submit">Registrar</button>
</form>