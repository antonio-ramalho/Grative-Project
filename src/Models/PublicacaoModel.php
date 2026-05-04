<?php

namespace App\Models;
use Kreait\Firebase\Factory;

class PublicacaoModel {

    public function __construct() {
        $caminhoChave = realpath(__DIR__ . '/../../config/firebase_credentials.json');

        if (!$caminhoChave || !file_exists($caminhoChave)) {
            throw new \Exception("Erro Crítico: Arquivo firebase_credentials.json não encontrado no caminho resolvido.");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $caminhoChave);

        $factory = (new Factory)->withServiceAccount($caminhoChave);

        $this->firestore = $factory->createFirestore();
        
        $this->storage = $factory->createStorage();
    }
    
    public function fazerUpload($endereco_img) {
        $img = file_get_contents($endereco_img);
        $nome_unico = 'publicacoes/' . uniqid() . '.jpg';
        $bucket = $this->storage->getBucket();
    
        $comprovante_envio = $bucket->upload($img, [
            'name' =>  $nome_unico
        ]);

        $link_da_imagem = $comprovante_envio->info()['mediaLink'];
        return $link_da_imagem;
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

        /*if ($snapshot->exists()){
            $dados = $snapshot->data();

            if (isset($dados['imagem_url']) && !empty($dados['imagem_url'])){
                $url_imagem = $dados['imagem_url'];
                $url_limpa = urldecode($url_imagem);
                $partes_url = explode('publicacoes/', $url_limpa);
                $nome_e_lixo = explode('?', $partes_url[1]);
                $caminho_url = 'publicacoes/' . $nome_e_lixo[0];

                $this->storage->getBucket()->object($caminho_url)->delete();
            }
        }*/

        $referencia_doc->delete();

        return true;
    }
}