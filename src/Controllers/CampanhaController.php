<?php
namespace App\Controllers;

class CampanhaController {
    
    public function criarCampanha() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_instituicao'])) {
            http_response_code(401);
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada. Faça login novamente.']);
            return;
        }

        $id_osc = $_SESSION['id_instituicao'];
        
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $objetivos = $_POST['objetivos'] ?? '';
        $meta_financeira = floatval($_POST['meta_financeira'] ?? 0);
        $data_encerramento = $_POST['data_encerramento'] ?? '';
    
        $imagem = $_FILES['imagem'] ?? null;

        if (empty($objetivos) || $meta_financeira <= 0) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erro' => 'Metas e objetivos são obrigatórios.']);
            return;
        }

        require_once __DIR__ . '/../Models/CampanhaModel.php';
        $campanhaModel = new \App\Models\CampanhaModel();
        
        $url_imagem = $campanhaModel->fazerUpload($imagem);

        if (!$url_imagem) {
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Falha ao fazer upload da imagem. Formato inválido ou permissão negada.']);
            return;
        }

        $dadosCampanha = [
            'id_instituicao' => $id_osc,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'objetivos' => $objetivos,
            'meta_financeira' => $meta_financeira,
            'arrecadado_atual' => 0.00,
            'data_encerramento' => $data_encerramento,
            'imagem_url' => $url_imagem, 
            'status' => 'ativa',
            'data_criacao' => date('Y-m-d H:i:s')
        ];
        
        if ($campanhaModel->salvarNoFirebase($dadosCampanha)) {
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Falha ao salvar a campanha no banco de dados.']);
        }
    }

    public function listarCampanhasOsc() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_instituicao'])) {
            http_response_code(401);
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        $id_osc = $_SESSION['id_instituicao'];

        require_once __DIR__ . '/../Models/CampanhaModel.php';
        $campanhaModel = new \App\Models\CampanhaModel();

        $campanhas = $campanhaModel->listarPorOsc($id_osc);

        header('Content-Type: application/json');
        echo json_encode($campanhas);
    }

    public function interromperCampanha() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_instituicao'])) {
            http_response_code(401);
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada. Faça login novamente.']);
            return;
        }

        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $id_campanha = $dadosRecebidos['id_campanha'] ?? null;

        if (empty($id_campanha)) {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erro' => 'ID da campanha não fornecido.']);
            return;
        }

        require_once __DIR__ . '/../Models/CampanhaModel.php';
        $campanhaModel = new \App\Models\CampanhaModel();
        
        if ($campanhaModel->interromperCampanha($id_campanha)) {
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Campanha não encontrada ou falha ao atualizar.']);
        }
    }

    public function listarCampanhasDestaque() {
        require_once __DIR__ . '/../Models/CampanhaModel.php';
        $campanhaModel = new \App\Models\CampanhaModel();
        
        $campanhas = $campanhaModel->listarCampanhasAtivas(3);
        
        header('Content-Type: application/json');
        echo json_encode($campanhas);
    }
}