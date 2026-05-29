<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Categorias - Grative</title>
    <link rel="stylesheet" href="/css/buscar_categoria.css">
</head>
<body>
    <nav class="navbar-grative">
        <div class="logo">GRATIVE</div>
        <div class="nav-icones">
            <div class="avatar-perfil"></div>
        </div>
    </nav>

    <div class="container-voltar">
    <a href="/home_doador?id=<?= $_GET['id'] ?? '300002' ?>" class="btn-voltar-grative">
        <i class="bi bi-arrow-left"></i> Voltar para Home
    </a>
    </div>

    <div class="filtro-wrapper">
        <header class="barra-filtros">
            <h1>Buscar por Categoria</h1>
            <p>Escolha uma causa para apoiar:</p>
            
            <div class="categorias-grid">
                <button class="card-categoria" onclick="filtrar('Saúde')">
                    <img src="/img/icons/check2-circle.svg" alt="Saúde">
                    <span>Saúde</span>
                </button>
                <button class="card-categoria" onclick="filtrar('Educação')">
                    <img src="/img/icons/pencil-square.svg" alt="Educação">
                    <span>Educação</span>
                </button>
                <button class="card-categoria" onclick="filtrar('Meio Ambiente')">
                    <img src="/img/icons/circle.svg" alt="Meio Ambiente">
                    <span>Meio Ambiente</span>
                </button>
            </div>
        </header>
    </div>

    <main class="container-principal">
        <div id="resultados-busca" class="oscs-grid-layout">
            <p class="aviso">Selecione uma categoria para ver as OSCs.</p>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/buscar_Categoria.js"></script>
</body>
</html>