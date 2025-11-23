<?php
date_default_timezone_set('America/Sao_Paulo');
$dataGeracao = date("d/m/Y H:i:s");

$logoPath = __DIR__ . "/img/logopng.png";
$logoBase64 = base64_encode(file_get_contents($logoPath));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, Helvetica, sans-serif; }
.cabecalho { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; }
.logo { width: 80px; }
.titulo { font-size: 28px; font-weight: bold; color: #40023c; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
table th { background: #40023c; color: #fff; padding: 10px; font-size: 14px; text-align: left; }
table td { padding: 8px; font-size: 13px; border-bottom: 1px solid #ccc; }
.rodape { margin-top: 35px; text-align: center; font-size: 12px; color: #444; }
</style>
</head>
<body>

<div class="cabecalho">
    <img src="data:image/png;base64,<?= $logoBase64 ?>" class="logo">
    <span class="titulo">RELATÓRIO – CLIENTES</span>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Perfil</th>
    </tr>
    </thead>

   <tbody>
<?php foreach ($clientes as $c): ?>
<tr>
    <td><?= $c->getId() ?></td>
    <td><?= htmlspecialchars($c->getNome()) ?></td>
    <td><?= htmlspecialchars($c->getEmail()) ?></td> <!-- 3ª coluna = E-mail -->
    <td>
        <?php
        $perfis = [
            1 => "Administrador",
            2 => "Cliente",
            3 => "Funcionário"
        ];
        $perfilRaw = $c->getPerfil();
        if (is_numeric($perfilRaw)) {
            echo $perfis[(int)$perfilRaw] ?? "N/A";    // 4ª coluna = Perfil
        } else {
            echo htmlspecialchars($perfilRaw);
        }
        ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>

</table>

<div class="rodape">
    Gerado em <?= $dataGeracao ?> — TuneHaus
</div>

</body>
</html>
