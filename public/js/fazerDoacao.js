
// Quando o usuário clicar no botão "Avançar"
document.querySelector('.btn-next').addEventListener('click', function(e) {
    e.preventDefault();

    // Pega o que foi digitado nos campos de valor e mensagem
    const valor = document.getElementById('valor_doacao').value;
    const mensagem = document.getElementById('mensagem_doacao').value;
    if (!valor) {
        alert("Por favor, insira um valor.");
        return;
    }

    // Endereço onde a doação será registrada
    const urlApi = '/api/doacao/registrar';

    // Manda os dados para o sistema salvar no banco
    fetch(urlApi, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            valor: valor,
            mensagem: mensagem,
            id_instituicao: 1, 
            id_doador: 1      
        })
    })
    .then(response => response.json()) // Recebe a resposta do sistema
    .then(data => {
        console.log("Dados recebidos do banco:", data);

    // Se o sistema criou a doação com sucesso
        if (data.id_doacao) {

            window.location.href = "/pagamento?id=" + data.id_doacao;
        } else {
            alert("Erro: O servidor não enviou o ID da doação. Verifique o Controller.");
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert("Erro ao conectar com o servidor. Verifique o terminal do PHP.");
    });
});