<?php
namespace App\Controllers;
use App\Models\RelatorioModel;

class RelatorioController {
    public function publicar() {

        $idLogado = $_SESSION['id_instituicao'];

        try {
            $input = file_get_contents('php://input');
            $dados = json_decode($input, true);

            if (!$dados || empty($dados['itens'])) {
                echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
                return;
            }

            $totalGastos = array_sum(array_column($dados['itens'], 'valor'));
            if ($totalGastos > $dados['saldo_total']) {
                echo json_encode(['success' => false, 'message' => 'Saldo insuficiente detectado no servidor.']);
                return;
            }

            $conn = require_once __DIR__ . '/../../config/database.php';

            $model = new RelatorioModel($conn);
            $sucesso = $model->salvarRelatorioCompleto(
                $idLogado,
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