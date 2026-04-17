import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, deleteUser} from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

// Configuração do Firebase usada para autenticação.
const firebaseConfig = {
    apiKey: "AIzaSyD2-P4gqUf3jrBAV-Nz5LFAvT5lom5_rMI",
    authDomain: "grative-f0ac9.firebaseapp.com",
    projectId: "grative-f0ac9",
    storageBucket: "grative-f0ac9.firebasestorage.app",
    messagingSenderId: "603920363639",
    appId: "1:603920363639:web:15e57b8bceb9fa4e27d672",
    measurementId: "G-CKM606DLJ7"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
  
        document.getElementById('btnExcluirConta').addEventListener('click', async () => {
            if (!confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
                return;
            }

            const user = auth.currentUser;

            if (user) {
                try {
                    // 1. Tenta deletar do Firebase primeiro
                    await deleteUser(user);
                    
                    // 2. Se deletou do Firebase, manda o PHP deletar do MySQL
                    const response = await fetch('/api/doador/excluir', {
                        method: 'POST'
                    });

                    if (response.ok) {
                        alert('Conta excluída com sucesso.');
                        window.location.href = '/login';
                    } else {
                        alert('Erro ao excluir do banco de dados.');
                    }
                } catch (error) {
                    // Se o usuário estiver logado há muito tempo, o Firebase pede reautenticação
                    if (error.code === 'auth/requires-recent-login') {
                        alert('Por motivos de segurança, você precisa fazer login novamente antes de excluir a conta.');
                        window.location.href = '/login';
                    } else {
                        alert('Erro ao excluir do Firebase: ' + error.message);
                    }
                }
            } else {
                // Se por algum motivo o currentUser estiver null, a gente força a exclusão no banco mesmo assim
                const response = await fetch('/api/doador/excluir', { method: 'POST' });
                if (response.ok) {
                    window.location.href = '/login';
                }
            }
        });