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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $street = trim($_POST['street']);
    $number = trim($_POST['number']);
    $district = trim($_POST['district']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);

    // Validação básica
    if (empty($name) || empty($email) || empty($password) || empty($street) || empty($number) || empty($district) || empty($city) || empty($state) || empty($country)) {
        echo 'Todos os campos são obrigatórios!';
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'E-mail inválido!';
        exit;
    }

    $hashedPassword = hash('sha256', $password);

    try {
        $db = new Database();
        $conn = $db->connection();

        // Iniciar transação
        $conn->beginTransaction();

        // Inserir dados na tabela users
        $queryUser = "INSERT INTO users (name, email, password, created_by) VALUES (:name, :email, :password, :created_by)";
        $stmtUser = $conn->prepare($queryUser);
        $stmtUser->bindParam(':name', $name);
        $stmtUser->bindParam(':email', $email);
        $stmtUser->bindParam(':password', $hashedPassword);
        $stmtUser->bindParam(':created_by', $_SESSION['user_id']);

        if (!$stmtUser->execute()) {
            throw new Exception('Erro ao cadastrar o usuário!');
        }

        // Obter o idUser gerado
        $userId = $conn->lastInsertId();

        // Inserir dados na tabela address
        $queryAddress = "INSERT INTO address (street, number, district, city, state, country) VALUES (:street, :number, :district, :city, :state, :country)";
        $stmtAddress = $conn->prepare($queryAddress);
        $stmtAddress->bindParam(':street', $street);
        $stmtAddress->bindParam(':number', $number);
        $stmtAddress->bindParam(':district', $district);
        $stmtAddress->bindParam(':city', $city);
        $stmtAddress->bindParam(':state', $state);
        $stmtAddress->bindParam(':country', $country);

        if (!$stmtAddress->execute()) {
            throw new Exception('Erro ao cadastrar o endereço!');
        }

        // Obter o idAddress gerado
        $idAddress = $conn->lastInsertId();

        // Inserir dados na tabela user_address
        $queryUserAddress = "INSERT INTO user_address (user_id, address_id) VALUES (:userId, :idAddress)";
        $stmtUserAddress = $conn->prepare($queryUserAddress);
        $stmtUserAddress->bindParam(':userId', $userId);
        $stmtUserAddress->bindParam(':idAddress', $idAddress);

        if (!$stmtUserAddress->execute()) {
            throw new Exception('Erro ao cadastrar a relação usuário-endereço!');
        }

        // Confirmar transação
        $conn->commit();

        echo 'Usuário e endereço cadastrados com sucesso!';
    } catch (PDOException $e) {
        $conn->rollBack();
        echo 'Erro de conexão: ' . $e->getMessage();
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'Erro: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
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
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Senha" required>
        <input type="text" name="street" placeholder="Rua" required>
        <input type="text" name="number" placeholder="Número" required>
        <input type="text" name="district" placeholder="Bairro" required>
        <input type="text" name="city" placeholder="Cidade" required>
        <input type="text" name="state" placeholder="Estado" required>
        <input type="text" name="country" placeholder="País" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>