<?php

namespace App\Controllers;

use App\Models\BuscaModel;

class BuscarController {

    // Abre a sua página de busca
    public function mostrarBusca() {
        // Caminho corrigido: sai de Controllers, entra em Views
        require_once __DIR__ . '/../Views/buscar_categoria.php';
    }

    // Lógica da API para filtrar
    public function filtrarPorCategoria() {
        $categoria = $_GET['cat'] ?? '';
        
        // Caminho para a conexão com o banco
        $conn = require __DIR__ . '/../../config/database.php';
        
        // Carrega o SEU model exclusivo para não mexer no do Felipe
        require_once __DIR__ . '/../Models/BuscaModel.php';
        
        $model = new BuscaModel($conn);
        $resultados = $model->filtrar($categoria);
        
        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit;
    }
}