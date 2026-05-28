<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Inserir docs page</h1>
    <div class="card-upload">
        <h2>Validação de Documento Oficial</h2>
        <p>Para prosseguir, precisamos validar um documento da OSC (com CNPJ, na validade e com brasão/assinatura).</p>
    
        <form id="formUploadDoc" enctype="multipart/form-data">
            <div class="input-group">
                <label for="documento">Selecione o arquivo (PDF, JPG ou PNG):</label>
                <input type="file" id="documento" name="documento" accept=".pdf, .jpg, .jpeg, .png" required>
            </div>
            <div>
                <select name="tipo_documento">
                    <option value="estatuto">Estatuto Social / CNPJ</option>
                    <option value="certificado">Certificado de Transparência</option>
                    <option value="parceria">Comprovante de Parceria Governamental</option>
                </select>
            </div>
            <button type="submit" id="btnUpload" class="btn-primary">Analisar com IA e Enviar</button>
        </form>

        <div id="loader" style="display: none; margin-top: 15px; color: #555;">
            <p>⏳<em>A Inteligência Artificial está auditando seu documento. Isso pode levar alguns segundos...</em></p>
        </div>
    
        <div id="mensagemRetorno" style="display: none; margin-top: 15px; padding: 15px; border-radius: 5px;"></div>
    </div>
    <script>
        document.getElementById('formUploadDoc').addEventListener('submit', async function(e) {
            e.preventDefault(); // Impede a página de piscar/recarregar

            const form = e.target;
            const formData = new FormData(form);
            const btnUpload = document.getElementById('btnUpload');
            const loader = document.getElementById('loader');
            const mensagem = document.getElementById('mensagemRetorno');

            // 1. Prepara a UI para o carregamento
            btnUpload.disabled = true;
            btnUpload.innerText = "Analisando...";
            loader.style.display = 'block';
            mensagem.style.display = 'none';
            mensagem.className = '';

            try {
                // ATENÇÃO: Substitua essa URL pela rota configurada no seu web.php que chama o InserirDocController@upload
                const response = await fetch('/upload-doc', {
                    method: 'POST',
                    body: formData // Envia o arquivo e os dados
                });

                const data = await response.json();

                // 2. Trata a resposta do PHP / Gemini
                mensagem.style.display = 'block';
                if (data.sucesso) {
                    mensagem.style.backgroundColor = '#d4edda';
                    mensagem.style.color = '#155724';
                    mensagem.innerHTML = `✅ <strong>Sucesso!</strong><br>${data.mensagem}<br>CNPJ lido: ${data.cnpj_identificado}`;
                    form.reset(); // Esvazia o input de arquivo
                } else {
                    mensagem.style.backgroundColor = '#f8d7da';
                    mensagem.style.color = '#721c24';
                    mensagem.innerHTML = `❌ <strong>Documento Rejeitado:</strong><br>${data.erro}`;
                }
            } catch (error) {
                mensagem.style.display = 'block';
                mensagem.style.backgroundColor = '#f8d7da';
                mensagem.style.color = '#721c24';
                mensagem.innerHTML = `❌ Falha ao tentar conectar com o servidor.`;
                console.error("Erro na requisição:", error);
            } finally {
                // 3. Devolve a página ao estado normal
                btnUpload.disabled = false;
                btnUpload.innerText = "Analisar com IA e Enviar";
                loader.style.display = 'none';
            }
        });
    </script>
    <style>
        /* Um CSS básico só para esse bloco, ajuste conforme o seu Figma */
        .card-upload { background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #eaeaea; }
        .input-group { margin: 20px 0; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .btn-primary { background-color: #f2683a; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-primary:disabled { background-color: #fbc4b2; cursor: not-allowed; }
    </style>
</body>
</html>