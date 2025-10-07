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
    <link rel="stylesheet" href="css/alterar-produto.css">
    <title>TUNEHAUS - Editar Produto</title>
</head>

<body>
<header>
    <div class="cabecalho">TUNEHAUS <img src="img/logopng.png" alt="Logo do site" class="logo"></div>
    <nav>
        <ul class="lista-produtos">
            <li><a href="home-logado.html">home</a></li>
            <li><a href="listar-produto.php">guitarras</a></li>
            <li><a href="#">violões</a></li>
            <li><a href="#">baixos</a></li>
            <li><a href="#">teclados</a></li>
            <li><a href="#">flautas</a></li>
            <li><a href="logout.php" class="botao-logout">logout</a></li>
        </ul>
    </nav>
</header>

     <main>
        <section class="editar-container">
            <div class="titulo-imagens">
                <img src="img/notasmusicais.png" alt="Notas musicais" class="notas-musicais">
                <h2>EDITAR <br> PRODUTO</h2>
                <img src="img/notasmusicais2.png" class="notas-musicais-dois">
            </div>
            <form method="POST" enctype="multipart/form-data" class="form-editar">
                
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto->getNome()) ?>" required>
              
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" required><?= htmlspecialchars($produto->getDescricao()) ?></textarea>
               
                    <label for="informacoes">Informações Adicionais</label>
                    <textarea name="informacoes" id="informacoes"><?= htmlspecialchars($produto->getInformacoes()) ?></textarea>
              
                    <label for="preco">Preço</label>
                    <input type="number" name="preco" id="preco" value="<?= htmlspecialchars($produto->getPreco()) ?>" required>

                    <label for="musica">Sugestão de Música</label>
                    <input type="url" name="musica" id="musica" value="<?= htmlspecialchars($produto->getMusica()) ?>">
                
                    <div class="img-preview">
                        <?php if ($produto->getImagem()): ?>
                            <p>Imagem atual:</p>
                            <img src="uploads/<?= $produto->getImagem() ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
                        <?php else: ?>
                            <p>Sem imagem cadastrada</p>
                        <?php endif; ?>
                    </div>
                
                    <label for="imagem">Nova imagem (opcional)</label>
                    <input type="file" name="imagem" id="imagem" accept="image/*">
              
                    <button type="submit" class="botao">SALVAR ALTERAÇÕES</button>
            </form>
        </section>
    </main>

</body>
</html>
