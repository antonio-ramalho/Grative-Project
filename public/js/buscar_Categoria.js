function filtrar(categoria) {
    const container = document.getElementById('resultados-busca');
    container.innerHTML = '<p>Buscando...</p>';

    fetch(`/api/oscs/categoria?cat=${categoria}`)
        .then(response => response.json())
        .then(oscs => {
            container.innerHTML = ''; // Limpa o aviso

            if (oscs.length === 0) {
                container.innerHTML = '<p>Nenhuma OSC encontrada nesta categoria.</p>';
                return;
            }

            oscs.forEach(osc => {
                const card = `
                    <div class="osc-card-busca">
                        <h3>${osc.nome_instituicao}</h3>
                        <p>${osc.descricao}</p>
                        <a href="/fazer-doacao?id_osc=${osc.id_instituicao}" class="btn-doar">Doar Agora</a>
                    </div>
                `;
                container.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire('Erro', 'Não foi possível buscar as OSCs.', 'error');
        });
}