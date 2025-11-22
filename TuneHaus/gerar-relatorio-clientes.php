<?php
require_once __DIR__ . "/vendor/autoload.php";

// Carrega classes da aplicação
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

use Dompdf\Dompdf;

// Busca usuários
$repo = new UsuarioRepositorio($pdo);
$clientes = $repo->buscarTodos();

// Gera HTML do PDF
ob_start();
require __DIR__ . "/conteudo-clientes-pdf.php";
$html = ob_get_clean();

// Instância Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

// Baixa o arquivo
$dompdf->stream("Relatorio-Clientes-TuneHaus.pdf", ["Attachment" => true]);
exit;
