document.getElementById('btnExcluirPerfil').addEventListener('click', function() {
    
    if(confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
        
        fetch('/api/osc/excluir', {
            method: 'POST',
        })
        .then(response => {
            window.location.href = '/login?msg=account_deleted';
        })
        .catch(error => {
            console.error('Erro ao excluir:', error);
            alert('Ocorreu um erro ao tentar excluir a conta.');
        });
    }
});

document.getElementById('btnEditarPerfil').addEventListener('click', function() {
    window.location.href = '/editar_osc';
});

const formPublicacao = document.getElementById('formNovaPublicacao')

formPublicacao.addEventListener('submit',function(event){
    event.preventDefault()

    const formData = new FormData(formPublicacao);

    return fetch("/api/publicacao/criar", {
        method: "POST",
        body: formData
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error("Falha ao enviar os dados para o servidor.");
        }
        return response;
    })
    .catch((error) => {
        console.error("Erro ao enviar dados ao servidor:", error);
        showAlert("Não foi possível enviar os dados. Tente novamente mais tarde.");
    });
})

document.addEventListener("DOMContentLoaded", function() {
    fetch('/api/osc/dados', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Não foi possível carregar os dados.");
        }
        return response.json();
    })
    .then(data => {
        if (data.nome_instituicao) {
            document.getElementById('nomeOscHeader').textContent = data.nome_instituicao;
        }
    })
    .catch(error => {
        console.error("Erro ao buscar dados do perfil:", error);
    });
});