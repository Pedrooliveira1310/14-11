<?php
// ===== CONFIGURAÇÃO DE CONEXÃO COM O BANCO DE DADOS =====
$servidor = "localhost";
$usuario  = "root";       
$senha    = "Senai@118";  
$banco    = "drpeanut";   

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("❌ Erro na conexão: " . $conn->connect_error);
}

// ===== INSERÇÃO DE DADOS =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_fornecedor     = $_POST["nome_fornecedor"] ?? '';
    $nome_do_fornecedor  = $_POST["nome_do_fornecedor"] ?? '';
    $cnpj                = $_POST["cnpj"] ?? '';
    $endereco            = $_POST["endereco"] ?? '';
    $telefone            = $_POST["telefone"] ?? '';
    $email               = $_POST["email"] ?? '';
    $observacoes         = $_POST["observacoes"] ?? '';

    if (!empty($nome_fornecedor) && !empty($nome_do_fornecedor)) {
        // Uso de prepared statement com backticks
        $stmt = $conn->prepare("
            INSERT INTO fornecedores 
            (`Nome_Fornecedor`, `Nome_do_Fornecedor`, `cnpj`, `Endereco`, `Telefone`, `Email`, `Observacoes`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssss", $nome_fornecedor, $nome_do_fornecedor, $cnpj, $endereco, $telefone, $email, $observacoes);

        if ($stmt->execute()) {
            echo "<p class='msg sucesso'>✅ Dados inseridos com sucesso!</p>";
        } else {
            echo "<p class='msg erro'>❌ Erro ao inserir dados: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='msg erro'>⚠️ Preencha todos os campos obrigatórios.</p>";
    }
}

// ===== CONSULTA OS DADOS =====
$sql_select = "SELECT `Nome_Fornecedor`, `Nome_do_Fornecedor`, `cnpj`, `Endereco`, `Telefone`, `Email`, `Observacoes` FROM fornecedores";
$resultado = $conn->query($sql_select);
if (!$resultado) {
    die("❌ Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Fornecedores</title>
    <link rel="stylesheet" href="tela3.css">
    <link rel="stylesheet" href="fornecedores.css">
   
</head>

<body>
    <!-- ===== FORMULÁRIO DE CADASTRO ===== -->
    <div class="container">
        <h1>Cadastro de Fornecedores</h1>

        <form action="fornecedores.php" method="POST">
            <label>Nome Fornecedor:</label>
            <input type="text" name="nome_fornecedor" required placeholder="Digite o nome do fornecedor principal">

            <label>Nome do Fornecedor (Fantasia):</label>
            <input type="text" name="nome_do_fornecedor" required placeholder="Digite o nome fantasia">

            <label>CNPJ:</label>
            <input type="text" name="cnpj" placeholder="Digite o CNPJ">

            <label>Endereço:</label>
            <input type="text" name="endereco" placeholder="Digite o endereço">

            <label>Telefone:</label>
            <input type="text" name="telefone" placeholder="Digite o telefone">

            <label>Email:</label>
            <input type="email" name="email" placeholder="Digite o email">

            <label>Observações:</label>
            <input type="text" name="observacoes" placeholder="Observações adicionais">

            <input type="submit" value="Cadastrar">
        </form>
    </div>

    <!-- ===== LISTAGEM DE FORNECEDORES ===== -->
    <div class="fornecedor">
        <h1>Listagem de Fornecedores</h1>

        <?php
        if ($resultado && $resultado->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Nome Fornecedor</th>
                    <th>Nome do Fornecedor</th>
                    <th>CNPJ</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Observações</th>
                  </tr>";

            while ($linha = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $linha['Nome_Fornecedor'] . "</td>";
                echo "<td>" . $linha['Nome_do_Fornecedor'] . "</td>";
                echo "<td>" . $linha['cnpj'] . "</td>";
                echo "<td>" . $linha['Endereco'] . "</td>";
                echo "<td>" . $linha['Telefone'] . "</td>";
                echo "<td>" . $linha['Email'] . "</td>";
                echo "<td>" . $linha['Observacoes'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p class='msg'>Nenhum fornecedor cadastrado ainda.</p>";
        }

        // Fecha a conexão
        $conn->close();
        ?>
    </div>
</body>
</html>