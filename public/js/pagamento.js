document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const idDoacao = urlParams.get('id');
    
    if (!idDoacao) {
        alert("Erro: ID da doação não encontrado.");
        window.location.href = '/home';
        return;
    }

    fetch(`/api/pagamento/detalhes?id=${idDoacao}`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                console.error("Erro da API:", data.erro);
                return;
            }

            document.getElementById('nome-osc').innerText = data.nome_instituicao;
            document.getElementById('pix-key').value = data.chave_pix;
            
            const valorFormatado = parseFloat(data.quantia).toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });
            document.getElementById('valor-display').innerHTML = `Valor da doação: <strong>${valorFormatado}</strong>`;

            const qrImg = document.getElementById('qr-code-pix');
            qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(data.chave_pix)}`;
            qrImg.style.display = 'inline-block'; 
        })
        .catch(err => console.error("Erro ao carregar detalhes:", err));


    const btnFinalizar = document.querySelector('.btn-next');

    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async (e) => {
            e.preventDefault();

            try {
                const response = await fetch('/api/doacao/confirmar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_doacao: idDoacao })
                });

                const data = await response.json();

                if (data.sucesso) {
                    alert("Pagamento confirmado com sucesso!");
                    window.location.href = "/obrigado";
                } else {
                    alert("Erro ao confirmar: " + (data.erro || "Tente novamente."));
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert("Erro crítico ao conectar com o servidor.");
            }
        });
    }
});