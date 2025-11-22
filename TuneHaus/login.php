<?php
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;
$erro = $_GET['erro'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuneHaus - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
</head>

<body>
  <header>
        <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" alt="Logo do site" class="logo"></div>
        <nav>
            <ul class="menu-principal">

                <li><a href="home.php">Home</a></li>

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

                <li><a href="html/suporte.html">Suporte</a></li>

                <li>
                    <form action="login.php" method="POST" class="form-login">
                        <button type="submit" class="botao-login">login</button>
                    </form>
                </li>

            </ul>
        </nav>
    </header>


    <main class="main">
        <?php
        if ($usuarioLogado) : ?>
        <section class="container-topo">
            <div class="topo-direita">
                <p>Você já está logado como <strong>
                        <?php echo htmlspecialchars($usuarioLogado); ?>
                    </strong></p>
            </div>
            <div class="conteudo">
                <a href="listar-produto.php" class="link-adm">Ir para o Painel Administrativo</a>
            </div>
            <form action="logout.php" method="post">
                <button type="submit" class="botao-sair">Sair</button>
            </form>
        </section>
        <?php else: ?>
        <div class="form-wrapper">
            <?php if ($erro === 'credenciais'): ?>
            <p class="mensagem-erro">Usuário ou senha incorretos.</p>
            <?php elseif ($erro === 'campos'): ?>
            <p class="mensagem-erro">Preencha e-mail e senha.</p>
            <?php endif; ?>

            <section class="container-form">
                <div class="login-container">
                    <div class="titulo-com-linhas">
                        <div class="linha"></div>
                        <h2>LOGIN</h2>
                        <div class="linha"></div>
                    </div>
                    <form action="autenticar.php" method="post">
                        <p class="endereco-email">Endereço de E-mail</p>
                        <input type="email" name="email" placeholder="Digite seu E-mail" required>
                        <p class="senha">Senha</p>
                        <input type="password" name="senha" placeholder="Digite sua senha" required>
                        <button type="submit" class="botao-logar">Login</button>
                    </form>
                    <div class="cadastre">
                        <p>Não tem cadastro ainda?<br><a href="cadastrar-cliente.php" class="cadastro">CADASTRE-SE</a>
                        </p>
                    </div>
                </div>
        </div>
        </section>
        <?php endif; ?>
    </main>


    <script>
    window.addEventListener('DOMContentLoaded', function() {
        var msg = document.querySelector('.mensagem-erro');
        if (msg) {
            setTimeout(function() {
                msg.classList.add('oculto');
            }, 5000);
        }
    });
    </script>

</body>

</html>