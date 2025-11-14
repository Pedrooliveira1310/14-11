<?php
include "conexao.php";

$erro = "";

// Só processa se vier do POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica se email já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $erro = "Email já cadastrado!";
    } else {

        // Insere usuário no banco
        $sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $senha);

        if ($stmt->execute()) {
            header("Location: ../login/login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="stylesheet" href="cadastro.css">
  <link rel="shortcut icon" href="img/iiico.ico" type="image/x-icon">
</head>

<body>
  <main class="container">

    <div class="left">

      <form class="login-box" action="cadastrar.php" method="POST">
        <h1>Cadastrar</h1>

        <?php if (!empty($erro)) { ?>
          <p style="color: red;"><?= $erro ?></p>
        <?php } ?>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="senha" placeholder="Senha" maxlength="8" required>

        <a href="Location: ../login/login.php">Já tenho conta</a>
        <button type="submit">Cadastrar</button>
      </form>

    </div>

    <div class="right">
      <video autoplay muted loop>
        <source src="img/WhatsApp Video 2025-09-11 at 13.22.54.mp4" type="video/mp4">
        Seu navegador não suporta vídeo.
      </video>
    </div>

  </main>
</body>
</html>
