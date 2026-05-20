<?php

namespace App\Models;

use PDO;

class BuscaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function filtrar($categoria) {
        // Consulta na tabela correta 'instituicao'
        $sql = "SELECT id_instituicao, nome_instituicao, descricao 
                FROM instituicao 
                WHERE categoria = :categoria";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}