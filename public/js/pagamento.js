document.addEventListener('DOMContentLoaded', () => {
    // 1. Pega o ID da doação pela URL
    const urlParams = new URLSearchParams(window.location.search);
    const idDoacao = urlParams.get('id');
    
    if (!idDoacao) {
        alert("Erro: ID da doação não encontrado.");
        window.location.href = '/home';
        return;
    }

    // 2. Busca os detalhes da doação no Banco
    fetch(`/api/pagamento/detalhes?id=${idDoacao}`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                console.error("Erro da API:", data.erro);
                return;
            }

            // Preenche os campos no HTML (IDs devem bater com o PHP)
            const nomeOsc = document.getElementById('nome-osc');
            const pixKey = document.getElementById('pix-key');
            const valorDisplay = document.getElementById('valor-display');
            const qrImg = document.getElementById('qr-code-pix');

            if (nomeOsc) nomeOsc.innerText = data.nome_instituicao;
            if (pixKey) pixKey.value = data.chave_pix;
            
            // Formata o valor (tenta 'quantia' ou 'valor')
            const valorBruto = data.quantia || data.valor || 0;
            const valorFormatado = parseFloat(valorBruto).toLocaleString('pt-br', {
                style: 'currency',
                currency: 'BRL'
            });

            if (valorDisplay) {
                valorDisplay.innerHTML = `Valor da doação: <strong>${valorFormatado}</strong>`;
            }

            // Gera o QR Code e força ele a aparecer
            if (qrImg) {
                qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(data.chave_pix)}`;
                qrImg.style.display = 'inline-block'; 
            }
        })
        .catch(err => console.error("Erro ao carregar detalhes:", err));

    // 3. Lógica do botão Finalizar (Confirmar e ir para Obrigado)
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

                const resData = await response.json();

                if (resData.sucesso) {
                    // Redireciona para a página de agradecimento
                    window.location.href = "/obrigado";
                } else {
                    alert("Erro ao confirmar: " + (resData.erro || "Tente novamente."));
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert("Erro ao conectar com o servidor.");
            }
        });
    }
});