<?php

namespace App\Controllers;

use App\Models\NotificacaoModel;

class CaixaEntradaController {
    
    public function index() {
        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();
        require '../src/Views/caixa_entrada.php';
    }

    public function listarAjax() {
        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();
        $conn = require_once __DIR__ . '/../../config/database.php';
        
        $model = new NotificacaoModel($conn);
        $notificacoes = $model->buscarPorInstituicao($_SESSION['id_instituicao']);
        
        echo json_encode(['sucesso' => true, 'dados' => $notificacoes]);
    }

    public function lerAjax() {
        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();
        $dados = json_decode(file_get_contents("php://input"), true);
        
        if (isset($dados['id_notificacao'])) {
            $conn = require_once __DIR__ . '/../../config/database.php';
            $model = new NotificacaoModel($conn);
            $model->marcarComoLida($dados['id_notificacao'], $_SESSION['id_instituicao']);
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false, 'erro' => 'ID não informado']);
        }
    }
}