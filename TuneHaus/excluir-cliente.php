<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = (int)$_GET['id'];

$repo->excluir($id);

header("Location: listar-clientes.php?delete=ok");
exit;
