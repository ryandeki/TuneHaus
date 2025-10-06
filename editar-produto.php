<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';

$repo = new ProdutoRepositorio($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $informacoes = $_POST['informacoes'] ?? null;
    $preco = (float)$_POST['preco'];
    $musica = $_POST['musica'] ?? null;

    $produto = $repo->buscarPorId($id);
    if (!$produto) {
        echo "<script>alert('Produto não encontrado!'); window.location='listar-produto.php';</script>";
        exit;
    }

    $imagem = $produto->getImagem();

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $nome_imagem = $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "uploads/" . $nome_imagem);
        $imagem = $nome_imagem;
    }

    $produto = new Produto($id, $nome, $descricao, $informacoes, $preco, $musica, $imagem);
    $repo->atualizar($produto);

    echo "<script>alert('Produto atualizado com sucesso!'); window.location='listar-produto.php';</script>";
    exit;
}

$id = (int)$_GET['id'];
$produto = $repo->buscarPorId($id);
if (!$produto) {
    echo "<script>alert('Produto não encontrado!'); window.location='listar-produto.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUNEHAUS - Editar Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }
        header img { height: 50px; }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #4b254b;
            font-weight: bold;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fefefe;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container h2 {
            text-align: center;
            color: #4b254b;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4b254b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #351736;
        }
        .img-preview {
            text-align: center;
            margin-bottom: 15px;
        }
        .img-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
        <div><h1>TUNEHAUS 🎵</h1></div>
        <nav>
            <a href="index.php">home</a>
            <a href="#">guitarras</a>
            <a href="#">violões</a>
            <a href="#">baixos</a>
            <a href="#">teclados</a>
            <a href="#">flautas</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <div class="container">
        <h2>EDITAR PRODUTO</h2>
        <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $produto->getId() ?>">

    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto->getNome()) ?>" required>
    </div>

    <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" required><?= htmlspecialchars($produto->getDescricao()) ?></textarea>
    </div>

    <div class="form-group">
        <label for="informacoes">Informações Adicionais</label>
        <textarea name="informacoes" id="informacoes"><?= htmlspecialchars($produto->getInformacoes()) ?></textarea>
    </div>

    <div class="form-group">
        <label for="preco">Preço</label>
        <input type="text" name="preco" id="preco" value="<?= htmlspecialchars($produto->getPreco()) ?>" required>
    </div>

    <div class="form-group">
        <label for="musica">Sugestão de Música</label>
        <input type="text" name="musica" id="musica" value="<?= htmlspecialchars($produto->getMusica()) ?>">
    </div>

    <div class="img-preview">
        <?php if ($produto->getImagem()): ?>
            <p>Imagem atual:</p>
            <img src="uploads/<?= $produto->getImagem() ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
        <?php else: ?>
            <p>Sem imagem cadastrada</p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="imagem">Nova imagem (opcional)</label>
        <input type="file" name="imagem" id="imagem" accept="image/*">
    </div>

    <button type="submit">SALVAR ALTERAÇÕES</button>
</form>
    </div>
</body>
</html>
