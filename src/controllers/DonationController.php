<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DonationController {

    public function mostrarHome() {
        require_once __DIR__ . '/../Views/Home.php';
    }

    public function mostrarFormulario() {
        require_once __DIR__ . '/../Views/FazerDoacao.php';
    }

    public function registrarDoacao() {
        $id_doador = $_SESSION['id_usuario'] ?? null;

        if (!$id_doador) {
            http_response_code(401);
            echo json_encode(["erro" => "Login necessário"]);
            return;
        }

        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

    
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);

        $payload = [
            'valor'          => $dados['valor'], 
            'mensagem'       => $dados['mensagem'] ?? null,
            'id_instituicao' => $dados['id_instituicao'] ?? 1,
            'id_doador'      => $id_doador
        ];

        $id_gerado = $model->salvar($payload);

        if ($id_gerado) {
            http_response_code(201);
            echo json_encode(["id_doacao" => $id_gerado]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco"]);
        }
        exit;
    }

    public function mostrarPagamento() {
        require_once __DIR__ . '/../Views/Pagamento.php';
    }

    public function apiDetalhesPagamento() {
        $id_doacao = $_GET['id'] ?? null;
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);
        $dados = $model->buscarDadosPagamento($id_doacao);
    
        header('Content-Type: application/json');
        echo json_encode($dados);
        exit;
    }

    public function confirmarDoacao() {
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);

        if ($model->confirmarPagamento($dados['id_doacao'])) {
            echo json_encode(["sucesso" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao confirmar"]);
        }        
    }

    public function mostrarSucesso() {
        require_once __DIR__ . '/../Views/Obrigado.php';
    }

    public function listarOscsApi() {
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);
        $oscs = $model->listarOscs(); 
        header('Content-Type: application/json');
        echo json_encode($oscs);
        exit;
    }
}