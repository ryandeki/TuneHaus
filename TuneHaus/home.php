<?php
session_start();
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';


$stmt = $pdo->query("SELECT imagem FROM home_banner WHERE id = 1");
$banner = $stmt->fetch();
$caminho_banner = $banner && $banner["imagem"] 
    ? "uploads/" . $banner["imagem"] 
    : "img/fundoinicial.jpg";

$slots = $pdo->query("SELECT * FROM home_slots ORDER BY id ASC")->fetchAll();

$slot1 = $slots[0] ?? null;
$slot2 = $slots[1] ?? null;

function buscarProduto($pdo, $id) {
    if (!$id) return null;
    $sql = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $sql->execute([$id]);
    return $sql->fetch();
}

$produto1 = buscarProduto($pdo, $slot1['produto_id'] ?? null);
$produto2 = buscarProduto($pdo, $slot2['produto_id'] ?? null);

$eAdmin = (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'Admin');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuneHaus - Home</title>
    <link rel="stylesheet" href="css/home.css">
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

    <section class="banner" style="background: url('<?= $caminho_banner ?>') center/cover no-repeat;">
        <div class="overlay">

            <?php if ($eAdmin): ?>
            <p>Bem-vindo(a), <?= $_SESSION['usuario'] ?></p>
            <h1>PAINEL ADMINISTRATIVO</h1>
            <a href="editar_banner.php" class="editar">Editar banner</a>
            <?php else: ?>
            <h1>BEM-VINDO(A)!</h1>
            <p>Aqui você encontra os seus instrumentos favoritos</p>
            <?php endif; ?>

        </div>
    </section>

    <main>
        <section class="produtos">

            <div class="produto linha">
                <div class="texto1">
                    <h2><?= $produto1 ? $produto1['nome'] : "Slot vazio" ?></h2>
                    <p><?= $produto1 ? $produto1['descricao'] : "" ?></p>

                    <?php if ($eAdmin): ?>
                    <a href="editar_slot.php?id=1" class="editar">Editar</a>
                    <?php else: ?>
                    <a href="<?= $produto1 ? 'produto.php?id=' . $produto1['id'] : '#' ?>" class="botao1">Saiba mais</a>
                    <?php endif; ?>
                </div>

                <div class="imagem1">
                    <img src="<?= $produto1 ? 'uploads/' . $produto1['imagem'] : 'img/placeholder.png' ?>">
                </div>
            </div>

            <div class="produto linha invertida">
                <div class="texto2">
                    <h2><?= $produto2 ? $produto2['nome'] : "Slot vazio" ?></h2>
                    <p><?= $produto2 ? $produto2['descricao'] : "" ?></p>

                    <?php if ($eAdmin): ?>
                    <a href="editar_slot.php?id=2" class="editar">Editar</a>
                    <?php else: ?>
                    <a href="<?= $produto2 ? 'produto.php?id=' . $produto2['id'] : '#' ?>" class="botao2">Saiba mais</a>
                    <?php endif; ?>
                </div>

                <div class="imagem2">
                    <img src="<?= $produto2 ? 'uploads/' . $produto2['imagem'] : 'img/placeholder.png' ?>">
                </div>
            </div>

        </section>
    </main>

</body>

</html>