<?php
class DonationModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getDonationsSum(string $start, string $end) {

        $sql = "SELECT SUM(quantia) as total, COUNT(*) as quantidade 
                FROM doacao 
                WHERE data_doacao BETWEEN :start AND :end";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("Erro na consulta de doações: " . $e->getMessage());
            return ['total' => 0, 'quantidade' => 0];
        }
    }
}