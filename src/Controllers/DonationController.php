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

        // 1. Pega o ID do Usuário pela Sessão (puxa quem está logado)
        // Certifica-te que no Login tu fazes: $_SESSION['usuario_id'] = $id_vido_do_banco;
        // No DonationController.php
            $id_usuario_logado = $_SESSION['id_usuario'] ?? null;

        // 2. Pega o ID da Instituição enviado pelo JavaScript
        $id_instituicao = $dados['id_instituicao'] ?? null;

        $valor = $dados['valor'] ?? $dados['quantia'] ?? null;

        if (!$id_usuario_logado) {
            http_response_code(401);
            echo json_encode(["erro" => "Deves estar logado para doar."]);
            return;
        }

        if (!$id_instituicao || !$valor) {
            http_response_code(400);
            echo json_encode(["erro" => "Dados da doação incompletos."]);
            return;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/DonationModel.php';
        $model = new DonationModel($conn);

        $payload = [
            'id_instituicao' => $id_instituicao,
            'id_doador'      => $id_usuario_logado,
            'quantia'        => $valor,
            'mensagem'       => $dados['mensagem'] ?? null
        ];

        try {
            $id_gerado = $model->salvar($payload);
            echo json_encode(["mensagem" => "Sucesso!", "id_doacao" => $id_gerado]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => $e->getMessage()]);
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