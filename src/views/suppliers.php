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
    $cnpj = trim($_POST['cnpj']);
    $idProducts = $_POST['idProduct']; // Array of selected products
    $street = trim($_POST['street']);
    $number = trim($_POST['number']);
    $district = trim($_POST['district']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);

    if (empty($name) || empty($cnpj) || empty($idProducts) || empty($street) || empty($number) || empty($district) || empty($city) || empty($state) || empty($country)) {
        echo 'Todos os campos são obrigatórios!';
    } else {
        try {
            $db = new Database();
            $conn = $db->connection();

            $conn->beginTransaction();

            // Inserir o fornecedor
            $querySupplier = "INSERT INTO suppliers (name, cnpj, created_by) VALUES (:name, :cnpj, :created_by)";
            $stmtSupplier = $conn->prepare($querySupplier);
            $stmtSupplier->bindParam(':name', $name);
            $stmtSupplier->bindParam(':cnpj', $cnpj);
            $stmtSupplier->bindParam(':created_by', $_SESSION['user_id']);

            if ($stmtSupplier->execute()) {
                $supplierId = $conn->lastInsertId();

                // Inserir o endereço
                $queryAddress = "INSERT INTO address (street, number, district, city, state, country) VALUES (:street, :number, :district, :city, :state, :country)";
                $stmtAddress = $conn->prepare($queryAddress);
                $stmtAddress->bindParam(':street', $street);
                $stmtAddress->bindParam(':number', $number);
                $stmtAddress->bindParam(':district', $district);
                $stmtAddress->bindParam(':city', $city);
                $stmtAddress->bindParam(':state', $state);
                $stmtAddress->bindParam(':country', $country);

                if ($stmtAddress->execute()) {
                    $addressId = $conn->lastInsertId();

                    // Associar o fornecedor ao endereço
                    $querySupplierAddress = "INSERT INTO supplier_address (supplier_id, address_id) VALUES (:supplier_id, :address_id)";
                    $stmtSupplierAddress = $conn->prepare($querySupplierAddress);
                    $stmtSupplierAddress->bindParam(':supplier_id', $supplierId);
                    $stmtSupplierAddress->bindParam(':address_id', $addressId);

                    if (!$stmtSupplierAddress->execute()) {
                        echo 'Erro ao associar endereço ao fornecedor!';
                        var_dump($stmtSupplierAddress->errorInfo());
                    }

                    // Associar produtos ao fornecedor
                    foreach ($idProducts as $productId) {
                        $querySupplierProduct = "INSERT INTO supplier_products (supplier_id, product_id) VALUES (:supplier_id, :product_id)";
                        $stmtSupplierProduct = $conn->prepare($querySupplierProduct);
                        $stmtSupplierProduct->bindParam(':supplier_id', $supplierId);
                        $stmtSupplierProduct->bindParam(':product_id', $productId);
                        if (!$stmtSupplierProduct->execute()) {
                            echo 'Erro ao associar produto ao fornecedor!';
                            var_dump($stmtSupplierProduct->errorInfo());
                        }
                    }
                    echo 'Fornecedor cadastrado com sucesso!';
                } else {
                    echo 'Erro ao cadastrar o endereço!';
                    var_dump($stmtAddress->errorInfo());
                }
            } else {
                echo 'Erro ao cadastrar o fornecedor!';
                var_dump($stmtSupplier->errorInfo());
            }

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            echo 'Erro de conexão: ' . $e->getMessage();
        }
    }
}

// Recuperar todos os produtos da tabela products
try {
    $db = new Database();
    $conn = $db->connection();
    $productQuery = "SELECT idProduct, name FROM products";
    $productStmt = $conn->prepare($productQuery);
    $productStmt->execute();
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Fornecedor</title>
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
        <input type="text" name="cnpj" placeholder="CNPJ" required><br>
        <label>Selecione os produtos:</label><br>
        <?php foreach ($products as $product): ?>
            <input type="checkbox" name="idProduct[]" value="<?php echo $product['idProduct']; ?>">
            <?php echo $product['name']; ?><br>
        <?php endforeach; ?>
        <input type="text" name="street" placeholder="Rua" required>
        <input type="text" name="number" placeholder="Número" required>
        <input type="text" name="district" placeholder="Bairro" required>
        <input type="text" name="city" placeholder="Cidade" required>
        <input type="text" name="state" placeholder="Estado" required>
        <input type="text" name="country" placeholder="País" required>
        <br>
        <button type="submit">Registrar</button>
        <button type="button" onclick="window.location.href = '../views'">Voltar</button>
    </form>
</body>
</html>