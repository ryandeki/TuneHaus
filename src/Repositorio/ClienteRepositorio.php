<?php
require_once __DIR__ . '/../Modelo/Cliente.php';

class ClienteRepositorio
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function salvar(Cliente $cliente)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO clientes (usuario_id, nome, sobrenome, email, cpf, data_nascimento)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $cliente->getUsuarioId(),
            $cliente->getNome(),
            $cliente->getSobrenome(),
            $cliente->getEmail(),
            $cliente->getCpf(),
            $cliente->getDataNascimento()
        ]);
        return $this->pdo->lastInsertId();
    }

    public function buscarPorCpf($cpf)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE cpf = ?");
        $stmt->execute([$cpf]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Cliente(
            $data['usuario_id'],
            $data['nome'],
            $data['sobrenome'],
            $data['email'],
            $data['data_nascimento'],
            $data['cpf']
        ) : null;
    }
}