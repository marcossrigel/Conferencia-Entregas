<?php
  session_start();
  include_once("config.php");

  if (!isset($_SESSION['id_fornecedor'])) {
      header("Location: index.php");
      exit;
  }

  $id_fornecedor = $_SESSION['id_fornecedor'];
  $fornecedor = $_SESSION['fornecedor'];

  $query = "SELECT * FROM entregas WHERE id_fornecedores = ? ORDER BY id DESC";
  $stmt = $conexao->prepare($query);
  $stmt->bind_param("i", $id_fornecedor);
  $stmt->execute();
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
      <p><strong>Divergência:</strong> <?= htmlspecialchars($entrega['divergencia']) ?></p>
      <p><strong>Observações:</strong> <?= htmlspecialchars($entrega['observacoes']) ?></p>
      
      <?php if (!empty($entrega['foto'])): ?>
        <p><strong>Foto:</strong><br><img src="uploads/<?= $entrega['foto'] ?>" width="200" style="margin-top:10px;"></p>
      <?php endif; ?>
      <?php if (!empty($entrega['assinatura_base64'])): ?>
        <p><strong>Assinatura:</strong><br><img src="uploads/<?= $entrega['assinatura_base64'] ?>" width="200" style="margin-top:10px;"></p>

        <a href="excluir_entrega.php?id=<?= $entrega['id'] ?>" onclick="return confirm('Deseja realmente excluir?');">🗑️ Excluir</a>

      <?php endif; ?>
      
    </div>
    <?php endwhile; ?>
    
    <p>
      <a href="gerar_pdf.php?id=<?= $entrega['id'] ?>" target="_blank">📄 Gerar PDF</a>
    </p>


  <div class="botao-voltar">
    <button onclick="window.location.href='home.php';">&lt; Voltar para Home</button>
  </div>
    
</div>

<script>
  const accordions = document.querySelectorAll(".accordion");
  accordions.forEach((acc) => {
    acc.addEventListener("click", function () {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      panel.style.display = (panel.style.display === "block") ? "none" : "block";
    });
  });
</script>

</body>
</html>
