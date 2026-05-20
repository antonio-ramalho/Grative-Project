<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <div class="header">
            <a href="/home_doador" class="close-btn"><i class="ph ph-x"></i></a>
        </div>

        <h2>Pagamento</h2>

        <div class="progress-bar">
            <div class="step">1</div>
            <div class="step active">2</div>
            <div class="step">3</div>
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 25px;">
                <img id="qr-code-pix" src="" alt="QR Code Pix" style="display:none; width: 180px; border-radius: 12px; border: 1px solid #eee; padding: 10px;">
                <p style="color: #888; font-size: 14px; margin-top: 10px;">Escaneie o código para doar</p>
            </div>

            <div class="input-group">
                <label>Copiar e colar chave Pix</label>
                <div class="input-wrapper">
                    <input type="text" id="pix-key" value="Carregando chave..." readonly>
                    <i class="ph ph-copy" onclick="copyPix()" style="cursor:pointer; color: #ff8c42; font-size: 24px;"></i>
                </div>
            </div>

            <div class="payment-card" style="cursor: default; border-color: #2e7d32; background: #e8f5e9;">
                <div class="card-content" style="color: #2e7d32; display: flex; align-items: center; gap: 10px;">
                    <i class="ph ph-check-circle" style="font-size: 24px;"></i>
                    <span id="valor-display" style="font-weight: bold; font-size: 18px;">Calculando...</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <button type="button" class="btn btn-next" id="btn-finalizar-doacao">
                Finalizar e ir para o Recibo
            </button>
        </div>
    </div>

    <script src="/js/pagamento.js"></script>
    <script>
        function copyPix() {
            const txt = document.getElementById("pix-key");
            txt.select();
            navigator.clipboard.writeText(txt.value);
            alert("Chave Pix copiada com sucesso!");
        }

        // Lógica de clique para ir para a página OBRIGADO
        document.getElementById('btn-finalizar-doacao').addEventListener('click', function() {
            // Aqui você pode adicionar uma verificação se quiser, ou apenas redirecionar
            window.location.href = "/obrigado"; 
        });
    </script>
</body>
</html>