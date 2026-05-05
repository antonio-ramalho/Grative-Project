<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Grative</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <div class="container" id="home-full">
        <div class="lateral-decor left"></div>
        <div class="lateral-decor right"></div>

        <header class="header-home">
            <div class="header-content">
                <div class="search-container">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" id="input-busca" placeholder="Pesquise Projetos Sociais...">
                </div>
                
                <div class="user-menu">
                    <button class="notif-btn">
                        <i class="ph ph-bell"></i>
                    </button>
                    <div class="user-profile">
                        <i class="ph ph-user-circle"></i>
                    </div>
                </div>
            </div>
        </header>

        <section class="main-content">
            <div class="section-header">
                <h1>Descubra Causas</h1>
                <p class="subtitle">Encontre projetos que precisam do seu apoio hoje</p>
            </div>

            <div class="filters-container">
                <div class="filters">
                    <button class="filter-btn active">Todos</button>
                    <button class="filter-btn">Educação</button>
                    <button class="filter-btn">Tecnologia</button>
                    <button class="filter-btn">Saúde</button>
                    <button class="filter-btn">Meio Ambiente</button>
                </div>
            </div>

            <div class="oscs-grid" id="lista-oscs">
                <div class="loading-placeholder">
                    <i class="ph ph-circle-notch"></i>
                    <p>Buscando projetos no banco...</p>
                </div>
            </div>
        </section>

        <nav class="bottom-nav">
            <a href="/home" class="nav-item active"><i class="ph ph-house"></i></a>
            <a href="/categorias" class="nav-item"><i class="ph ph-magnifying-glass-plus"></i></a>
            <a href="/noticias" class="nav-item"><i class="ph ph-article"></i></a>
            <a href="/perfil" class="nav-item"><i class="ph ph-user"></i></a>
        </nav>
    </div>

    <script src="/js/home.js"></script>
</body>
</html>