document.querySelector('.btn-next').addEventListener('click', function(e) {
    e.preventDefault();

    // 1. Captura o ID da instituição da URL de forma segura
    const urlParams = new URLSearchParams(window.location.search);
    const idOsc = urlParams.get('id_osc') || urlParams.get('id'); 

    if (!idOsc) {
        alert("Erro: Instituição não identificada na URL.");
        return;
    }

    // 2. Captura os valores do formulário
    const valorInput = document.getElementById('quantia');
    const valor = parseFloat(valorInput.value);
    const mensagem = document.getElementById('mensagem_doacao').value;

    // 3. Validação básica
    if (isNaN(valor) || valor <= 0) {
        alert("Por favor, insira um valor válido para a doação.");
        valorInput.focus();
        return;
    }

    const urlApi = '/api/doacao/registrar';

    // 4. Envio dos dados para o servidor
    fetch(urlApi, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            valor: valor,
            mensagem: mensagem,
            id_instituicao: idOsc
            // REMOVIDO: id_doador: 1 (O servidor deve pegar isso da SESSION por segurança)
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Falha no registro da doação');
        return response.json();
    })
    .then(data => {
        if (data.id_doacao) {
            // Redireciona para a tela de pagamento com o ID gerado pelo banco
            window.location.href = "/pagamento?id=" + data.id_doacao;
        } else {
            alert("Erro ao processar: " + (data.erro || "ID não retornado"));
        }
    })
    .catch(error => {
        console.error('Erro técnico:', error);
        alert("Não foi possível conectar ao servidor. Verifique sua conexão.");
    });
});