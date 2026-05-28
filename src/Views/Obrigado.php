<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso - Grative</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="background-color: #2c2f33; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;">

    <div style="background: linear-gradient(135deg, #ff8c42, #2e7d32); padding: 3px; border-radius: 22px; box-shadow: 0 15px 40px rgba(0,0,0,0.4); width: 100%; max-width: 700px; margin: 20px;">
        
        <div class="app-container" style="margin: 0; width: 100%; max-width: none; border-radius: 20px;">
            <div class="header" style="text-align: right;">
                <a href="/home_doador" style="color: #ccc; font-size: 24px; text-decoration: none;"><i class="ph ph-x"></i></a>
            </div>

            <h2 style="text-align: center; margin-bottom: 20px;">Doação Concluída!</h2>

            <div class="progress-bar" style="margin-bottom: 40px;">
                <div class="step active" style="background: #2e7d32; border-color: #2e7d32;"><i class="ph ph-check" style="color: white;"></i></div>
                <div class="step active" style="background: #2e7d32; border-color: #2e7d32;"><i class="ph ph-check" style="color: white;"></i></div>
                <div class="step active" style="background: #ff8c42; border-color: #ff8c42; color: white;">3</div>
            </div>

            <div class="content" style="text-align: center; padding: 20px 0;">
                <div style="margin-bottom: 20px;">
                    <i class="ph-fill ph-check-circle" style="font-size: 100px; color: #2e7d32; filter: drop-shadow(0 5px 15px rgba(46, 125, 50, 0.2));"></i>
                </div>
                
                <h3 style="font-size: 26px; color: #333; margin-bottom: 15px;">Muito obrigado, Breno!</h3>
                
                <p style="color: #666; font-size: 18px; line-height: 1.6; margin-bottom: 20px;">
                    Sua doação foi confirmada com sucesso. Você acaba de fazer a diferença!
                </p>
                
                <div style="display: inline-block; background: #f4f6f8; padding: 10px 20px; border-radius: 50px; color: #888; font-size: 14px; border: 1px solid #eee;">
                    Redirecionando em <strong id="contador" style="color: #ff8c42;">5</strong>s
                </div>
            </div>

            <div class="footer" style="margin-top: 40px;">
                <a href="/home_doador" class="btn-next" style="text-decoration: none; display: block; text-align: center; border: none;">
                    Voltar para o Início
                </a>
            </div>
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
</body>
</html>