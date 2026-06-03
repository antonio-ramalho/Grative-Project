<?php
namespace App\Models;
use Kreait\Firebase\Factory;

class CampanhaModel {
    private $firestore;

    public function __construct() {
        $caminhoChave = realpath(__DIR__ . '/../../config/firebase_credentials.json');

        if (!$caminhoChave || !file_exists($caminhoChave)) {
            throw new \Exception("Erro Crítico: Arquivo firebase_credentials.json não encontrado.");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $caminhoChave);
        $factory = (new Factory)->withServiceAccount($caminhoChave);
        $this->firestore = $factory->createFirestore();
    }

    public function fazerUpload($arquivoOriginal) {
        if ($arquivoOriginal['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extensao = strtolower(pathinfo($arquivoOriginal['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['png', 'jpg', 'jpeg'];

        if (!in_array($extensao, $extensoes_permitidas)) {
            return false; 
        }

        $nome_unico = uniqid('campanha_') . "." . $extensao;
        $pasta_destino = __DIR__ . '/../../public/img/uploads/campanhas/';
        
        if (!is_dir($pasta_destino)) {
            mkdir($pasta_destino, 0777, true);
        }

        $caminho_fisico = $pasta_destino . $nome_unico;
        $caminho_temporario = $arquivoOriginal['tmp_name'];

        $imagem = null;
        if ($extensao == 'jpg' || $extensao == 'jpeg') {
            $imagem = @imagecreatefromjpeg($caminho_temporario);
        } elseif ($extensao == 'png') {
            $imagem = @imagecreatefrompng($caminho_temporario);
        }

        if ($imagem) {
            if ($extensao == 'jpg' || $extensao == 'jpeg') {
                imagejpeg($imagem, $caminho_fisico, 60);
            } elseif ($extensao == 'png') {
                imagepng($imagem, $caminho_fisico, 7);
            }
            imagedestroy($imagem);
            return '/img/uploads/campanhas/' . $nome_unico;
        }

        if (move_uploaded_file($caminho_temporario, $caminho_fisico)) {
            return '/img/uploads/campanhas/' . $nome_unico;
        }

        return false;
    }

    public function salvarNoFirebase($dadosCampanha) {
        $colecao = $this->firestore->database()->collection('campanhas');
        $colecao->add($dadosCampanha);
        return true;
    }
    
    public function listarPorOsc($id_osc) {
        $lista_campanhas = [];
        $colecao = $this->firestore->database()->collection('campanhas');
        $documentos = $colecao->where('id_instituicao', '=', $id_osc)->documents();

        foreach ($documentos as $doc) {
            if ($doc->exists()) {
                $dados = $doc->data();
                $dados['id'] = $doc->id(); 
                $lista_campanhas[] = $dados;
            }
        }
        return $lista_campanhas;
    }

    public function interromperCampanha($id_documento) {
        $referencia_doc = $this->firestore->database()->collection('campanhas')->document($id_documento);
        $snapshot = $referencia_doc->snapshot();

        if ($snapshot->exists()) {
            $referencia_doc->update([
                ['path' => 'status', 'value' => 'cancelada']
            ]);
            return true;
        }

        return false;
    }
}