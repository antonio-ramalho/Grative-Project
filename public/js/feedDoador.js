let publicacaoSelecionada = null;

async function carregarFeedGeral() {
    const divFeed = document.getElementById("feedDoador");
    divFeed.innerHTML = "";

    const resposta = await fetch('/api/feed-geral');
    const publicacoes = await resposta.json();

    let todosOsCardsHTML = '';

    publicacoes.forEach(publicacao => {
        let tagImagem = '';
        if (publicacao.imagem_url) {
            tagImagem = `<img src="${publicacao.imagem_url}" class="img-fluid rounded mt-2" style="width: 100%; object-fit: cover;">`;
        }

        todosOsCardsHTML += `
            <div class="card mb-4 p-1 card-publicacao" data-id="${publicacao.id}">
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
                    <button class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent btn-comentar" data-id-publicacao="${publicacao.id}">
                        <i class="bi bi-chat-left-text fs-6"></i> Comentar
                    </button>
                    <button class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent">
                        <i class="bi bi-share fs-6"></i> Compartilhar
                    </button>
                </div>

                <div class="px-4 pb-3 secao-comentarios">
                    <div class="lista-comentarios"></div>
                    <button class="btn-ver-todos" style="display: none;">Ver todos os comentários</button>
                </div>
            </div>
        `;
    });

    divFeed.innerHTML = todosOsCardsHTML;
    
    anexarListenersComentarios();

    publicacoes.forEach(publicacao => {
        carregarComentarios(publicacao.id);
    });
}

function anexarListenersComentarios() {
    document.querySelectorAll('.btn-comentar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            publicacaoSelecionada = btn.getAttribute('data-id-publicacao');
            abrirModalComentario();
        });
    });
}

function abrirModalComentario() {
    const modal = new bootstrap.Modal(document.getElementById('modalComentario'));
    modal.show();

    setTimeout(() => {
        document.getElementById('textoComentario').focus();
    }, 400);
}

async function enviarComentario() {
    const texto = document.getElementById('textoComentario').value.trim();
    
    if (!texto) {
        alert('Por favor, digite um comentário');
        return;
    }
    
    if (!publicacaoSelecionada) {
        alert('Erro: publicação não identificada');
        return;
    }
    
    try {
        const resposta = await fetch('/api/comentario/adicionar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_publicacao: publicacaoSelecionada,
                comentario: texto
            })
        });
        
        const resultado = await resposta.json();
        
        if (resultado.sucesso) {
            document.getElementById('textoComentario').value = '';
            bootstrap.Modal.getInstance(document.getElementById('modalComentario')).hide();
            
            carregarComentarios(publicacaoSelecionada);
        } else {
            alert('Erro ao enviar comentário: ' + resultado.erro);
        }
    } catch (erro) {
        console.error('Erro:', erro);
        alert('Erro ao enviar comentário');
    }
}

async function carregarComentarios(idPublicacao) {
    try {
        const resposta = await fetch(`/api/comentario/listar?id_publicacao=${idPublicacao}`);
        
        if (!resposta.ok) throw new Error("Erro na requisição");
        
        const comentarios = await resposta.json();

        const postElement = document.querySelector(`.card-publicacao[data-id="${idPublicacao}"]`);
        if (!postElement) return;

        const listaDiv = postElement.querySelector('.lista-comentarios');
        const btnVerTodos = postElement.querySelector('.btn-ver-todos');

        listaDiv.innerHTML = ''; 

        if (comentarios.length > 0) {
            const limitePreview = 2;
            const previewComentarios = comentarios.slice(0, limitePreview);

            renderizarLista(previewComentarios, listaDiv);

            if (comentarios.length > limitePreview) {
                btnVerTodos.textContent = `Ver todos os ${comentarios.length} comentários`;
                btnVerTodos.style.display = 'block';

                btnVerTodos.onclick = () => {
                    listaDiv.innerHTML = ''; 
                    renderizarLista(comentarios, listaDiv); 
                    btnVerTodos.style.display = 'none'; 
                };
            } else {
                btnVerTodos.style.display = 'none';
            }
        }
    } catch (erro) {
        console.error("Erro ao buscar comentários do post", idPublicacao, erro);
    }
}

function renderizarLista(arrayComentarios, container) {
    arrayComentarios.forEach(c => {
        const div = document.createElement('div');
        div.className = 'comentario-item d-flex justify-content-between align-items-center mb-1';
        
        div.innerHTML = `
            <div>
                <strong>${c.nome_usuario}</strong> <span>${c.texto}</span>
            </div>
            <button onclick="deletarComentario(${c.id}, this)" class="btn btn-sm text-danger p-0 border-0 bg-transparent" title="Excluir comentário">
                <i class="bi bi-trash3"></i>
            </button>
        `;
        
        container.appendChild(div);
    });
}
async function deletarComentario(idComentario, botaoElemento) {

    if (!confirm("Tem certeza que deseja apagar este comentário?")) {
        return;
    }

    try {
        const resposta = await fetch('/api/comentario/deletar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_comentario: idComentario })
        });

        const resultado = await resposta.json();

        if (resposta.ok && resultado.sucesso) {

            botaoElemento.closest('.comentario-item').remove();
        } else {

            alert('Aviso: ' + (resultado.erro || 'Não foi possível excluir.'));
        }
    } catch (erro) {
        console.error('Erro ao deletar:', erro);
        alert('Erro de conexão ao tentar excluir o comentário.');
    }
}
carregarFeedGeral();