<?php

namespace App\Controllers;

use App\Models\ProximidadeModel;

class ProximidadeController {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idDoador = $_SESSION['id_usuario'] ?? ($_GET['id'] ?? null);
        $doador = null;

        if ($idDoador) {
            $conn = require __DIR__ . '/../../config/database.php';
            $stmt = $conn->prepare("SELECT latitude, longitude FROM usuario WHERE id_usuario = :id");
            $stmt->bindValue(':id', $idDoador);
            $stmt->execute();
            $doador = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        include __DIR__ . '/../views/buscar_proximidade.php'; 
    }

    public function buscar() {
       
        header('Content-Type: application/json');

        $lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
        $lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

        if (!$lat || !$lng) {
            http_response_code(400);
            echo json_encode(["error" => "Latitude e Longitude não fornecidas pelo navegador."]);
            return;
        }

        $conn = require __DIR__ . '/../../config/database.php';

        $model = new ProximidadeModel($conn);
        
        $resultados = $model->buscarOscsProximas($lat, $lng, 150);

        echo json_encode($resultados);
    }
}