<?php

namespace App\Controllers;
use App\Models\GetDonationModel;
use PDOException;

class DonationRelatorioController {
    
    public function getDoacoes() {

        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();

        $idLogado = $_SESSION['id_instituicao'];
        $dataInicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $dataFim = $_GET['fim'] ?? date('Y-m-d');

        try {
            $conn = require_once __DIR__ . '/../../config/database.php';
            $model = new GetDonationModel($conn);
            $dados = $model->getDonationsSum($dataInicio, $dataFim, $idLogado);
            require '../src/Views/Relatorio.php';
        }
        catch (PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            die("Erro ao conectar ao banco de dados com segurança.");
        }
    }
}