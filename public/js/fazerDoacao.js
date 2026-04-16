// Quando o usuário clicar no botão "Avançar"
document.querySelector('.btn-next').addEventListener('click', function(e) {
    e.preventDefault();

    // --- O QUE FALTAVA COMEÇA AQUI ---
    // Pega o ID da OSC que o PHP mandou pela URL (ex: ?id_osc=5)
    const urlParams = new URLSearchParams(window.location.search);
    const idOsc = urlParams.get('id_osc') || 1; // Se não houver ID na URL, usa 1 por segurança
    // --- O QUE FALTAVA TERMINA AQUI ---

    const valor = document.getElementById('valor_doacao').value;
    const mensagem = document.getElementById('mensagem_doacao').value;

    if (!valor) {
        alert("Por favor, insira um valor.");
        return;
    }

    const urlApi = '/api/doacao/registrar';

    fetch(urlApi, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            valor: valor,
            mensagem: mensagem,
            id_instituicao: idOsc, // AGORA USA O ID QUE VEIO DA HOME
            id_doador: 1      
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Dados recebidos do banco:", data);

        if (data.id_doacao) {
            // Mantém o ID na URL para a próxima tela também
            window.location.href = "/pagamento?id=" + data.id_doacao;
        } else {
            alert("Erro: O servidor não enviou o ID da doação.");
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert("Erro ao conectar com o servidor.");
    });
});