<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes | TuneHaus</title>
    <link rel="stylesheet" href="css/suporte.css">
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


    <h1 class="title">FALE CONOSCO!</h1>

    <section class="contact-list">

        <div class="contact-item">
            <div class="icon">
                <img src="img/icone-gmail.png" class="icon-img">
            </div>
            <p class="contact-text">tunehaus@gmail.com</p>
        </div>

        <div class="contact-item">
            <div class="icon">
                <img src="img/icone-telefone.webp" class="icon-img telefone">
            </div>
            <p class="contact-text">+55 (41) 9999-9999</p>
        </div>

        <div class="contact-item">
            <div class="icon insta-icon">
                <img src="img/icone-instagram.jpg" class="icon-img instagram">
            </div>
            <p class="contact-text">@tunehaus</p>
        </div>

    </section>

    <section class="sac">
        <div class="sac-icon">
            <img src="img/icone-sac.png" class="sac-icon">
        </div>
        <h2>SAC</h2>
        <p>Central de atendimento: 0800-025-9666</p>
    </section>

</body>

</html>