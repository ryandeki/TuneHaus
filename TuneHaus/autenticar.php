<?php
session_start();


require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';

//Permitir somente POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

//Campos obrigatórios
if ($email === '' || $senha === '') {
    header('Location: login.php?erro=campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);

//Credenciais corretas
if ($repo->autenticar($email, $senha)) {
    //Regenera a sessão 
    session_regenerate_id(true);
    //Setar a sessão com o email do usuário
    $_SESSION['usuario'] = $email;
    // echo '<pre>';
    // var_dump($_SESSION);
    // echo '</pre>';
    header('Location:listar-produto.php');
    exit;
}

//Falha nas credenciais
header('Location: login.php?erro=credenciais');
exit;
