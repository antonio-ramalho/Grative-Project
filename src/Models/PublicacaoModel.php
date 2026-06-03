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
        $extensao = strtolower($extensao);
        $imagem = null;

        // Cria a imagem na memória para podermos manipular
        if ($extensao == 'jpg' || $extensao == 'jpeg') {
            $imagem = @imagecreatefromjpeg($endereco_img);
        } elseif ($extensao == 'png') {
            $imagem = @imagecreatefrompng($endereco_img);
        }

        // Se conseguiu ler a imagem, faz a compressão
        if ($imagem) {
            if ($extensao == 'jpg' || $extensao == 'jpeg') {
                // Salva em disco com 60% da qualidade original (reduz o peso drasticamente)
                imagejpeg($imagem, $caminho, 60); 
            } elseif ($extensao == 'png') {
                // Nível de compressão PNG vai de 0 a 9 (7 é excelente)
                imagepng($imagem, $caminho, 7); 
            }
            
            // Limpa a memória do servidor
            unset($imagem);
            return '/img/uploads/publicacoes/'.$nome_unico;
        }

        // Fallback: Se a biblioteca GD falhar por algum motivo, salva do jeito antigo
        if(move_uploaded_file($endereco_img, $caminho)){
            return '/img/uploads/publicacoes/'.$nome_unico;
        }

        return null;
    }

    public function salvarNoBanco($dados_publicacao) {
     
        if (!isset($dados_publicacao['curtidas'])) {
            $dados_publicacao['curtidas'] = 0;
        }
        $colecao = $this->firestore->database()->collection('publicacoes');
        $colecao->add($dados_publicacao);

        return true;
    }
    // Substitua a função incrementarCurtida por esta:
    public function alternarCurtida($id_documento, $id_usuario) {
        $referencia_doc = $this->firestore->database()->collection('publicacoes')->document($id_documento);
        $snapshot = $referencia_doc->snapshot();

        if ($snapshot->exists()) {
            $dados = $snapshot->data();
            $curtidas_atuais = isset($dados['curtidas']) ? (int)$dados['curtidas'] : 0;
            
            // Pega a lista de quem já curtiu (se não existir, cria uma vazia)
            $usuarios_que_curtiram = isset($dados['usuarios_que_curtiram']) ? $dados['usuarios_que_curtiram'] : [];

            // Verifica se o ID do usuário já está na lista
            if (in_array($id_usuario, $usuarios_que_curtiram)) {
                // Já curtiu -> DESCURTIR (Tira da lista e diminui 1)
                $usuarios_que_curtiram = array_diff($usuarios_que_curtiram, [$id_usuario]);
                $novas_curtidas = max(0, $curtidas_atuais - 1); // Evita números negativos
            } else {
                // Não curtiu -> CURTIR (Coloca na lista e aumenta 1)
                $usuarios_que_curtiram[] = $id_usuario;
                $novas_curtidas = $curtidas_atuais + 1;
            }

            // Reorganiza o array para o Firebase salvar corretamente
            $usuarios_que_curtiram = array_values($usuarios_que_curtiram);

            // Atualiza os dois campos no Firestore
            $referencia_doc->update([
                ['path' => 'curtidas', 'value' => $novas_curtidas],
                ['path' => 'usuarios_que_curtiram', 'value' => $usuarios_que_curtiram]
            ]);

            return $novas_curtidas;
        }

        return false;
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
       $envelopes = $colecao->orderBy('data_publicacao', 'DESC')->limit(10)->documents();

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