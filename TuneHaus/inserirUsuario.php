<?php
    require_once __DIR__ . '/../src/conexao-bd.php';
    require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
    require_once __DIR__ . '/../src/Modelo/Usuario.php';

    $email = 'ryan@exemplo.com';
    $senha = '1234';
    $nome = 'Ryan';
    $perfil = 'Admin';

    $repo = new UsuarioRepositorio($pdo);

    if ($repo->buscarPorEmail($email)) {
        echo "Usuário já existe! {$email}\n";
        exit;
    }

    $usuario = new Usuario(0, $nome, $email, $senha, $perfil);
    $repo->salvar($usuario);

    echo "Usuário inserido: {$email}\n";



?>