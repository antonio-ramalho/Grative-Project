<?php

namespace App\Controllers;

// Importa o seu model isolado
use App\Models\ProximidadeModel;

class ProximidadeController {
    
    /**
     * Renderiza a página visual (View) da busca por localização
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Pega o id do doador logado na sessão ou via parâmetro GET
        $idDoador = $_SESSION['id_usuario'] ?? ($_GET['id'] ?? null);
        $doador = null;

        if ($idDoador) {
            $conn = require __DIR__ . '/../../config/database.php';
            $stmt = $conn->prepare("SELECT latitude, longitude FROM usuario WHERE id_usuario = :id");
            $stmt->bindValue(':id', $idDoador);
            $stmt->execute();
            $doador = $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        // Carrega o seu arquivo PHP de visualização injetando a variável $doador configurada
        include __DIR__ . '/../views/buscar_proximidade.php'; 
    }

    /**
     * Rota de API que processa a requisição AJAX do JavaScript
     * Calcula as distâncias e retorna o JSON com as instituições próximas
     */
    public function buscar() {
        // Define que o retorno desta rota sempre será um JSON estruturado
        header('Content-Type: application/json');

        // Captura os parâmetros enviados na URL pelo Fetch do JavaScript
        $lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
        $lng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

        // Validação básica caso os parâmetros não cheguem corretamente
        if (!$lat || !$lng) {
            http_response_code(400);
            echo json_encode(["error" => "Latitude e Longitude não fornecidas pelo navegador."]);
            return;
        }

        // 1. Puxa o arquivo de configuração e armazena a conexão ativa na variável $conn
        $conn = require __DIR__ . '/../../config/database.php';

        // 2. Instancia o seu Model passando a conexão $conn por parâmetro no construtor
        $model = new ProximidadeModel($conn);
        
        // 3. Executa a busca no banco de dados num raio limite de 50km
        $resultados = $model->buscarOscsProximas($lat, $lng, 50);

        // 4. Devolve o array de resultados convertido em JSON para o Frontend ler
        echo json_encode($resultados);
    }
}