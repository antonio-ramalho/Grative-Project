
document.querySelector('.btn-next').addEventListener('click', function(e) {
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const idOsc = urlParams.get('id_osc') || 1; 

    
    const valorInput = document.getElementById('quantia');
    const valor = parseFloat(valorInput.value);
    const mensagem = document.getElementById('mensagem_doacao').value;

    if (isNaN(valor) || valor <= 0) {
        alert("Por favor, insira um valor válido e maior que zero.");
        return;
    }

    const urlApi = '/api/doacao/registrar';

    fetch(urlApi, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            valor: valor,
            mensagem: mensagem,
            id_instituicao: idOsc,
            id_doador: 1      
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Dados recebidos do banco:", data);

        if (data.id_doacao) {
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