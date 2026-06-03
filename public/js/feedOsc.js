let publicacaoSelecionadaOsc = null;

async function carregarFeedOsc(){
    const divPublicacoes = document.getElementById("feed-publicacoes");
    divPublicacoes.innerHTML = "";
    
    const resposta = await fetch('/api/feed-osc');
    const publicacoes = await resposta.json();

    let htmlAcumulado = ""; 

    publicacoes.forEach(publicacao => {
        let tagImagem = ``;
        if (publicacao.imagem_url) {
            const extensao = publicacao.imagem_url.split('.').pop().toLowerCase();
            const formatosVideo = ['mp4', 'mov', 'avi', 'webm'];
            
            // Estilo padrão para manter tudo do mesmo tamanho, centralizado e sem cortar
            const estiloMidia = "width: 100%; height: 400px; object-fit: contain; background-color: #000;"; 

            if (formatosVideo.includes(extensao)) {
                let tipoMime = 'video/mp4'; 
                if (extensao === 'webm') tipoMime = 'video/webm';
                else if (extensao === 'mov') tipoMime = 'video/quicktime';
                else if (extensao === 'avi') tipoMime = 'video/x-msvideo';

                tagImagem = `
                    <div class="mt-3 text-center rounded overflow-hidden" style="background-color: #000;">
                        <video controls preload="metadata" style="${estiloMidia}">
                            <source src="${publicacao.imagem_url}" type="${tipoMime}">
                            Seu navegador não suporta a tag de vídeo.
                        </video>
                    </div>`;
            } else {
                tagImagem = `
                    <div class="mt-3 text-center rounded overflow-hidden" style="background-color: #f8f9fa;">
                        <img src="${publicacao.imagem_url}" style="width: 100%; height: 400px; object-fit: contain;">
                    </div>`;
            }
        }

        const cardHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex align-items-center gap-3 bg-white border-0">
                    <i class="bi bi-person-circle fs-2 text-secondary"></i> 
                    <div class="d-flex flex-column">
                        <span class="fw-bold">${publicacao.nome_osc}</span>
                        <span class="text-muted small">${publicacao.data_publicacao}</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <h3>${publicacao.titulo}</h3>
                    <p>${publicacao.descricao}</p>
                    ${tagImagem}
                </div>
                
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-4">
                        <span class="text-muted" style="cursor: pointer;"><i class="bi bi-heart"></i> Curtir</span>
                        <span class="text-muted btn-comentar-osc" style="cursor: pointer;" data-id-publicacao="${publicacao.id}"><i class="bi bi-chat"></i> Comentar</span>
                    </div>
                    
                    <button class="btn btn-outline-danger btn-sm" data-id="${publicacao.id}">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </div>
            </div>
        `;
        
        htmlAcumulado += cardHTML; 
    });
    
    divPublicacoes.innerHTML = htmlAcumulado;
    
    anexarListenersComentariosOsc();
}

function anexarListenersComentariosOsc() {
    document.querySelectorAll('.btn-comentar-osc').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            publicacaoSelecionadaOsc = btn.getAttribute('data-id-publicacao');
            abrirModalComentarioOsc();
        });
    });
}

function abrirModalComentarioOsc() {
    const modal = new bootstrap.Modal(document.getElementById('modalComentarioOsc'));
    modal.show();
    document.getElementById('textoComentarioOsc').focus();
}

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
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_publicacao: publicacaoSelecionadaOsc,
                comentario: texto
            })
        });
        
        const resultado = await resposta.json();
        
        if (resultado.sucesso) {
            document.getElementById('textoComentarioOsc').value = '';
            bootstrap.Modal.getInstance(document.getElementById('modalComentarioOsc')).hide();
            carregarFeedOsc();
        } else {
            alert('Erro ao enviar comentário: ' + resultado.erro);
        }
    } catch (erro) {
        console.error('Erro:', erro);
        alert('Erro ao enviar comentário');
    }
}

document.getElementById("feed-publicacoes").addEventListener("click", async (event) => {
    
    const botaoExcluir = event.target.closest('.btn-outline-danger');
    
    if (botaoExcluir) {
        const idPublicacao = botaoExcluir.getAttribute('data-id');

        if (confirm("Tem certeza que deseja excluir esta publicação?")) {
            await excluirPostagem(idPublicacao);
        }
    }
});

async function excluirPostagem(id) {
    const formData = new FormData();
    formData.append('id_documento', id);

    const resposta = await fetch('/api/excluir-publicacao', {
        method: 'POST',
        body: formData
    });

    const resultado = await resposta.json();

    if (resultado.sucesso) {
        alert("Publicação removida com sucesso!");
        carregarFeedOsc();
    } else {
        alert("Erro ao excluir: " + resultado.erro);
    }
}

carregarFeedOsc();