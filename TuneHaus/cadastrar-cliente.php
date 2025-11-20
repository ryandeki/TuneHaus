<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php'; // deve definir $pdo
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/Modelo/Cliente.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/ClienteRepositorio.php';

$usuarioRepo = new UsuarioRepositorio($pdo);
$clienteRepo = new ClienteRepositorio($pdo);

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmaSenha = trim($_POST['confirma_senha'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $dataNascimento = trim($_POST['data_nascimento'] ?? '');

    // Validações
    if ($nome === '')
        $erros[] = 'Nome é obrigatório.';
    if ($sobrenome === '')
        $erros[] = 'Sobrenome é obrigatório.';
    if ($email === '')
        $erros[] = 'E-mail é obrigatório.';
    if ($senha === '')
        $erros[] = 'Senha é obrigatória.';
    if ($senha !== $confirmaSenha)
        $erros[] = 'As senhas não conferem.';
    if ($cpf === '')
        $erros[] = 'CPF é obrigatório.';
    if ($dataNascimento === '')
        $erros[] = 'Data de nascimento é obrigatória.';

    // Verifica se já existe usuário com o email
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch())
        $erros[] = 'E-mail já cadastrado.';

    // Verifica se já existe cliente com o mesmo CPF
    $stmt2 = $pdo->prepare("SELECT id FROM clientes WHERE cpf = ?");
    $stmt2->execute([$cpf]);
    if ($stmt2->fetch())
        $erros[] = 'CPF já cadastrado.';

    if (empty($erros)) {
        try {
            // 1) Cria usuário
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $usuario = new Usuario(null, $nome, 'user', $email, $senhaHash);
            $usuarioId = (int) $usuarioRepo->salvar($usuario);

            // 2) Cria cliente
            $cliente = new Cliente($usuarioId, $nome, $sobrenome, $email, $dataNascimento, $cpf);
            $clienteRepo->salvar($cliente);

            // Redireciona para a mesma página com parâmetro de sucesso
            header('Location: cadastrar-cliente.php?sucesso=1');
            exit;
        } catch (Exception $e) {
            $erros[] = 'Erro ao cadastrar cliente: ' . $e->getMessage();
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
            <ul class="lista-produtos">
                <li><a href="home-deslogado.html">home</a></li>
                <li><a href="#">guitarras</a></li>
                <li><a href="#">violões</a></li>
                <li><a href="#">baixos</a></li>
                <li><a href="#">teclados</a></li>
                <li><a href="#">flautas</a></li>
                <li><a href="login.php" class="botao-login">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="cadastro-container">
            <div class="titulo-imagens">
                <h2>CADASTRE-SE!</h2>
            </div>

            <?php if (!empty($erros)): ?>
            <div class="erros">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" class="form-cadastro">
                <div class="linha-dupla">
                    <div>
                        <label>Nome</label>
                        <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label>Sobrenome</label>
                        <input type="text" name="sobrenome" value="<?= htmlspecialchars($_POST['sobrenome'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

                <div class="linha-dupla">
                    <div>
                        <label>Data de nascimento</label>
                        <input type="date" name="data_nascimento"
                            value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label>CPF</label>
                        <input type="text" name="cpf" value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>
                    </div>
                </div>

                <label>Senha</label>
                <input type="password" name="senha" required>

                <label>Confirme a senha</label>
                <input type="password" name="confirma_senha" required>

                <button type="submit" class="botao">CONCLUIR CADASTRO</button>
            </form>
        </section>
    </main>

    <!-- Alerta SweetAlert após cadastro bem-sucedido -->
    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
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