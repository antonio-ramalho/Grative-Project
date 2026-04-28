<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar OSC por Categoria</title>
    <link rel="stylesheet" href="/css/geral.css">
    <link rel="stylesheet" href="/css/buscar_categoria.css">
</head>
<body>
    <header>
        <h1>Buscar por Categoria</h1>
        <p>Escolha uma causa para apoiar:</p>
    </header>

    <main class="container">
        <div class="categorias-grid">
            <button class="cat-card" onclick="filtrar('Saúde')">
                <img src="/img/icons/check2-circle.svg" alt="Saúde">
                <span>Saúde</span>
            </button>
            <button class="cat-card" onclick="filtrar('Educação')">
                <img src="/img/icons/pencil-square.svg" alt="Educação">
                <span>Educação</span>
            </button>
            <button class="cat-card" onclick="filtrar('Meio Ambiente')">
                <img src="/img/icons/circle.svg" alt="Meio Ambiente">
                <span>Meio Ambiente</span>
            </button>
        </div>

        <div id="resultados-busca" class="resultados-container">
            <p class="aviso">Selecione uma categoria para ver as OSCs.</p>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/buscar_Categoria.js"></script>
</body>
</html>