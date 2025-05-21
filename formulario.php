<?php 
session_start();
include("config.php");

// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    // Garante que o usuário está logado
    if (!isset($_SESSION['id_fornecedor'])) {
        echo "Acesso não autorizado. Faça login.";
        exit;
    }

    $id_fornecedor = $_SESSION['id_fornecedor'];
    $fornecedor = isset($_SESSION['nome_fantasia']) ? $_SESSION['nome_fantasia'] : 'Desconhecido';
    $responsavel = $_POST['responsavel'];
    $produto = $_POST['produto'];
    $quantidade = $_POST['quantidade'];
    $peso_etiqueta = $_POST['peso_etiqueta'];
    $peso_balanca = $_POST['peso_balanca'];
    $tara = $_POST['tara'];
    $peso_liquido = $_POST['peso_liquido'];
    $divergencia = $_POST['divergencia'];
    $observacoes = $_POST['observacoes'];

    // Upload da foto
    $nome_arquivo = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nome_arquivo = 'foto_' . time() . '_' . basename($_FILES['foto']['name']);
        $caminho_destino = 'uploads/' . $nome_arquivo;
        move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_destino);
    }

    // Assinatura digital
    $assinatura_base64 = '';
    if (!empty($_POST['assinatura_base64'])) {
        $assinatura_base64 = str_replace('data:image/png;base64,', '', $_POST['assinatura_base64']);
        $dados = base64_decode($assinatura_base64);
        $nome_arquivo_assinatura = 'assinatura_' . time() . '.png';
        $caminho_assinatura = 'uploads/' . $nome_arquivo_assinatura;
        if (file_put_contents($caminho_assinatura, $dados)) {
            $assinatura_base64 = $nome_arquivo_assinatura;
        }
    }

    // Inserção no banco
    $stmt = $conexao->prepare("
        INSERT INTO entregas (
            id_fornecedores, fornecedor, responsavel_recebimento, produto, quantidade_pedida,
            peso_etiqueta, peso_balanca, tara, peso_liquido, divergencia,
            observacoes, foto, assinatura_base64
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isssissssssss",
        $id_fornecedor, $fornecedor, $responsavel, $produto, $quantidade,
        $peso_etiqueta, $peso_balanca, $tara, $peso_liquido, $divergencia,
        $observacoes, $nome_arquivo, $assinatura_base64
    );

    if ($stmt->execute()) {
        echo "<script>alert('Dados inseridos com sucesso!'); window.location.href='formulario.php';</script>";
    } else {
        echo "Erro ao inserir: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conferência de Entrega</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #e3e8ec;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
      min-height: 100vh;
    }
    .container {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      max-width: 500px;
      width: 100%;
    }
    .main-title {
      font-size: 22px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-size: 15px;
    }
    input[type="text"],
    input[type="number"],
    input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      box-sizing: border-box;
    }
    .row {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }
    .row .col {
      flex: 1;
    }
    .button-group {
      text-align: center;
      margin-top: 25px;
    }
    .button-group button {
      padding: 12px 20px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      background-color: #4da6ff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }
    .button-group button:hover {
      background-color: #3399ff;
    }
    .cancelar-link {
      text-align: center;
      margin-top: 15px;
    }
    .cancelar-link a {
      color: red;
      font-weight: bold;
      text-decoration: none;
    }
    @media (max-width: 480px) {
      .row {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="main-title">Conferência de Entrega</div>
    
    <form method="POST" enctype="multipart/form-data" onsubmit="return salvarAssinaturaAntesDeEnviar()">
      <p><strong>Fornecedor:</strong> <?= isset($_SESSION['nome_fantasia']) ? htmlspecialchars($_SESSION['nome_fantasia']) : 'Não identificado' ?></p>

      <label>Responsável Recebimento</label>
      <input type="text" name="responsavel" required>

      <label>Produto</label>
      <input type="text" name="produto" required>

      <label>Quantidade Pedida</label>
      <input type="number" name="quantidade" required>

      <div class="row">
        <div class="col">
          <label>Peso da Etiqueta</label>
          <input type="text" id="peso_etiqueta" name="peso_etiqueta">
        </div>
        <div class="col">
          <label>Peso da Balança</label>
          <input type="text" id="peso_balanca" name="peso_balanca">
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label>Tara</label>
          <input type="text" name="tara">
        </div>
        <div class="col">
          <label>Peso Líquido</label>
          <input type="text" name="peso_liquido">
        </div>
      </div>

      <label>Divergência</label>
      <input type="text" name="divergencia">

      <label>Observações</label>
      <input type="text" name="observacoes">

      <label>Upload de Foto</label>
      <input type="file" name="foto">

      <h4 style="margin-top: 10px;">Assinatura Digital</h4>
      <canvas id="signature-pad" width="290" height="100" style="border:1px solid #ccc; border-radius:10px;"></canvas>
      <input type="hidden" name="assinatura_base64" id="assinatura_base64">
      
      <div style="margin-top: 10px;">
        <button onclick="clearSignature()" type="button">Limpar</button>
      </div>

      <div class="button-group">
        <button type="submit" name="submit">Confirmar Entrega</button>
      </div>

      <div class="cancelar-link">
        <a href="home.php">Cancelar</a>
      </div>
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script>
  const canvas = document.getElementById('signature-pad');
  const signaturePad = new SignaturePad(canvas);

  function clearSignature() {
    signaturePad.clear();
  }

  function saveSignature() {
    const dataURL = signaturePad.toDataURL('image/png');
    document.getElementById('assinatura_base64').value = dataURL;
  }

  function salvarAssinaturaAntesDeEnviar() {
    if (signaturePad.isEmpty()) {
      alert("Por favor, assine antes de enviar.");
      return false;
    }
    saveSignature();
    return true;
  }
</script>

</body>
</html>
