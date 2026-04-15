<?php

class DonationController {
    // Abre a página de pagamento (QR Code de teste)
    public function mostrarPagamento() {
        require_once __DIR__ . '/../Views/Pagamento.php';
    }

    // Abre o formulário inicial de doação
    public function mostrarFormulario() {
        require_once __DIR__ . '/../Views/FazerDoacao.php';
    }

    // Recebe os dados da primeira tela e salva no banco como "pendente"
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

    // Recebe o ID e muda o status para "pagamento efetuado"
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

    // Abre a tela final de agradecimento
    public function mostrarSucesso() {
        require_once __DIR__ . '/../Views/Obrigado.php';
    }
}