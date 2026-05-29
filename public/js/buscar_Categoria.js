function filtrar(categoria) {
    const container = document.getElementById('resultados-busca');
    container.innerHTML = '<p class="aviso">Buscando...</p>';

   
    fetch(`/api/oscs/categoria?cat=${categoria}`)
        .then(response => {
            if (!response.ok) throw new Error('Erro na rota');
            return response.json();
        })
        .then(oscs => {
            container.innerHTML = ''; 

            if (!oscs || oscs.length === 0) {
                container.innerHTML = '<p class="aviso">Nenhuma OSC encontrada.</p>';
                return;
            }

            oscs.forEach(osc => {
                
                const card = `
                    <div class="card-figma">
                        <div class="card-header">
                            <div class="circulo-avatar"></div>
                            <div class="textos">
                                <h3>${osc.nome_instituicao}</h3>
                                <p>${osc.descricao || 'Projeto social dedicado à comunidade.'}</p>
                            </div>
                        </div>
                        <a href="/fazer-doacao?id=${osc.id_instituicao}" class="btn-laranja">Apoiar Projeto</a>
                    </div>
                `;
                container.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Erro:', error);
            container.innerHTML = '<p class="aviso">Erro ao carregar dados.</p>';
            Swal.fire('Erro', 'Verifique se o banco de dados está ligado!', 'error');
        });
}