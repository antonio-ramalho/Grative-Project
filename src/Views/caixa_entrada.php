<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa de Entrada - Grative</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/caixa_entrada.css"> </head>
<body>

    <header class="topbar">
        <div class="logo">GRATIVE</div>
    </header>

    <div class="main-container">
        
        <div class="page-header">
            <h1>Sua Caixa de Entrada</h1>
            <button class="btn-voltar" onclick="history.back()">
                <span>←</span> Voltar
            </button>
        </div>

        <div class="card-inbox">
            <header class="inbox-header">
                <h2>Notificações Recentes</h2>
                <span id="badge-nao-lidas" class="badge" style="display: none;">0 novas</span>
            </header>

            <ul id="lista-notificacoes" class="inbox-list">
                <div class="loader">Carregando mensagens...</div>
            </ul>
        </div>

    </div>

    <script src="js/caixa_entrada.js"></script> </body>
</html>