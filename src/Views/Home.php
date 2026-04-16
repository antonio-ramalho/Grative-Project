<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Grative</title>
    <link rel="stylesheet" href="/css/estilo_home.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <div class="container">
        <header class="header-home">
            <div class="search-container">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" placeholder="Pesquise Projetos">
            </div>
            <div class="user-profile">
                <i class="ph ph-user-circle"></i>
            </div>
        </header>

        <section class="content">
            <h1>Perfis das Oscs</h1>

            <div class="filters">
                <button class="filter-btn active">Padrão</button>
                <button class="filter-btn">Todos</button>
                <button class="filter-btn">Todos</button>
                <button class="filter-btn">Todos</button>
            </div>

            <div class="oscs-list">
                
                <div class="osc-card">
                    <div class="osc-main-info">
                        <div class="profile-image">
                            <i class="ph ph-user"></i>
                        </div>
                        
                        <div class="osc-details">
                            <div class="title-row">
                                <h3>Nome da OSC Exemplo</h3>
                                <span class="score-label">Score</span>
                            </div>
                            
                            <div class="description-lines">
                                <p>Esta é uma descrição fixa para teste visual. Depois o PHP vai trocar este texto pelo que está no banco.</p>
                            </div>
                            
                            <a href="#" class="more-link">...Saber mais sobre</a>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="/fazer-doacao" class="btn-doar">
                            Doar
                        </a>
                    </div>
                </div>
                </div>
        </section>

        <nav class="bottom-nav">
            <i class="ph ph-house"></i>
            <i class="ph ph-magnifying-glass-plus"></i>
            <i class="ph ph-article"></i>
            <i class="ph ph-bookmarks"></i>
        </nav>
    </div>
    <script src="/js/home.js"></script>
</body>
</html>