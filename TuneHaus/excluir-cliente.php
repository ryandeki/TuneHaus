<?php
session_start();

require_once __DIR__ . '/../src/conexao-bd.php';

// Verifica se o ID foi enviado via POST
$id = $_POST['id'] ?? null;

if ($id) {
    // Prepara e executa a exclusão do cliente
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    // Define o alerta de sucesso para exibir após redirecionamento
    $_SESSION['alert'] = 'excluido';
}

// Redireciona de volta para a lista de clientes
header('Location: listar-clientes.php');
exit;