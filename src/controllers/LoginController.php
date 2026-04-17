<?php
namespace App\Controllers;

use App\Models\OscModel; 
use App\Models\DoadorModel;

class LoginController {
    
    public function index() {
        require_once __DIR__ . '/../views/login.html';
    }

    // Agora recebemos a requisição via JSON (API) do JavaScript
    public function authenticateApi() {
        $jsonRecebido = file_get_contents('php://input');
        $dados = json_decode($jsonRecebido, true);

        $firebaseUid = $dados['uid'] ?? null;

        if (!$firebaseUid) {
            http_response_code(400);
            echo json_encode(["erro" => "Falha na comunicação. UID não recebido."]);
            return;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        
        $oscModel = new OscModel($conn); 
        $doadorModel = new DoadorModel($conn);

        // 1. Procura na tabela de Instituição
        $idInstituicao = $oscModel->buscarIdPorFirebaseUid($firebaseUid);
        
        if ($idInstituicao) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['logged_in'] = true;
            $_SESSION['id_instituicao'] = $idInstituicao;

            echo json_encode(["sucesso" => true, "redirect" => "/home_osc?id=" . $idInstituicao]);
            return;
        }

        // 2. Se não achou, procura na tabela de Doador
        $idDoador = $doadorModel->buscarIdPorFirebaseUid($firebaseUid);

        if ($idDoador) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['logged_in'] = true;
            $_SESSION['id_usuario'] = $idDoador;

            echo json_encode(["sucesso" => true, "redirect" => "/home_doador?id=" . $idDoador]);
            return;
        }

        // 3. Se não achou em nenhum, retorna erro
        http_response_code(404);
        echo json_encode(["erro" => "Usuário não encontrado no banco de dados da Grative."]);
    }

    public function logout() {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: /login?msg=logout_success");
        exit;
    }
}