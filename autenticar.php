<?php
session_start();


require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    header('Location: login.php?erro=campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);
$usuario = $repo->buscarPorEmail($email);

// DEBUG: ver o que o banco retornou
echo '<pre>';
var_dump($usuario);
echo '</pre>';

// Se o usuário existe, verificar a senha
if ($usuario) {
    echo "Senha digitada: $senha\n";
    echo "Hash do banco: " . $usuario->getSenha() . "\n";
    $verifica = password_verify($senha, $usuario->getSenha());
    echo "password_verify: ";
    var_dump($verifica);
    exit;
}

// Se não encontrou
echo "Usuário não encontrado";
exit;