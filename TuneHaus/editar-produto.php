<?php

session_start();
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';

if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    header("Location: home.php");
    exit;
}

$repo = new ProdutoRepositorio($pdo);

try {
    $stmtCat = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome");
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categorias = [];
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$produto = $repo->buscarPorId($id);
if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.location='listar-produto.php';</script>";
    exit;
}

$erros = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $informacoes = trim($_POST['informacoes']) ?: null;
    $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;
    $musica = trim($_POST['musica']) ?: null;
    $categoria_id = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;

    if ($nome === '')
        $erros[] = 'Nome é obrigatório.';
    if ($descricao === '')
        $erros[] = 'Descrição é obrigatória.';
    if ($preco <= 0)
        $erros[] = 'Informe um preço válido.';
    if (empty($categoria_id))
        $erros[] = 'Escolha a categoria.';

    $imagem = $produto->getImagem();
    $uploadsDir = __DIR__ . '/uploads/';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        if (!is_dir($uploadsDir))
            mkdir($uploadsDir, 0755, true);

        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_imagem = uniqid('img_', true) . '.' . $ext;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadsDir . $nome_imagem)) {
            $imagem = $nome_imagem;
        } else {
            $erros[] = 'Falha ao enviar a imagem. A imagem atual será mantida.';
        }
    }

    if (empty($erros)) {
        $produtoAtualizado = new Produto(
            $produto->getId(),
            $nome,
            $descricao,
            $informacoes,
            $preco,
            $musica,
            $imagem,
            $categoria_id
        );

        try {
            $repo->atualizar($produtoAtualizado);
            $_SESSION['alert'] = 'atualizado';
            header('Location: listar-produto.php');
            exit;
        } catch (Exception $e) {
            $erros[] = 'Erro ao atualizar produto: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUNEHAUS - Editar Produto</title>
    <link rel="stylesheet" href="css/alterar-produto.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
</head>

<body>

    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION["usuario"]);
$perfil = $_SESSION["perfil"] ?? null;
?>


    <header>
        <div class="cabecalho">
            TUNEHAUS
            <img src="img/logopng.png" alt="Logo do site" class="logo">
        </div>

        <nav>
            <ul class="menu-principal">

                <li><a href="home.php">Home</a></li>

                <?php if ($logado && $perfil === 'Admin'): ?>
                <li><a href="listar-clientes.php">Clientes</a></li>
                <?php endif; ?>

                <li class="dropdown">
                    <a href="#">Produtos ▾</a>
                    <ul class="submenu">
                        <li><a href="listar-produto.php">Todos</a></li>
                        <li><a href="listar-produto.php?categoria=1">Guitarras</a></li>
                        <li><a href="listar-produto.php?categoria=2">Violões</a></li>
                        <li><a href="listar-produto.php?categoria=3">Baixos</a></li>
                        <li><a href="listar-produto.php?categoria=4">Teclados</a></li>
                        <li><a href="listar-produto.php?categoria=5">Flautas</a></li>
                    </ul>
                </li>

                <li><a href="suporte.php">Suporte</a></li>

                <?php if ($logado): ?>
                <li>
                    <form action="logout.php" method="POST">
                        <button type="submit" class="botao-logout">logout</button>
                    </form>
                </li>
                <?php else: ?>
                <li><a href="login.php" class="botao-login">login</a></li>
                <?php endif; ?>

            </ul>
        </nav>
    </header>

    <main>
        <section class="editar-container">
            <div class="titulo-imagens">
                <img src="img/notasmusicais.png" alt="Notas musicais" class="notas-musicais">
                <h2>EDITAR <br> PRODUTO</h2>
                <img src="img/notasmusicais2.png" class="notas-musicais-dois">
            </div>

            <?php if (!empty($erros)): ?>
            <div class="erros">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-editar">
                <input type="hidden" name="id" value="<?= $produto->getId() ?>">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto->getNome()) ?>" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"
                    required><?= htmlspecialchars($produto->getDescricao()) ?></textarea>

                <label for="informacoes">Informações Adicionais</label>
                <textarea name="informacoes"
                    id="informacoes"><?= htmlspecialchars($produto->getInformacoes()) ?></textarea>

                <label for="preco">Preço</label>
                <input type="number" name="preco" id="preco" value="<?= htmlspecialchars($produto->getPreco()) ?>"
                    required>

                <label for="musica">Sugestão de Música</label>
                <input type="url" name="musica" id="musica" value="<?= htmlspecialchars($produto->getMusica()) ?>">

                <label for="categoria_id">Categoria</label>
                <select name="categoria_id" id="categoria_id" required>
                    <option value="">Selecione a categoria</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                        <?= ($produto->getCategoriaId() == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <div class="img-preview">
                    <?php
                    $imagemAtual = $produto->getImagem();
                    $caminhoImagem = $imagemAtual ? (str_starts_with($imagemAtual, 'img/') ? $imagemAtual : 'uploads/' . $imagemAtual) : 'img/nao-encontrada.jpg';
                    ?>
                    <p>Imagem atual:</p>
                    <img src="<?= htmlspecialchars($caminhoImagem) ?>"
                        alt="<?= htmlspecialchars($produto->getNome()) ?>">
                </div>

                <label for="imagem">Nova imagem (opcional)</label>
                <input type="file" name="imagem" id="imagem" accept="image/*">

                <button type="submit" class="botao">SALVAR ALTERAÇÕES</button>
            </form>
        </section>
    </main>
</body>

</html>