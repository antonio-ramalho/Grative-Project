<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class FirebaseService {
    private $auth;

    public function __construct() {
        $caminhoCredenciais = $_ENV['FIREBASE_CREDENTIALS'];
        
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../' . $caminhoCredenciais);
        
        $this->auth = $factory->createAuth();
    }

    public function verificarToken($idToken) {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken->claims()->get('sub');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function loginWithEmail($email, $password) {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            
            return $signInResult->idToken(); 
        } catch (UserNotFound | InvalidPassword $e) {
            return false; 
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deletarUsuario($uid) {
        try {
            $this->auth->deleteUser($uid);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}