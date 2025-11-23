<?php
session_start();

require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogadoEmail = $_SESSION['usuario'];

$repoUser = new UsuarioRepositorio($pdo);
$usuario = $repoUser->buscarPorEmail($usuarioLogadoEmail);

$perfil = $usuario->getPerfil();
$usuarioLogado = $usuario->getNome();
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
    <div class="topo">
        <p>Bem-vindo, <strong><?= htmlspecialchars($usuarioLogado) ?></strong>!</p>
        <?php if ($perfil === 'Admin'): ?>
        <h1>Painel Administrativo</h1>
        <?php endif; ?>
        <div class="titulo-com-linhas">
            <div class="linha"></div>
            <h2><?= htmlspecialchars($tituloPagina) ?></h2>
            <div class="linha"></div>
        </div>
    </div>

    <section class="produtos-container">
        <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $produto): ?>
        <a href="produto.php?id=<?= $produto->getId() ?>" class="link-card">
            <div class="produto-card">
                <?php
                    $img = $produto->getImagem();
                    $caminhoImagem = $img ? 'uploads/' . $img : 'img/nao-encontrada.jpg';
                ?>
                <img src="<?= htmlspecialchars($caminhoImagem) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
                <h3><?= htmlspecialchars($produto->getNome()) ?></h3>
                <p><?= htmlspecialchars($produto->getDescricao()) ?></p>
                <p class="preco">R$ <?= number_format($produto->getPreco(), 2, ",", ".") ?></p>
        </a>

        <!-- SOMENTE ADMIN VÊ OS BOTÕES -->
        <?php if ($perfil === 'Admin'): ?>
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
        <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p class="nenhum-produto">Nenhum produto encontrado nesta categoria.</p>
        <?php endif; ?>
    </section>

    <?php if ($perfil === 'Admin'): ?>
    <div class="acoes">
        <button class="btcadastrar" onclick="window.location='cadastrar-produto.php'">CADASTRAR PRODUTO</button>
        <br>
        <button class="btgerar" onclick="window.location='gerar-relatorio-produtos.php'">
            GERAR RELATÓRIO PDF
        </button>
    </div>
    <?php endif; ?>
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