
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


const cpfInput = document.getElementById("cpf_doador");
const telefoneInput = document.getElementById("telefone_doador");
const formEditar = document.getElementById("formEditarDoador");


document.addEventListener("DOMContentLoaded", () => {
    if(cpfInput && cpfInput.value) {
        cpfInput.value = formatCpfMask(cpfInput.value);
    }
    if(telefoneInput && telefoneInput.value) {
        telefoneInput.value = formatPhoneMask(telefoneInput.value);
    }
});


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


if (formEditar) {
    formEditar.addEventListener("submit", () => {
        cpfInput.value = cpfInput.value.replace(/\D/g, "");
        telefoneInput.value = telefoneInput.value.replace(/\D/g, "");
    });
}