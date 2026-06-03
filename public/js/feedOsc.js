let publicacaoSelecionadaOsc = null;

async function carregarFeedOsc(){
    const divPublicacoes = document.getElementById("feed-publicacoes");
    divPublicacoes.innerHTML = "";
    
    const resposta = await fetch('/api/feed-osc');
    const publicacoes = await resposta.json();


    publicacoes.forEach(publicacao => {
        let tagImagem = ``
        if (publicacao.imagem_url){
            tagImagem = `<img src="${publicacao.imagem_url}" class="img-fluid rounded mt-3">`;
        }

        const cardHTML = `
                    <div class="card border-0 shadow-sm">
                        <!--? Cabeçalho -->
                        <div class="card-header d-flex align-items-center gap-3 bg-white border-0">
                            <i class="bi bi-person-circle fs-2 text-secondary"></i> 
                            <div class="d-flex flex-column">
                                <span class="fw-bold">${publicacao.nome_osc}</span>
                                <span class="text-muted small">${publicacao.data_publicacao}</span>
                            </div>
                        </div>
                        
                        <!--? Corpo -->
                        <div class="card-body">
                            <h3>${publicacao.titulo}</h3>
                            <p>${publicacao.descricao}</p>
                            ${tagImagem}
                        </div>
                        
                        <!--? Rodapé -->
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <!--? Interações -->
                            <div class="d-flex gap-4">
                                <span class="text-muted" style="cursor: pointer;"><i class="bi bi-heart"></i> Curtir</span>
                                <span class="text-muted btn-comentar-osc" style="cursor: pointer;" data-id-publicacao="${publicacao.id}"><i class="bi bi-chat"></i> Comentar</span>
                            </div>
                            
                            <!--? Ações -->
                            <button class="btn btn-outline-danger btn-sm" data-id="${publicacao.id}">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </div>
                    </div>
        `
        divPublicacoes.innerHTML += cardHTML;
    });
    
    anexarListenersComentariosOsc();
}

function anexarListenersOsc() {
    // Listener para abrir o modal de comentário limpo
    document.querySelectorAll('.btn-comentar-osc').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            publicacaoSelecionadaOsc = btn.getAttribute('data-id-publicacao');
            document.getElementById('textoComentarioOsc').value = ''; // Limpa se for comentário normal
            abrirModalComentarioOsc();
        });
    });

    // Listener para excluir postagem sem dar F5
    document.querySelectorAll('.btn-excluir-osc').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const idPublicacao = btn.getAttribute('data-id');
            if (confirm("Tem certeza que deseja excluir esta publicação?")) {
                await excluirPostagem(idPublicacao);
            }
        });
    });
}

function abrirModalComentarioOsc() {
    const modal = new bootstrap.Modal(document.getElementById('modalComentarioOsc'));
    modal.show();
    setTimeout(() => {
        document.getElementById('textoComentarioOsc').focus();
    }, 400);
}

// Lógica de Carregar e Renderizar os Comentários
async function carregarComentariosOsc(idPublicacao) {
    try {
        const resposta = await fetch(`/api/comentario/listar?id_publicacao=${idPublicacao}`);
        if (!resposta.ok) return;

        const comentarios = await resposta.json();
        const postElement = document.querySelector(`.card-publicacao-osc[data-id="${idPublicacao}"]`);
        if (!postElement) return;

        const listaDiv = postElement.querySelector(".lista-comentarios-osc");
        const btnVerTodos = postElement.querySelector(".btn-ver-todos-osc");

        listaDiv.innerHTML = "";

        if (comentarios.length > 0) {
            const limitePreview = 3; // Mostra os últimos 3
            const previewComentarios = comentarios.slice(0, limitePreview);

            renderizarListaComentariosOsc(previewComentarios, listaDiv, idPublicacao);

            if (comentarios.length > limitePreview) {
                btnVerTodos.textContent = `Ver todos os ${comentarios.length} comentários`;
                btnVerTodos.style.display = "block";
                btnVerTodos.onclick = () => {
                    listaDiv.innerHTML = "";
                    renderizarListaComentariosOsc(comentarios, listaDiv, idPublicacao);
                    btnVerTodos.style.display = "none";
                };
            } else {
                btnVerTodos.style.display = "none";
            }
        }
    } catch (erro) {
        console.error("Erro ao buscar comentários do post", idPublicacao, erro);
    }
}

function renderizarListaComentariosOsc(arrayComentarios, container, idPublicacao) {
    arrayComentarios.forEach((c) => {
        const div = document.createElement("div");
        div.className = "comentario-item d-flex flex-column mb-2";

        // Botão de responder mágico (Preenche o modal com o @NomeDoUsuario)
        let botaoResponder = `
            <button onclick="prepararResposta('${c.nome_usuario}', '${idPublicacao}')" class="btn btn-sm text-grative-green p-0 border-0 bg-transparent ms-3 fw-bold" style="font-size: 0.75rem;">
                Responder
            </button>
        `;

        // Verifica se a OSC tem permissão para apagar
        let botaoLixeira = c.pode_deletar ? 
            `<button onclick="deletarComentarioOsc(${c.id}, this)" class="btn btn-sm text-danger p-0 border-0 bg-transparent ms-2" title="Excluir comentário"><i class="bi bi-trash3"></i></button>` : '';

        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong style="font-size: 0.95rem;">${c.nome_usuario}</strong> 
                    <span style="font-size: 0.95rem;">${c.comment || c.texto}</span>
                    <div class="d-flex align-items-center mt-1">
                        <small class="text-muted" style="font-size: 0.75rem;">${c.data_comentario || 'Recentemente'}</small>
                        ${botaoResponder}
                    </div>
                </div>
                ${botaoLixeira}
            </div>
        `;
        container.appendChild(div);
    });
}

// Função disparada ao clicar em "Responder"
function prepararResposta(nomeUsuario, idPublicacao) {
    publicacaoSelecionadaOsc = idPublicacao;
    const textarea = document.getElementById('textoComentarioOsc');
    textarea.value = `@${nomeUsuario} `; // Adiciona a menção
    abrirModalComentarioOsc();
}

// Enviar comentário modificado para não dar Reload
async function enviarComentarioOsc() {
    const texto = document.getElementById('textoComentarioOsc').value.trim();
    
    if (!texto) {
        alert('Por favor, digite um comentário');
        return;
    }
    
    if (!publicacaoSelecionadaOsc) {
        alert('Erro: publicação não identificada');
        return;
    }
    
    try {
        const resposta = await fetch('/api/comentario/adicionar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_publicacao: publicacaoSelecionadaOsc,
                comentario: texto
            })
        });
        
        const resultado = await resposta.json();
        
        if (resultado.sucesso) {
            document.getElementById('textoComentarioOsc').value = '';
            bootstrap.Modal.getInstance(document.getElementById('modalComentarioOsc')).hide();
            
            // Recarrega SÓ a lista de comentários dessa postagem
            carregarComentariosOsc(publicacaoSelecionadaOsc);
        } else {
            alert('Erro ao enviar comentário: ' + resultado.erro);
        }
    } catch (erro) {
        console.error('Erro:', erro);
        alert('Houve um problema de conexão.');
    }
}

async function deletarComentarioOsc(idComentario, botaoElemento) {
    if (!confirm("Tem certeza que deseja apagar este comentário?")) return;
    
    try {
        const resposta = await fetch("/api/comentario/deletar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({id_comentario: idComentario}),
        });

        const resultado = await resposta.json();

        if (resposta.ok && resultado.sucesso) {
            const cardPost = botaoElemento.closest('.card-publicacao-osc');
            if (cardPost) {
                carregarComentariosOsc(cardPost.getAttribute('data-id'));
            }
        } else {
            alert("Aviso: " + (resultado.erro || "Não autorizado."));
        }
    } catch (erro) {
        console.error("Erro ao deletar:", erro);
    }
}

async function excluirPostagem(id) {
    const formData = new FormData();
    formData.append('id_documento', id);

    const resposta = await fetch('/api/excluir-publicacao', {
        method: 'POST',
        body: formData
    });

    const resultado = await resposta.json();

    if (resultado.sucesso) {
        // Remove a postagem da tela instantaneamente
        const cardRemover = document.querySelector(`.card-publicacao-osc[data-id="${id}"]`);
        if(cardRemover) cardRemover.remove();
    } else {
        alert("Erro ao excluir: " + resultado.erro);
    }
}

carregarFeedOsc();