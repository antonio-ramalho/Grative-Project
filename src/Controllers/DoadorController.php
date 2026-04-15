<?php
// src/Controllers/DoadorController.php

class DoadorController {
    public function mostrarFormulario() {
        require_once __DIR__ . '/../views/cadastroDoador.html';
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
        require_once __DIR__ . '/../Models/DoadorModel.php';

        $model = new DoadorModel($conn);
        $salvouComSucesso = $model->salvar($dados);

        if ($salvouComSucesso) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Doador cadastrado com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco de dados. Verifique os dados e tente novamente."]);
        }
    }
}