<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

$repo = new ProdutoRepositorio($pdo);
$produtos = $repo->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>TUNEHAUS - Guitarras</title>
    <!-- Ajuste do caminho do CSS -->
    <link rel="stylesheet" href="css/guitarras.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
</head>
<body>

<header>
    <div class="cabecalho">
        TUNEHAUS <img src="img/logopng.png" alt="Logo do site" class="logo">
    </div>
    <nav>
        <ul class="lista-produtos">
            <li><a href="home-logado.html">home</a></li>
            <li><a href="#">guitarras</a></li>
            <li><a href="#">violões</a></li>
            <li><a href="#">baixos</a></li>
            <li><a href="#">teclados</a></li>
            <li><a href="#">flautas</a></li>
            <li><a href="home-deslogado.html" class="botao-logout">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>GUITARRAS</h2>

<section class="produtos-container">
    <?php foreach($produtos as $produto): ?>
        <div class="produto-card">
            <img src="<?= $produto->getImagem() ? "uploads/".$produto->getImagem() : "img/sem_imagem.png" ?>" 
                 alt="<?= htmlspecialchars($produto->getNome()) ?>">
            <h3><?= htmlspecialchars($produto->getNome()) ?></h3>
            <p><?= htmlspecialchars($produto->getDescricao()) ?></p>
            <p class="preco">R$ <?= number_format($produto->getPreco(), 2, ",", ".") ?></p>
            <div class="botoes">
                <form action="editar-produto.php" method="GET" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                    <button type="submit" class="bteditar">Editar</button>
                </form>
                <form action="remover-produto.php" method="GET" style="display:inline;"
                      onsubmit="return confirm('Deseja realmente excluir este produto?')">
                    <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                    <button type="submit" class="btremover">Remover</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<div class="acoes">
    <button class="btcadastrar" onclick="window.location='cadastrar-produto.php'">CADASTRAR PRODUTO</button>
</div>
</main>
</body>
</html>
