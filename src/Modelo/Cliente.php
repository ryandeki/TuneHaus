<?php

class Cliente
{

    private $id;
    private $usuario_id;
    private $nome;
    private $sobrenome;
    private $email;
    private $data_nascimento;
    private $cpf;

    public function __construct($usuario_id, $nome, $sobrenome, $email, $data_nascimento, $cpf)
    {
        $this->usuario_id = $usuario_id;
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->email = $email;
        $this->data_nascimento = $data_nascimento;
        $this->cpf = $cpf;
    }

    // Getters e setters
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getSobrenome()
    {
        return $this->sobrenome;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }
    public function getCpf()
    {
        return $this->cpf;
    }
}