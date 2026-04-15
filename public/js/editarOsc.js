function formatCnpjMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 14);
    let formatted = digits;
    formatted = formatted.replace(/^(\d{2})(\d)/, "$1.$2");
    formatted = formatted.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    formatted = formatted.replace(/\.(\d{3})(\d)/, ".$1/$2");
    formatted = formatted.replace(/(\d{4})(\d)/, "$1-$2");
    return formatted;
}

function formatCepMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 8);
    let formatted = digits;
    formatted = formatted.replace(/^(\d{5})(\d)/, "$1-$2");
    return formatted;
}

function formatPhoneMask(rawValue) {
    const digits = rawValue.replace(/\D/g, "").slice(0, 11);
    let formatted = digits;
    formatted = formatted.replace(/^(\d{2})(\d)/g, "($1) $2");
    formatted = formatted.replace(/(\d)(\d{4})$/, "$1-$2");
    return formatted;
}

const cnpjInput = document.getElementById("cnpj_osc");
const cepInput = document.getElementById("cep_osc");
const telefoneInput = document.getElementById("telefone_osc");
const formEditar = document.getElementById("formEditarOsc");


document.addEventListener("DOMContentLoaded", () => {
    
    if(cnpjInput.value) cnpjInput.value = formatCnpjMask(cnpjInput.value);
    if(cepInput.value) cepInput.value = formatCepMask(cepInput.value);
    if(telefoneInput.value) telefoneInput.value = formatPhoneMask(telefoneInput.value);

    const estadoSalvo = document.getElementById('estadoSalvo').value;
    if(estadoSalvo) {
        document.getElementById('estado_osc').value = estadoSalvo;
    }
});

cnpjInput.addEventListener("input", (event) => {
    event.target.value = formatCnpjMask(event.target.value);
});

cepInput.addEventListener("input", (event) => {
    event.target.value = formatCepMask(event.target.value);
});

telefoneInput.addEventListener("input", (event) => {
    event.target.value = formatPhoneMask(event.target.value);
});

formEditar.addEventListener("submit", (event) => {
    cnpjInput.value = cnpjInput.value.replace(/\D/g, "");
    cepInput.value = cepInput.value.replace(/\D/g, "");
    telefoneInput.value = telefoneInput.value.replace(/\D/g, "");
    
});