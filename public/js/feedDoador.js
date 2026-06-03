let publicacaoSelecionada = null;
let instituicaoSelecionada = null;

async function carregarFeedGeral() {
  const divFeed = document.getElementById("feedDoador");
  divFeed.innerHTML = "";

  const resposta = await fetch("/api/feed-geral");
  const publicacoes = await resposta.json();

  let todosOsCardsHTML = "";

  publicacoes.forEach((publicacao) => {
    let tagImagem = "";
    
    if (publicacao.imagem_url) {
      const extensao = publicacao.imagem_url.split('.').pop().toLowerCase();
      const formatosVideo = ['mp4', 'mov', 'avi', 'webm'];
      
      if (formatosVideo.includes(extensao)) {
          let tipoMime = 'video/mp4'; 
          if (extensao === 'webm') tipoMime = 'video/webm';
          else if (extensao === 'mov') tipoMime = 'video/quicktime';
          else if (extensao === 'avi') tipoMime = 'video/x-msvideo';

          tagImagem = `
              <div class="mt-2 text-center rounded overflow-hidden" style="background-color: #000;">
                  <video controls preload="metadata" style="width: 100%; height: 400px; object-fit: contain;">
                      <source src="${publicacao.imagem_url}" type="${tipoMime}">
                      Seu navegador não suporta a tag de vídeo.
                  </video>
              </div>`;
      } else {
          tagImagem = `
              <div class="mt-2 text-center rounded overflow-hidden" style="background-color: #f8f9fa;">
                  <img src="${publicacao.imagem_url}" style="width: 100%; height: 400px; object-fit: contain;">
              </div>`;
      }
    }

    let classeCoracao = publicacao.usuario_curtiu ? 'bi-heart-fill text-danger' : 'bi-heart';

    todosOsCardsHTML += `
            <div class="card mb-4 p-1 card-publicacao" data-id="${publicacao.id}">
                <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-3" style="color: #444c54;"></i>
                        <div class="lh-sm">
                            <h6 class="m-0 fw-bold" style="font-size: 0.95rem;">${publicacao.nome_osc}</h6>
                            <small style="font-size: 0.75rem; color: #88939c;">${publicacao.data_publicacao || "Recentemente"}</small>
                        </div>
                    </div>
                    <span class="badge badge-score border-0 fw-normal px-2 py-1">Score ${publicacao.trust_score || "0.0"}</span>
                </div>

                <div class="card-body py-1">
                    <h5 class="card-title fw-bold fs-6 mb-3 mt-1">${publicacao.titulo}</h5>
                    
                    <p class="mb-3" style="font-size: 0.9rem; color: var(--text-dark);">
                        ${publicacao.descricao}
                    </p>
                    
                    ${tagImagem}
                </div>

                <div class="card-footer bg-white border-0 pt-2 pb-2 d-flex justify-content-center gap-5 px-4">
                    
                    <button onclick="curtirPostagem('${publicacao.id}', this)" class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent">
                        <i class="bi ${classeCoracao} fs-6"></i> Curtir <span class="contador-curtidas">${publicacao.curtidas || 0}</span>
                    </button>

                    <button class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent btn-comentar" data-id-publicacao="${publicacao.id}" data-id-instituicao="${publicacao.id_instituicao}">
                        <i class="bi bi-chat-left-text fs-6"></i> Comentar
                    </button>
                    
                    <button onclick="compartilharPostagem('${publicacao.titulo}', '${publicacao.descricao}')" class="btn btn-post-action btn-sm px-3 d-flex align-items-center gap-2 border-0 bg-transparent">
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

  publicacoes.forEach((publicacao) => {
    carregarComentarios(publicacao.id);
  });
}

function anexarListenersComentarios() {
  document.querySelectorAll(".btn-comentar").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      publicacaoSelecionada = btn.getAttribute("data-id-publicacao");
      instituicaoSelecionada = btn.getAttribute("data-id-instituicao");
      abrirModalComentario();
    });
  });
}

function abrirModalComentario() {
  const modal = new bootstrap.Modal(document.getElementById("modalComentario"));
  modal.show();

  setTimeout(() => {
    document.getElementById("textoComentario").focus();
  }, 400);
}

async function enviarComentario() {
  const texto = document.getElementById("textoComentario").value.trim();

  if (!texto) {
    alert("Por favor, digite um comentário");
    return;
  }

  if (!publicacaoSelecionada) {
    alert("Erro: publicação não identificada");
    return;
  }

  try {
    const resposta = await fetch("/api/comentario/adicionar", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "same-origin",
      body: JSON.stringify({
        id_publicacao: publicacaoSelecionada,
        comentario: texto,
        id_instituicao_dona: instituicaoSelecionada,
      }),
    });

    const resultado = await resposta.json();

    if (resultado.sucesso) {
      document.getElementById("textoComentario").value = "";
      bootstrap.Modal.getInstance(document.getElementById("modalComentario")).hide();

      // Recarrega apenas os comentários desse post, mantendo o usuário no mesmo lugar!
      carregarComentarios(publicacaoSelecionada);
    } else {
      alert("Erro ao enviar comentário: " + resultado.erro);
    }
  } catch (erro) {
    console.error("Erro na comunicação com o servidor:", erro);
    alert("Houve um problema de conexão. Tente novamente.");
  }
}

async function carregarComentarios(idPublicacao) {
  try {
    const resposta = await fetch(`/api/comentario/listar?id_publicacao=${idPublicacao}`);

    if (!resposta.ok) throw new Error("Erro na requisição");

    const comentarios = await resposta.json();

    const postElement = document.querySelector(`.card-publicacao[data-id="${idPublicacao}"]`);
    if (!postElement) return;

    const listaDiv = postElement.querySelector(".lista-comentarios");
    const btnVerTodos = postElement.querySelector(".btn-ver-todos");

    listaDiv.innerHTML = "";

    if (comentarios.length > 0) {
      const limitePreview = 2;
      const previewComentarios = comentarios.slice(0, limitePreview);

      renderizarLista(previewComentarios, listaDiv);

      if (comentarios.length > limitePreview) {
        btnVerTodos.textContent = `Ver todos os ${comentarios.length} comentários`;
        btnVerTodos.style.display = "block";

        btnVerTodos.onclick = () => {
          listaDiv.innerHTML = "";
          renderizarLista(comentarios, listaDiv);
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

function renderizarLista(arrayComentarios, container) {
  arrayComentarios.forEach((c) => {
    const div = document.createElement("div");
    div.className = "comentario-item d-flex justify-content-between align-items-center mb-1";

    let botaoLixeira = "";

    if (c.pode_deletar) {
      botaoLixeira = `
                <button onclick="deletarComentario(${c.id}, this)" class="btn btn-sm text-danger p-0 border-0 bg-transparent" title="Excluir comentário">
                    <i class="bi bi-trash3"></i>
                </button>
            `;
    }

    div.innerHTML = `
            <div>
                <strong>${c.nome_usuario}</strong> <span style="font-size: 0.95rem;">${c.comment || c.texto}</span>
            </div>
            ${botaoLixeira}
        `;

    container.appendChild(div);
  });
}

async function deletarComentario(idComentario, botaoElemento) {
  if (!confirm("Tem certeza que deseja apagar este comentário?")) {
    return;
  }

  try {
    const resposta = await fetch("/api/comentario/deletar", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({id_comentario: idComentario}),
    });

    const resultado = await resposta.json();

    if (resposta.ok && resultado.sucesso) {
      // Pega o ID do post pai subindo pelas divs e atualiza só a lista dele
      const cardPost = botaoElemento.closest('.card-publicacao');
      if (cardPost) {
          const idPost = cardPost.getAttribute('data-id');
          carregarComentarios(idPost);
      }
    } else {
      alert("Aviso: " + (resultado.erro || "Não foi possível excluir."));
    }
  } catch (erro) {
    console.error("Erro ao deletar:", erro);
    alert("Erro de conexão ao tentar excluir o comentário.");
  }
}

// Função para Curtir Dinâmica
async function curtirPostagem(idPostagem, botaoElement) {
    try {
        const response = await fetch('/curtir-publicacao', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_publicacao: idPostagem })
        });

        const data = await response.json();

        if (data.sucesso) {
            // 1. Atualiza o número de curtidas direto na tela
            const contadorElement = botaoElement.querySelector('.contador-curtidas');
            if (contadorElement) contadorElement.textContent = data.curtidas;
            
            // 2. Inverte o coração (vazio <-> preenchido vermelho)
            const iconeElement = botaoElement.querySelector('i');
            if (iconeElement.classList.contains('bi-heart-fill')) {
                iconeElement.classList.remove('bi-heart-fill', 'text-danger');
                iconeElement.classList.add('bi-heart');
            } else {
                iconeElement.classList.remove('bi-heart');
                iconeElement.classList.add('bi-heart-fill', 'text-danger');
            }
        } else {
            console.error(data.erro);
        }
    } catch (error) {
        console.error("Erro ao curtir:", error);
    }
}

// Função para Compartilhar (Web Share API)
function compartilharPostagem(titulo, descricao) {
    const urlPost = window.location.href; 

    if (navigator.share) {
        navigator.share({
            title: `Olhe este impacto: ${titulo} - Grative`,
            text: descricao,
            url: urlPost
        }).then(() => {
            console.log('Postagem compartilhada com sucesso!');
        }).catch((error) => {
            console.log('Erro ao compartilhar', error);
        });
    } else {
        alert('Seu navegador não suporta a função nativa de compartilhar. Copie o link do site!');
    }
}

carregarFeedGeral();