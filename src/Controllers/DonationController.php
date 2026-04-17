<?php

class DonationController {

    // 1. Carrega a casca da Home
    public function mostrarHome() {
        require_once __DIR__ . '/../Views/Home.php';
    }

    // 2. API para a Home (usada pelo home.js)
    public function listarOscsApi() {
        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        
        $model = new DonationModel($conn);
        $oscs = $model->listarOscs(); 
    
        header('Content-Type: application/json');
        echo json_encode($oscs);
        exit;
    }

    // 3. Abre o formulário de valor da doação
    public function mostrarFormulario() {
        require_once __DIR__ . '/../Views/FazerDoacao.php';
    }

    // 4. Salva a doação no banco e retorna o ID gerado
    public function registrarDoacao() {
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

        if (!$dados || empty($dados['valor'])) {
            http_response_code(400);
            echo json_encode(["erro" => "O valor da doação é obrigatório."]);
            return;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';

        $model = new DonationModel($conn);

        $payload = [
            'id_instituicao' => $dados['id_instituicao'] ?? 1,
            'id_doador'      => $dados['id_doador'] ?? 1,
            'valor'          => $dados['valor'],
            'mensagem'       => $dados['mensagem'] ?? null
        ];

        $id_gerado = $model->salvar($payload);

        if ($id_gerado) {
            http_response_code(201);
            echo json_encode([
                "mensagem" => "Doação registrada com sucesso!",
                "id_doacao" => $id_gerado
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao processar a doação no servidor."]);
        }
    }

    // 5. Carrega a casca da página de Pagamento (sem PHP no HTML)
    public function mostrarPagamento() {
        require_once __DIR__ . '/../Views/Pagamento.php';
    }

    // 6. API para a página de Pagamento (usada pelo pagamento.js)
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

    // 7. Confirma que o usuário pagou
    public function confirmarDoacao() {
        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);

        if (!isset($dados['id_doacao'])) {
            http_response_code(400);
            echo json_encode(["erro" => "ID da doação não informado."]);
            return;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);

        if ($model->confirmarPagamento($dados['id_doacao'])) {
            echo json_encode(["sucesso" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao confirmar pagamento."]);
        }        
    }

    // 8. Tela final de sucesso
    public function mostrarSucesso() {
        require_once __DIR__ . '/../Views/Obrigado.php';
    }
}