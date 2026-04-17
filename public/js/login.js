import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";

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

document.addEventListener('DOMContentLoaded', () => {
    // Pegando os elementos do HTML (ajuste os IDs conforme o seu login.html)
    const formLogin = document.querySelector('form'); // Pega o formulário da página
    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');

    if (formLogin) {
        formLogin.addEventListener('submit', async (event) => {
            event.preventDefault(); // Impede o envio tradicional do formulário

            const email = emailInput.value;
            const password = passwordInput.value;

            try {
                // 1. Faz login no Firebase
                const userCredential = await signInWithEmailAndPassword(auth, email, password);
                const uid = userCredential.user.uid;

                // 2. Manda o UID pro PHP descobrir de quem é essa conta
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ uid: uid })
                });

                const data = await response.json();

                if (response.ok && data.redirect) {
                    // 3. Deu certo! Vai pra home da OSC ou do Doador
                    window.location.href = data.redirect;
                } else {
                    alert(data.erro || "Erro ao localizar o usuário no sistema.");
                }

            } catch (error) {
                if (error.code === 'auth/invalid-credential') {
                    alert("Email ou senha incorretos.");
                } else {
                    alert("Erro ao fazer login: " + error.message);
                }
            }
        });
    }

    // Seus botões de redirecionamento de cadastro continuam normais
    const btnRegisterOsc = document.getElementById('btnGoToRegisterOsc');
    if(btnRegisterOsc) {
        btnRegisterOsc.addEventListener('click', () => { window.location.href = '/cadastro_osc'; });
    }

    const btnRegisterDoador = document.getElementById('btnGoToRegisterDoador');
    if(btnRegisterDoador) {
        btnRegisterDoador.addEventListener('click', () => { window.location.href = '/cadastro_doador'; });
    }
});