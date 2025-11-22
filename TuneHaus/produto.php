<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

// Pegar id do produto via GET
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: listar-produto.php');
    exit;
}

$produtoRepo = new ProdutoRepositorio($pdo);
$categoriaRepo = new CategoriaRepositorio($pdo);

// Buscar produto
$produto = $produtoRepo->buscarPorId($id);
if (!$produto) {
    header('Location: listar-produto.php');
    exit;
}

// Buscar produtos semelhantes (mesma categoria, excluindo o atual)
// Aqui reutilizo listarPorCategoria mas removo o atual (até 4 similares)
$similares = [];
$categoriaId = $produto->getCategoriaId();
if ($categoriaId !== null) {
    $todosDaCategoria = $produtoRepo->listarPorCategoria($categoriaId);
    foreach ($todosDaCategoria as $p) {
        if ($p->getId() !== $produto->getId()) {
            $similares[] = $p;
        }
        if (count($similares) >= 4) break;
    }
}

// perfil do usuario (opcional, caso queira mostrar admin)
$perfil = null;
$usuarioLogado = null;
if (isset($_SESSION['usuario'])) {
    $repoUser = new UsuarioRepositorio($pdo);
    $user = $repoUser->buscarPorEmail($_SESSION['usuario']);
    if ($user) {
        $perfil = $user->getPerfil();
        $usuarioLogado = $user->getNome();
    }
}

// caminho padrão da imagem (fallback para a imagem enviada por você)
$fallbackImagem = '/mnt/data/Produto DETALHES.jpg'; // caminho local do arquivo enviado

// função helper para exibir texto com segurança
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title><?= e($produto->getNome()) ?> - TUNEHAUS</title>
    <link rel="stylesheet" href="css/produto.css">
</head>
<body>
    <header>
        <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" alt="Logo do site" class="logo"></div>
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
                        <button type="submit" class="botao-logout">logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container main-produto">
        <div class="top-area">
            <div class="imagem-grande">
                <?php
                    $img = $produto->getImagem();
                    $caminhoImagem = $img ? 'uploads/' . $img : $fallbackImagem;
                ?>
                <div class="frame">
                    <img src="<?= e($caminhoImagem) ?>" alt="<?= e($produto->getNome()) ?>">
                </div>

                <?php if ($produto->getMusica()): ?>
                    <p class="musica-sugestao">Sugestão de música: <a href="<?= e($produto->getMusica()) ?>" target="_blank"><?= e($produto->getMusica()) ?></a></p>
                <?php endif; ?>
            </div>

            <aside class="detalhes">
                <h1 class="titulo"><?= e($produto->getNome()) ?></h1>
                <?php if ($produto->getInformacoes()): ?>
                    <p class="subinfo"><?= e($produto->getInformacoes()) ?></p>
                <?php endif; ?>

                <p class="preco">R$ <?= number_format($produto->getPreco(), 2, ",", ".") ?></p>

                <div class="acoes-produto">
                    <button class="btn-comprar">Comprar</button>
                    <button class="btn-fav" aria-label="Adicionar aos favoritos">♡</button>
                </div>

                <?php if ($produto->getDescricao()): ?>
                <ul class="lista-descricao">
                    <?php
                        // Se a descrição contém quebras de linha, transformamos em itens
                        $desc = $produto->getDescricao();
                        $linhas = preg_split('/\r\n|\r|\n/', $desc);
                        foreach ($linhas as $l): 
                            $texto = trim($l);
                            if ($texto === '') continue;
                    ?>
                        <li><?= e($texto) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </aside>
        </div>

        <section class="similares">
            <h2>Produtos<br>semelhantes</h2>

            <div class="similares-grid">
                <?php foreach ($similares as $s): 
                    $imgS = $s->getImagem() ? 'uploads/' . $s->getImagem() : $fallbackImagem;
                ?>
                <article class="card-similar">
                    <a class="card-link" href="produto.php?id=<?= $s->getId() ?>">
                        <img src="<?= e($imgS) ?>" alt="<?= e($s->getNome()) ?>">
                        <h3><?= e($s->getNome()) ?></h3>
                        <p class="mini-desc"><?= e($s->getDescricao()) ?></p>
                        <p class="mini-preco">R$ <?= number_format($s->getPreco(), 2, ",", ".") ?></p>
                        <div class="saiba-mais">Saiba mais</div>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
