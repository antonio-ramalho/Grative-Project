<?php

namespace App\Models;
use Kreait\Firebase\Factory;

class PublicacaoModel {

    private $firestore;

    public function __construct() {
        $caminhoChave = realpath(__DIR__ . '/../../config/firebase_credentials.json');

        if (!$caminhoChave || !file_exists($caminhoChave)) {
            throw new \Exception("Erro Crítico: Arquivo firebase_credentials.json não encontrado no caminho resolvido.");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $caminhoChave);

        $factory = (new Factory)->withServiceAccount($caminhoChave);

        $this->firestore = $factory->createFirestore();
        
    }
    
    public function fazerUpload($endereco_img, $extensao) {
        $nome_unico = uniqid() . "." . $extensao;
        $caminho = __DIR__ . '/../../public/img/uploads/publicacoes/' . $nome_unico;
        
        if(move_uploaded_file($endereco_img, $caminho)){
            return '/img/uploads/publicacoes/'.$nome_unico;
        }

        return null;
    }

    public function salvarNoBanco($dados_publicacao) {
        $colecao = $this->firestore->database()->collection('publicacoes');
        $colecao->add($dados_publicacao);

        return true;
    }

    public function listarPorOsc($id_osc) {
        $lista_publicacoes = [];

        $colecao = $this->firestore->database()->collection('publicacoes');
        $envelopes = $colecao->where('id_instituicao', '=', $id_osc)->documents();

        foreach ($envelopes as $envelope) {
            if ($envelope->exists()) {
                
                $dados_da_carta = $envelope->data();
                $codigo_rastreio = $envelope->id();
                $dados_da_carta['id'] = $codigo_rastreio;
                $lista_publicacoes[] = $dados_da_carta;
            }
        }
        return $lista_publicacoes;
    }

    public function deletarPublicacao($id_documento){
        $referencia_doc = $this->firestore->database()->collection('publicacoes')->document($id_documento);

        $snapshot = $referencia_doc->snapshot();

        if ($snapshot->exists()){
            $dados = $snapshot->data();

            if (isset($dados['imagem_url']) && !empty($dados['imagem_url'])){
                $url_imagem = $dados['imagem_url'];
                $caminho_imagem = __DIR__ . '/../../public' . $url_imagem;
                if(file_exists($caminho_imagem)){
                    unlink($caminho_imagem);
                }
            }
        }

        $referencia_doc->delete();

        return true;
    }

    public function listarFeedGlobal(){
        $lista_publicacoes = [];
        $colecao = $this->firestore->database()->collection('publicacoes');
        $envelopes = $colecao->documents();

        foreach ($envelopes as $envelope) {
            if ($envelope->exists()) {
                
                $dados_da_carta = $envelope->data();
                $codigo_rastreio = $envelope->id();
                $dados_da_carta['id'] = $codigo_rastreio;
                $lista_publicacoes[] = $dados_da_carta;
            }
        }
        return $lista_publicacoes;
    }
}