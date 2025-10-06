<?php
    class Usuario
    {
        private int $id;
        private string $email;
        private string $senha;

        //Método construtor
        public function __construct(int $id, string $email, string $senha){
            $this->id = $id;
            $this->email = $email;
            $this->senha = $senha;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getSenha(): string
        {
            return $this->senha;
        }
    }
?>