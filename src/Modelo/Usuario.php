<?php
class Usuario {
    private $id;
    private $nome;
    private $perfil;
    private $email;
    private $senha;

    public function __construct($id = null, $nome, $perfil, $email, $senha) {
        $this->id = $id;
        $this->nome = $nome;
        $this->perfil = $perfil;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getPerfil() { return $this->perfil; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
}
?>