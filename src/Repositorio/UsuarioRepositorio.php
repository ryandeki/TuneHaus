<?php
    class UsuarioRepositorio
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        private function formarObjeto(array $dados): Usuario
        {
            return new Usuario((int)$dados['id'], $dados['nome'], $dados['email'], $dados['senha'], $dados['perfil']);
        }

       public function salvar(Usuario $usuario) {
            $sql = "INSERT INTO usuarios (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
            $stmt->bindValue(':perfil', $usuario->getPerfil());
            $stmt->execute();
        }

        public function buscarPorEmail($email) {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Usuario($row['id'], $row['nome'], $row['email'], $row['senha'], $row['perfil']);
            }

            return null;
        }

        public function autenticar($email, $senhaDigitada) {
            $usuario = $this->buscarPorEmail($email);
            if ($usuario && password_verify($senhaDigitada, $usuario->getSenha())) {
                return true;
            }
            return false;
        }
    }

?>