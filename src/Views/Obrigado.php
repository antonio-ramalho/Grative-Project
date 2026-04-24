<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/estilo_sucesso.css">
</head>
<body>
    <div class="app-container">
        <div class="success-card">
            <div class="icon-check">
                <i class="ph-fill ph-check-circle"></i>
            </div>
            <div class="progress-bar">
                <div class="step">1</div>
                <div class="step">2</div>
                <div class="step active">3</div>
            </div>
            <h2>Muito obrigado!</h2>
            <p>Sua doação foi confirmada e o status atualizado. Sua ajuda faz toda a diferença!</p>
            
            <a href="/home_doador" class="btn-voltar">
                Voltar para o Início
            </a>

            Você será redirecionado para o feed em <span id="contador" class="fw-bold text-success">5</span> segundos...
        </div>
    </div>
        <script>
        let tempo = 5; 
        const display = document.getElementById('contador');
        const contagem = setInterval(function() {
            tempo--; 
            display.innerText = tempo;

            if (tempo <= 0) {
                clearInterval(contagem); 
                window.location.href = "/home_doador"; 
            }
        }, 1000);
        </script>
    <script src="/js/sucesso.js"></script>
</body>
</html>