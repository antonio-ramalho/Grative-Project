<?php
namespace App\Controllers;

use App\Services\FirebaseService;
// IMPORTANTE: Adicione o Model aqui
use App\Models\OscModel; 

class LoginController {
    
    public function index() {
        require_once __DIR__ . '/../views/login.html';
    }

    public function authenticate() {

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            header("Location: /login?error=empty_fields");
            exit;
        }

        $firebaseService = new FirebaseService();
        $token = $firebaseService->loginWithEmail($email, $password);

        if ($token) {
            $firebaseUid = $firebaseService->verificarToken($token);

            if ($firebaseUid) {
                
                $conn = require __DIR__ . '/../../config/database.php';
                
                $oscModel = new OscModel($conn); 

                $idInstituicao = $oscModel->buscarIdPorFirebaseUid($firebaseUid);
                
                if ($idInstituicao) {
                    session_start();
                    $_SESSION['user_token'] = $token;
                    $_SESSION['logged_in'] = true;
                    
                    $_SESSION['id_instituicao'] = $idInstituicao;

                    header("Location: /home_osc?id=" . $idInstituicao);
                    
                    exit;

                } else {
                    header("Location: /login?error=instituicao_nao_encontrada");
                    exit;
                }
            } else {
                header("Location: /login?error=token_invalido");
                exit;
            }

        } else {
            header("Location: /login?error=invalid_credentials");
            exit;
        }
    }

    public function logout() {
        session_start();

        $_SESSION = array();

        session_destroy();
        header("Location: /login?msg=logout_success");
        exit;
    }
}