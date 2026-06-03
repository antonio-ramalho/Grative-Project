document.getElementById("posterCampanha").addEventListener("change", function (event) {
  const file = this.files[0];
  const previewImg = document.getElementById("previewCampanha");
  const labelTexto = document.getElementById("textoLabelPoster");

  if (file) {
    if (!file.type.startsWith("image/")) {
      alert("Formato inválido! Por favor, selecione um arquivo de imagem (PNG, JPG).");
      this.value = "";
      esconderPreview();
      return;
    }

    const maxSizeMB = 5;
    if (file.size > maxSizeMB * 1024 * 1024) {
      alert(`A imagem é muito grande! O tamanho máximo permitido é ${maxSizeMB}MB.`);
      this.value = "";
      esconderPreview();
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      previewImg.src = e.target.result;
      previewImg.classList.remove("d-none");
      if (labelTexto) labelTexto.textContent = "Imagem Selecionada";
    };
    reader.readAsDataURL(file);
  } else {
    esconderPreview();
  }

  function esconderPreview() {
    previewImg.src = "";
    previewImg.classList.add("d-none");
    if (labelTexto) labelTexto.textContent = "Adicionar Pôster (Imagem)";
  }
});

document.getElementById("formNovaCampanha").addEventListener("submit", async function (event) {
  event.preventDefault();

  const titulo = document.querySelector('input[name="titulo"]').value.trim();
  const descricao = document.querySelector('textarea[name="descricao"]').value.trim();
  const objetivos = document.getElementById("objetivosCampanha").value.trim();
  const meta = document.getElementById("metaCampanha").value.trim();
  const dataEncerramento = document.querySelector('input[name="data_encerramento"]').value;
  const imagem = document.getElementById("posterCampanha").files[0];

  if (!titulo || !descricao || !objetivos) {
    alert("Por favor, preencha todos os campos de texto.");
    return;
  }

  if (!meta || parseFloat(meta) <= 0) {
    alert("A campanha só pode ir ao ar após a inserção de metas financeiras claras e maiores que zero.");
    return;
  }

  if (!dataEncerramento) {
    alert("Informe a data de encerramento da campanha.");
    return;
  }

  const dataEscolhida = new Date(dataEncerramento);
  const dataHoje = new Date();
  dataHoje.setHours(0, 0, 0, 0);

  if (dataEscolhida <= dataHoje) {
    alert("A data de encerramento deve ser no futuro (a partir de amanhã).");
    return;
  }

  if (!imagem) {
    alert("Você precisa adicionar um pôster (imagem) para a campanha.");
    return;
  }

  const formData = new FormData(this);
  const btnSubmit = document.getElementById("btnPublicarCampanha");
  const textoOriginalBotao = btnSubmit.innerHTML;

  btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Publicando...';
  btnSubmit.disabled = true;

  try {
    const response = await fetch("/api/campanha/criar", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (response.ok && data.sucesso) {
      this.reset();
      document.getElementById("previewCampanha").classList.add("d-none");
      const labelTexto = document.getElementById("textoLabelPoster");
      if (labelTexto) labelTexto.textContent = "Adicionar Pôster (Imagem)";

      const modalElement = document.getElementById("modalCampanha");
      const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
      modalInstance.hide();

      setTimeout(() => {
        alert("Campanha registrada com sucesso! Ela já está disponível para os doadores.");
      }, 400);
    } else {
      alert("Erro: " + (data.erro || "Falha ao criar campanha."));
    }
  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Falha de conexão com o servidor. Tente novamente.");
  } finally {
    btnSubmit.innerHTML = textoOriginalBotao;
    btnSubmit.disabled = false;
  }
});

// ==========================================
// SISTEMA DE ABAS (DASHBOARD vs CAMPANHAS)
// ==========================================
const btnAbaInicio = document.getElementById("btnAbaInicio");
const btnAbaCampanhas = document.getElementById("btnAbaCampanhas");
const abaDashboard = document.getElementById("abaDashboard");
const abaCampanhas = document.getElementById("abaCampanhas");
const tituloPagina = document.getElementById("tituloPagina");

btnAbaInicio.addEventListener("click", () => {
  abaCampanhas.classList.add("d-none");
  abaDashboard.classList.remove("d-none");

  btnAbaCampanhas.classList.remove("active");
  btnAbaInicio.classList.add("active");
  tituloPagina.textContent = "Dashboard";
});

btnAbaCampanhas.addEventListener("click", () => {
  abaDashboard.classList.add("d-none");
  abaCampanhas.classList.remove("d-none");

  btnAbaInicio.classList.remove("active");
  btnAbaCampanhas.classList.add("active");
  tituloPagina.textContent = "Minhas Campanhas";
  carregarMinhasCampanhas();
});

async function carregarMinhasCampanhas() {
  const grid = document.getElementById("gridCampanhas");
  grid.innerHTML =
    '<div class="col-12 text-center text-muted"><div class="spinner-border text-success"></div><p>Carregando campanhas...</p></div>';

  try {
    const response = await fetch("/api/campanhas/osc");
    const campanhas = await response.json();

    if (campanhas.length === 0) {
      grid.innerHTML =
        '<div class="col-12 text-center text-muted mt-5"><i class="bi bi-rocket fs-1"></i><p class="mt-2">Você ainda não tem nenhuma campanha ativa.</p></div>';
      return;
    }

    grid.innerHTML = "";

    campanhas.forEach((camp) => {
      const meta = parseFloat(camp.meta_financeira) || 0;
      const arrecadado = parseFloat(camp.arrecadado_atual) || 0;
      let porcentagem = meta > 0 ? (arrecadado / meta) * 100 : 0;
      if (porcentagem > 100) porcentagem = 100;

      const cardHTML = `
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="transition: transform 0.2s;">
                        <img src="${camp.imagem_url}" class="card-img-top" style="height: 160px; object-fit: cover;" alt="Pôster da Campanha">
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="${camp.titulo}">${camp.titulo}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-${camp.status === "ativa" ? "success" : "danger"}">
                                        ${camp.status.toUpperCase()}
                                    </span>
                                    
                                    ${
                                      camp.status === "ativa"
                                        ? `
                                    <button onclick="interromperCampanha('${camp.id}')" class="btn btn-sm text-danger p-0 border-0 bg-transparent" title="Interromper/Cancelar Campanha">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </button>
                                    `
                                        : ""
                                    }
                                </div>
                            </div>
                            
                            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${camp.descricao}
                            </p>
                            
                            <div class="progress mb-2 bg-success bg-opacity-10" style="height: 8px; border-radius: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${porcentagem}%; border-radius: 10px;"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between small mb-3">
                                <span class="text-muted fw-semibold">R$ ${arrecadado.toFixed(2).replace(".", ",")}</span>
                                <span class="fw-bold text-success">Meta: R$ ${meta.toFixed(2).replace(".", ",")}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                                <small class="text-muted"><i class="bi bi-calendar-event"></i> Até ${camp.data_encerramento.split("-").reverse().join("/")}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
      grid.innerHTML += cardHTML;
    });
  } catch (error) {
    console.error("Erro ao carregar campanhas:", error);
    grid.innerHTML = '<div class="col-12 text-center text-danger"><p>Erro ao carregar campanhas.</p></div>';
  }
}

window.interromperCampanha = async function (id_campanha) {
  if (
    !confirm(
      "Tem certeza que deseja interromper esta campanha? Ela aparecerá como cancelada e os doadores não poderão mais contribuir.",
    )
  ) {
    return;
  }

  try {
    const response = await fetch("/api/campanha/interromper", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({id_campanha: id_campanha}),
    });

    const data = await response.json();

    if (response.ok && data.sucesso) {
      alert("Campanha interrompida com sucesso!");
      carregarMinhasCampanhas();
    } else {
      alert("Erro: " + (data.erro || "Falha ao interromper campanha."));
    }
  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Falha de conexão com o servidor. Tente novamente.");
  }
};
