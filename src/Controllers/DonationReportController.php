<?php
class DonationReportController {
    public function index() {

        $dataInicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $dataFim = $_GET['fim'] ?? date('Y-m-d');

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => true,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ];

        try {
            $pdo = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
                $options
            );

            $model = new DonationModel($pdo);
            $dados = $model->getDonationsSum($dataInicio, $dataFim);

            require '../src/Views/reports/donations.php';
        }
        catch (PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            die("Erro ao conectar ao banco de dados com segurança.");
        }
    }
}