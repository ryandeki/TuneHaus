<?php
require_once __DIR__ . '/../Modelo/Usuario.php';

class UsuarioRepositorio
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function salvar(Usuario $usuario)
    {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, perfil, email, senha) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario->getNome(), $usuario->getPerfil(), $usuario->getEmail(), $usuario->getSenha()]);
        return $this->pdo->lastInsertId();
    }

    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Usuario($data['id'], $data['nome'], $data['perfil'], $data['email'], $data['senha']) : null;
    }

    public function autenticar($email, $senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && password_verify($senha, $data['senha'])) {
            return new Usuario($data['id'], $data['nome'], $data['perfil'], $data['email'], $data['senha']);
        }

        return null;
    }
}
?>