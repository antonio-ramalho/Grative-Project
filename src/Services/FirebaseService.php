<?php

namespace App\Services; // Ajuste o namespace conforme o seu composer.json

use Kreait\Firebase\Factory;

class FirebaseService {
    private $auth;

    public function __construct() {
        // Pega o caminho do arquivo .env (como configuramos antes)
        $caminhoCredenciais = $_ENV['FIREBASE_CREDENTIALS']; 

        // Inicializa o Firebase
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/../../' . $caminhoCredenciais);

        $this->auth = $factory->createAuth();
    }

    // Função que você vai chamar depois para validar o token que vier do front-end
    public function verificarToken($idToken) {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken->claims()->get('sub'); // Retorna o UID do usuário
        } catch (\Exception $e) {
            return false; // Token inválido
        }
    }
}