async function carregarFeedGeral() {
    const divFeed = document.getElementById("feedDoador");
    divFeed.innerHTML = "";

    const resposta = await fetch('/api/feed-geral');
    const publicacoes = await resposta.json();

    publicacoes.forEach(publicacao => {
        
        let tagImagem = '';
        if (publicacao.imagem_url) {
            tagImagem = `<img src="${publicacao.imagem_url}" class="img-fluid rounded mt-2" style="width: 100%; object-fit: cover;">`;
        }

        const cardHTML = `
            <div class="card mb-4 p-1">
                <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-3" style="color: #444c54;"></i>
                        <div class="lh-sm">
                            <h6 class="m-0 fw-bold" style="font-size: 0.95rem;">${publicacao.nome_osc}</h6>
                            <small style="font-size: 0.75rem; color: #88939c;">${publicacao.data_publicacao || 'Recentemente'}</small>
                        </div>
                    </div>
                    <span class="badge badge-score border-0 fw-normal px-2 py-1">Score 0.0</span>
                </div>

                <div class="card-body py-1">
                    <h5 class="card-title fw-bold fs-6 mb-3 mt-1">${publicacao.titulo}</h5>
                    
                    <p class="mb-3" style="font-size: 0.9rem; color: var(--text-dark);">
                        ${publicacao.descricao}
                    </p>
                    
                    ${tagImagem}
                </div>

                <div class="card-footer bg-white border-0 pt-2 pb-2 d-flex justify-content-center gap-5 px-4">
                    <button class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent">
                        <i class="bi bi-chat-left-text fs-6"></i> Comentar
                    </button>
                    <button class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent">
                        <i class="bi bi-share fs-6"></i> Compartilhar
                    </button>
                </div>
            </div>
        `;

        divFeed.innerHTML += cardHTML;
    });
}

carregarFeedGeral();