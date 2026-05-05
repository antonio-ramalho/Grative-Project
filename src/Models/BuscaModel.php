<?php

namespace App\Models;

use PDO;

class BuscaModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function filtrar($categoria) {
        // Usamos LIKE para ele encontrar a palavra mesmo se o acento vier diferente
        $sql = "SELECT id_instituicao, nome_instituicao, descricao FROM instituicao WHERE categoria LIKE :categoria";
        
        $stmt = $this->conn->prepare($sql);
        // O trim() limpa espaços e os % ajudam a achar a palavra dentro do texto
        $termo = "%" . trim($categoria) . "%"; 
        $stmt->bindParam(':categoria', $termo);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}