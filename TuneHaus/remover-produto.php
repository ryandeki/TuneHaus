<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

if (!isset($_GET['id'])) {
    $_SESSION['alert'] = "erro";
    header("Location: listar-produto.php");
    exit;
}

$id = (int)$_GET['id'];
$repo = new ProdutoRepositorio($pdo);
$repo->excluir($id);

// avisa que excluiu
$_SESSION['alert'] = "excluido";

header("Location: listar-produto.php");
exit;