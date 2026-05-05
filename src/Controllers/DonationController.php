<?php

class DonationController {

    private function getModel() {
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        return new DonationModel($conn);
    }

    public function mostrarHome() {
        require_once __DIR__ . '/../Views/Home.php';
    }

    public function listarOscsApi() {
        $model = $this->getModel();
        $oscs = $model->listarOscs(); 
    
        header('Content-Type: application/json');
        echo json_encode($oscs);
        exit;
    }

    public function mostrarFormulario() {
        require_once __DIR__ . '/../Views/FazerDoacao.php';
    }

    public function registrarDoacao() {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
    
        $idLogado = $_SESSION['id_usuario'] ?? $dados['id_doador'] ?? null;
    
        if (!$dados || empty($dados['valor'])) {
            http_response_code(400);
            echo json_encode(["erro" => "O valor da doação é obrigatório."]);
            return;
        }
    
        if (!$idLogado) {
            http_response_code(401);
            echo json_encode(["erro" => "Usuário não identificado. Faça login."]);
            return;
        }
    
        $model = $this->getModel();
    
        $payload = [
            'id_instituicao' => $dados['id_instituicao'] ?? 1,
            'id_doador'      => $idLogado, 
            'valor'          => $dados['valor'],
            'mensagem'       => $dados['mensagem'] ?? null
        ];
    
        try {
            $id_gerado = $model->salvar($payload);
    
            if ($id_gerado) {
                http_response_code(201);
                echo json_encode([
                    "mensagem" => "Doação registrada com sucesso!",
                    "id_doacao" => $id_gerado
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro no banco: " . $e->getMessage()]);
        }
    }

    public function mostrarPagamento() {
        require_once __DIR__ . '/../Views/Pagamento.php';
    }

    public function apiDetalhesPagamento() {
        header('Content-Type: application/json');
        
        $id_doacao = $_GET['id'] ?? null;
        if (!$id_doacao) {
            echo json_encode(["erro" => "ID da doação ausente."]);
            return;
        }
        
        $model = $this->getModel();
        $dados = $model->buscarDadosPagamento($id_doacao);
    
        echo json_encode($dados);
        exit;
    }

    public function confirmarDoacao() {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

        if (!isset($dados['id_doacao'])) {
            http_response_code(400);
            echo json_encode(["erro" => "ID da doação não informado."]);
            return;
        }

        $model = $this->getModel();

        if ($model->confirmarPagamento($dados['id_doacao'])) {
            echo json_encode(["sucesso" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao confirmar pagamento."]);
        }        
    }

    public function mostrarSucesso() {
        require_once __DIR__ . '/../Views/Obrigado.php';
    }
}