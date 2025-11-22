<?php
session_start();
require_once __DIR__ . '/../src/conexao-bd.php';

/* --- PERMISSÃO SOMENTE ADMIN --- */
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    header("Location: home.php");
    exit;
}

$mensagem = "";

/* --- SE ENVIAR O FORMULÁRIO --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['banner']['name'])) {

        $arquivo = $_FILES['banner'];

        $pasta = "uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);

        $nomeFinal = uniqid() . "-" . basename($arquivo['name']);
        $caminho = $pasta . $nomeFinal;

        move_uploaded_file($arquivo['tmp_name'], $caminho);

        // Atualiza no banco
        $sql = $pdo->prepare("UPDATE home_banner SET imagem = ? WHERE id = 1");
        $sql->execute([$nomeFinal]);

        $mensagem = "Banner atualizado com sucesso!";
    }
}

/* --- BANNER ATUAL --- */
$stmt = $pdo->query("SELECT imagem FROM home_banner WHERE id = 1");
$banner = $stmt->fetch();
$caminho_atual = $banner && $banner['imagem']
    ? "uploads/" . $banner['imagem']
    : "../img/fundoinicial.jpg";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Banner</title>
    <link rel="stylesheet" href="css/editar-home.css">
</head>
<header>
    <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" class="logo"></div>
</header>

<main>
    <section class="editar-container">

        <div class="titulo-imagens">
            <img src="img/notasmusicais.png" class="notas-musicais">
            <h2>EDITAR<br>BANNER</h2>
            <img src="img/notasmusicais2.png" class="notas-musicais-dois">
        </div>

        <p style="color:green; font-weight:bold;"><?= $mensagem ?></p>

        <div class="img-preview">
            <p>Banner atual:</p>
            <img src="<?= $caminho_atual ?>">
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-banner">
            <label>Escolher nova imagem</label>
            <input type="file" name="banner" required>

            <button class="botao">SALVAR ALTERAÇÕES</button>

                <a href="home.php" class="botao-voltar">Voltar</a>
        </form>
    </section>
</main>
</html>
