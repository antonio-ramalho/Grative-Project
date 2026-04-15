document.getElementById('btnExcluirPerfil').addEventListener('click', function() {
    
    // Pergunta de segurança nativa do navegador
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