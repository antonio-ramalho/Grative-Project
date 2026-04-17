<?php
namespace App\Controllers;

use App\Models\DoadorModel;
use App\Services\FirebaseService; // Importe caso vá deletar o Auth também

class DoadorController {
    
    public function mostrarFormulario() {
        require_once __DIR__ . '/../views/cadastroDoador.html';
    }

    public function cadastrar() {
        $jsonRecebido = file_get_contents('php://input');
        $dados = json_decode($jsonRecebido, true);

        if (!$dados) {
            http_response_code(400); 
            echo json_encode(["erro" => "Nenhum dado válido recebido."]);
            return;
        }

        $conn = require_once __DIR__ . '/../../config/database.php';
        
        $model = new DoadorModel($conn);
        $idrecebido = $model->salvar($dados);

        if ($idrecebido) {
            // Cria a sessão igual ao fluxo da OSC
           if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['id_usuario'] = $idrecebido; 
            $_SESSION['logged_in'] = true;

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Usuário cadastrado com sucesso!",
                "id" => $idrecebido
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao salvar no banco de dados. Verifique os dados e tente novamente."]);
        }
    }
    // Adicione esta função dentro da classe DoadorController
    public function mostrarHomeDoador() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Proteção: Se o usuário tentar acessar a home sem logar/cadastrar, manda pro login
        if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['logged_in'])) {
            header("Location: /login");
            exit;
        }

        // Pega o ID da sessão ou da URL
        $id_doador = $_GET['id'] ?? $_SESSION['id_usuario'];

        // Carrega o seu HTML lindão da home
        require_once __DIR__ . '/../views/home_doador.html';
    }
    public function mostrarFormularioEdicao() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: /login");
            exit;
        }

        $id = $_SESSION['id_usuario'];
        $conn = require __DIR__ . '/../../config/database.php';
        $model = new DoadorModel($conn);
        
        $doador = $model->buscarPorId($id);

        if (!$doador) {
            echo "Erro: Doador não encontrado.";
            exit;
        }

        // Você precisará criar este arquivo HTML/PHP depois!
        require_once __DIR__ . '/../views/editar_doador.php'; 
    }

    public function atualizar() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        $id = $_SESSION['id_usuario'] ?? null;
        
        if (!$id) {
            header("Location: /login");
            exit;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        $model = new DoadorModel($conn);
        
        if ($model->editar($id, $_POST)) {
            header("Location: /home_doador?status=updated");
        } else {
            header("Location: /editar_doador?error=update_failed");
        }
        exit;
    }

    public function excluir() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        $id = $_SESSION['id_usuario'];

        $conn = require __DIR__ . '/../../config/database.php';
        $model = new DoadorModel($conn);

        // Removemos a busca do firebaseUid e a chamada do FirebaseService
        if ($model->excluir($id)) {
            session_destroy();
            // Retorna JSON para o JavaScript saber que deu certo
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Falha ao deletar do banco.']);
        }
        exit;
    }
}