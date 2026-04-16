document.addEventListener('DOMContentLoaded', () => {
    const btnRegister = document.getElementById('btnGoToRegister');
    
    if(btnRegister) {
        btnRegister.addEventListener('click', () => {
            // Redireciona para a sua rota de cadastro
            window.location.href = '/cadastro_osc'; 
        });
    }
});