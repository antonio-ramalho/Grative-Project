<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar OSCs Próximas - Grative</title>
    <link rel="stylesheet" href="/css/buscar_categoria.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar-grative">
        <div class="logo">GRATIVE</div>
        <div class="nav-icones">
            <div class="avatar-perfil"></div>
        </div>
    </nav>
    <div class="container-voltar">
    <a href="/home_doador?id=<?= $_GET['id'] ?? ($_SESSION['id_usuario'] ?? '300002') ?>" class="btn-voltar-grative">
        <i class="bi bi-arrow-left"></i> Voltar para Home
    </a>
</div>
    <div class="filtro-wrapper">
        <header class="barra-filtros">
            <h1>Buscar por Proximidade</h1>
            <p>Encontre instituições sociais perto de você:</p>
            
            <div style="display: flex; justify-content: center; width: 100%; margin-top: 20px;">
                <button id="btnLocalizacao" class="btn-laranja" style="border: none; cursor: pointer; padding: 12px 24px; background-color: #1a8853;">
                    <i class="bi bi-geo-alt-fill"></i> Usar Minha Localização
                </button>
            </div>
        </header>
    </div>

    <main class="container-principal">
        <div id="gridResultados" class="oscs-grid-layout">
            <p class="aviso">Clique no botão acima para buscar as OSCs mais próximas.</p>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // O teu PHP preenche estas variáveis com os dados do doador logado vindos do banco
    const doadorLat = "<?= $doador['latitude'] ?? '' ?>";
    const doadorLng = "<?= $doador['longitude'] ?? '' ?>";
    </script>

    <script src="/js/buscar_proximidade.js"></script>
</body>

</html>