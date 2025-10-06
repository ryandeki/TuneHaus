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
            <ul class="lista-produtos">
                <li><a href="home-deslogado.html">home</a></li>
                <li><a href="guitarras.html">guitarras</a></li>
                <li><a href="#">violões</a></li>
                <li><a href="#">baixos</a></li>
                <li><a href="#">teclados</a></li>
                <li><a href="#">flautas</a></li>
                <li><a href="login.php" class="botao-login">Login</a></li>
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
                    <form action="logout.php" method="post">
                        <button type="submit" class="botao-sair">Sair</button>
                    </form>
                </div>
                <div class="conteudo">
                    <a href="listar-produto.php" class="link-adm">Ir para o Painel Administrativo</a>
                </div>
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
                <p>Não tem cadastro ainda?<br><a href="cadastrar-cliente.html" class="cadastro">CADASTRE-SE</a></p>
            </div>
        </div>
        </section>
        <?php endif; ?>
    </main>


<script>
    window.addEventListener('DOMContentLoaded', function(){
        var msg = document.querySelector('.mensagem-erro');
        if(msg){
            setTimeout(function(){
                msg.classList.add('oculto');
            }, 5000);
        }
    });
</script>

</body>

</html>