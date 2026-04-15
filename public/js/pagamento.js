document.addEventListener('DOMContentLoaded', () => {

    const urlParams = new URLSearchParams(window.location.search);
    
    // Procura especificamente pelo número (ID) da doação
    const idDoacao = urlParams.get('id');
    console.log("ID da Doação identificado:", idDoacao);

    // Procura o botão de finalizar na tela
    const btnFinalizar = document.querySelector('.btn-next');

    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!idDoacao) {
                alert("Erro: ID da doação não encontrado na URL. Tente refazer o processo.");
                return;
            }

            try {
                // Envia para o sistema a ordem de confirmar o pagamento desse ID
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