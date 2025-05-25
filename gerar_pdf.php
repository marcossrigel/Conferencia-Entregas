<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
include("config.php");

use Dompdf\Dompdf;

if (!isset($_SESSION['id_fornecedor'])) {
    echo "Acesso negado.";
    exit;
}

$id_fornecedor = $_SESSION['id_fornecedor'];
$fornecedor = $_SESSION['fornecedor'] ?? 'Desconhecido';

$stmt = $conexao->prepare("SELECT * FROM entregas WHERE id_fornecedores = ? ORDER BY id DESC");
$stmt->bind_param("i", $id_fornecedor);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Nenhuma entrega encontrada.";
    exit;
}

ob_start(); // Inicia buffer de saída para capturar o HTML
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Entregas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        .entrega { margin-bottom: 30px; page-break-inside: avoid; }
        .entrega h2 { margin-bottom: 10px; }
        .entrega p { margin: 3px 0; }
        img { margin-top: 5px; max-width: 250px; height: auto; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>

<h1>Relatório de Entregas - <?= htmlspecialchars($fornecedor) ?></h1>

<?php while ($entrega = $resultado->fetch_assoc()): ?>
    <div class="entrega">
        <h2>Produto: <?= htmlspecialchars($entrega['produto']) ?></h2>
        <p><strong>Responsável:</strong> <?= htmlspecialchars($entrega['responsavel_recebimento']) ?></p>
        <p><strong>Quantidade:</strong> <?= htmlspecialchars($entrega['quantidade_pedida']) ?></p>
        <p><strong>Peso Etiqueta:</strong> <?= htmlspecialchars($entrega['peso_etiqueta']) ?> |
           <strong>Peso Balança:</strong> <?= htmlspecialchars($entrega['peso_balanca']) ?></p>
        <p><strong>Tara:</strong> <?= htmlspecialchars($entrega['tara']) ?> |
           <strong>Peso Líquido:</strong> <?= htmlspecialchars($entrega['peso_liquido']) ?></p>
        <p><strong>Divergência:</strong> <?= htmlspecialchars($entrega['divergencia']) ?></p>
        <p><strong>Observações:</strong> <?= htmlspecialchars($entrega['observacoes']) ?></p>
        <p><strong>Data:</strong> <?= htmlspecialchars($entrega['data_registro']) ?></p>

        <?php if (!empty($entrega['foto']) && file_exists("uploads/{$entrega['foto']}")): ?>
            <p><strong>Foto:</strong><br>
            <img src="uploads/<?= $entrega['foto'] ?>" alt="Foto do produto"></p>
        <?php endif; ?>

        <?php if (!empty($entrega['assinatura_base64']) && file_exists("uploads/{$entrega['assinatura_base64']}")): ?>
            <p><strong>Assinatura:</strong><br>
            <img src="uploads/<?= $entrega['assinatura_base64'] ?>" alt="Assinatura"></p>
        <?php endif; ?>
    </div>
    <hr>
<?php endwhile; ?>

</body>
</html>

<?php
$html = ob_get_clean(); // Captura o HTML do buffer

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_entregas.pdf", ["Attachment" => false]);
?>
