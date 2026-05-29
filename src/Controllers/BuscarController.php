<?php

namespace App\Controllers;

use App\Models\BuscaModel;

class BuscarController {

    
    public function mostrarBusca() {
        
        require_once __DIR__ . '/../Views/buscar_categoria.php';
    }

   
    public function filtrarPorCategoria() {
        $categoria = $_GET['cat'] ?? '';
        
        
        $conn = require __DIR__ . '/../../config/database.php';
        
        
        require_once __DIR__ . '/../Models/BuscaModel.php';
        
        $model = new BuscaModel($conn);
        $resultados = $model->filtrar($categoria);
        
        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit;
    }
}