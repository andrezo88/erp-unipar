<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo 'Todos os campos são obrigatórios!';
        error_log('Erro: Todos os campos são obrigatórios!', 0);
    } else {
        try {
            $db = new Database();
            $conn = $db->connection();

            $query = "SELECT idUser, password FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && hash_equals($user['password'], hash('sha256', $password))) {
                $_SESSION['user_id'] = $user['idUser'];
                echo 'Login realizado com sucesso!';
                // Redirecionar para a página inicial ou painel de controle
                header('Location: ../views');
                exit();
            } else {
                echo 'Email ou senha inválidos!';
                error_log('Erro: Email ou senha inválidos!', 0);
            }
        } catch (PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
            error_log('Erro de conexão: ' . $e->getMessage(), 0);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Login</button><br>
        <button type="button" onclick="window.location.href = '../views'">voltar</button>
    </form>
</body>
</html>