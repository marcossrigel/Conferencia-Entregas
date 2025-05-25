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
    }
    .row {
      display: flex;
      gap: 10px;
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
    #divergencia {
      display: inline-block;
      padding: 8px 12px;
      margin-top: 5px;
      font-weight: bold;
      background-color: #f0f0f0;
      border-radius: 8px;
    }
  </style>
</head>
<body>
<?php
  session_start();
  include("config.php");
  $nome_fornecedor = 'Não identificado';
  if (isset($_SESSION['id_fornecedor'])) {
    $stmt = $conexao->prepare("SELECT fornecedor FROM fornecedores WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id_fornecedor']);
    $stmt->execute();
    $stmt->bind_result($nome_fornecedor);
    $stmt->fetch();
    $stmt->close();
  }
?>
  <div class="container">
    <div class="main-title">Conferência de Entrega</div>
    <form onsubmit="return false">
      <p style="text-align: right; font-size: 12px; color: gray;">
        Registro em: <?= date("d/m/Y H:i:s") ?>
      </p>
      <p><strong>Fornecedor:</strong> <?= htmlspecialchars($nome_fornecedor) ?></p>

      <label>Responsável Recebimento</label>
      <input type="text" name="responsavel" placeholder="Produto">

      <label>Quantidade Pedida</label>
      <input type="number" name="quantidade">

      <div class="row">
        <div class="col">
          <label>Peso da Etiqueta</label>
          <input type="text" id="peso_etiqueta">
        </div>
        <div class="col">
          <label>Peso da Balança</label>
          <input type="text" id="peso_balanca">
        </div>
      </div>

      <label>Divergência</label>
      <label id="divergencia">---</label>

      <div class="row">
        <div class="col">
          <label>Peso Líquido</label>
          <input type="text" id="peso_liquido" readonly>
        </div>
      </div>

      <label>Observações</label>
      <input type="file">

      <label>Assinatura Digital</label>
      <input type="text" placeholder="(simulação de campo de assinatura)">
      <textarea placeholder="(canvas)"></textarea>

      <div class="button-group">
        <button type="submit">Confirmar Entrega</button>
      </div>

      <div class="cancelar-link">
        <a href="#">Cancelar</a>
      </div>
    </form>
  </div>

  <script>
    const tara = 5.0;
    const pesoEtiquetaInput = document.getElementById('peso_etiqueta');
    const pesoBalancaInput = document.getElementById('peso_balanca');
    const pesoLiquidoInput = document.getElementById('peso_liquido');
    const divergenciaLabel = document.getElementById('divergencia');

    function atualizarDivergencia() {
      const etiqueta = parseFloat(pesoEtiquetaInput.value.replace(',', '.')) || 0;
      const balanca = parseFloat(pesoBalancaInput.value.replace(',', '.')) || 0;
      const liquido = balanca - tara;

      pesoLiquidoInput.value = liquido.toFixed(1).replace('.', ',');

      const diferenca = balanca - etiqueta;
      if (diferenca < 0) {
        divergenciaLabel.textContent = "Não está ok";
        divergenciaLabel.style.color = "red";
      } else {
        divergenciaLabel.textContent = "OK";
        divergenciaLabel.style.color = "green";
      }
    }

    pesoEtiquetaInput.addEventListener('input', atualizarDivergencia);
    pesoBalancaInput.addEventListener('input', atualizarDivergencia);
  </script>
</body>
</html>
