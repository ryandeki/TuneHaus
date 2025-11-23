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
$usuarioRepo = new UsuarioRepositorio($pdo);

// 🔎 BUSCA
$busca = trim($_GET['busca'] ?? '');

// PAGINAÇÃO --------------------------------------
$limite = 6; 
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $limite;

// Se tiver busca → pagina resultados filtrados
if ($busca !== '') {
    $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nome LIKE ? OR email LIKE ?");
    $stmtTotal->execute(["%$busca%", "%$busca%"]);
    $totalUsuarios = $stmtTotal->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT * FROM usuarios 
        WHERE nome LIKE ? OR email LIKE ? 
        ORDER BY id DESC 
        LIMIT $limite OFFSET $offset
    ");
    $stmt->execute(["%$busca%", "%$busca%"]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT $limite OFFSET $offset");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$totalPaginas = ceil($totalUsuarios / $limite);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes | TuneHaus</title>
    <link rel="stylesheet" href="css/listar-clientes.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <main class="conteudo">

        <h2 class="titulo">Lista de Clientes</h2>

        <!-- 🔎 Caixa de busca -->
        <form class="form-busca" method="GET">
            <input type="text" name="busca" placeholder="Buscar por nome ou e-mail..."
                value="<?= htmlspecialchars($busca) ?>">
            <button type="submit">Buscar</button>
        </form>

        <table class="tabela">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($usuarios) === 0): ?>
                <tr>
                    <td colspan="5" class="vazio">Nenhum cliente encontrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['perfil']) ?></td>

                    <td>
                        <a href="editar-cliente.php?id=<?= $u['id'] ?>" class="btn editar">Editar</a>

                        <form action="excluir-cliente.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn excluir btn-excluir">Excluir</button>
                        </form>

                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="gerar-relatorio-clientes.php" class="btn-relatorio">Gerar Relatório</a>

        <!-- ⭐ PAGINAÇÃO -->
        <div class="paginacao">
            <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>&busca=<?= urlencode($busca) ?>">&laquo; Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a class="<?= $i == $pagina ? 'ativa' : '' ?>" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>&busca=<?= urlencode($busca) ?>">Próxima &raquo;</a>
            <?php endif; ?>
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
            iconColor: "#40023c",
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
        title: "Excluído!",
        text: "O cliente foi removido com sucesso.",
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