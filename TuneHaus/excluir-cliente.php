<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['alert'] = 'excluido';
}

header('Location: listar-clientes.php');
exit;