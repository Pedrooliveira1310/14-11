<?php
$servidor = "localhost";
$usuario  = "root";
$senha    = "Senai@118";
$banco    = "produtos";

// ---------------------- CONEXÃO ----------------------
$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// ---------------------- BUSCAR TODOS OS FORNECEDORES ----------------------
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Lista de Produtos</title>
</head>
<body>

<h1>Lista de Produtos</h1>

<table border="1" width="100%">
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

    <?php while($f = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $f['id'] ?></td>
        <td><?= $f['Nome'] ?></td>
        <td><?= $f['Descrição'] ?></td>
        <td><?= $f['Quantidade'] ?></td>
        <td><?= $f['Preço'] ?></td>
        <td><?= $f['Fornecedor'] ?></td>
        <td><?= $f['Imagem'] ?></td>
        <td><?= $f['Ações'] ?></td>

        <td>
            <a href="tela5.php?id=<?= $f['id'] ?>">Editar</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>