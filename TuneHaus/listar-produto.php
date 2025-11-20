<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'];
$repo = new ProdutoRepositorio($pdo);

$categoriaId = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;

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
    <link rel="stylesheet" href="css/listar-produto.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <?php foreach ($produtos as $produto): ?>
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

                    <form action="remover-produto.php" method="GET" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                        <button type="submit" class="btremover btn-excluir">Remover</button>
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

    <script>
    document.addEventListener("click", function(e) {
        const target = e.target.closest && e.target.closest(".btn-excluir");
        if (!target) return;

        const form = target.closest("form");
        if (!form) return;

        e.preventDefault();

        Swal.fire({
            title: "Deseja excluir?",
            html: "Essa ação não poderá ser desfeita!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            background: "rgba(233, 195, 255, 1)",
            color: "#292929ff",
            confirmButtonColor: "#c0392b",
            cancelButtonColor: "#6a1b9a",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    </script>

    <?php if (isset($_SESSION['alert'])): ?>
    <script>
    Swal.fire({
        title: "<?= $_SESSION['alert'] === 'excluido' ? 'Excluído!' : 'Atualizado!' ?>",
        text: "<?= $_SESSION['alert'] === 'excluido' ? 'O produto foi removido com sucesso.' : 'O produto foi editado com sucesso.' ?>",
        icon: "success",
        background: "rgba(233, 195, 255, 1)",
        color: "#292929",
        confirmButtonColor: "#6a1b9a",
        confirmButtonText: "OK"
    });
    </script>
    <?php unset($_SESSION['alert']); endif; ?>
</body>

</html>