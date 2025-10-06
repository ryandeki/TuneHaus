<?php
require_once __DIR__ . '/../Modelo/Produto.php';

class ProdutoRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    private function formarObjeto(array $dados): Produto
    {
        return new Produto(
            (int)$dados['id'],
            $dados['nome'],
            $dados['descricao'],
            $dados['informacoes'] ?? null,
            (float)$dados['preco'],
            $dados['musica'] ?? null,
            $dados['imagem'] ?? null
        );
    }
    public function salvar(Produto $produto): void
    {
        $sql = "INSERT INTO produtos (nome, descricao, informacoes, preco, musica, imagem)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $produto->getNome());
        $stmt->bindValue(2, $produto->getDescricao());
        $stmt->bindValue(3, $produto->getInformacoes());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getMusica());
        $stmt->bindValue(6, $produto->getImagem());
        $stmt->execute();
    }
    public function listar(): array
    {
        $sql = "SELECT * FROM produtos";
        $stmt = $this->pdo->query($sql);
        $produtos = [];

        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = $this->formarObjeto($linha);
        }

        return $produtos;
    }
    public function buscarPorId(int $id): ?Produto
    {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->formarObjeto($dados) : null;
    }
    public function atualizar(Produto $produto): void
    {
        $sql = "UPDATE produtos
                SET nome = ?, descricao = ?, informacoes = ?, preco = ?, musica = ?, imagem = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $produto->getNome());
        $stmt->bindValue(2, $produto->getDescricao());
        $stmt->bindValue(3, $produto->getInformacoes());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getMusica());
        $stmt->bindValue(6, $produto->getImagem());
        $stmt->bindValue(7, $produto->getId());
        $stmt->execute();
    }
    public function excluir(int $id): void
    {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}
?>
