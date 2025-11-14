<?php
include "conexao.php";

// ---------------------- CADASTRAR PRODUTO ----------------------
if (isset($_POST['cadastrar'])) {

    $nome            = $_POST['nome'];
    $descricao       = $_POST['descricao'];
    $quantidade      = $_POST['quantidade'];
    $preco           = $_POST['preco'];
    $nome_fornecedor = $_POST['nome_fornecedor'];

    // Upload de imagem
    $imagem = "";
    if (!empty($_FILES['imagem']['name'])) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $ext = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);
        $nomeImg = uniqid() . "." . $ext;

        $caminho = $pasta . $nomeImg;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $imagem = $caminho;
        }
    }

    $sql = $conn->prepare("
        INSERT INTO produtos (nome, descricao, quantidade, preco, nome_fornecedor, imagem)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $sql->bind_param("ssisss", $nome, $descricao, $quantidade, $preco, $nome_fornecedor, $imagem);
    $sql->execute();
}

// ---------------------- EXCLUIR PRODUTO ----------------------
if (isset($_GET['del'])) {
    $id = $_GET['del'];

    $imgQuery = $conn->prepare("SELECT imagem FROM produtos WHERE id_produtos=?");
    $imgQuery->bind_param("i", $id);
    $imgQuery->execute();
    $result = $imgQuery->get_result()->fetch_assoc();

    if ($result['imagem'] && file_exists($result['imagem'])) {
        unlink($result['imagem']);
    }

    $delete = $conn->prepare("DELETE FROM produtos WHERE id_produtos=?");
    $delete->bind_param("i", $id);
    $delete->execute();
}

// ---------------------- BUSCAR PRODUTOS ----------------------
$produtos = $conn->query("SELECT * FROM produtos");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="produtos.css">
</head>
<body>

<main>
    <div class="container">

        <h1>Cadastro de Produtos</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <h2>Nome do Produto:</h2>
            <input type="text" name="nome" required>

            <h2>Descrição:</h2>
            <input type="text" name="descricao">

            <h2>Quantidade em Estoque:</h2>
            <input type="number" name="quantidade" required>

            <h2>Preço:</h2>
            <input type="text" name="preco" required>

            <h2>Nome do Fornecedor:</h2>
            <input type="text" name="nome_fornecedor">

            <h2>Imagem:</h2>
            <input type="file" name="imagem" accept="image/*">

            <br><br>
            <input type="submit" name="cadastrar" value="Cadastrar">

        </form>

    </div>
</main>

<footer class="fornecedor">
    <h1>Listagem de Produtos</h1>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Fornecedor</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($p = $produtos->fetch_assoc()) { ?>
            <tr>
                <td><?= $p['id_produtos'] ?></td>
                <td><?= $p['nome'] ?></td>
                <td><?= $p['descricao'] ?></td>
                <td><?= $p['quantidade'] ?></td>
                <td>R$ <?= $p['preco'] ?></td>
                <td><?= $p['nome_fornecedor'] ?></td>
                <td>
                    <?php if ($p['imagem']) { ?>
                        <img src="<?= $p['imagem'] ?>" width="60">
                    <?php } ?>
                </td>
                <td>
                    <a href="?del=<?= $p['id_produtos'] ?>" onclick="return confirm('Excluir produto?')">Excluir</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</footer>

</body>
</html>
