<?php
    //Classe de persistência no BD
    class UsuarioRepositorio
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        private function formarObjeto(array $dados): Usuario
        {
            return new Usuario((int)$dados['id'], $dados['email'], $dados['senha']);
        }

        public function buscarPorEmail(string $email): ?Usuario
        {
            $sql = "SELECT id, email, senha FROM usuarios WHERE email =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $email);
            $stmt->execute();
            $dados = $stmt->fetch();
            return $dados ? $this->formarObjeto($dados): null ;
        }

        public function autenticar(string $email, string $senhaDigitada): bool 
        {
        $usuario = $this->buscarPorEmail($email);
        return $usuario && password_verify($senhaDigitada, $usuario->getSenha());
        }

        public function salvar(Usuario $usuario): void
        {
            $sql = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $usuario->getEmail());
            $stmt->bindValue(2, password_hash($usuario->getSenha(),PASSWORD_DEFAULT));
            $stmt->execute();
        }
    }

?>