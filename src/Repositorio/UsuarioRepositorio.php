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
    // A senha que vem do objeto Usuario é a senha pura digitada pelo usuário
    $senhaPlain = $usuario->getSenha();

    // Criamos o hash de forma correta (apenas 1 vez)
    $senhaHash = password_hash($senhaPlain, PASSWORD_DEFAULT);

    $stmt = $this->pdo->prepare("
        INSERT INTO usuarios (nome, perfil, email, senha)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $usuario->getNome(),
        $usuario->getPerfil(),
        $usuario->getEmail(),
        $senhaHash
    ]);

    return $this->pdo->lastInsertId();
}


        public function atualizar(Usuario $usuario)
    {
        $stmt = $this->pdo->prepare("
            UPDATE usuarios 
            SET nome = ?, email = ?, perfil = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getPerfil(),
            $usuario->getId()
        ]);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }


   
    public function buscarTodos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY nome ASC");
        $usuarios = [];

        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new Usuario(
                $linha['id'],
                $linha['nome'],
                $linha['perfil'],
                $linha['email'],
                $linha['senha'] ?? ""
            );
        }

        return $usuarios;
    }


        public function contarUsuarios()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }

    public function buscarPaginado($offset, $limit)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios ORDER BY id DESC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        $usuarios = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new Usuario(
                $data['id'],
                $data['nome'],
                $data['perfil'],
                $data['email'],
                $data['senha']
            );
        }

        return $usuarios;
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

    public function buscarPorEmail($email)
    {
    $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $data
        ? new Usuario($data['id'], $data['nome'], $data['perfil'], $data['email'], $data['senha'])
        : null;
    }

}
?>