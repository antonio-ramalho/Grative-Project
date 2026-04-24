document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("container-app");

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    cadastrar_categoria();
    ver_categorias();
    atualizarSaldo();
    form.reset();
  });

  ver_categorias();
  atualizarSaldo();

  document.getElementById("btn-publicar").addEventListener("click", () => {
    publicarRelatorio();
  });
});

function cadastrar_categoria() {
  let categorias_storage = JSON.parse(localStorage.getItem("categorias_dict")) || {};

  const id = window.idEmEdicao || Date.now();
  const categoria = document.getElementById("selecionar-categoria").value;
  const valor = parseFloat(document.getElementById("categoria-valor").value);

  if (valor < 0 || valor === 0) {
    alert("Valor invalido");
    return null;
  }

  categorias_storage[id] = {
    id: id,
    categoria: categoria,
    valor: valor,
  };

  localStorage.setItem("categorias_dict", JSON.stringify(categorias_storage));

  window.idEmEdicao = null;
  document.getElementById("btn-salvar-categoria").innerText = "Nova Categoria";
  document.getElementById("form-categoria").reset();
}

function voltarParaHome() {
  localStorage.removeItem("categorias_dict");
  window.location.href = "/home_osc";
}

function inserir_tabela_categoria() {
  var html = `
    <table id="tabela-categorias">
        <tbody id="corpo-tabela">
            </tbody>
    </table>`;

  document.getElementById("lista_categorias").innerHTML = html;
}

function ver_categorias() {
  inserir_tabela_categoria();
  const tbody = document.getElementById("corpo-tabela");
  const categorias_list = JSON.parse(localStorage.getItem("categorias_dict")) || {};

  if (Object.keys(categorias_list).length === 0) {
    const linha = `
        <tr>
            <td>Não existem categorias cadastradas!</td>
        </tr>
    `;
    tbody.innerHTML += linha;
    return null;
  }

  Object.values(categorias_list).forEach((item) => {
    const linha = `
        <tr>
            <td>
                <div class="td_div" data-valor-gasto="${item.valor}">
                    <span>${item.categoria}</span>
                    <span> | </span>
                    <span>${Number(item.valor).toLocaleString("pt-BR", {style: "currency", currency: "BRL"})}</span>
                </div> 
            </td>
            <td><button onclick="prepararEdicao(${item.id})" class="botao_item"><img class="img_icon" src="/img/icons/pencil-square.svg" alt="icone-usuario"></button></td>
            <td><button onclick="excluir_item(${item.id})" class="botao_item"><img class="img_icon" src="/img/icons/exclamation-circle.svg" alt="icone-usuario"></button></td>
        </tr>
    `;
    tbody.innerHTML += linha;
  });
}

function excluir_item(id) {
  let categorias_dict = JSON.parse(localStorage.getItem("categorias_dict")) || {};

  delete categorias_dict[id];

  localStorage.setItem("categorias_dict", JSON.stringify(categorias_dict));
  ver_categorias();
  atualizarSaldo();
}

function prepararEdicao(id) {
  const categorias = JSON.parse(localStorage.getItem("categorias_dict")) || {};
  const item = categorias[id];

  if (item) {
    document.getElementById("selecionar-categoria").value = item.categoria;
    document.getElementById("categoria-valor").value = item.valor;
    window.idEmEdicao = id;
    document.getElementById("btn-salvar-categoria").innerText = "Atualizar Categoria";
    verificarIntegridadeFinanceira(item.valor);
  }
}

function atualizarSaldo() {
  const saldoInicial = parseFloat(document.getElementById("container-app").dataset.saldoInicial);
  const categorias = JSON.parse(localStorage.getItem("categorias_dict")) || {};
  const totalGastos = Object.values(categorias).reduce((acc, item) => acc + Number(item.valor), 0);
  const saldoRestante = parseFloat(saldoInicial - totalGastos);

  document.getElementById("saldo-dinamico").innerText = saldoRestante.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });

  verificarIntegridadeFinanceira(saldoRestante);
}

function verificarIntegridadeFinanceira(saldo) {
  const elementoSaldo = document.getElementById("saldo-dinamico");
  const btnSalvar = document.getElementById("btn-salvar-categoria");
  const btn_publicar = document.getElementById("btn-publicar");

  if (saldo != 0) {
    btn_publicar.disabled = true;
    btn_publicar.style.opacity = "0.5";
    btn_publicar.style.cursor = "not-allowed";
  } else {
    btn_publicar.disabled = false;
    btn_publicar.style.opacity = "1";
    btn_publicar.style.cursor = "pointer";
  }

  if (saldo <= 0) {
    console.log(window.idEmEdicao);
    elementoSaldo.style.color = "#e53935";
    btnSalvar.disabled = true;
    btnSalvar.style.opacity = "0.5";
    btnSalvar.style.cursor = "not-allowed";
  } else {
    elementoSaldo.style.color = "inherit";
    btnSalvar.disabled = false;
    btnSalvar.style.opacity = "1";
    btnSalvar.style.cursor = "pointer";
  }
}

async function publicarRelatorio() {
  const btn = document.getElementById("btn-publicar");
  const categorias = JSON.parse(localStorage.getItem("categorias_dict")) || {};
  const saldoInicial = parseFloat(document.getElementById("container-app").dataset.saldoInicial);

  btn.disabled = true;
  btn.innerText = "Publicando...";

  try {
    const response = await fetch("/relatorio/publicar", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        saldo_total: saldoInicial,
        itens: Object.values(categorias),
      }),
    });

    const resultado = await response.json();

    if (resultado.success) {
      alert("Relatório publicado com sucesso!");
      voltarParaHome();
    } else {
      throw new Error(resultado.message || "Erro desconhecido");
    }
  } catch (error) {
    alert("Erro ao publicar: " + error.message);
    btn.disabled = false;
    btn.innerText = "Publicar Relatório";
  }
}
