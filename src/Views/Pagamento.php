<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <div class="header">
            <a href="/home" class="close-btn"><i class="ph ph-x"></i></a>
        </div>
        
        <div class="content">
            <h2>Pagamento para <span id="nome-osc">...</span></h2>
            
            <div class="progress-bar">
                <div class="step">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step active">4</div>
            </div>

            <div class="qr-container" style="text-align: center; margin: 20px 0;">
                <img id="qr-code-pix" src="" alt="QR Code Pix" style="display:none; border-radius: 8px; border: 1px solid #eee;">
            </div>

            <div class="input-group">
                <label>Copiar e colar chave Pix</label>
                <div class="method-card" style="justify-content: flex-start; gap: 10px;">
                    <input type="text" id="pix-key" value="Carregando..." readonly style="border:none; background:transparent; flex:1; font-size: 12px;">
                    <i class="ph ph-copy" onclick="copyPix()" style="cursor:pointer; font-size: 20px; color: #4CAF50;"></i>
                </div>
            </div>

            <div class="msg-box">
                <i class="ph ph-check-circle"></i>
                <span id="valor-display">Calculando valor...</span>
            </div>
        </div>

        <div class="footer">
            <button type="button" class="btn btn-next" style="width: 100%; border: none; cursor: pointer;">
                Finalizar Pagamento
            </button>
        </div>
    </div> 

    <script src="/js/pagamento.js"></script>
    
    <script>
        function copyPix() {
            const txt = document.getElementById("pix-key");
            txt.select();
            navigator.clipboard.writeText(txt.value);
            alert("Chave Pix copiada!");
        }
    </script>
</body>
</html>