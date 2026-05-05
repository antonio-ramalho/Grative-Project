// Usamos o ID 'btn-proximo' que colocamos no botão do PHP novo
const botaoProximo = document.getElementById('btn-proximo');

if (botaoProximo) {
    botaoProximo.addEventListener('click', function(e) {
        e.preventDefault();

        // 1. Pega o ID da OSC pela URL (mantive a sua lógica do || 1)
        const urlParams = new URLSearchParams(window.location.search);
        const idOsc = urlParams.get('id_osc') || 1; 

        // 2. Captura os inputs pelos IDs que estão no PHP
        const valorInput = document.getElementById('quantia');
        const mensagemInput = document.getElementById('mensagem_doacao');

        const valor = parseFloat(valorInput.value);
        const mensagem = mensagemInput.value;

        // Validação básica
        if (isNaN(valor) || valor <= 0) {
            alert("Por favor, insira um valor válido.");
            return;
        }

        const urlApi = '/api/doacao/registrar';

        // 3. Envia os dados para o back-end
        fetch(urlApi, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                valor: valor,
                mensagem: mensagem,
                id_instituicao: idOsc,
                id_doador: 1 // ID fixo conforme sua regra atual
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Erro na resposta do servidor');
            return response.json();
        })
        .then(data => {
            console.log("Dados recebidos:", data);

            if (data.id_doacao) {
                // Se o banco gravou, vai para a tela de pagamento
                window.location.href = "/pagamento?id=" + data.id_doacao;
            } else {
                alert("Erro: O servidor não retornou o ID da doação.");
            }
        })
        .catch(error => {
            console.error('Erro no Fetch:', error);
            alert("Erro ao conectar com o servidor. Verifique o console (F12).");
        });
    });
}