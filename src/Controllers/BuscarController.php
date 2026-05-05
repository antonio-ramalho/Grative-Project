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
    // Lógica da API para filtrar
    public function filtrarPorCategoria() {
        // CORREÇÃO: Troquei 'cat' por 'categoria' para bater com o seu JS
        $categoria = $_GET['categoria'] ?? ''; 
        
        try {
            // Caminho para a conexão com o banco
            $conn = require __DIR__ . '/../../config/database.php';
            
            // Carrega o SEU model
            require_once __DIR__ . '/../Models/BuscaModel.php';
            
            $model = new BuscaModel($conn);
            $resultados = $model->filtrar($categoria);
            
            header('Content-Type: application/json');
            echo json_encode($resultados);
            
        } catch (\Exception $e) {
            // Se der erro, avisa o que aconteceu (ajuda no debug)
            header('Content-Type: application/json', true, 500);
            echo json_encode(['erro' => $e->getMessage()]);
        }
        exit;
    }
}