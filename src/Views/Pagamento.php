<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="app-container">
        <div class="header">
            <a href="ConfirmarInformacoes.php" class="close-btn"><i class="ph ph-x"></i></a>
        </div>
        
        <div class="content">
            <h2>Pagamento</h2>
            
            <div class="progress-bar">
                <div class="step">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step active">4</div>
            </div>

            <div class="qr-placeholder">QR CODE</div>

            <div class="input-group">
                <label>Copiar e colar</label>
                <div class="method-card" style="justify-content: flex-start; gap: 10px;">
                    <input type="text" value="pix-token-grative-123" readonly style="border:none; background:transparent; flex:1;">
                    <i class="ph ph-copy" style="cursor:pointer; font-size: 20px;"></i>
                </div>
            </div>

            <div class="msg-box">
                <i class="ph ph-check-circle"></i>
                <span>Mensagem personalizada da OSC!</span>
            </div>
        </div>

        <div class="footer">
            <a href="ConfirmarInformacoes.php" class="btn btn-next">Finalizar</a>
        </div>
    </div>
</body>
</html>