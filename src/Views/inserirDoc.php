<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Documentos - Grative</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #f4f5f7;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #6b7280;
            --grative-green: #2e7d32;
            --grative-orange: #f2683a; 
            --border-color: #e5e7eb;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        .topbar {
            background-color: var(--card-bg);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar .logo {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--grative-green);
            letter-spacing: 0.5px;
        }

        .topbar .icons {
            display: flex;
            gap: 20px;
            font-size: 1.2rem;
            color: var(--text-muted);
            cursor: pointer;
        }

        .main-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .btn-voltar {
            background-color: white;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-voltar:hover {
            background-color: #f9fafb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card-upload {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02), 0 1px 3px rgba(0,0,0,0.05);
        }

        .card-upload p {
            color: var(--text-muted);
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        input[type="file"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: var(--text-main);
            background-color: #fafafa;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        input[type="file"]:focus, select:focus {
            outline: none;
            border-color: var(--grative-green);
            background-color: #fff;
        }

        .btn-primary {
            background-color: var(--grative-green);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary:hover {
            background-color: #123124;
        }

        .btn-primary:disabled {
            background-color: #a3b8af;
            cursor: not-allowed;
        }

        .alert-box {
            margin-top: 20px;
            padding: 16px;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
            display: none;
        }

        #loader {
            display: none;
            margin-top: 20px;
            color: var(--grative-green);
            text-align: center;
            font-weight: 500;
        }

        .section-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;}
        
        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .doc-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: box-shadow 0.2s;
        }

        .doc-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

        .doc-header { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
        .doc-icon { font-size: 1.5rem; background: #f3f4f6; padding: 10px; border-radius: 8px; }
        
        .doc-title { font-weight: 600; font-size: 0.95rem; color: var(--text-main); margin: 0; text-transform: capitalize;}
        .doc-date { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;}

        .doc-info { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; }
        .doc-info span { display: block; margin-bottom: 4px;}
        .doc-info strong { color: var(--text-main); }

        .btn-danger {
            background-color: white; color: var(--danger-color); border: 1px solid var(--danger-color);
            padding: 8px; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.85rem;
            transition: 0.2s; width: 100%; font-family: 'Inter', sans-serif;
        }
        .btn-danger:hover { background-color: var(--danger-bg); }

        .empty-state { text-align: center; padding: 40px; color: var(--text-muted); grid-column: 1 / -1; }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">GRATIVE</div>
    </header>

    <div class="main-container">
        
        <div class="page-header">
            <h1>Validação de Documento</h1>
            <button class="btn-voltar" onclick="history.back()">
                <span>←</span> Voltar
            </button>
        </div>

        <div class="card-upload">
            <p>Para prosseguir e aumentar o <strong>Trust Score</strong> da sua OSC, faça o upload de um documento oficial válido (Estatuto, Certificados ou Parcerias).</p>
        
            <form id="formUploadDoc" enctype="multipart/form-data">
                
                <div class="input-group">
                    <label for="tipo_documento">Tipo de Documento</label>
                    <select name="tipo_documento" id="tipo_documento">
                        <option value="estatuto">Estatuto Social / CNPJ</option>
                        <option value="certificado">Certificado de Transparência</option>
                        <option value="parceria">Comprovante de Parceria Governamental</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="documento">Arquivo (PDF, JPG ou PNG)</label>
                    <input type="file" id="documento" name="documento" accept=".pdf, .jpg, .jpeg, .png" required>
                </div>
                
                <button type="submit" id="btnUpload" class="btn-primary">Analisar com IA e Enviar</button>
            </form>

            <div id="loader">
                <p><em>Seu arquivo está sendo auditado. Isso pode levar alguns segundos...</em></p>
            </div>
        
            <div id="mensagemRetorno" class="alert-box"></div>

            <h2 class="section-title">Documentos Cadastrados</h2>
                <div class="docs-grid" id="docsGrid">
            <div class="empty-state">Carregando documentos...</div>
        </div>
        </div>
    </div>

    <script src="js/inserirDocs.js"></script>
</body>
</html>