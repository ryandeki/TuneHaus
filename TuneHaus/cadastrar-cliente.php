<?php
session_start();

require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';


$usuarioRepo = new UsuarioRepositorio($pdo);
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmaSenha = trim($_POST['confirma_senha'] ?? '');

    if ($nome === '') $erros[] = 'Nome é obrigatório.';
    if ($email === '') $erros[] = 'E-mail é obrigatório.';
    if ($senha === '') $erros[] = 'Senha é obrigatória.';
    if ($senha !== $confirmaSenha) $erros[] = 'As senhas não conferem.';

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $erros[] = 'E-mail já cadastrado.';

    if (empty($erros)) {
        try {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $usuario = new Usuario(null, $nome, 'User', $email, $senhaHash); 

            $usuarioRepo->salvar($usuario);

            $_SESSION['sucesso_cadastro'] = true;
            header('Location: cadastrar-cliente.php');
            exit;
        } catch (Exception $e) {
            $erros[] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes | TuneHaus</title>
    <link rel="stylesheet" href="css/cadastrar-cliente.css">
    <link rel="icon" href="img/logopng.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                <li><a href="suporte.php">Suporte</a></li>

                <li>
                    <form action="login.php" method="POST" class="form-login">
                        <button type="submit" class="botao-login">login</button>
                    </form>
                </li>

            </ul>
        </nav>
    </header>

    <main>
        <section class="cadastro-container">
            <div class="titulo-imagens">
                <h2>CADASTRE-SE!</h2>
            </div>

            <?php if(!empty($erros)): ?>
            <div class="erros">
                <ul>
                    <?php foreach($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" class="form-cadastro">

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>

                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required>

                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" value="<?= htmlspecialchars($_POST['senha'] ?? '') ?>"
                    required>

                <label for="confirma_senha">Confirme a senha</label>
                <input type="password" name="confirma_senha" id="confirma_senha" required>

                <button type="submit" class="botao">CONCLUIR CADASTRO</button>
            </form>
        </section>
    </main>

    <?php if(!empty($_SESSION['sucesso_cadastro'])): 
        unset($_SESSION['sucesso_cadastro']); ?>
    <script>
    Swal.fire({
        title: 'Cadastrado!',
        text: 'Cadastro realizado com sucesso. Realize o Login.',
        icon: 'success',
        confirmButtonText: 'OK',
        background: "rgba(233, 195, 255, 1)",
        color: "#292929",
        confirmButtonColor: "#6a1b9a"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php';
        }
    });
    </script>
    <?php endif; ?>

</body>

</html>