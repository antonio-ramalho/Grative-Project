<?php
namespace App\Controllers;
use App\Models\RelatorioModel;
use PDO;

class RelatorioController {
    public function publicar() {
        $id = 1;

        try {
            $input = file_get_contents('php://input');
            $dados = json_decode($input, true);

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_SSL_CA => true,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ];

            $pdo = new PDO(
                    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
                    $_ENV['DB_USERNAME'],
                    $_ENV['DB_PASSWORD'],
                    $options
            );

            if (!$dados || empty($dados['itens'])) {
                echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
                return;
            }

            $totalGastos = array_sum(array_column($dados['itens'], 'valor'));
            if ($totalGastos > $dados['saldo_total']) {
                echo json_encode(['success' => false, 'message' => 'Saldo insuficiente detectado no servidor.']);
                return;
            }

            $model = new RelatorioModel($pdo);
            $sucesso = $model->salvarRelatorioCompleto(
                $id,
                $dados['saldo_total'],
                $dados['itens']
            );

            echo json_encode(['success' => $sucesso]);
        }
        catch (\Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erro no Banco: ' . $e->getMessage()
            ]);
        }
    }
}