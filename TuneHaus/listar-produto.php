<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Componentes/alertas.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$repo = new ProdutoRepositorio($pdo);

$categoriaId = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;

$nomesCategorias = [
    1 => "Guitarras",
    2 => "Violões",
    3 => "Baixos",
    4 => "Teclados",
    5 => "Flautas"
];

$tituloPagina = $categoriaId && isset($nomesCategorias[$categoriaId]) 
                ? $nomesCategorias[$categoriaId] 
                : "Todos os Produtos";

if ($categoriaId) {
    $produtos = $repo->listarPorCategoria($categoriaId);
} else {
    $produtos = $repo->listar();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>TUNEHAUS - <?= htmlspecialchars($tituloPagina) ?></title>
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
            <li><a href="html/home-logado.html">Home</a></li>
            <li><a href="listar-produto.php?categoria=1">Guitarras</a></li>
            <li><a href="listar-produto.php?categoria=2">Violões</a></li>
            <li><a href="listar-produto.php?categoria=3">Baixos</a></li>
            <li><a href="listar-produto.php?categoria=4">Teclados</a></li>
            <li><a href="listar-produto.php?categoria=5">Flautas</a></li>
            <li>
                <form action="logout.php" method="POST" style="display:inline">
                    <button type="submit" class="botao-logout">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main>
    <div class="topo">
        <p>Bem-vindo, <strong><?= htmlspecialchars($usuarioLogado) ?></strong>!</p>
        <h1>Painel Administrativo</h1>
        <div class="titulo-com-linhas">
            <div class="linha"></div>
                <h2><?= htmlspecialchars($tituloPagina) ?></h2>
            <div class="linha"></div>
        </div>
    </div>

<section class="produtos-container">
    <?php if (!empty($produtos)): ?>
        <?php foreach($produtos as $produto): ?>
            <div class="produto-card">
                <?php
                    $img = $produto->getImagem();
                    $caminhoImagem = $img ? 'uploads/' . $img : 'img/nao-encontrada.jpg';
                ?>
                <img src="<?= htmlspecialchars($caminhoImagem) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">

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
    <?php else: ?>
        <p class="nenhum-produto">Nenhum produto encontrado nesta categoria.</p>
    <?php endif; ?>
</section>



<div class="acoes">
    <button class="btcadastrar" onclick="window.location='cadastrar-produto.php'">CADASTRAR PRODUTO</button>
</div>
</main>
</body>
</html>
