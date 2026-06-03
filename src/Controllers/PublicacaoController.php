<?php

namespace App\Controllers;

class PublicacaoController {
    private $id_osc;
    private $PublicacaoModel;

    public function __construct(){
        $this->PublicacaoModel = new \App\Models\PublicacaoModel();
    }

    public function mostrarFeedOsc() {
        require_once __DIR__ . '/../views/feedOsc.html';
        require_once __DIR__ . '/../Helpers/VerificarSessao.php';
        $this->id_osc = verificarSessao();
    }

    public function fazerPublicacao() {
        require_once __DIR__ . '/../Helpers/VerificarSessao.php';
        $this->id_osc = verificarSessao();

        $titulo = $_POST['titulo'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $imagem = $_FILES['imagem'] ?? null;
        $endereco_img = null;
        $dados_publicacao = array();

        if(empty($titulo) || empty($descricao)){
            http_response_code(400);
            echo json_encode(['erro' => 'O título e a descrição são obrigatórios!']);
            return;
        }

        if(isset($imagem) && $imagem['error'] == 0 ){
            $nome_original = $imagem['name'];
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
            $extensoes_permitidas = ['png','jpg','jpeg'];
            if(!in_array($extensao, $extensoes_permitidas)){
                http_response_code(400);
                echo json_encode(['erro' => 'Formato de imagem inválido. Use apenas PNG ou JPG.']);
                return;
            }
            $endereco_img = $imagem['tmp_name'];
        }

        $dados_publicacao = [
            'titulo' => $titulo, 
            'descricao' => $descricao, 
            'id_instituicao' => $this->id_osc, 
            'imagem_url' => null, 
            'data_publicacao' => date('Y-m-d H:i:s'),
            'curtidas' => 0
        ];

        if($endereco_img != null){
            $url_final = $this->PublicacaoModel -> fazerUpload($endereco_img, $extensao);
            if (empty($url_final)){
                http_response_code(500);
                echo json_encode(['erro' => 'Falha ao tentar gravar a imagem no servidor.']);
                return;
            }
            $dados_publicacao['imagem_url'] = $url_final;
        }

        $resultado = $this->PublicacaoModel->salvarNoBanco($dados_publicacao);
        echo json_encode(['sucesso' => true]);
    }

    public function curtir() {
        header('Content-Type: application/json');
        
        // Garante que a sessão está rodando para pegarmos o ID do doador
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Pega o ID do usuário logado
        $id_usuario = $_SESSION['id_usuario'] ?? null;

        if (!$id_usuario) {
            http_response_code(401);
            echo json_encode(['erro' => 'Você precisa estar logado para curtir.']);
            return;
        }

        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $id_publicacao = $dadosRecebidos['id_publicacao'] ?? null;

        if (empty($id_publicacao)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID da publicação é obrigatório']);
            return;
        }

        // Chama a função nova passando também o ID do usuário
        $nova_quantidade = $this->PublicacaoModel->alternarCurtida($id_publicacao, $id_usuario);

        if ($nova_quantidade !== false) {
            echo json_encode(['sucesso' => true, 'curtidas' => $nova_quantidade]);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao processar a curtida no banco.']);
        }
    }

    public function listarFeed(){
        require_once __DIR__ . '/../Helpers/VerificarSessao.php';
        $this->id_osc = verificarSessao();

        $listaPublicacoes = $this->PublicacaoModel->listarPorOsc($this->id_osc);
        $nome_osc = $_SESSION['nome_instituicao'] ?? 'Minha OSC';

        foreach ($listaPublicacoes as &$publicacao) {
            $publicacao['nome_osc'] = $nome_osc;
        }

        header('Content-Type: application/json');
        echo json_encode($listaPublicacoes);
    }

    public function excluirPublicacao(){
        require_once __DIR__ . '/../Helpers/VerificarSessao.php';
        $this->id_osc = verificarSessao();

        $id_publicacao = $_POST['id_documento'] ?? null;

        if(empty($id_publicacao) || $id_publicacao == 'undefined'){
            http_response_code(400);
            echo json_encode(['erro' => 'O documento não foi encontrado']);
            return;
        }

        $resultado = $this->PublicacaoModel->deletarPublicacao($id_publicacao);

        if ($resultado) {
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno ao tentar excluir.']);
        }
    }

    public function listarFeedGlobal(){
        $lista_publicacoes = [];

        $PublicacaoModel = new \App\Models\PublicacaoModel();
        $lista_publicacoes = $PublicacaoModel->listarFeedGlobal();

        $lista_final = [];
        $cacheOscs = [];

        require_once __DIR__ . '/../../config/database.php';
        $osc_model = new \App\Models\OscModel($conn);

        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $id_usuario_logado = $_SESSION['id_usuario'] ?? null;

        foreach ($lista_publicacoes as $publicacao) {
            $id_instituicao = $publicacao['id_instituicao'] ?? null;

            $nome_osc = 'Instituição Desconhecida';
            $trust_score = '0.0';

            if ($id_instituicao) {
                if (array_key_exists($id_instituicao, $cacheOscs)) {
                    $nome_osc = $cacheOscs[$id_instituicao]['nome'];
                    $trust_score = $cacheOscs[$id_instituicao]['score'];
                } else {
                    $resultadoBanco = $osc_model->buscarPorId($id_instituicao);

                    if ($resultadoBanco && isset($resultadoBanco['nome_instituicao'])){
                        $nome_osc = $resultadoBanco['nome_instituicao'];
                        
                        $trust_score = isset($resultadoBanco['trust_score']) ? number_format((float)$resultadoBanco['trust_score'], 1, '.', '') : '0.0';

                        $cacheOscs[$id_instituicao] = [
                            'nome' => $nome_osc,
                            'score' => $trust_score
                        ];
                    }
                }
            }

            // Atribui os dados da OSC
            $publicacao['nome_osc'] = $nome_osc;
            $publicacao['trust_score'] = $trust_score; 

            // Garante que curtidas inicie em 0 se não existir
            $publicacao['curtidas'] = isset($publicacao['curtidas']) ? (int)$publicacao['curtidas'] : 0;
            
            // Verifica se o usuário atual curtiu
            $lista_curtidas = isset($publicacao['usuarios_que_curtiram']) ? $publicacao['usuarios_que_curtiram'] : [];
            $publicacao['usuario_curtiu'] = in_array($id_usuario_logado, $lista_curtidas);
            
            // Adiciona na lista final limpa, sem duplicatas
            $lista_final[] = $publicacao;
        }

        // Ordena a lista de forma decrescente (maior engajamento primeiro)
        usort($lista_final, function($a, $b) {
            return $b['curtidas'] <=> $a['curtidas'];
        });

        header('Content-Type: application/json');
        echo json_encode($lista_final);
    }

    public function adicionarComentario()
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Pega quem estiver logado
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $id_instituicao = $_SESSION['id_instituicao'] ?? null;

        if (!$id_usuario && !$id_instituicao) {
            http_response_code(401);
            echo json_encode(['erro' => 'Você precisa estar logado para comentar.']);
            exit;
        }

        $dadosRecebidos = json_decode(file_get_contents('php://input'), true);
        $postId = intval($dadosRecebidos['id_publicacao'] ?? 0);
        $comment = trim($dadosRecebidos['comentario'] ?? '');
        $idOscDonaDoPost = intval($dadosRecebidos['id_instituicao_dona'] ?? 0);

        if ($postId <= 0 || $comment === '') {
            http_response_code(400);
            echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos ou comentário vazio.']);
            exit;
        }

        try {
            $conn = require __DIR__ . '/../../config/database.php';
            require_once __DIR__ . '/../Models/Comment.php';
            
            $commentModel = new \App\Models\Comment($conn);
            
            $commentModel->create([
                'post_id' => $postId,
                'comment' => htmlspecialchars($comment, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                'usuario_id' => $id_usuario,
                'instituicao_id' => $id_instituicao
            ]);

            if ($id_usuario && $idOscDonaDoPost > 0) {
                require_once __DIR__ . '/../Models/NotificacaoModel.php';
                $notificacaoModel = new \App\Models\NotificacaoModel($conn);
                $mensagem = "Tem um novo comentário na sua publicação!";
                $link = "/feedOsc/" . $postId; 
                $notificacaoModel->criarNotificacao($idOscDonaDoPost, $mensagem, $link);
            }

            echo json_encode(['sucesso' => true]);
            exit;

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Erro no servidor: ' . $e->getMessage()]);
            exit;
        }
    }

    public function listarComentarios() {
        $id_publicacao = $_GET['id_publicacao'] ?? null;

        if (empty($id_publicacao)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID da publicação é obrigatório']);
            return;
        }
       
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $id_logado_doador = $_SESSION['id_usuario'] ?? null;
        $id_logado_osc = $_SESSION['id_instituicao'] ?? null;

        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/Comment.php';
        
        $commentModel = new \App\Models\Comment($conn);
        $comentarios = $commentModel->getByPostId($id_publicacao);

        // Verifica de quem é o comentário para desenhar a lixeira
        foreach ($comentarios as &$c) {
            $c['pode_deletar'] = false;
            if ($id_logado_doador && $c['usuario_id'] == $id_logado_doador) {
                $c['pode_deletar'] = true;
            } elseif ($id_logado_osc && $c['instituicao_id'] == $id_logado_osc) {
                $c['pode_deletar'] = true;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($comentarios);
    }

    public function deletarComentario() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $id_instituicao = $_SESSION['id_instituicao'] ?? null;

        if (!$id_usuario && !$id_instituicao) {
            http_response_code(401);
            echo json_encode(['erro' => 'Sua sessão expirou ou você não está logado.']);
            exit;
        }

        $json = file_get_contents('php://input');
        $dados = json_decode($json, true);
        $id_comentario = $dados['id_comentario'] ?? null;

        if (empty($id_comentario)) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID do comentário é obrigatório']);
            exit;
        }

        $conn = require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/Comment.php';
        
        $commentModel = new \App\Models\Comment($conn);
        $comentario = $commentModel->getById($id_comentario);

        if (!$comentario) {
            http_response_code(404);
            echo json_encode(['erro' => 'Comentário não encontrado']);
            exit;
        }

        // Valida se quem está tentando excluir é realmente o autor
        $autorizado = false;
        if ($id_usuario && $comentario['usuario_id'] == $id_usuario) {
            $autorizado = true;
        } elseif ($id_instituicao && $comentario['instituicao_id'] == $id_instituicao) {
            $autorizado = true;
        }

        if (!$autorizado) {
            http_response_code(403);
            echo json_encode(['erro' => 'Não autorizado. Você só pode excluir seus próprios comentários.']);
            exit;
        }
        
        $resultado = $commentModel->delete($id_comentario);

        if ($resultado) {
            echo json_encode(['sucesso' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro interno ao tentar excluir o comentário.']);
        }
        exit;
    }
}