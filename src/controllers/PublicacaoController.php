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
            $endereco_img = $imagem['tmp_name'];
        }

        $dados_publicacao = ['titulo' => $titulo, 'descricao' => $descricao, 'id_instituicao' => $this->id_osc, 'imagem_url' => null, 'data_publicacao' => date('Y-m-d H:i:s')];

        if($endereco_img != null){
            $url_final = $this->PublicacaoModel -> fazerUpload($endereco_img);
            $dados_publicacao['imagem_url'] = $url_final;
        }

        $resultado = $this->PublicacaoModel->salvarNoBanco($dados_publicacao);
        echo json_encode(['sucesso' => true]);
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
        $cacheNomes = [];

        require_once __DIR__ . '/../../config/database.php';
        $osc_model = new \App\Models\OscModel($conn);

        foreach ($lista_publicacoes as $publicacao) {
            $id_instituicao = $publicacao['id_instituicao'] ?? null;

            $nome_osc = 'Instituição Desconhecida';

            if ($id_instituicao) {
                if (array_key_exists($id_instituicao, $cacheNomes)) {
                    $nome_osc = $cacheNomes[$id_instituicao];
                } else {
                    $resultadoBanco = $osc_model->buscarPorId($id_instituicao);

                    if ($resultadoBanco && isset($resultadoBanco['nome_instituicao'])){
                        $nome_osc = $resultadoBanco['nome_instituicao'];

                        $cacheNomes[$id_instituicao] = $nome_osc;
                    }

                }
            }

            $publicacao['nome_osc'] = $nome_osc;
            $lista_final[] = $publicacao;
        }

        header('Content-Type: application/json');
        echo json_encode($lista_final);
    }
}