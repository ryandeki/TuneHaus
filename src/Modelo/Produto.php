<?php
class Produto
{
    private ?int $id;
    private string $nome;
    private string $descricao;
    private ?string $informacoes;
    private float $preco;
    private ?string $musica;
    private string $imagem;
    private ?int $categoria_id;

    public function __construct(
        ?int $id,
        string $nome,
        string $descricao,
        ?string $informacoes,
        float $preco,
        ?string $musica,
        string $imagem,
        ?int $categoria_id
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->informacoes = $informacoes;
        $this->preco = $preco;
        $this->musica = $musica;
        $this->imagem = $imagem;
        $this->categoria_id = $categoria_id;
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): string { return $this->descricao; }
    public function getInformacoes(): ?string { return $this->informacoes; }
    public function getPreco(): float { return $this->preco; }
    public function getMusica(): ?string { return $this->musica; }
    public function getImagem(): string { return $this->imagem; }
    public function getCategoriaId(): ?int { return $this->categoria_id; }

    public function setImagem(string $imagem): void { $this->imagem = $imagem; }
}
