document.getElementById("btnExcluirPerfil").addEventListener("click", function () {
  if (confirm("Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
    fetch("/api/osc/excluir", {
      method: "POST",
    })
      .then((response) => {
        window.location.href = "/login?msg=account_deleted";
      })
      .catch((error) => {
        console.error("Erro ao excluir:", error);
        alert("Ocorreu um erro ao tentar excluir a conta.");
      });
  }
});

document.getElementById("btnEditarPerfil").addEventListener("click", function () {
  window.location.href = "/editar_osc";
});

const modalPublicacao = document.getElementById("modalPublicacao");
const controladorModal = bootstrap.Modal.getOrCreateInstance(modalPublicacao);
const divAlertaPublicacao = document.getElementById("alertaPublicacao");
const formPublicacao = document.getElementById("formNovaPublicacao");
const inputImagem = document.getElementById("imagemProjeto");
const labelImagem = document.querySelector('label[for="imagemProjeto"]');
const htmlOriginalLabel = labelImagem.innerHTML;

inputImagem.addEventListener("change", function () {
  if (this.files && this.files.length > 0) {
    const nomeArquivo = this.files[0].name;

    const nomeExibicao = nomeArquivo.length > 20 ? nomeArquivo.substring(0, 20) + "..." : nomeArquivo;

    labelImagem.innerHTML = `<i class="bi bi-check-circle-fill fs-4 text-success"></i> <span class="text-success fw-bold ms-2" style="font-size: 0.9rem;">${nomeExibicao}</span>`;
  } else {
    labelImagem.innerHTML = htmlOriginalLabel;
  }
});

formPublicacao.addEventListener("submit", async function (event) {
  event.preventDefault();

  document.getElementById("btnEnviarPublicacao").blur();

  const formData = new FormData(formPublicacao);

  try {
    const response = await fetch("/api/publicacao/criar", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Falha ao enviar os dados para o servidor.");
    }

    formPublicacao.reset();
    document.querySelector('label[for="imagemProjeto"]').innerHTML = htmlOriginalLabel;

    const modalElement = document.getElementById("modalPublicacao");
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
    modalInstance.hide();

    divAlertaPublicacao.classList.remove("d-none");
    setTimeout(function () {
      divAlertaPublicacao.classList.add("show");
    }, 20);

    setTimeout(function () {
      divAlertaPublicacao.classList.remove("show");
      setTimeout(function () {
        divAlertaPublicacao.classList.add("d-none");
      }, 300);
    }, 3000);
  } catch (error) {
    console.error("Erro ao enviar dados ao servidor:", error);
    alert("Não foi possível enviar a publicação. A imagem pode ser muito grande ou ocorreu um erro no servidor.");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  fetch("/api/osc/dados", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Não foi possível carregar os dados.");
      }
      return response.json();
    })
    .then((data) => {
      if (data.nome_instituicao) {
        document.getElementById("nomeOscHeader").textContent = data.nome_instituicao;
      }
    })
    .catch((error) => {
      console.error("Erro ao buscar dados do perfil:", error);
    });
});

document.addEventListener("DOMContentLoaded", function () {
  fetch("/api/osc/dashboard", {
    method: "GET",
    headers: {Accept: "application/json"},
  })
    .then((response) => {
      if (!response.ok) throw new Error("Erro ao carregar métricas");
      return response.json();
    })
    .then((data) => {
      document.getElementById("dashDoacoes").textContent = data.doacoes || "R$ 0,00";
      document.getElementById("dashScore").textContent = data.score || "0.0";
      document.getElementById("dashLikes").textContent = data.likes || "0";
      document.getElementById("dashInteracoes").textContent = data.interacoes || "0";
    })
    .catch((error) => {
      console.error("Erro no Dashboard:", error);
      document.getElementById("dashDoacoes").textContent = "R$ 0,00";
      document.getElementById("dashScore").textContent = "0.0";
      document.getElementById("dashLikes").textContent = "0";
      document.getElementById("dashInteracoes").textContent = "0";
    });
});
