<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Categorias - Grative</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/buscar_categoria.css">
</head>
<body>
    <main class="container-principal">
        <!-- BARRA DE FILTROS: Fica fixa no topo e protegida do JavaScript -->
        <header class="barra-filtros">
            <h2 class="titulo-sessao">Categorias</h2>
            <div class="categorias-grid">
                <button class="card-categoria"><span>Saúde</span></button>
                <button class="card-categoria"><span>Educação</span></button>
                <button class="card-categoria"><span>Meio Ambiente</span></button>
            </div>
        </header>

        <!-- ÁREA DE RESULTADOS: Onde os cards serão injetados -->
        <div id="lista-oscs" class="oscs-grid-layout">
            <div class="placeholder-inicial">
                <p>Selecione uma categoria acima para listar os projetos.</p>
            </div>
        </div>
    </main>

    <script src="/js/home.js"></script>
</body>
</html>