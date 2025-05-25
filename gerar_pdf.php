<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
include("config.php");

if (!isset($_GET['id'])) {
    echo "ID inválido.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $conexao->prepare("SELECT * FROM entregas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$entrega = $resultado->fetch_assoc();

if (!$entrega) {
    echo "Entrega não encontrada.";
    exit;
}

$html = "
<h1 style='text-align: center;'>Resumo da Entrega</h1>
<hr>
<p><strong>Fornecedor:</strong> {$entrega['fornecedor']}</p>
<p><strong>Produto:</strong> {$entrega['produto']}</p>
<p><strong>Responsável:</strong> {$entrega['responsavel_recebimento']}</p>
<p><strong>Quantidade:</strong> {$entrega['quantidade_pedida']}</p>
<p><strong>Peso Etiqueta:</strong> {$entrega['peso_etiqueta']} | 
   <strong>Peso Balança:</strong> {$entrega['peso_balanca']}</p>
<p><strong>Tara:</strong> {$entrega['tara']} | 
   <strong>Peso Líquido:</strong> {$entrega['peso_liquido']}</p>
<p><strong>Divergência:</strong> {$entrega['divergencia']}</p>
<p><strong>Observações:</strong> {$entrega['observacoes']}</p>
<p><strong>Data de Registro:</strong> {$entrega['data_registro']}</p>
";

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("resumo_entrega_{$id}.pdf", "I"); // "I" = abre no navegador
?>
