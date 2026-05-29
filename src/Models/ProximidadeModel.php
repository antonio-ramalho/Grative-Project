<?php

namespace App\Models;

use PDO;

class ProximidadeModel {
    private $db;

    public function __construct($conexaoBanco) {
        $this->db = $conexaoBanco;
    }

    public function buscarOscsProximas($userLat, $userLng, $raioMaximo = 50) {
        $sql = "SELECT id_instituicao, nome_instituicao, descricao, cidade, estado, latitude, longitude,
                (6371 * acos(
                    cos(radians(:userLat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:userLng)) + 
                    sin(radians(:userLat)) * sin(radians(latitude))
                )) AS distancia 
                FROM instituicao 
                HAVING distancia < :raioMaximo 
                ORDER BY distancia ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':userLat', $userLat);
        $stmt->bindValue(':userLng', $userLng);
        
        // Garante que o banco trate o raio corretamente como número inteiro
        $stmt->bindParam(':raioMaximo', $raioMaximo, PDO::PARAM_INT);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}