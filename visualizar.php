<!DOCTYPE html>
<html lang="pt-br">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Visualizar Iniciativas</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #e9eef1;
  margin: 0;
  padding: 20px;
}

.container {
  max-width: 800px;
  margin: auto;
}

h1 {
  font-size: 28px;
  text-align: center;
  margin-bottom: 20px;
  color: #000;
}

.accordion {
  background-color: #fff;
  color: #333;
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

.button-left {
  margin: 10px 18px 0;
  display: flex;
  justify-content: flex-start;
}

.button-left button {
  padding: 8px 16px;
  background-color: #4da6ff;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.button-left button:hover {
  background-color: #3399ff;
}

.acoes {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
  margin-top: 20px;
  padding: 0 18px;
}

.acoes button {
  padding: 12px;
  font-size: 15px;
  border: none;
  border-radius: 10px;
  background-color: #f1f5f9;
  color: #333;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
  flex: 1 1 45%;
  min-width: 140px;
}

.acoes button:hover {
  background-color: #e0e7ff;
}

.botao-voltar {
  display: flex;
  justify-content: center;
  margin-top: 40px;
}

.botao-voltar button {
  padding: 10px 20px;
  background-color: #4da6ff;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.botao-voltar button:hover {
  background-color: #3399ff;
}

@media (max-width: 768px) {
  .container {
    padding: 15px;
  }
  h1 {
    font-size: 22px;
  }
  .panel p {
    font-size: 14px;
  }
  .acoes {
    flex-direction: column;
  }
  .acoes button {
    flex: 1 1 100%;
  }
}
</style>
</head>
<body>
<div class="container">
  <h1>Iniciativas Cadastradas</h1>

  <button class="accordion">
    <strong>Nome da Iniciativa Exemplo</strong>
    <span class="seta">⌄</span>
  </button>
  <div class="panel">
    <p><strong>Status:</strong> Em Execução | <strong>Data da Vistoria:</strong> 2025-05-18</p>
    <p><strong>Execução:</strong> 80% | <strong>Previsto:</strong> 85% | <strong>Variação:</strong> -5% | <strong>Valor Médio:</strong> R$ 150.000,00</p>
    <p><strong>Secretaria:</strong> Educação | <strong>Órgão:</strong> SEEC | <strong>Processo SEI:</strong> 1234567-89.2024.8.26.0000</p>
    <p><strong>Gestor Responsável:</strong> João Silva | <strong>Fiscal Responsável:</strong> Maria Oliveira</p>
    <p><strong>Objeto:</strong> Construção de creche no bairro X</p>
    <p><strong>Informações Gerais:</strong> Obra em ritmo acelerado, dentro do cronograma revisado.</p>
    <p><strong>Observações:</strong> Aguardando entrega de materiais do lote complementar.</p>

    <div class="button-left">
      <button onclick="window.location.href='editar_iniciativa.html';">Status andamento</button>
    </div>

    <div class="acoes">
      <button onclick="window.location.href='acompanhamento.html';">🛠 Acompanhar Pendências</button>
      <button onclick="window.location.href='infocontratuais.html';">📄 Informações Contratuais</button>
      <button onclick="window.location.href='medicoes.html';">📊 Medições</button>
      <button onclick="window.location.href='cronogramamarcos.html';">📆 Cronograma</button>
      <button onclick="window.location.href='fotografico.html';">📷 Fotografias</button>
      <button onclick="window.location.href='galeria.html';">💾 Galeria</button>
    </div>
  </div>

  <div class="botao-voltar">
    <button onclick="window.location.href='home.html';">&lt; Voltar</button>
  </div>
</div>

<script>
  const accordions = document.querySelectorAll(".accordion");
  accordions.forEach((acc) => {
    acc.addEventListener("click", function () {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  });
</script>

</body>
</html>
