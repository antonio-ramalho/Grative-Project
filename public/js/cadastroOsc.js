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

const cnpjInput = document.getElementById("cnpj_osc");
const cepInput = document.getElementById("cep_osc");
const telefoneInput = document.getElementById("telefone_osc");
const senhaInput = document.getElementById("senha_osc");
const btnMostrarSenha = document.getElementById("btnMostrarSenha");
const iconeOlho = document.getElementById("iconeOlho");
const formCadastroOsc = document.getElementById("formCadastroOsc");
const divAlertas = document.getElementById("divAlertas");

// Exibe mensagem dentro do container de alertas.
function showAlert(message, tipo = "danger") {
    divAlertas.classList.remove("d-none", "alert-danger", "alert-success");
    divAlertas.classList.add(`alert-${tipo}`);
    divAlertas.innerHTML = `<p class='text-${tipo} fw-bold mb-0'>${message}</p>`;
}

function getInputValue(idDoElemento){
    return document.getElementById(idDoElemento).value;
}

// Esconde o container de alertas.
function hideAlert() {
    divAlertas.classList.add("d-none");
    divAlertas.innerHTML = "";
}

// Formata o CNPJ enquanto o usuário digita.
function formatCnpjMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 14);
    let formatted = digits;

    formatted = formatted.replace(/^(\d{2})(\d)/, "$1.$2");
    formatted = formatted.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    formatted = formatted.replace(/\.(\d{3})(\d)/, ".$1/$2");
    formatted = formatted.replace(/(\d{4})(\d)/, "$1-$2");

    return formatted;
}

// Calcula um dígito verificador do CNPJ usando pesos específicos.
function calculateCnpjDigit(numbers, weights) {
    const sum = numbers.reduce((acc, digit, index) => acc + digit * weights[index], 0);
    const remainder = sum % 11;
    return remainder < 2 ? 0 : 11 - remainder;
}

// Valida se o CNPJ possui 14 dígitos e se seus dígitos verificadores coincidem.
function isValidCnpj(cnpjRaw) {
    const cnpjNumbers = cnpjRaw.replace(/\D/g, "");

    if (cnpjNumbers.length !== 14 || new Set(cnpjNumbers).size === 1) {
        return false;
    }

    const digits = Array.from(cnpjNumbers, Number);
    if (digits.some(Number.isNaN)) {
        return false;
    }

    const firstDigit = calculateCnpjDigit(digits.slice(0, 12), [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
    const secondDigit = calculateCnpjDigit(digits.slice(0, 13), [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);

    return firstDigit === digits[12] && secondDigit === digits[13];
}

// Formata o CEP no padrão: 00000-000
function formatCepMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 8);
    let formatted = digits;

    formatted = formatted.replace(/^(\d{5})(\d)/, "$1-$2");

    return formatted;
}

// Formata o Celular/Telefone no padrão: (00) 00000-0000 ou (00) 0000-0000
function formatPhoneMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 11);
    let formatted = digits;

    formatted = formatted.replace(/^(\d{2})(\d)/g, "($1) $2");
    formatted = formatted.replace(/(\d)(\d{4})$/, "$1-$2");

    return formatted;
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
                showAlert("Algo deu errado! Por favor, tente novamente.");
            }

            return Promise.reject(error);
        });
}

// Coleta os dados do formulário para envio posterior.
function collectFormData(userUid) {
    return {
        nome_osc: getInputValue("nome_osc"),
        cnpj_osc: getInputValue("cnpj_osc").replace(/\D/g, ""),
        cep_osc: getInputValue("cep_osc").replace(/\D/g, ""),
        telefone_osc: getInputValue("telefone_osc").replace(/\D/g, ""),
        email_osc: getInputValue("email_osc"),
        senha_osc: getInputValue("senha_osc"),
        logradouro_osc: getInputValue("logradouro_osc"),
        num_ende_osc: getInputValue("num_ende_osc"),
        bairro_osc: getInputValue("bairro_osc"),
        cidade_osc: getInputValue("cidade_osc"),
        estado_osc: getInputValue("estado_osc"),
        descricao_osc: getInputValue("descricao_osc"),
        pix_osc: getInputValue("pix_osc"),
        id_firebase: userUid
    };
}

// Envia os dados para o backend via fetch.
function submitOscData(data) {
    return fetch("/api/osc/cadastrar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error("Falha ao enviar os dados para o servidor.");
        }
        return response.json(); // Isso retorna o JSON com o ID que colocamos no PHP
    })
    .catch((error) => {
        console.error("Erro ao enviar dados ao servidor:", error);
        showAlert("Não foi possível enviar os dados. Tente novamente mais tarde.");
    });
}

// Atualiza a máscara do input do CNPJ em tempo real.
cnpjInput.addEventListener("input", (event) => {
    event.target.value = formatCnpjMask(event.target.value);
});

// Atualiza a máscara do CEP em tempo real
cepInput.addEventListener("input", (event) => {
    event.target.value = formatCepMask(event.target.value);
});

// Atualiza a máscara do Telefone/Celular em tempo real
telefoneInput.addEventListener("input", (event) => {
    event.target.value = formatPhoneMask(event.target.value);
});

// Alterna a visibilidade da senha e o ícone do olho.
btnMostrarSenha.addEventListener("click", () => {
    const isPassword = senhaInput.type === "password";
    senhaInput.type = isPassword ? "text" : "password";
    iconeOlho.classList.replace(
        isPassword ? "bi-eye-fill" : "bi-eye-slash-fill",
        isPassword ? "bi-eye-slash-fill" : "bi-eye-fill"
    );
});

// Valida o formulário e executa o fluxo de cadastro.
formCadastroOsc.addEventListener("submit", async (event) => {
    event.preventDefault();
    hideAlert();

    if (!formCadastroOsc.checkValidity()) {
        formCadastroOsc.classList.add("was-validated");
        showAlert("Por favor, preencha todos os campos obrigatórios!");
        return;
    }

    const cnpjRaw = getInputValue("cnpj_osc").replace(/\D/g, "");
    if (!isValidCnpj(cnpjRaw)) {
        showAlert("Insira um CNPJ válido!");
        return;
    }

    const emailOsc = getInputValue("email_osc");
    const senhaOsc = getInputValue("senha_osc");

    try {
        const userUid = await registrarNovoUsuario(emailOsc, senhaOsc);
        const dadosOsc = collectFormData(userUid);
        
        const resposta = await submitOscData(dadosOsc);
        
        if (resposta && resposta.id) {
            showAlert("Instituição cadastrada com sucesso! Redirecionando...", "success");
            window.location.href = `/home_osc?id=${resposta.id}`;
        }
    } catch (error) {

    }
});



