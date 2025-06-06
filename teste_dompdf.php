<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>PDF gerado com Dompdf!</h1><p>Funcionando perfeitamente 🎉</p>');

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("teste.pdf", ["Attachment" => false]);
?>
