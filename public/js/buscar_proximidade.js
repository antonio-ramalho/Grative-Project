document.getElementById("btnLocalizacao").addEventListener("click", () => {
    const container = document.getElementById("gridResultados");

    const latValida = (typeof doadorLat !== "undefined" && doadorLat !== null) ? String(doadorLat).trim() : "";
    const lngValida = (typeof doadorLng !== "undefined" && doadorLng !== null) ? String(doadorLng).trim() : "";

   
    if (latValida === "" || lngValida === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Sem Localização no Perfil',
            text: 'O teu perfil de doador não possui coordenadas válidas. Verifica o teu CEP cadastrado.',
            confirmButtonColor: '#1a8853'
        });
        return;
    }

    container.innerHTML = '<p class="aviso">A calcular distâncias a partir do teu endereço cadastrado...</p>';


    fetch(`/api/osc/buscar_proximidade?lat=${latValida}&lng=${lngValida}`)
        .then(response => {
            if (!response.ok) throw new Error("Erro na requisição.");
            return response.json();
        })
        .then(instituicoes => {
            container.innerHTML = "";

            if (!instituicoes || instituicoes.length === 0) {
                container.innerHTML = '<p class="aviso">Nenhuma instituição encontrada num raio de 50km.</p>';
                return;
            }

            instituicoes.forEach(osc => {
                const distanciaFormatada = parseFloat(osc.distancia).toFixed(1);
                const idOsc = osc.id_instituicao || osc.id;
                
                const cardHtml = `
                    <div class="card-figma">
                        <div class="card-header">
                            <div class="circulo-avatar"></div>
                            <div class="textos">
                                <h3>${osc.nome_instituicao}</h3>
                                <p style="color: #1a8853; font-weight: bold; margin: 4px 0;">
                                    <i class="bi bi-geo-alt-fill"></i> ${distanciaFormatada} km de distância
                                </p>
                                <p>${osc.descricao || 'Projeto social dedicado à comunidade.'}</p>
                                <small style="display: block; color: #88939c; margin-top: 5px;">
                                    ${osc.cidade || 'Não informada'} - ${osc.estado || 'UF'}
                                </small>
                            </div>
                        </div>
                        <a href="/fazer-doacao?id=${idOsc}" class="btn-laranja">Apoiar Projeto</a>
                    </div>`;
                
                container.innerHTML += cardHtml;
            });
        })
        .catch(error => {
            console.error(error);
            container.innerHTML = '<p class="aviso">Erro ao carregar dados.</p>';
            Swal.fire('Erro', 'Verifica se o banco de dados está ligado!', 'error');
        });
});