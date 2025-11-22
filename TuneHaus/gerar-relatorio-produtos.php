<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';

// Ativar buffer
ob_start();
require __DIR__ . '/conteudo-produtos-pdf.php';
$html = ob_get_clean();

// Gerar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Nome do arquivo
$filename = 'Relatorio-Produtos-' . date('dmY') . '.pdf';

// Download
$dompdf->stream($filename, ['Attachment' => 1]);
exit;
?>
