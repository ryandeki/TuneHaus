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
            $dados['imagem'] ?? null,
            $dados['categoria_id'] ?? null,
        );
    }
    public function salvar(Produto $produto): bool {
        $sql = "INSERT INTO produtos (nome, descricao, informacoes, preco, musica, imagem, categoria_id)
                VALUES (:nome, :descricao, :informacoes, :preco, :musica, :imagem, :categoria_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':descricao', $produto->getDescricao());
        $stmt->bindValue(':informacoes', $produto->getInformacoes());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':musica', $produto->getMusica());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':categoria_id', $produto->getCategoriaId(), PDO::PARAM_INT);
        return $stmt->execute();
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
    public function listarPorCategoria(int $categoriaId): array
    {
        $sql = "SELECT * FROM produtos WHERE categoria_id = :categoriaId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':categoriaId', $categoriaId, PDO::PARAM_INT);
        $stmt->execute();

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['informacoes'],
                $row['preco'],
                $row['musica'],
                $row['imagem'],
                $row['categoria_id']
            );
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

    public function atualizar(Produto $produto): bool {
        $sql = "UPDATE produtos SET nome=:nome, descricao=:descricao, informacoes=:informacoes,
                preco=:preco, musica=:musica, imagem=:imagem, categoria_id=:categoria_id
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':descricao', $produto->getDescricao());
        $stmt->bindValue(':informacoes', $produto->getInformacoes());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':musica', $produto->getMusica());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':categoria_id', $produto->getCategoriaId(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $produto->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function excluir(int $id): void
    {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }

    public function contarProdutos($categoriaId = null) {
    if ($categoriaId) {
        $sql = "SELECT COUNT(*) FROM produtos WHERE categoria_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoriaId]);
    } else {
        $sql = "SELECT COUNT(*) FROM produtos";
        $stmt = $this->pdo->query($sql);
    }

    return $stmt->fetchColumn();
    }
    public function listarPaginado(int $offset, int $limite, ?int $categoriaId = null): array{
    if ($categoriaId) {
        $sql = "SELECT * FROM produtos WHERE categoria_id = :categoria ORDER BY id DESC LIMIT :offset, :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':categoria', $categoriaId, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM produtos ORDER BY id DESC LIMIT :offset, :limite";
        $stmt = $this->pdo->prepare($sql);
    }

    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
    $stmt->execute();

    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $produtos = [];

    foreach ($dados as $p) {
        $produtos[] = new Produto(
            $p['id'],
            $p['nome'],
            $p['descricao'],
            $p['informacoes'],
            $p['preco'],
            $p['musica'],
            $p['imagem'],
            $p['categoria_id']
        );
    }

    return $produtos;
    }


}
?>
