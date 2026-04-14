<?php
namespace App\Models;

use PDO;

class RelatorioModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function salvarRelatorioCompleto($instituicaoId, $total, $itens) {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO relatorio (instituicao_id, total, data_pub) 
                    VALUES (:id, :total, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $instituicaoId, ':total' => $total]);

            $relatorioId = $this->pdo->lastInsertId();

            $sqlItem = "INSERT INTO relatorio_itens (relatorio_id, categoria, valor) 
                        VALUES (:rel_id, :cat, :val)";
            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($itens as $item) {
                $stmtItem->execute([
                    ':rel_id' => $relatorioId,
                    ':cat'    => $item['categoria'],
                    ':val'    => $item['valor']
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            error_log("Erro no Model Relatorio: " . $e->getMessage());
            throw $e;
        }
    }
}