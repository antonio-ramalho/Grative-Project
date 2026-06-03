document.addEventListener("DOMContentLoaded", () => {
  carregarNotificacoes();
});

async function carregarNotificacoes() {
  const lista = document.getElementById("lista-notificacoes");
  const badge = document.getElementById("badge-nao-lidas");

  try {
    const resposta = await fetch("/api/notificacoes");
    const json = await resposta.json();

    if (json.sucesso) {
      lista.innerHTML = "";
      let qtdNaoLidas = 0;

      if (json.dados.length === 0) {
        lista.innerHTML = '<li class="empty-state">Nenhuma notificação por enquanto.</li>';
        badge.innerText = "0 novas";
        return;
      }

      json.dados.forEach((notif) => {
        if (notif.lida == 0) qtdNaoLidas++;

        const li = document.createElement("li");
        li.className = `inbox-item ${notif.lida == 0 ? "unread" : ""}`;

        // Garante que o link aponta para o lugar certo (se o banco não enviar, vai pro feed geral)
        const linkDestino = notif.link ? notif.link : "/feedOsc";

        // Envolvemos a div 'inbox-content' inteira em uma tag <a>
        li.innerHTML = `
                    <a href="${linkDestino}" class="inbox-content" style="text-decoration: none; color: inherit; display: block; flex-grow: 1;">
                        <p class="inbox-text">${notif.mensagem}</p>
                        <span class="inbox-date">${new Date(notif.data_criacao).toLocaleString("pt-BR")}</span>
                    </a>
                    ${notif.lida == 0 ? `<button class="btn-ler" onclick="marcarComoLida(${notif.id}, this); event.stopPropagation();">✔ Lida</button>` : ""}
                `;
        lista.appendChild(li);
      });

      badge.innerText = `${qtdNaoLidas} novas`;
      badge.style.display = qtdNaoLidas > 0 ? "inline-block" : "none";
    }
  } catch (erro) {
    lista.innerHTML = '<li class="empty-state error">Erro ao carregar notificações.</li>';
    console.error("Erro:", erro);
  }
}

async function marcarComoLida(idNotificacao, botaoElemento) {
  try {
    const resposta = await fetch("/api/notificacoes/ler", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({id_notificacao: idNotificacao}),
    });

    const json = await resposta.json();
    if (json.sucesso) {
      const li = botaoElemento.closest("li");
      li.classList.remove("unread");
      botaoElemento.remove();

      const badge = document.getElementById("badge-nao-lidas");
      let atual = parseInt(badge.innerText);
      if (atual > 1) {
        badge.innerText = `${atual - 1} novas`;
      } else {
        badge.style.display = "none";
      }
    }
  } catch (erro) {
    alert("Erro ao marcar como lida.");
  }
}
