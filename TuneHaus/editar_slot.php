<?php
session_start();
require_once __DIR__ . '/../src/conexao-bd.php';

/* --- PERMISSÃO SOMENTE ADMIN --- */
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    header("Location: home.php");
    exit;
}

/* --- VERIFICA SE O SLOT FOI ENVIADO --- */
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

$slot_id = intval($_GET['id']);
$mensagem = "";

/* --- SALVAR SLOT --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $produto_id = $_POST['produto_id'];

    $sql = $pdo->prepare("UPDATE home_slots SET produto_id = ? WHERE id = ?");
    $sql->execute([$produto_id, $slot_id]);

    $mensagem = "Slot atualizado com sucesso!";
}

/* --- LISTA DE PRODUTOS --- */
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome ASC")->fetchAll();

/* --- SLOT ATUAL --- */
$sql = $pdo->prepare("SELECT * FROM home_slots WHERE id = ?");
$sql->execute([$slot_id]);
$slot = $sql->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Slot <?= $slot_id ?></title>
    <link rel="stylesheet" href="css/editar-home.css">
</head>
<header>
    <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" class="logo"></div>
</header>
<body>
<main>
    <section class="editar-container">

        <div class="titulo-imagens">
            <img src="img/notasmusicais.png" class="notas-musicais">
            <h2>EDITAR<br>SLOT</h2>
            <img src="img/notasmusicais2.png" class="notas-musicais-dois">
        </div>

        <p style="color:green; font-weight:bold;"><?= $mensagem ?></p>

        <form method="POST" class="form-editar">
            <label>Selecione um produto:</label>
            <select name="produto_id">
                <?php foreach ($produtos as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($slot['produto_id'] == $p['id']) ? "selected" : "" ?>>
                        <?= $p['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="botao">SALVAR ALTERAÇÕES</button>
            <a href="home.php" class="botao-voltar">Voltar</a>
        </form>

    </section>
</main>
</body>
</html>
