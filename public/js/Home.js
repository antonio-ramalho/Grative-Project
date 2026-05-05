document.addEventListener('DOMContentLoaded', function() {
    const listaResultados = document.getElementById('lista-oscs'); 
    const botoesFiltro = document.querySelectorAll('.card-categoria');

    // Função para construir os cards com o layout do Figma
    function renderizarCards(oscs) {
        // Limpa APENAS a div de baixo
        listaResultados.innerHTML = ''; 

        if (oscs.length === 0) {
            listaResultados.innerHTML = '<p class="aviso">Nenhuma instituição encontrada nesta categoria.</p>';
            return;
        }

        oscs.forEach(osc => {
            listaResultados.innerHTML += `
                <div class="card-figma">
                    <div class="card-header">
                        <div class="circulo-avatar"></div>
                        <div class="textos">
                            <h3>${osc.nome_instituicao}</h3>
                            <p class="desc">${osc.descricao || 'Projeto social dedicado à transformação da comunidade.'}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="/fazer-doacao?id=${osc.id_instituicao}" class="btn-laranja">Apoiar Projeto</a>
                    </div>
                </div>
            `;
        });
    }

    // Lógica de clique
    botoesFiltro.forEach(btn => {
        btn.addEventListener('click', () => {
            const categoria = btn.innerText.trim();
            
            // Feedback visual no botão selecionado
            botoesFiltro.forEach(b => b.classList.remove('ativo'));
            btn.classList.add('ativo');

            listaResultados.innerHTML = '<div class="carregando">Buscando projetos...</div>';
            
            fetch(`/api/oscs/categoria?categoria=${categoria}`)
                .then(res => res.json())
                .then(dados => renderizarCards(dados))
                .catch(err => {
                    console.error(err);
                    listaResultados.innerHTML = '<p class="aviso">Erro ao conectar com o banco de dados.</p>';
                });
        });
    });
});