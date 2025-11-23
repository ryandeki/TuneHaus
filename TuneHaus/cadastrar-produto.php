<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    header("Location: home.php");
    exit;
}

$repo = new ProdutoRepositorio($pdo);

try {
    $stmtCat = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome");
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categorias = [];
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $informacoes = trim($_POST['informacoes'] ?? null);
    $preco = isset($_POST['preco']) ? (float) str_replace(',', '.', $_POST['preco']) : 0.0;
    $musica = trim($_POST['musica'] ?? null);
    $categoria_id = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;

    if ($nome === '') $erros[] = 'Nome é obrigatório.';
    if ($descricao === '') $erros[] = 'Descrição é obrigatória.';
    if ($preco <= 0) $erros[] = 'Informe um preço válido.';
    if (empty($categoria_id)) $erros[] = 'Escolha a categoria.';

    $uploadsDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

    $imagemFilename = null; 

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagemFilename = uniqid('img_', true) . '.' . $ext;

        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadsDir . $imagemFilename)) {
            $erros[] = 'Falha ao enviar a imagem. Será usada imagem padrão.';
            $imagemFilename = null;
        }
    }

    if (empty($erros)) {
        $produto = new Produto(
            null, 
            $nome,
            $descricao,
            $informacoes,
            $preco,
            $musica,
            $imagemFilename,
            $categoria_id
        );

        try {
            $repo->salvar($produto);

            $_SESSION['alert'] = 'cadastrado';

            header('Location: listar-produto.php');
            exit;
        } catch (Exception $e) {
            $erros[] = 'Erro ao salvar produto: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUNEHAUS - Cadastrar Produto</title>
    <link rel="stylesheet" href="css/cadastrar-produto.css">
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

    <main>
        <section class="cadastro-container">
            <div class="titulo-imagens">
                <img src="img/notasmusicais.png" alt="Notas musicais" class="notas-musicais">
                <h2>CADASTRAR <br> PRODUTOS</h2>
                <img src="img/notasmusicais2.png" class="notas-musicais-dois">
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

            <form method="POST" enctype="multipart/form-data" class="form-cadastro">

                <label for="categoria_id">Categoria</label>
                <select name="categoria_id" id="categoria_id" required>
                    <option value="">Selecione a categoria</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Digite o nome do produto" required>

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" placeholder="Digite a descrição do produto"
                    required></textarea>

                <label for="informacoes">Informações Adicionais</label>
                <textarea name="informacoes" id="informacoes" placeholder="Digite as informações do produto"></textarea>

                <label for="preco">Preço</label>
                <input type="number" name="preco" id="preco" placeholder="R$" required>

                <label for="musica">Sugestão de música</label>
                <input type="url" name="musica" id="musica" placeholder="https://exemplo.com.br">

                <label for="imagem">Escolher imagem</label>
                <input type="file" name="imagem" id="imagem" accept="image/*">

                <button type="submit" class="botao">CADASTRAR PRODUTO</button>
            </form>
        </section>
    </main>
</body>

</html>