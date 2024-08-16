<?php
session_start();
require_once '../config/database.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Função de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if (empty($name) || empty($price) || empty($stock)) {
        echo 'Todos os campos são obrigatórios!';
    } else {
        try {
            $db = new Database();
            $conn = $db->connection();

            $query = "INSERT INTO products (name, price, stock, created_by) VALUES (:name, :price, :stock, :created_by)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':created_by', $_SESSION['user_id']);
            if ($stmt->execute()) {
                echo 'Produto cadastrado com sucesso!';
            } else {
                echo 'Erro ao cadastrar o produto!';
                var_dump($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <style>
        .logout {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="logout">
        <a href="?logout=true">Logout</a>
    </div>
    <form method="POST">
        <input type="text" name="name" placeholder="Nome" required>
        <input type="number" step="0.01" name="price" placeholder="Preço" required>
        <input type="number" name="stock" placeholder="Estoque" required>
        <br>
        <button type="submit">Registrar</button>
        <button type="button" onclick="window.location.href = '../views'">Voltar</button>
    </form>
</body>
</html>