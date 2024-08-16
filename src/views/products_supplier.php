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

try {
    $db = new Database();
    $conn = $db->connection();

    // Obter a lista de fornecedores
    $supplierQuery = "SELECT idSupplier, name FROM suppliers";
    $supplierStmt = $conn->prepare($supplierQuery);
    $supplierStmt->execute();
    $suppliers = $supplierStmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar se um fornecedor foi selecionado
    $selectedSupplier = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : null;

    // Query para obter produtos do fornecedor selecionado
    $query = "SELECT * FROM supplier_products_view";
    
    if ($selectedSupplier) {
        $query .= " WHERE supplier_name = (SELECT name FROM suppliers WHERE idSupplier = :supplier_id)";
    }

    $stmt = $conn->prepare($query);

    if ($selectedSupplier) {
        $stmt->bindParam(':supplier_id', $selectedSupplier, PDO::PARAM_INT);
    }

    $stmt->execute();
    $supplierProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos por Fornecedor</title>
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
    <h1>Produtos por Fornecedor</h1>

    <!-- Formulário para selecionar o fornecedor -->
    <form method="post" action="">
        <label for="supplier_id">Selecione o Fornecedor:</label>
        <select name="supplier_id" id="supplier_id">
            <option value="">Todos</option>
            <?php foreach ($suppliers as $supplier): ?>
                <option value="<?php echo htmlspecialchars($supplier['idSupplier']); ?>" <?php if ($selectedSupplier == $supplier['idSupplier']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($supplier['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Fornecedor</th>
                <th>Produto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($supplierProducts as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Botão para voltar à página anterior -->
    <button onclick="window.location.href='../views';">Voltar</button>
</body>
</html>