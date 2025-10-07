<?php
class Usuario {
  private $id;
    private $nome;
    private $email;
    private $senha;
    private $perfil;

    public function __construct($id, $nome, $email, $senha, $perfil) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->perfil = $perfil;
    }

    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getPerfil() { return $this->perfil; }
}
?>