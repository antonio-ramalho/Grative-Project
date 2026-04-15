<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fazer Doação - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="/css/style.css">">   
</head>
<body>
    <div class="app-container">
        <div class="header">
            <a href="ConfirmarInformacoes.php" class="close-btn"><i class="ph ph-x"></i></a>
        </div>
        
        <div class="content">
            <h2>Fazer Doação</h2>
            
            <div class="progress-bar">
                <div class="step">1</div>
                <div class="step active">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>

            <div class="input-group">
                <label for="valor_doacao">Digite o valor</label>
                <div class="input-wrapper">
                    <i class="ph ph-currency-dollar"></i>
                    <input type="number" id="valor_doacao" name="valor" placeholder="Valor" required>
                </div>
            </div>

            <div class="input-group">
                <label for="mensagem_doacao">Mensagem (opcional)</label>
                <div class="input-wrapper">
                    <i class="ph ph-chat-centered-text"></i>
                    <input type="text" id="mensagem_doacao" name="mensagem" placeholder="Escreva uma mensagem">
                </div>
            </div>

        <div class="input-group">
        <label>Selecione o método de pagamento</label>
        <label class="payment-card">
            <div class="card-content">
                <i class="ph ph-pix-logo"></i>
                <span>Pix</span>
            </div>
            <input type="radio" name="metodo" value="pix" checked>
        </label>
        </div>
        
        </div>
        <div class="footer">
            <a href="ConfirmarInformacoes.php" class="btn btn-cancel">Voltar</a>
            <a href="#" class="btn btn-next">Avançar</a>
        </div>
    </div>

    <script src="/js/fazerDoacao.js"></script>
</body>
</html>