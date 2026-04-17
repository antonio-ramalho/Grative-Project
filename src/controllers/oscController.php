<?php

namespace App\Controllers;

use App\Models\OscModel;
use App\Services\FirebaseService;

class OscController {

    public function mostrarFormulario() {
        require_once __DIR__ . '/../views/cadastro_osc.html';
    }

    public function mostrarHomeOsc() {
        require_once __DIR__ . '/../views/home_osc.html';
    }

    public function cadastrar() {
        $jsonRecebido = file_get_contents('php://input');

        $dados = json_decode($jsonRecebido, true);

        if (!$dados) {
            http_response_code(400);
            echo json_encode(["erro" => "Nenhum dado válido recebido."]);
            return;
        }

        $conn = require_once __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/OscModel.php';

        $model = new OscModel($conn);
        $idrecebido = $model->salvar($dados);


        if ($idrecebido) {
            session_start();
            $_SESSION['id_instituicao'] = $idrecebido; 
            $_SESSION['logged_in'] = true;

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Instituição cadastrada com sucesso!",
                "id" => $idrecebido
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco de dados. Verifique os dados e tente novamente."]);
    }

}

public function atualizar() {
        session_start();
        $id = $_SESSION['id_instituicao'] ?? $_SESSION['id_osc_logada'] ?? null;
        
        $conn = require __DIR__ . '/../../config/database.php';
        $oscModel = new OscModel($conn);
        
        $dados = $_POST; 
        
        if ($oscModel->editar($id, $dados)) {
            header("Location: /home_osc?status=updated");
        } else {
            header("Location: /editar_osc?error=update_failed");
        }
        exit;
    }

    
    
    public function excluir() {
        session_start();
        $id = $_SESSION['id_instituicao'];

        $conn = require __DIR__ . '/../../config/database.php';
        $oscModel = new OscModel($conn);

        $firebaseUid = $oscModel->buscarFirebaseUid($id);

        if ($oscModel->excluir($id)) {
            
            if ($firebaseUid) {
                $firebaseService = new FirebaseService(); 
                $firebaseService->deletarUsuario($firebaseUid);
            }

            session_destroy();
            header("Location: /login?msg=account_deleted");
        } else {
            header("Location: /home_osc?error=delete_failed");
        }
        exit;
    }

// Método para exibir a tela de edição
    public function mostrarFormularioEdicao() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verifica se o usuário está logado
        if (!isset($_SESSION['id_instituicao'])) {
            header("Location: /login");
            exit;
        }

        $id = $_SESSION['id_instituicao'];

        $conn = require __DIR__ . '/../../config/database.php';
        $oscModel = new OscModel($conn);
        
        // Busca os dados da instituição logada
        $instituicao = $oscModel->buscarPorId($id);

        if (!$instituicao) {
            echo "Erro: Instituição não encontrada.";
            exit;
        }


        require_once __DIR__ . '/../views/editar_osc.php';
    }
}