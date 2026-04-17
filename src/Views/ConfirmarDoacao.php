<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grative - Confirmar Doação</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="app-container">
        
        <section id="cabecalho" class="interface">
            <div class="header">
                <a href="ConfirmarInformacoes.php" class="close-btn"><i class="ph ph-x"></i></a>
            </div>
        </section>

        <section id="conteudo_passo_3" class="interface">
            <div class="content">
                <h2 style="color: #333; text-align: left;">Confirmar doação</h2>
                
                <div class="progress-bar">
                    <div class="step">1</div>
                    <div class="step">2</div>
                    <div class="step active">3</div>
                    <div class="step">4</div>
                </div>

                <div class="input-group">
                    <label>Instituição (OSC)</label>
                    <div class="input-wrapper">
                        <i class="ph ph-buildings"></i>
                        <input type="text" value="Cão Amigo" readonly>
                    </div>
                </div>
                
                <div class="input-group">
                    <label>Valor da Doação</label>
                    <div class="input-wrapper">
                        <i class="ph ph-currency-dollar"></i>
                        <input type="text" value="R$ 50,00" readonly>
                    </div>
                </div>
                
                <div class="input-group">
                    <label>Método Escolhido</label>
                    <div class="input-wrapper">
                        <i class="ph ph-credit-card"></i>
                        <input type="text" value="Pix" readonly>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <a href="FazerDoacao.php" class="btn btn-cancel">Voltar</a>
            <a href="Pagamento.php" class="btn btn-doar">Confirmar e Doar</a>
        </footer>
    </div>
</body>
</html>