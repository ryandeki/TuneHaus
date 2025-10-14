<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $informacoes = $_POST['informacoes'] ?? null;
    $preco = (float)($_POST['preco'] ?? 0);
    $musica = $_POST['musica'] ?? null;

    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) mkdir($pasta, 0755, true);
        $imagem = $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagem);
    }

    $produto = new Produto(0, $nome, $descricao, $informacoes, $preco, $musica, $imagem);
    $repo = new ProdutoRepositorio($pdo);
    $repo->salvar($produto);

    echo "<script>alert('Produto cadastrado com sucesso!'); window.location='listar-produto.php';</script>";
    exit;
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
        <section class="cadastro-container">
            <div class="titulo-imagens">
                <img src="img/notasmusicais.png" alt="Notas musicais" class="notas-musicais">
                <h2>CADASTRAR <br> PRODUTOS</h2>
                <img src="img/notasmusicais2.png" class="notas-musicais-dois">
            </div>
            <form method="POST" enctype="multipart/form-data" class="form-cadastro">
                
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" placeholder="Digite o nome do produto">
              
                    <label  for="descricao">Descrição</label>
                    <textarea placeholder="Digite a descrição do produto" name="descricao" id="descricao" required></textarea>
               
                    <label for="informacoes">Informações Adicionais </label>
                    <textarea placeholder="Digite as Informações do produto" name="informacoes" id="informacoes"></textarea>
              
                    <label for="preco">Preço</label>
                    <input type="number" placeholder="R$" name="preco" id="preco" value="" required>
                
                    <label for="musica">Sugestão de música</label>
                    <input type="url" placeholder="https://exemplo.com.br" name="musica" id="musica" value="">
                
                    <label for="imagem">Imagem</label>
                    <input type="file" name="imagem" id="imagem" accept="image/*">
              
                    <button type="submit" class="botao">CADASTRAR PRODUTO</button>
            </form>
        </section>
    </main>
</body>
</html>
