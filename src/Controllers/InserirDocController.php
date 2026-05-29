<?php

namespace App\Controllers;
use App\Models\DocumentoModel;
use App\Services\GeminiService;
use App\Services\TrustScoreService;

require_once __DIR__ . '/../Services/GeminiService.php';
require_once __DIR__ . '/../Models/DocumentoModel.php'; 

class InserirDocController {
    public function index() {
        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();
        require '../src/Views/inserirDoc.php';
    }

    public function listar() {
        require_once '../src/Helpers/VerificarSessao.php';
        verificarSessao();

        try {
            $conn = require __DIR__ . '/../../config/database.php';
            $model = new \App\Models\DocumentoModel($conn);
            $documentos = $model->listarDocumentos($_SESSION['id_instituicao']);
            
            echo json_encode(['sucesso' => true, 'documentos' => $documentos]);
        } catch (\Exception $e) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro no banco: ' . $e->getMessage()]);
        }
    }

    public function excluir() {
        @session_start();
        
        if (!isset($_SESSION['id_instituicao'])) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
            return;
        }

        $dados = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($dados['id_documento'])) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID do documento não informado.']);
            return;
        }

        $idDocumento = $dados['id_documento'];
        $idInstituicao = $_SESSION['id_instituicao'];

        try {
            $conn = require __DIR__ . '/../../config/database.php';
            $model = new \App\Models\DocumentoModel($conn);
            
            $caminhoArquivo = $model->buscarCaminhoArquivo($idDocumento, $idInstituicao);
            
            $apagou = $model->excluirDocumento($idDocumento, $idInstituicao);
            
            if ($apagou) {
                if ($caminhoArquivo && file_exists($caminhoArquivo)) {
                    @unlink($caminhoArquivo);
                }

                require_once __DIR__ . '/../Services/TrustScoreService.php';
                $trustService = new \App\Services\TrustScoreService($conn);
                $trustService->atualizarScoreDaOsc($idInstituicao);

                echo json_encode(['sucesso' => true]);
            } else {
                 echo json_encode(['sucesso' => false, 'erro' => 'Documento não encontrado ou sem permissão.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro interno: ' . $e->getMessage()]);
        }
    }

    public function upload() {
        try {
            if (!isset($_FILES['documento']) || $_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['sucesso' => false, 'erro' => 'Falha no envio do arquivo.']);
                return;
            }

            $arquivo = $_FILES['documento'];
            $caminhoTemp = $arquivo['tmp_name'];
            $mimeType = mime_content_type($caminhoTemp);
            $tipo_doc = $_POST['tipo_documento'];
            
            
            $tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png'];
            if (!in_array($mimeType, $tiposPermitidos)) {
                echo json_encode(['sucesso' => false, 'erro' => 'Formato não suportado. Use PDF, JPG ou PNG.']);
                return;
            }

            $gemini = new GeminiService();
            $resultadoIA = $gemini->analisarDocumento($caminhoTemp, $mimeType);

            if (isset($resultadoIA['valido']) && $resultadoIA['valido'] === true) {
                
                $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
                $nomeNovo = "doc_osc_" . $_SESSION['id_instituicao'] . "_" . time() . "." . $extensao;

                $diretorioUpload = __DIR__ . '/../../storage/uploads/';
                if (!is_dir($diretorioUpload)) {
                    mkdir($diretorioUpload, 0777, true);
                }

                $caminhoFinal = __DIR__ . '/../../storage/uploads/' . $nomeNovo;
                
                move_uploaded_file($caminhoTemp, $caminhoFinal);
                $conn = require_once __DIR__ . '/../../config/database.php';

                $model = new \App\Models\DocumentoModel($conn);
                $model->salvarDocumento($_SESSION['id_instituicao'], $caminhoFinal, $resultadoIA['cnpj'], $tipo_doc);

                try {
                    require_once __DIR__ . '/../Services/TrustScoreService.php';
                    $trustService = new \App\Services\TrustScoreService($conn);
                    $trustService->atualizarScoreDaOsc($_SESSION['id_instituicao']);
                }
                catch (\Exception $e) {
                   @unlink($caminhoFinal); 
                    echo json_encode([
                        'sucesso' => false, 
                        'erro' => 'Falha ao calcular Trust Score: ' . $e->getMessage()
                    ]);
                    return;
                }
                
                echo json_encode([
                    'sucesso' => true, 
                    'mensagem' => 'Documento aprovado e validado com sucesso!',
                    'cnpj_identificado' => $resultadoIA['cnpj']
                ]);
                
            } else {
                @unlink($caminhoTemp); 
                $motivo = $resultadoIA['motivo'] ?? 'Documento não atende aos critérios de validação.';
                
                echo json_encode(['sucesso' => false, 'erro' => $motivo]);
            }
        }
        catch (\Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erro no Banco: ' . $e->getMessage()
            ]);
        }
    }
}