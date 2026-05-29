import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";


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


const formCadastroDoador = document.getElementById("formCadastroDoador");
const divAlertas = document.getElementById("divAlertas");
const senhaInput = document.getElementById("senha_doador");
const confirmaSenhaInput = document.getElementById("confirma_senha_doador");
const cpfInput = document.getElementById("cpf_doador");
const telefoneInput = document.getElementById("telefone_doador");


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

const fileInput = document.getElementById('profile-pic-input');
const imgPreview = document.getElementById('pic-preview');


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


function showAlert(message) {
    divAlertas.classList.remove("d-none");
    divAlertas.innerHTML = `<p class='text-danger fw-bold mb-0'>${message}</p>`;
}


function getInputValue(idDoElemento){
    return document.getElementById(idDoElemento).value;
}


function hideAlert() {
    divAlertas.classList.add("d-none");
    divAlertas.innerHTML = "";
}


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


function collectFormData(userUid) {
    return {
        nome_doador: getInputValue("nome_doador"),
        usuario_doador: getInputValue("usuario_doador"),
        cpf_doador: getInputValue("cpf_doador").replace(/\D/g, ""), 
        telefone_doador: getInputValue("telefone_doador").replace(/\D/g, ""), 
        email_doador: getInputValue("email_doador"),
        data_nasc_doador: getInputValue("data_nasc_doador"),
        id_firebase: userUid
    };
}


function submitDoadorData(data) {
    
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


formCadastroDoador.addEventListener("submit", async (event) => {
    event.preventDefault();
    hideAlert();

    
    if (!formCadastroDoador.checkValidity()) {
        formCadastroDoador.classList.add("was-validated");
        showAlert("Por favor, preencha todos os campos obrigatórios corretamente!");
        return;
    }

    
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

        
        if (resposta && resposta.id) {
            alert("Cadastro realizado com sucesso! Redirecionando...");
            
            window.location.href = `/home_doador?id=${resposta.id}`; 
        }
        
    } catch (error) {
        console.log("Fluxo de cadastro interrompido por erro.", error);
    }
});