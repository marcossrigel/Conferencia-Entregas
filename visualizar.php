<?php
  session_start();
  include_once("config.php");

  if (!isset($_SESSION['id_fornecedor'])) {
      header("Location: index.php");
      exit;
  }

  $id_fornecedor = $_SESSION['id_fornecedor'];
  $tipo_usuario = $_SESSION['tipo_usuario'] ?? 'fornecedor';

  if ($tipo_usuario === 'admin') {
    $query = "SELECT * FROM entregas ORDER BY id DESC";
    $stmt = $conexao->prepare($query);
    $stmt->execute(); // ← ESSENCIAL AQUI
    
} else {
    $query = "SELECT * FROM entregas WHERE id_fornecedores = ? ORDER BY id DESC";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $id_fornecedor);
    $stmt->execute();
}
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minhas Entregas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #e9eef1;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: auto;
    }

    h1 {
      font-size: 24px;
      text-align: center;
      margin-bottom: 20px;
      color: #000;
    }

    .accordion {
      background-color: #fff;
      cursor: pointer;
      padding: 18px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 18px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .accordion:hover {
      background-color: #f9f9f9;
    }

    .panel {
      padding: 0 0 15px 0;
      display: none;
      background-color: white;
      overflow: hidden;
      border-radius: 0 0 10px 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 15px;
    }

    .panel p {
      margin: 10px 18px;
      font-size: 15px;
      line-height: 1.5;
    }

    .modal-overlay {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal {
      background: white;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      max-width: 300px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .modal button {
      margin: 10px 5px;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .modal .confirm {
      background-color: #e74c3c;
      color: white;
    }

    .modal .cancel {
      background-color: #bdc3c7;
      color: black;
    }


    .seta {
      font-size: 22px;
      transform: rotate(0deg);
      transition: transform 0.3s ease;
    }

    .accordion.active .seta {
      transform: rotate(180deg);
    }

    .botao-voltar {
      text-align: center;
      margin-top: 40px;
    }

    .botao-voltar button {
      padding: 10px 20px;
      background-color: #4da6ff;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .botao-voltar button:hover {
      background-color: #3399ff;
    }
  </style>
</head>

<body>

<div class="container">
  <h1>Minhas Entregas</h1>

  <?php while ($entrega = $resultado->fetch_assoc()): ?>
    <button class="accordion">
      <strong><?= htmlspecialchars($entrega['produto']) ?></strong>
      <span class="seta">⌄</span>
    </button>

    <div class="panel">
      <p><strong>Responsável:</strong> <?= htmlspecialchars($entrega['responsavel_recebimento']) ?></p>
      <p><strong>Quantidade:</strong> <?= htmlspecialchars($entrega['quantidade_pedida']) ?></p>
      <p><strong>Peso Etiqueta:</strong> <?= htmlspecialchars($entrega['peso_etiqueta']) ?> | 
         <strong>Peso Balança:</strong> <?= htmlspecialchars($entrega['peso_balanca']) ?></p>
      <p><strong>Tara:</strong> <?= htmlspecialchars($entrega['tara']) ?> | 
         <strong>Peso Líquido:</strong> <?= htmlspecialchars($entrega['peso_liquido']) ?></p>
      
      <?php
        $div = floatval($entrega['divergencia']);
        $status = $div < 0 ? 'Não está ok' : 'OK';
        $cor = $div < 0 ? 'red' : 'green';
      ?>
      <p>
        <strong>Divergência:</strong> 
        <?= number_format($div, 2, ',', '') ?> 
        <span style="color:<?= $cor ?>; font-weight: bold;">(<?= $status ?>)</span>
      </p>
      
      <p><strong>Observações:</strong> <?= htmlspecialchars($entrega['observacoes']) ?></p>
      
      <?php if (!empty($entrega['foto'])): ?>
    <p><strong>Foto:</strong><br><img src="uploads/<?= $entrega['foto'] ?>" width="200" style="margin-top:10px;"></p>
    <?php endif; ?>
    <?php if (!empty($entrega['assinatura_base64'])): ?>
      <p><strong>Assinatura:</strong><br>
        <img src="uploads/<?= htmlspecialchars($entrega['assinatura_base64']) ?>" width="200">
      </p>
    <?php endif; ?>

    <button 
      type="button"
      class="btn-excluir"
      data-id="<?= $entrega['id'] ?>"
      style="margin: 10px 18px; padding: 8px 12px; background-color: #ff4d4d; color: white; border: none; border-radius: 8px; cursor: pointer;">
      🗑 Excluir
    </button>
     
    </div>
    <?php endwhile; ?>
    
    <p>
      <a href="gerar_pdf.php" target="_blank">📄 Gerar PDF</a>
    </p>

  <div class="botao-voltar">
    <button onclick="window.location.href='home.php';">&lt; Voltar para Home</button>
  </div>
    
</div>

<div class="modal-overlay" id="confirmModal">
  <div class="modal">
    <p>Tem certeza que deseja excluir esta entrega?</p>
    <form id="deleteForm" method="get" action="excluir_entrega.php">
      <input type="hidden" name="id" id="deleteId">
      <button type="submit" class="confirm">Sim, excluir</button>
      <button type="button" class="cancel" onclick="fecharModal()">Cancelar</button>
    </form>
  </div>
</div>

  <script>
    const accordions = document.querySelectorAll(".accordion");
    const botoesExcluir = document.querySelectorAll(".btn-excluir");
    const modal = document.getElementById("confirmModal");
    const deleteId = document.getElementById("deleteId");
  
    accordions.forEach((acc) => {
      acc.addEventListener("click", function () {
        this.classList.toggle("active");
        const panel = this.nextElementSibling;
        panel.style.display = (panel.style.display === "block") ? "none" : "block";
      });
    });
  
    botoesExcluir.forEach(botao => {
      botao.addEventListener("click", function () {
        const entregaId = this.getAttribute("data-id");
        deleteId.value = entregaId;
        modal.style.display = "flex";
      });
    });
  
    function fecharModal() {
      modal.style.display = "none";
    }
  
  </script>
</body>
</html>
