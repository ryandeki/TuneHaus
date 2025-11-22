<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);

// Verifica ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];
$usuario = $repo->buscarPorId($id);

if (!$usuario) {
    die("Usuário não encontrado.");
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $perfil = trim($_POST['perfil']);

    if ($nome === '') $erros[] = "Nome é obrigatório.";
    if ($email === '') $erros[] = "E-mail é obrigatório.";

    if (empty($erros)) {
        $usuarioAtualizado = new Usuario(
            $usuario->getId(),
            $nome,
            $perfil,
            $email,
            $usuario->getSenha()
        );

        if ($repo->atualizar($usuarioAtualizado)) {
            header("Location: listar-clientes.php?edit=ok");
            exit;
        }
    }
}
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="css/editar-clientes.css">
</head>
<body>

<header>
    <div class="cabecalho">
        TUNEHAUS 
        <img src="img/logopng.png" alt="Logo" class="logo">
    </div>
</header>

<main>
    <section class="editar-container">

        <div class="titulo-imagens">
            <img src="img/notasmusicais.png" class="notas-musicais">
            <h2>EDITAR<br>CLIENTE</h2>
            <img src="img/notasmusicais2.png" class="notas-musicais-dois">
        </div>

        <?php if (!empty($erros)): ?>
            <div class="erros">
                <?php foreach ($erros as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-editar">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>">

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>">

            <label>Perfil:</label>
            <select name="perfil">
                <option value="User" <?= $usuario->getPerfil() === 'User' ? 'selected' : '' ?>>User</option>
                <option value="Admin" <?= $usuario->getPerfil() === 'Admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <button type="submit" class="botao">SALVAR ALTERAÇÕES</button>
            <a href="listar-clientes.php" class="botao-voltar">Voltar</a>

        </form>

    </section>
</main>

</body>
</html>

