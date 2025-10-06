<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';


if (!isset($_GET['id'])) {
    echo "<script>alert('ID do produto não informado!'); window.location='index.php';</script>";
    exit;
}

$id = (int)$_GET['id'];

$repo = new ProdutoRepositorio($pdo);
$repo->excluir($id);

echo "<script>alert('Produto removido com sucesso!'); window.location='listar-produto.php';</script>";
?>
