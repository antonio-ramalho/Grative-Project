// Funções de Máscara
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

// Elementos
const cpfInput = document.getElementById("cpf_doador");
const telefoneInput = document.getElementById("telefone_doador");
const formEditar = document.getElementById("formEditarDoador");

// Formata ao carregar a página (pega o dado cru do PHP e deixa bonito)
document.addEventListener("DOMContentLoaded", () => {
    if(cpfInput && cpfInput.value) {
        cpfInput.value = formatCpfMask(cpfInput.value);
    }
    if(telefoneInput && telefoneInput.value) {
        telefoneInput.value = formatPhoneMask(telefoneInput.value);
    }
});

// Formata enquanto digita
if (cpfInput) {
    cpfInput.addEventListener("input", (event) => {
        event.target.value = formatCpfMask(event.target.value);
    });
}

if (telefoneInput) {
    telefoneInput.addEventListener("input", (event) => {
        event.target.value = formatPhoneMask(event.target.value);
    });
}

// Limpa as máscaras antes de enviar para o PHP (opcional, pois o PHP já limpa, mas evita bugs)
if (formEditar) {
    formEditar.addEventListener("submit", () => {
        cpfInput.value = cpfInput.value.replace(/\D/g, "");
        telefoneInput.value = telefoneInput.value.replace(/\D/g, "");
    });
}