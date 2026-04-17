import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// Configuração do Firebase usada para autenticação.
const firebaseConfig = {
    apiKey: "AIzaSyD2-P4gqUf3jrBAV-Nz5LFAvT5lom5_rMI",
    authDomain: "grative-f0ac9.firebaseapp.com",
    projectId: "grative-f0ac9",
    storageBucket: "grative-f0ac9.firebasestorage.app",
    messagingSenderId: "603920363639",
    appId: "1:603920363639:web:15e57b8bceb9fa4e27d672",
    measurementId: "G-CKM606DLJ7"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

// Elementos do DOM
const formCadastroDoador = document.getElementById("formCadastroDoador");
const divAlertas = document.getElementById("divAlertas");
const senhaInput = document.getElementById("senha_doador");
const confirmaSenhaInput = document.getElementById("confirma_senha_doador");
const cpfInput = document.getElementById("cpf_doador");
const telefoneInput = document.getElementById("telefone_doador");

// --- Adicionado: Máscaras de CPF e Telefone ---
function formatCpfMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 11);
    let formatted = digits;
    formatted = formatted.replace(/^(\d{3})(\d)/, "$1.$2");
    formatted = formatted.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
    formatted = formatted.replace(/\.(\d{3})(\d)/, ".$1-$2");
    return formatted;
}

function formatPhoneMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 11);
    let formatted = digits;
    formatted = formatted.replace(/^(\d{2})(\d)/g, "($1) $2");
    formatted = formatted.replace(/(\d)(\d{4})$/, "$1-$2");
    return formatted;
}

if (cpfInput) {
    cpfInput.addEventListener("input", (e) => { e.target.value = formatCpfMask(e.target.value); });
}
if (telefoneInput) {
    telefoneInput.addEventListener("input", (e) => { e.target.value = formatPhoneMask(e.target.value); });
}
// Elementos da Foto de Perfil
const fileInput = document.getElementById('profile-pic-input');
const imgPreview = document.getElementById('pic-preview');

// Lógica para Preview da Imagem de Perfil
if (fileInput && imgPreview) {
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}

// Exibe mensagem de erro dentro do container de alertas.
function showAlert(message) {
    divAlertas.classList.remove("d-none");
    divAlertas.innerHTML = `<p class='text-danger fw-bold mb-0'>${message}</p>`;
}

// Retorna o valor de um input pelo ID
function getInputValue(idDoElemento){
    return document.getElementById(idDoElemento).value;
}

// Esconde o container de alertas.
function hideAlert() {
    divAlertas.classList.add("d-none");
    divAlertas.innerHTML = "";
}

// Registra um novo usuário no Firebase Auth.
function registrarNovoUsuario(email, password) {
    return createUserWithEmailAndPassword(auth, email, password)
        .then((userCredential) => userCredential.user.uid)
        .catch((error) => {
            const errorCode = error.code;

            if (errorCode === "auth/email-already-in-use") {
                showAlert("Email já cadastrado! Por favor, tente novamente com outro.");
            } else if (errorCode === "auth/weak-password") {
                showAlert("A senha deve ter pelo menos 6 caracteres! Por favor, tente novamente.");
            } else if (errorCode === "auth/invalid-email") {
                showAlert("O formato do e-mail é inválido! Por favor, coloque um email válido.");
            } else {
                console.error("Erro inesperado no Firebase Auth:", error.message);
                showAlert("Algo deu errado na autenticação! Por favor, tente novamente.");
            }

            return Promise.reject(error);
        });
}

// Coleta os dados do formulário para envio posterior.
// Nota: A imagem não está sendo enviada no corpo do JSON aqui. Se for usar o Storage do Firebase,
// o ideal é fazer o upload da imagem antes e passar apenas a URL gerada para cá.
function collectFormData(userUid) {
    return {
        nome_doador: getInputValue("nome_doador"),
        usuario_doador: getInputValue("usuario_doador"),
        cpf_doador: getInputValue("cpf_doador").replace(/\D/g, ""), // Limpa a formatação
        telefone_doador: getInputValue("telefone_doador").replace(/\D/g, ""), // Limpa a formatação
        email_doador: getInputValue("email_doador"),
        data_nasc_doador: getInputValue("data_nasc_doador"),
        id_firebase: userUid
    };
}

// Envia os dados para o backend via fetch.
function submitDoadorData(data) {
    // Ajuste a rota da API de acordo com o seu roteador (ex: index.php ou rotas do framework)
    return fetch("/api/doador/cadastrar", { 
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(async (response) => {
        const responseData = await response.json();
        if (!response.ok) {
            throw new Error(responseData.erro || "Falha ao enviar os dados para o servidor.");
        }
        return responseData;
    })
    .catch((error) => {
        console.error("Erro ao enviar dados ao servidor:", error);
        showAlert("Não foi possível salvar os dados no banco. Tente novamente mais tarde.");
        return Promise.reject(error);
    });
}

// Valida o formulário e executa o fluxo de cadastro.
formCadastroDoador.addEventListener("submit", async (event) => {
    event.preventDefault();
    hideAlert();

    // Checagem nativa do HTML (required, minlength, type="email")
    if (!formCadastroDoador.checkValidity()) {
        formCadastroDoador.classList.add("was-validated");
        showAlert("Por favor, preencha todos os campos obrigatórios corretamente!");
        return;
    }

    // Checagem customizada: Senhas conferem?
    const senha = senhaInput.value;
    const confirmaSenha = confirmaSenhaInput.value;

    if (senha !== confirmaSenha) {
        showAlert("As senhas informadas não coincidem!");
        return;
    }

    const emailDoador = getInputValue("email_doador");

    try {
        const userUid = await registrarNovoUsuario(emailDoador, senha);
        const dadosDoador = collectFormData(userUid);
        const resposta = await submitDoadorData(dadosDoador);

        // Se tudo deu certo e o PHP devolveu o ID:
        if (resposta && resposta.id) {
            alert("Cadastro realizado com sucesso! Redirecionando...");
            // Redireciona mandando o ID pela URL (igual você fez na OSC)
            window.location.href = `/home_doador?id=${resposta.id}`; 
        }
        
    } catch (error) {
        console.log("Fluxo de cadastro interrompido por erro.", error);
    }
});