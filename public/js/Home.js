document.addEventListener('DOMContentLoaded', function() {
    const listaOscs = document.querySelector('.oscs-list');

    fetch('/api/oscs')
        .then(response => response.json())
        .then(oscs => {

            listaOscs.innerHTML = '';

            if (oscs.length === 0) {
                listaOscs.innerHTML = '<p>Nenhuma OSC cadastrada no sistema.</p>';
                return;
            }

            oscs.forEach(osc => {
                const card = `
                    <div class="osc-card">
                        <div class="osc-main-info">
                            <div class="profile-image">
                                <i class="ph ph-user"></i>
                            </div>
                            <div class="osc-details">
                                <div class="title-row">
                                    <h3>${osc.nome_instituicao}</h3>
                                    <span class="score-label">Score</span>
                                </div>
                                <div class="description-lines">
                                    <p>${osc.descricao || 'Sem descrição disponível.'}</p>
                                </div>
                                <a href="#" class="more-link">...Saber mais sobre</a>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="/fazer-doacao?id_osc=${osc.id_instituicao}" class="btn-doar">
                                Doar
                            </a>
                        </div>
                    </div>
                `;
                listaOscs.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Erro ao carregar OSCs:', error);
            listaOscs.innerHTML = '<p>Erro técnico ao carregar dados. Verifique o console do seu Ryzen 3.</p>';
        });
});