<?php
// src/Controllers/OscController.php

class OscController {

    // Método responsável por mostrar a tela de cadastro
    public function mostrarFormulario() {
        // Aqui dentro chamamos a view!
        require_once __DIR__ . '/../views/cadastro_osc.html';
    }

    // O nosso receptor de dados!
    public function cadastrar() {
        $jsonRecebido = file_get_contents('php://input');

        $dados = json_decode($jsonRecebido, true);

        if (!$dados) {
            http_response_code(400); // Bad Request
            echo json_encode(["erro" => "Nenhum dado válido recebido."]);
            return;
        }

        $conn = require_once __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/OscModel.php';

        $model = new OscModel($conn);
        $salvouComSucesso = $model->salvar($dados);

        if ($salvouComSucesso) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Instituição cadastrada com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco de dados. Verifique os dados e tente novamente."]);
    }

}

}