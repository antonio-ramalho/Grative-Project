<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grative - Finalizar Doação</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="app-container" id="doacao-full-site">
        
        <div class="lateral-decor left"></div>
        <div class="lateral-decor right"></div>

        <header class="header-premium">
            <div class="header-container">
                <a href="javascript:history.back()" class="btn-voltar-topo">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div class="titulo-central">
                    <i class="ph ph-hand-heart"></i>
                    <h1>Finalizar Doação</h1>
                </div>
                <div class="spacer"></div>
            </div>
        </header>
        
        <main class="content main-box">
            <div class="progress-container" style="margin-bottom: 30px;">
                <div class="progress-bar" style="display:flex; justify-content:center; gap:15px;">
                    <div class="step active"><i class="ph ph-check"></i></div>
                    <div class="step active">2</div>
                    <div class="step">3</div>
                </div>
            </div>

            <div class="split-layout">
                <div class="form-side">
                    <form id="form-doacao-grative">
                        <div class="input-group">
                            <label>Quanto deseja doar?</label>
                            <div class="input-wrapper">
                                <input type="number" id="quantia" placeholder="R$ 0,00" step="0.01" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Mensagem (opcional)</label>
                            <div class="input-wrapper">
                                <input type="text" id="mensagem_doacao" placeholder="Deixe um recado carinhoso">
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Método de pagamento</label>
                            <div class="payment-card active" style="display:flex; align-items:center; gap:10px; padding:15px; border:1px solid #2d7a34; border-radius:12px; background:#e9f5eb;">
                                <i class="ph ph-pix-logo" style="font-size:24px; color:#2d7a34;"></i>
                                <span style="font-weight:600; color:#2d7a34;">Pix Instantâneo</span>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="info-side">
                    <div class="impact-card" style="text-align:center;">
                        <i class="ph ph-buildings" style="font-size: 50px; color: var(--laranja); margin-bottom:15px;"></i>
                        <h3 id="nome-osc-detalhe" style="margin-bottom:5px;">Carregando...</h3>
                        <span class="score-label">Verificada</span>
                        <p style="font-size:14px; color:#666; margin-top:15px; line-height:1.6;">
                            Sua doação ajuda a financiar projetos sociais através da tecnologia.
                        </p>
                    </div>
                </aside>
            </div>

            <div class="footer-container-internal">
                <button type="button" onclick="history.back()" style="background:none; border:none; color:#718096; cursor:pointer; font-weight:600;">Voltar</button>
                <button type="button" id="btn-proximo" class="btn-confirmar-premium">
                    Confirmar Doação <i class="ph ph-arrow-right"></i>
                </button>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/fazerDoacao.js"></script>
</body>
</html>