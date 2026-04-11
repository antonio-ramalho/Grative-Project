<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fazer Doação - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../public/css/style.css">
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
                <label>Digite o valor</label>
                <div class="input-wrapper">
                    <i class="ph ph-currency-dollar"></i>
                    <input type="number" placeholder="Valor">
                </div>
            </div>

            <div class="input-group">
                <label>Mensagem (opcional)</label>
                <div class="input-wrapper">
                    <i class="ph ph-chat-centered-text"></i>
                    <input type="text" placeholder="Escreva uma mensagem">
                </div>
            </div>

            <p style="font-size: 14px; font-weight: 600; margin-top: 20px;">Escolher método de pagamento</p>
            
            <div class="method-card">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="ph ph-currency-dollar"></i>
                    <span>Pix - Escolher</span>
                </div>
                <div class="radio-circle"></div>
            </div>
        </div>

        <div class="footer">
            <a href="ConfirmarInformacoes.php" class="btn btn-cancel">Voltar</a>
            <a href="ConfirmarDoacao.php" class="btn btn-next">Avançar</a>
        </div>
    </div>
</body>
</html>