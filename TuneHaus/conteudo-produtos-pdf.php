<?php
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';

$repo = new ProdutoRepositorio($pdo);
$produtos = $repo->listar();

// Logo da TuneHaus no topo do PDF
$logoPath = __DIR__ . '/img/logopng.png';
$logoBase64 = base64_encode(file_get_contents($logoPath));
$logoSrc = "data:image/png;base64,$logoBase64";

date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('d/m/Y H:i');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 40px;
    }

    .header {
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 3px solid #5A189A;
        padding-bottom: 10px;
        margin-bottom: 25px;
    }

    .header img {
        width: 65px;
    }

    .header h1 {
        font-size: 22px;
        color: #5A189A;
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th {
        background: #5A189A;
        color: white;
        padding: 8px;
        font-size: 14px;
    }

    td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 13px;
    }

    .footer {
        margin-top: 25px;
        text-align: center;
        font-size: 12px;
        color: #777;
    }
</style>

</head>
<body>

<div class="header">
    <img src="<?= $logoSrc ?>" alt="Logo TuneHaus">
    <h1>RELATÓRIO — PRODUTOS</h1>
</div>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço (R$)</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($produtos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p->getNome()) ?></td>

            <td>
                <?php
                    $cat = $p->getCategoriaId();
                    $categorias = [
                        1 => "Guitarras",
                        2 => "Violões",
                        3 => "Baixos",
                        4 => "Teclados",
                        5 => "Flautas"
                    ];
                    echo $categorias[$cat] ?? "N/A";
                ?>
            </td>

            <td><?= number_format($p->getPreco(), 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Gerado em <?= $dataAtual ?>
</div>

</body>
</html>
