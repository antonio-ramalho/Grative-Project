document.getElementById("formUploadDoc").addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);
  const btnUpload = document.getElementById("btnUpload");
  const loader = document.getElementById("loader");
  const mensagem = document.getElementById("mensagemRetorno");

  btnUpload.disabled = true;
  btnUpload.innerText = "Analisando...";
  loader.style.display = "block";
  mensagem.style.display = "none";
  mensagem.className = "alert-box";

  try {
    const response = await fetch("/upload-doc", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    mensagem.style.display = "block";
    if (data.sucesso) {
      mensagem.style.backgroundColor = "#ecfdf5";
      mensagem.style.color = "#065f46";
      mensagem.style.border = "1px solid #a7f3d0";
      mensagem.innerHTML = `✅ <strong>Sucesso!</strong><br>${data.mensagem}<br>CNPJ lido: ${data.cnpj_identificado}`;
      form.reset();
      carregarDocumentos();
    } else {
      mensagem.style.backgroundColor = "#fef2f2";
      mensagem.style.color = "#991b1b";
      mensagem.style.border = "1px solid #fecaca";
      mensagem.innerHTML = `❌ <strong>Documento Rejeitado:</strong><br>${data.erro}`;
    }
  } catch (error) {
    mensagem.style.display = "block";
    mensagem.style.backgroundColor = "#fef2f2";
    mensagem.style.color = "#991b1b";
    mensagem.style.border = "1px solid #fecaca";
    mensagem.innerHTML = `❌ Falha ao tentar conectar com o servidor.`;
    console.error("Erro na requisição:", error);
  } finally {
    btnUpload.disabled = false;
    btnUpload.innerText = "Analisar com IA e Enviar";
    loader.style.display = "none";
  }
});

document.addEventListener("DOMContentLoaded", () => {
  carregarDocumentos();
});

async function carregarDocumentos() {
  const grid = document.getElementById("docsGrid");

  try {
    const response = await fetch("/listar-docs");
    const data = await response.json();

    grid.innerHTML = "";

    if (!data.sucesso || data.documentos.length === 0) {
      grid.innerHTML = '<div class="empty-state">Você ainda não possui documentos validados.</div>';
      return;
    }

    data.documentos.forEach((doc) => {
      let dataFormatada = doc.data_envio ? new Date(doc.data_envio).toLocaleDateString("pt-BR") : "Data não informada";

      const card = document.createElement("div");
      card.className = "doc-card";
      card.innerHTML = `
                        <div class="box-doc">
                            <div class="doc-header">
                                <div>
                                    <p class="doc-title">${doc.tipo || "Documento"}</p>
                                    <p class="doc-date">Enviado em: ${dataFormatada}</p>
                                </div>
                            </div>
                            <div class="doc-info">
                                <span><strong>CNPJ Vinculado:</strong> ${doc.cnpj || "Não identificado"}</span>
                                <span><strong>Status:</strong> ${doc.status || "Aprovado"}</span>
                            </div>
                            <button class="btn-danger" onclick="excluirDocumento(${doc.id})">Excluir Documento</button>
                        </div>
                    `;
      grid.appendChild(card);
    });
  } catch (error) {
    grid.innerHTML = '<div class="empty-state" style="color: #dc2626;">Erro ao carregar a lista de documentos.</div>';
  }
}

async function excluirDocumento(id) {
  if (!confirm("Tem certeza que deseja excluir este documento? O seu Trust Score pode diminuir.")) {
    return;
  }

  try {
    const response = await fetch("/excluir-doc", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({id_documento: id}),
    });

    const data = await response.json();

    if (data.sucesso) {
      alert("Documento excluído com sucesso!");
      carregarDocumentos();
    } else {
      alert("Erro ao excluir: " + (data.erro || "Falha desconhecida."));
    }
  } catch (error) {
    alert("Falha de comunicação com o servidor ao excluir.");
  }
}
