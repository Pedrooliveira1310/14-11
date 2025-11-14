<?php
include 'conexao.php';
session_start();

// Só processa se vier pelo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Busca usuário
    $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Achou o usuário?
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if ($usuario['senha'] === $senha) {
            $_SESSION['usuario'] = $usuario['email'];
            header("Location: ../menu/menu.html");
            exit;
        }
    }

    $erro = "Email ou senha incorretos!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Dr. Peanut</title>
    <link rel="stylesheet" href="login.css">
    <link rel="shortcut icon" href="img/iiico.ico" type="image/x-icon">
</head>

<body>

    <main class="container">

        <!-- Lado Esquerdo -->
        <div class="left">
            <form class="login-box" action="login.php" method="POST">
                <h1>Entrar</h1>

                <?php if (!empty($erro)) { ?>
                    <p style="color:red;"><?= $erro ?></p>
                <?php } ?>

                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="senha" placeholder="Senha" required>

                <button type="submit">Entrar</button>
            </form>
        </div>

        <!-- Lado Direito GIF/VIDEO -->
        <div class="right">
            <video autoplay muted loop>
                <source src="img/WhatsApp Video 2025-09-11 at 13.22.54.mp4" type="video/mp4">
                Seu navegador não suporta vídeo.
            </video>
        </div>

    </main>

</body>
</html>
