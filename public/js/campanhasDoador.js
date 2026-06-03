document.addEventListener("DOMContentLoaded", () => {
  carregarCampanhasDestaque();
});

async function carregarCampanhasDestaque() {
  const container = document.getElementById("container-campanhas-destaque");
  if (!container) return;

  try {
    const response = await fetch("/api/campanhas/destaque");
    const campanhas = await response.json();

    if (campanhas.length === 0) {
      container.innerHTML = `
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center bg-white">
                    <p class="text-muted small mb-0">Nenhuma campanha ativa no momento.</p>
                </div>
            `;
      return;
    }

    container.innerHTML = "";

    campanhas.forEach((camp) => {
      const meta = parseFloat(camp.meta_financeira) || 0;
      const arrecadado = parseFloat(camp.arrecadado_atual) || 0;
      let porcentagem = meta > 0 ? (arrecadado / meta) * 100 : 0;
      if (porcentagem > 100) porcentagem = 100;

      const cardHTML = `
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 110px; overflow: hidden; position: relative;">
                        <span class="badge bg-success position-absolute top-0 end-0 m-2" style="font-size: 0.65rem; z-index: 2;">ATIVA</span>
                        <img src="${camp.imagem_url}" class="w-100 h-100" style="object-fit: cover;" alt="${camp.titulo}">
                    </div>
                    
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="${camp.titulo}">${camp.titulo}</h6>
                        <p class="text-muted mb-2" style="font-size: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2;">
                            ${camp.descricao}
                        </p>
                        
                        <div class="progress mb-2 bg-success bg-opacity-10" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: ${porcentagem}%; border-radius: 10px;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-success fw-bold" style="font-size: 0.75rem;">R$ ${arrecadado.toFixed(2).replace(".", ",")}</span>
                            <span class="text-muted" style="font-size: 0.65rem;">Meta: R$ ${meta.toFixed(2).replace(".", ",")}</span>
                        </div>
                    </div>
                </div>
            `;
      container.innerHTML += cardHTML;
    });
  } catch (error) {
    console.error("Erro ao carregar campanhas em destaque:", error);
    container.innerHTML = '<p class="text-danger small px-1 text-center">Erro ao carregar campanhas.</p>';
  }
}
